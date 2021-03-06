<?php
	namespace ewgraCmsModules;

	/**
	 * Generated by meta builder!
	 * Do not edit this class!
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 */
	abstract class AutoNavigation
	{
		private $id = null;

		private $categoryId = null;

		/**
		 * @var NavigationCategory
		 */
		private $category = null;

		/**
		 * @var \ewgraFramework\HttpUrl
		 */
		private $uri = null;

		private $position = null;

		/**
		 * @var NavigationStatus
		 */
		private $status = null;

		/**
		 * @return NavigationDA
		 */
		public static function da()
		{
			return NavigationDA::me();
		}

		/**
		 * @return NavigationProto
		 */
		public static function proto()
		{
			return NavigationProto::me();
		}

		/**
		 * @return AutoNavigation
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
		 * @return AutoNavigation
		 */
		public function setCategoryId($categoryId)
		{
			$this->category = null;
			$this->categoryId = $categoryId;

			return $this;
		}

		public function getCategoryId()
		{
			return $this->categoryId;
		}

		/**
		 * @return AutoNavigation
		 */
		public function setCategory(NavigationCategory $category)
		{
			$this->categoryId = $category->getId();
			$this->category = $category;

			return $this;
		}

		/**
		 * @return NavigationCategory
		 */
		public function getCategory()
		{
			if (!$this->category && $this->getCategoryId())
				$this->category = NavigationCategory::da()->getById($this->getCategoryId());

			return $this->category;
		}

		/**
		 * @return AutoNavigation
		 */
		public function setUri(\ewgraFramework\HttpUrl $uri)
		{
			$this->uri = $uri;

			return $this;
		}

		/**
		 * @return \ewgraFramework\HttpUrl
		 */
		public function getUri()
		{
			return $this->uri;
		}

		/**
		 * @return AutoNavigation
		 */
		public function setPosition($position)
		{
			$this->position = $position;

			return $this;
		}

		public function getPosition()
		{
			return $this->position;
		}

		/**
		 * @return AutoNavigation
		 */
		public function setStatus(NavigationStatus $status)
		{
			$this->status = $status;

			return $this;
		}

		/**
		 * @return NavigationStatus
		 */
		public function getStatus()
		{
			return $this->status;
		}
	}
?>