<?php
	namespace ewgraCmsModules;
	
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class PageViewFilesController extends \ewgraFramework\ChainController
	{
		private $joinContentTypes = array();
		
		private $additionalJoinUrl = '/join';
		
		/**
		 * @return PageViewFilesController
		 */
		public function addJoinContentType(\ewgraFramework\ContentType $contentType)
		{
			$this->joinContentTypes[$contentType->getId()] = $contentType;
			return $this;
		}
		
		public function getJoinContentTypes()
		{
			return $this->joinContentTypes;
		}
		
		/**
		 * @return PageViewFilesController
		 */
		public function importSettings(array $settings = null)
		{
			if(isset($settings['additionalJoinUrl']))
				$this->additionalJoinUrl = $settings['additionalJoinUrl'];
			
			if(isset($settings['joinContentTypes'])) {
				\ewgraFramework\Assert::isArray($settings['joinContentTypes']);
				
				foreach ($settings['joinContentTypes'] as $contentTypeName) {
					$contentType = \ewgraFramework\ContentType::createByName($contentTypeName);
					
					\ewgraFramework\Assert::isTrue(
						$contentType->canBeJoined(),
						'Don\'t know how join content-type '.$contentType
					);
				
					$this->addJoinContentType($contentType);
				}
			}
			
			return $this;
		}
		
		/**
		 * @return \ewgraFramework\ModelAndView
		 */
		public function handleRequest(
			\ewgraFramework\HttpRequest $request,
			\ewgraFramework\ModelAndView $mav
		) {
			$viewFiles = 
				\ewgraCms\ViewFile::da()->getByPage(
					$request->getAttachedVar(\ewgraCms\AttachedAliases::PAGE)
				);
			
			$inheritanceFiles =
				array_diff_assoc(
					\ewgraCms\ViewFile::da()->getInheritanceByIds(array_keys($viewFiles)),
					$viewFiles
				);
				
			$viewFiles = $inheritanceFiles;
			
			while ($inheritanceFiles) {
				$viewFiles = $viewFiles+$inheritanceFiles;
				
				$inheritanceFiles =
					array_diff_assoc(
						\ewgraCms\ViewFile::da()->getInheritanceByIds(
							array_keys($inheritanceFiles)
						),
						$viewFiles
					);
			}
			
			if ($this->getJoinContentTypes())
				$viewFiles = $this->joinFiles($request, $viewFiles);
			
			$mav->getModel()->set('files', $viewFiles);
			
			return parent::handleRequest($request, $mav);
		}

		private function joinFiles(\ewgraFramework\HttpRequest $request, array $viewFiles)
		{
			$files =
				MediaFilesJoiner::create()->
				setDefaultHost($request->getServerVar('SERVER_NAME'))->
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