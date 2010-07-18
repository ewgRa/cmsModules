<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class NavigationModule extends CmsModule
	{
		private $categoryIds = null;
		private $rightIds = null;
		
		/**
		 * @return NavigationModule
		 */
		public function setCategoryIds(array $categoryIds)
		{
			$this->categoryIds = $categoryIds;
			return $this;
		}
		
		public function getCategoryIds()
		{
			return $this->categoryIds;
		}
		
		/**
		 * @return NavigationModule
		 */
		public function setRightIds(array $rightIds)
		{
			$this->rightIds = $rightIds;
			return $this;
		}
		
		public function getRightIds()
		{
			return $this->rightIds;
		}
		
		/**
		 * @return NavigationModule
		 */
		public function importSettings(array $settings = null)
		{
			$this->setCategoryIds($settings['categories']);
			
			if (isset($settings['rights']))
				$this->setRightIds($settings['rights']);

			return $this;
		}
		
		/**
		 * @return Model
		 */
		public function getModel()
		{
			$result = Model::create();
			
			if ($this->checkAccess())
				$result->setData($this->getData());
				
			return $result;
		}
		
		private function checkAccess()
		{
			if (!$this->getRightIds())
				return true;
			
			if (!$this->hasUser())
				return false;
			
			return
				$this->getUser()->checkAccess(
					Right::da()->getByIds($this->getRightIds())
				);
		}
		
		private function getData()
		{
			$result = array();
			
			$result['navigationList'] =
				Navigation::da()->getByCategoryIds(
					$this->getCategoryIds()
				);

			$result['navigationDataList'] = array();
			
			$navigationDataList =
				NavigationData::da()->getList(
					$result['navigationList'],
					array($this->getLocalizer()->getRequestLanguage())
				);
				
			foreach ($navigationDataList as $navigationData) {
				$result['navigationDataList'][$navigationData->getNavigationId()] =
					$navigationData;
			}
			
			$result['baseUrl'] = $this->getBaseUrl();
			
			return $result;
		}
	}
?>