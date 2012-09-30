<?php
	namespace ewgraCmsModules;

	/**
	 * Generated by meta builder!
	 * Do not edit this class!
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 */
	abstract class AutoContent
	{
		private $id = null;

		/**
		 * @var ContentStatus
		 */
		private $status = null;

		/**
		 * @return ContentDA
		 */
		public static function da()
		{
			return ContentDA::me();
		}

		/**
		 * @return ContentProto
		 */
		public static function proto()
		{
			return ContentProto::me();
		}

		/**
		 * @return AutoContent
		 */
		public function setId($id)
		{
			$this->id = $id;

			return $this;
		}

		public function getId()
		{
			\ewgraFramework\Assert::isNotNull($this->id);
			return $this->id;
		}

		public function hasId()
		{
			return ($this->id !== null);
		}

		/**
		 * @return AutoContent
		 */
		public function setStatus(ContentStatus $status)
		{
			$this->status = $status;

			return $this;
		}

		/**
		 * @return ContentStatus
		 */
		public function getStatus()
		{
			return $this->status;
		}
	}
?>