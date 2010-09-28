<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class UserRight401Controller extends UserRightController
	{
		private $requiredRightAliases = array();
		
		/**
		 * @return UserRight401Controller
		 */
		public static function create(ChainController $controller = null)
		{
			return new self($controller);	
		}
		
		/**
		 * @return UserRight401Controller
		 */
		public function setRequiredRightAliases(array $aliases)
		{
			$this->requiredRightAliases = $aliases;
			return $this;
		}
		
		/**
		 * @return ModelAndView
		 */
		public function handleRequest(
			HttpRequest $request,
			ModelAndView $mav
		) {
			$this->setRequiredRights(
				Right::da()->getByAliases($this->requiredRightAliases)
			);

			try {
				return parent::handleRequest($request, $mav);
			} catch(PageAccessDeniedException $e) {
				$pageHeader = $request->getAttachedVar(AttachedAliases::PAGE_HEADER);
				
				$pageHeader->
					add('WWW-Authenticate', 'Basic realm="Enter you auth data"')->
					add($request->getServerVar('SERVER_PROTOCOL').' 401 Unauthorized');
				
				return
					ModelAndView::create()->
					setView(
						PhpView::create()->
						loadLayout(
							File::create()->
							setPath(
								dirname(__FILE__).'/../../view/php/401unauthorized.php'
							)
						)
					);
			}
		}
	}
?>