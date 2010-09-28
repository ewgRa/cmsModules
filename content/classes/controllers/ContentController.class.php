<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class ContentController extends ChainController
	{
		private $units = null;
		
		/**
		 * @return ContentController
		 */
		public function setUnits($units)
		{
			$this->units = $units;
			return $this;
		}
		
		public function getUnits()
		{
			return $this->units;
		}
		
		public function importSettings(array $settings = null)
		{
			Assert::isArray($settings['units']);
			$this->setUnits($settings['units']);

			return $this;
		}
		
		/**
		 * @return ModelAndView
		 */
		public function handleRequest(
			HttpRequest $request,
			ModelAndView $mav
		) {
			$result['contentList'] = Content::da()->getByIds($this->getUnits());
			
			$result['contentDataList'] = array();
			
			$contentDataList =
				ContentData::da()->getList(
					$result['contentList'],
					array(
						$request->getAttachedVar(AttachedAliases::LOCALIZER)->
						getRequestLanguage()
					)
				);
			
			foreach ($contentDataList as $contentData) {
				$result['contentDataList'][$contentData->getContentId()] =
					$contentData;
			}
			
			$result['replaceFilter'] = $this->getReplaceFilter($request);
		
			$mav->getModel()->merge($result);
			
			return parent::handleRequest($request, $mav);
		}

		/**
		 * @return StringReplaceFilter
		 */
		private function getReplaceFilter(HttpRequest $request)
		{
			return
				StringReplaceFilter::create()->
				addReplacement(
					'%baseUrl%', 
					$request->getAttachedVar(AttachedAliases::BASE_URL)->getPath()
				);
		}
	}
?>