<?php
	namespace ewgraCmsModules;
	
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	class UserRightController extends \ewgraFramework\ChainController
	{
		private $requiredRights = array();
		
		public function setRequiredRights(array $rights)
		{
			$this->requiredRights = $rights;
			return $this;	
		}
		
		/**
		 * @return \ewgraFramework\ModelAndView
		 */
		public function handleRequest(
			\ewgraFramework\HttpRequest $request,
			\ewgraFramework\ModelAndView $mav
		) {
			$user =
				$request->hasAttachedVar(\ewgraCms\AttachedAliases::USER)
					? $request->getAttachedVar(\ewgraCms\AttachedAliases::USER)
					: null;
			
			$result = true;
			
			if ($this->requiredRights && !$user)
				$result = false;
				
			if ($result && $this->requiredRights && $user)
				$result = $user->checkAccess($this->requiredRights);

			if (!$result)
				throw \ewgraCms\PageAccessDeniedException::create();
						
			return parent::handleRequest($request, $mav);
		}
	}
?>