<?php
	namespace ewgraCmsModules;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class PageRightController extends UserRightController
	{
		/**
		 * @return \ewgraFramework\ModelAndView
		 */
		public function handleRequest(
			\ewgraFramework\HttpRequest $request,
			\ewgraFramework\ModelAndView $mav
		) {
			$page = $request->getAttachedVar(\ewgraCms\AttachedAliases::PAGE);

			$user =
				$request->hasAttachedVar(\ewgraCms\AttachedAliases::USER)
					? $request->getAttachedVar(\ewgraCms\AttachedAliases::USER)
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
			} catch(\ewgraCms\PageAccessDeniedException $e) {
				if (!$redirectPage)
					throw $e;

				return $this->catchPageAccessDeniedException($request, $redirectPage);
			}
		}

		private function catchPageAccessDeniedException(
			\ewgraFramework\HttpRequest $request,
			\ewgraCms\Page $redirectPage
		) {
			$url =
				\ewgraFramework\HttpUrl::createFromString(
					$redirectPage->getPath().'?backurl='
					.base64_encode($request->getUrl())
				);

			$request->setUrl($url);

			$modelAndView = \ewgraFramework\ModelAndView::create();

			$chainController = $this->getFirstController();

			$mav = $chainController->handleRequest($request, $modelAndView);

			$request->getAttachedVar(\ewgraCms\AttachedAliases::PAGE_HEADER)->
				add(
					$request->getServerVar('SERVER_PROTOCOL')
					.' 403 Forbidden'
				);

			return $mav;
		}
	}
?>