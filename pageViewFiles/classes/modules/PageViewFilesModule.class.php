<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class PageViewFilesModule extends CmsModule
	{
		private $joinContentTypes = array();
		
		private $additionalJoinUrl = '/join';
		
		/**
		 * @return PageViewFilesModule
		 */
		public function addJoinContentType(ContentType $contentType)
		{
			$this->joinContentTypes[$contentType->getId()] = $contentType;
			return $this;
		}
		
		public function getJoinContentTypes()
		{
			return $this->joinContentTypes;
		}
		
		/**
		 * @return PageViewFilesModule
		 */
		public function importSettings(array $settings = null)
		{
			if(isset($settings['additionalJoinUrl']))
				$this->additionalJoinUrl = $settings['additionalJoinUrl'];
			
			if(isset($settings['joinContentTypes'])) {
				Assert::isArray($settings['joinContentTypes']);
				
				foreach ($settings['joinContentTypes'] as $contentTypeName) {
					$contentType = ContentType::createByName($contentTypeName);
					
					Assert::isTrue(
						$contentType->canBeJoined(),
						'Don\'t know how join content-type '.$contentType
					);
				
					$this->addJoinContentType($contentType);
				}
			}
			
			$cacheTicket = ViewFile::da()->createCacheTicket();
			
			if ($cacheTicket) {
				$this->setCacheTicket(
					$cacheTicket->
						setPrefix(__CLASS__.'/'.__FUNCTION__)->
						setKey($this->getPage())
				);
			}
			
			return $this;
		}
		
		/**
		 * @return Model
		 */
		public function getModel()
		{
			$viewFiles = ViewFile::da()->getByPage($this->getPage());
			
			$inheritanceFiles =
				array_diff_assoc(
					ViewFile::da()->getInheritanceByIds(array_keys($viewFiles)),
					$viewFiles
				);
				
			$viewFiles = $inheritanceFiles;
			
			while ($inheritanceFiles) {
				$viewFiles = $viewFiles+$inheritanceFiles;
				
				$inheritanceFiles =
					array_diff_assoc(
						ViewFile::da()->getInheritanceByIds(
							array_keys($inheritanceFiles)
						),
						$viewFiles
					);
			}
			
			if ($this->getJoinContentTypes())
				$viewFiles = $this->joinFiles($viewFiles);
			
			$model = Model::create()->set('files', $viewFiles);
			
			return $model;
		}

		public static function createJoinedListsCacheTicket()
		{
			$cacheTicket = ViewFile::da()->createCacheTicket();
			
			Assert::isNotNull($cacheTicket);
			
			return
				$cacheTicket->
				setPrefix(__CLASS__.'/'.__FUNCTION__);
		}
		
		/**
		 * @return PageViewFilesModule
		 */
		protected function storeCacheTicketData($data)
		{
			if ($cacheTicket = $this->getCacheTicket())
				ViewFile::da()->addTicketToTag($cacheTicket);
			
			return parent::storeCacheTicketData($data);
		}
		
		private function joinFiles(array $viewFiles)
		{
			$files =
				MediaFilesJoiner::create()->
				setContentTypes($this->getJoinContentTypes())->
				joinFiles($viewFiles);

			foreach ($files as $file) {
				if($file instanceof JoinedViewFile) {
					$this->createJoinedListsCacheTicket()->
						setKey($file->getPath())->
						storeData($file);
					
					if ($this->additionalJoinUrl)
						$file->setPath($this->additionalJoinUrl.'/'.$file->getPath());
				}
			}

			return $files;
		}
	}
?>