<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class Auth401Controller extends ChainController
	{
		private $requiredRights = array('root');
		
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
			
			$requiredRights =
				array_keys(Right::da()->getByAliases($this->requiredRights));
			
			if (
				$user && $user->getId()
				&& array_intersect(
					UserRight::da()->getRightIdsByUser($user),
					$requiredRights
				)
					== $requiredRights
			)
				return parent::handleRequest($request, $mav);
			
			return $this->unAuthorized('Enter you auth data', 'Need auth', $request);
		}
		
		/**
		 * FIXME: stay in framework area
		 */
		private function unAuthorized($realm, $cancelMessage, HttpRequest $request)
		{
			header('WWW-Authenticate: Basic realm="' . $realm . '"');
			header($request->getServerVar('SERVER_PROTOCOL').' 401 Unauthorized');
			echo $cancelMessage;
			die();
		}
	}
?>