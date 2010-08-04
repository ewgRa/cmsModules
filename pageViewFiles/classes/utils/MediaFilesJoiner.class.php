<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class MediaFilesJoiner
	{
		private $contentTypes = null;
		
		/**
		 * @return MediaFilesJoiner
		 */
		public static function create()
		{
			return new self;
		}
		
		/**
		 * @return MediaFilesJoiner
		 */
		public function setContentTypes(array $contentTypes)
		{
			$this->contentTypes = $contentTypes;
			return $this;
		}
		
		public function getContentTypes()
		{
			return $this->contentTypes;
		}
		
		public function joinFiles(array $files)
		{
			Assert::isNotNull($this->getContentTypes());
			
			$bufferJoinFiles = array();
			
			$joinFiles = array();
			
			foreach ($files as $file) {
				if(
					in_array(
						$file->getContentType()->getId(),
						array_keys($this->getContentTypes())
					)
					&& $file->isJoinable()
				) {
					if (!isset($bufferJoinFiles[$file->getContentType()->getId()]))
						$bufferJoinFiles[$file->getContentType()->getId()] = array();
					
					$bufferJoinFiles[$file->getContentType()->getId()][] = $file;
				}
				else
					$joinFiles[] = $file;
			}
			
			foreach ($bufferJoinFiles as $contentTypeId => $files) {
				$contentType = ContentType::create($contentTypeId);
				
				$joinFiles[] =
					JoinedViewFile::create()->
					setFiles($files)->
					setContentType($contentType);
			}
			
			return $joinFiles;
		}
	}
?>