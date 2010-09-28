<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class NavigationController extends ChainController
	{
		private $categoryIds = null;
		private $rightIds = null;
		
		/**
		 * @return NavigationController
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
		 * @return NavigationController
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
		 * @return NavigationController
		 */
		public function importSettings(array $settings = null)
		{
			$this->setCategoryIds($settings['categories']);
			
			if (isset($settings['rights']))
				$this->setRightIds($settings['rights']);

			return $this;
		}
		
		/**
		 * @return ModelAndView
		 */
		public function handleRequest(
			HttpRequest $request,
			ModelAndView $mav
		) {
			if ($this->checkAccess())
				$mav->getModel()->merge($this->getData($request));
				
			return parent::handleRequest($request, $mav);
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
		
		private function getData(HttpRequest $request)
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
					array(
						$request->getAttachedVar(AttachedAliases::LOCALIZER)->
							getRequestLanguage())
				);
				
			foreach ($navigationDataList as $navigationData) {
				$result['navigationDataList'][$navigationData->getNavigationId()] =
					$navigationData;
			}
			
			$result['baseUrl'] = 
				$request->getAttachedVar(AttachedAliases::BASE_URL);
			
			return $result;
		}
	}
?>