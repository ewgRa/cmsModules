<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class PageRightController extends ChainController
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
			
			$rights = Right::da()->getByIds($rightIds);

			$result = true;
			
			if ($rights && !$user)
				$result = false;
				
			if ($result && $rights && $user)
				$result = $user->checkAccess($rights);
			
			if (!$result)
				throw PageAccessDeniedException::create();
			
			return parent::handleRequest($request, $mav);
		}

		public static function catchPageAccessDeniedException(
			HttpRequest $request,
			PageAccessDeniedException $e
		) {
			$rights =
				PageRight::da()->getByPage(
					$request->getAttachedVar(AttachedAliases::PAGE)
				);
			
			$right = array_shift($rights);
			
			$url = 
				HttpUrl::createFromString(
					$right->getRedirectPage()->getPath().'?backurl='
					.base64_encode($request->getUrl())
				);
			
			$request->setUrl($url);

			$modelAndView = ModelAndView::create();
			
			$chainController = createCommonChain();
			$chainController->handleRequest($request, $modelAndView);
			
			$request->getAttachedVar(AttachedAliases::PAGE_HEADER)->
				add(
					$request->getServerVar('SERVER_PROTOCOL')
					.' 403 Forbidden'
				);
			
			return $modelAndView->render();
		}
	}
?>