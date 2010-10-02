<?php
	namespace ewgraCmsModules;
	
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class UserController extends \ewgraFramework\ChainController
	{
		/**
		 * @return \ewgraFramework\ModelAndView
		 */
		public function handleRequest(
			\ewgraFramework\HttpRequest $request,
			\ewgraFramework\ModelAndView $mav
		) {
			$user = null;
			
			if (
				\ewgraFramework\Session::me()->isStarted()
				&& \ewgraFramework\Session::me()->has('userId')
			) {
				$user = 
					User::da()->getById(
						\ewgraFramework\Session::me()->get('userId')
					);
				
				$request->setAttachedVar(\ewgraCms\AttachedAliases::USER, $user);
			}
			
			return parent::handleRequest($request, $mav);
		}
	}
?>