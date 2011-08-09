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
				array_diff_key(
					\ewgraCms\ViewFile::da()->getInheritanceByIds(array_keys($viewFiles)),
					$viewFiles
				);

			$viewFiles = $inheritanceFiles;

			while ($inheritanceFiles) {
				$viewFiles = $viewFiles+$inheritanceFiles;

				$inheritanceFiles =
					array_diff_key(
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
			if (!defined('ewgraCmsModules\JOIN_FILES_DIR'))
				throw new \Exception('please, define ewgraCmsModules\JOIN_FILES_DIR first');

			$files =
				MediaFilesJoiner::create()->
				setDefaultHost($request->getServerVar('SERVER_NAME'))->
				setContentTypes($this->getJoinContentTypes())->
				joinFiles($viewFiles);

			foreach ($files as $file) {
				if($file instanceof JoinedViewFile) {
					if ($this->additionalJoinUrl)
						$file->setPath($this->additionalJoinUrl.'/'.$file->getPath());

					$joinedFile =
						\ewgraFramework\File::create()->
						setPath(
							JOIN_FILES_DIR.DIRECTORY_SEPARATOR
							.basename($file->getPath())
						);

					if (defined('ewgraCmsModules\JOIN_FILES_VERSION'))
						$file->setPath($file->getPath().'?v='.JOIN_FILES_VERSION);

					if (!$joinedFile->isExists())
						$file->buildToFile($joinedFile);
				}
			}

			return $files;
		}
	}
?>