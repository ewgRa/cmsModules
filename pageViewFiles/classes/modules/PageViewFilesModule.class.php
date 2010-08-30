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
			
			return $this;
		}
		
		public function getRenderedModel()
		{
			$this->setCacheTicket(
				ViewFile::da()->createCacheTicket()->
				setKey(__CLASS__, __FUNCTION__, $this->getPage())
			);
			
			return parent::getRenderedModel();
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

		/**
		 * @return PageViewFilesModule
		 */
		protected function storeCacheTicketData($data)
		{
			if ($cacheTicket = $this->getCacheTicket())
				ViewFile::da()->addCacheTicketToTag($cacheTicket);
			
			return parent::storeCacheTicketData($data);
		}
		
		private function joinFiles(array $viewFiles)
		{
			$files =
				MediaFilesJoiner::create()->
				setDefaultHost($this->getRequest()->getServerVar('SERVER_NAME'))->
				setContentTypes($this->getJoinContentTypes())->
				joinFiles($viewFiles);

			foreach ($files as $file) {
				if($file instanceof JoinedViewFile) {
					if ($this->additionalJoinUrl)
						$file->setPath($this->additionalJoinUrl.'/'.$file->getPath());
						
					$file->da()->insert($file);
				}
			}

			return $files;
		}
	}
?>