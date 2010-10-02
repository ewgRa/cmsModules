<?php
	namespace ewgraCmsModules;
	
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class PageRightController extends UserRightController
	{
		/**
		 * @return ModelAndView
		 */
		public function handleRequest(
			HttpRequest $request,
			ModelAndView $mav
		) {
			$page = $request->getAttachedVar(AttachedAliases::PAGE);
			
			$user =
				$request->hasAttachedVar(AttachedAliases::USER)
					? $request->getAttachedVar(AttachedAliases::USER)
					: null;
			
			$pageRights = PageRight::da()->getByPage($page);
			$rightIds = array();
			
			foreach ($pageRights as $pageRight)
				$rightIds[] = $pageRight->getRightId();
			
			$this->setRequiredRights(Right::da()->getByIds($rightIds));
			
			$redirectPage = null;
			
			if ($pageRights) {
				$firstRight = array_shift($pageRights);
				$redirectPage = $firstRight->getRedirectPage();
			}

			try {
				return parent::handleRequest($request, $mav);
			} catch(PageAccessDeniedException $e) {
				if (!$redirectPage)
					throw $e;
				
				return $this->catchPageAccessDeniedException($request, $redirectPage);
			}
		}

		private function catchPageAccessDeniedException(
			HttpRequest $request, 
			Page $redirectPage
		) {
			$url = 
				HttpUrl::createFromString(
					$redirectPage->getPath().'?backurl='
					.base64_encode($request->getUrl())
				);
			
			$request->setUrl($url);

			$modelAndView = ModelAndView::create();
			
			$chainController = $this->getFirstController();

			$mav = $chainController->handleRequest($request, $modelAndView);

			$request->getAttachedVar(AttachedAliases::PAGE_HEADER)->
				add(
					$request->getServerVar('SERVER_PROTOCOL')
					.' 403 Forbidden'
				);

			return $mav;
		}
	}
?>