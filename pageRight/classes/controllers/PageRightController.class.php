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
	}
?>