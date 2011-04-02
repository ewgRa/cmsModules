<?php
	namespace ewgraCmsModules;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class JoinedViewFileDA extends \ewgraFramework\Singleton
	{
		/**
		 * @return JoinedViewFileDA
		 */
		public static function me()
		{
			return parent::getInstance(__CLASS__);
		}

		public function getByPath($path)
		{
			$cacheTicket =
				$this->createCacheTicket()->
				addKey($path);

			return $cacheTicket->restoreData();
		}

		public function dropByPath($path)
		{
			$cacheTicket =
				$this->createCacheTicket()->
				addKey($path)->
				drop();

			return $this;
		}

		public function insert(JoinedViewFile $file)
		{
			$cacheTicket= $this->createCacheTicket();

			$cacheTicket->
				addKey($file->getPath())->
				storeData($file);

			\ewgraCms\ViewFile::da()->addCacheTicketToTag($cacheTicket);

			return $this;
		}

		/**
		 * @return CacheTicket
		 */
		private function createCacheTicket()
		{
			return
				\ewgraCms\ViewFile::da()->createCacheTicket()->
				setKey(__CLASS__, __FUNCTION__);
		}
	}
?>