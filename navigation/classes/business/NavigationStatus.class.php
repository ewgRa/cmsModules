<?php
	namespace ewgraCmsModules;
	
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class NavigationStatus extends \ewgraFramework\Enumeration
	{
		const NORMAL	= 1;
		const HIDDEN	= 2;
		
		protected $names = array(
			self::NORMAL 	=> 'normal',
			self::HIDDEN 	=> 'hidden'
		);
		
		/**
		 * @return NavigationStatus
		 */
		public static function create($id)
		{
			return new self($id);
		}
		
		/**
		 * @return NavigationStatus
		 */
		public static function any()
		{
			return self::normal();
		}
		
		/**
		 * @return NavigationStatus
		 */
		public static function normal()
		{
			return self::create(self::NORMAL);
		}

		/**
		 * @return NavigationStatus
		 */
		public static function hidden()
		{
			return self::create(self::HIDDEN);
		}
	}
?>