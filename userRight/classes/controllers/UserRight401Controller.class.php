<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 * FIXME: extends from UserRightController
	*/
	final class UserRight401Controller extends ChainController
	{
		private $requiredRights = array();
		
		/**
		 * @return ModelAndView
		 */
		public function handleRequest(
			HttpRequest $request,
			ModelAndView $mav
		) {
			$user =
				$request->hasAttachedVar(AttachedAliases::USER)
					? $request->getAttachedVar(AttachedAliases::USER)
					: null;
			
			$requiredRights = Right::da()->getByAliases($this->requiredRights);
			
			if (
				$user 
				&& $user->getId()
				&& $user->checkAccess($requiredRights)
			)
				return parent::handleRequest($request, $mav);
			
			$pageHeader = $request->getAttachedVar(AttachedAliases::PAGE_HEADER);
			
			$pageHeader->
				add('WWW-Authenticate', 'Basic realm="Enter you auth data"')->
				add($request->getServerVar('SERVER_PROTOCOL').' 401 Unauthorized');
			
			$mav->setView(
				PhpView::create()->
				loadLayout(
					File::create()->
					setPath(
						dirname(__FILE__).'/../../view/php/401unauthorized.php'
					)
				)
			);
			
			return $mav;
		}
	}
?>