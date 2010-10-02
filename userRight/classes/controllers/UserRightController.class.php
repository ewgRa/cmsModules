<?php
	namespace ewgraCmsModules;
	
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	class UserRightController extends ChainController
	{
		private $requiredRights = array();
		
		public function setRequiredRights(array $rights)
		{
			$this->requiredRights = $rights;
			return $this;	
		}
		
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
			
			$result = true;
			
			if ($this->requiredRights && !$user)
				$result = false;
				
			if ($result && $this->requiredRights && $user)
				$result = $user->checkAccess($this->requiredRights);

			if (!$result)
				throw PageAccessDeniedException::create();
						
			return parent::handleRequest($request, $mav);
		}
	}
?>