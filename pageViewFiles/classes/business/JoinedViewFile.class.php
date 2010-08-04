<?php
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
		public function setContentType(ContentType $contentType)
		{
			$this->contentType = $contentType;
			return $this;
		}
		
		/**
		 * @return ContentType
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
		
		public function getJoinedContent($defaultHost, $defaultScheme = 'http')
		{
			$result = '';
	
			foreach ($this->getFiles() as $file) {
				$url = HttpUrl::createFromString($file->getPath());
				
				if (!$url->getHost()) {
					$url->
						setHost($defaultHost)->
						setScheme($defaultScheme);
				}
				
				$result .= file_get_contents($url).PHP_EOL.PHP_EOL;
			}
			
			return $result;
		}
	}
?>