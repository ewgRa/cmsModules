<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class UserController extends ChainController
	{
		/**
		 * @return ModelAndView
		 */
		public function handleRequest(
			HttpRequest $request,
			ModelAndView $mav
		) {
			$user = null;
			
			if (
				Session::me()->isStarted()
				&& Session::me()->has('userId')
			) {
				$user = User::da()->getById(Session::me()->get('userId'));
				$request->setAttachedVar(AttachedAliases::USER, $user);
			}
			
			return parent::handleRequest($request, $mav);
		}
	}
?>