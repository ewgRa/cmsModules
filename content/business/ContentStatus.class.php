<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class ContentStatus extends Enumeration
	{
		const NORMAL	= 1;
		const DELETED	= 2;
		
		protected $names = array(
			self::NORMAL 	=> 'normal',
			self::DELETED 	=> 'deleted'
		);
		
		/**
		 * @return ContentStatus
		 */
		public static function create($id)
		{
			return new self($id);
		}
		
		/**
		 * @return ContentStatus
		 */
		public static function any()
		{
			return self::normal();
		}
		
		/**
		 * @return ContentStatus
		 */
		public static function normal()
		{
			return self::create(self::NORMAL);
		}

		/**
		 * @return ContentStatus
		 */
		public static function deleted()
		{
			return self::create(self::DELETED);
		}
	}
?>