<?php
	namespace ewgraCmsModules;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class JoinedViewFile
	{
		private $files = null;

		/**
		 * @var ContentType
		 */
		private $contentType = null;

		private $path = null;

		/**
		 * @return JoinedViewFile
		 */
		public static function create()
		{
			return new self;
		}

		/**
		 * @return JoinedViewFile
		 */
		public function setFiles(array $files)
		{
			$this->files = $files;
			return $this;
		}

		public function getFiles()
		{
			return $this->files;
		}

		/**
		 * @return JoinedViewFile
		 */
		public function setContentType(\ewgraFramework\ContentType $contentType)
		{
			$this->contentType = $contentType;
			return $this;
		}

		/**
		 * @return \ewgraFramework\ContentType
		 */
		public function getContentType()
		{
			return $this->contentType;
		}

		/**
		 * @return JoinedViewFile
		 */
		public function setPath($path)
		{
			$this->path = $path;
			return $this;
		}

		public function getPath()
		{
			if (!$this->path) {
				$this->path =
					md5(serialize($this->getFiles())).'.'
					.$this->getContentType()->getFileExtension();
			}

			return $this->path;
		}

		public function buildToFile(\ewgraFramework\File $file)
		{
			$fileData = $this->getJoinedContent();

			$dir = $file->getDir();

			if (!$dir->isExists())
				$dir->make();

			$file->setContent($fileData);
			$file->chmod(\ewgraFramework\File::PERMISSIONS);

			return $fileData;
		}

		private function getJoinedContent()
		{
			$result = '';

			foreach ($this->getFiles() as $file) {
				$url = \ewgraFramework\HttpUrl::createFromString($file->getPath());

				$content = $url->downloadContent();

				if ($this->getContentType()->getId() == \ewgraFramework\ContentType::TEXT_CSS)
					$content = $this->importCss($content, $url);

				$result .= $content.PHP_EOL.PHP_EOL;
			}

			if ($this->getContentType()->getId() == \ewgraFramework\ContentType::TEXT_CSS) {
				$charsetPattern = '/^(@CHARSET.+?)$/m';

				if (preg_match_all($charsetPattern, $result, $matches, PREG_SET_ORDER)) {
					$charset = $matches[0][1];
					$result = preg_replace($charsetPattern, '', $result);
					$result = $charset.PHP_EOL.$result;
				}
			}

			return $result;
		}

		private function importCss($content, $url) {
			$importPattern = '/@IMPORT url\("(.+)"\);/';

			if (preg_match_all($importPattern, $content, $matches, PREG_SET_ORDER)) {
				$content = preg_replace($importPattern, '%IMPORT_$1%', $content);

				foreach($matches as $match) {
					$importUrl = clone $url;

					$importUrl->setPath(pathinfo($url->getPath(), PATHINFO_DIRNAME).'/'.$match[1]);

					$content = str_replace(
						'%IMPORT_'.$match[1].'%',
						$this->importCss($importUrl->downloadContent(), $importUrl),
						$content
					);
				}
			}

			return $content;
		}
	}
?>