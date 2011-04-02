<?php
	namespace ewgraCmsModules;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class BuildJoinFileController extends \ewgraFramework\ChainController
	{
		private $storageDir = null;

		public function setStorageDir($dir)
		{
			$this->storageDir = $dir;
			return $this;
		}

		/**
		 * @return \ewgraFramework\ModelAndView
		 */
		public function handleRequest(
			\ewgraFramework\HttpRequest $request,
			\ewgraFramework\ModelAndView $mav
		) {
			$requestFile = $request->getServerVar('REQUEST_URI');

			$extension = pathinfo($requestFile, PATHINFO_EXTENSION);

			$contentType =
				\ewgraFramework\ContentType::createByExtension($extension);

			if (!$contentType) {
				throw \ewgraFramework\DefaultException::create(
					'Don\'t known content-type for file '.$requestFile
				);
			}

			$joinedViewFile = JoinedViewFile::da()->getByPath($requestFile);

			$request->
				getAttachedVar(\ewgraCms\AttachedAliases::PAGE_HEADER)->
				add('Content-type', $contentType);

			$mav->getModel()->set(
				'fileContent',
				$joinedViewFile->buildToFile(
					\ewgraFramework\File::create()->
					setPath(
						$this->storageDir.DIRECTORY_SEPARATOR
						.basename($requestFile)
					)
				)
			);

			return $mav;
		}
	}
?>