<?php
	namespace ewgraCmsModules;
	
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class Auth401Controller extends BaseAuthController
	{
		public static function create(\ewgraFramework\ChainController $controller = null)
		{
			return new self($controller);	
		}
		
		protected function importLoginForm(
			\ewgraFramework\HttpRequest $request, 
			\ewgraFramework\Form $form
		) {
			if ($request->hasServerVar('PHP_AUTH_USER'))
				$form->import($request->getServer());
			
			return $this;
		}	
		
		/**
		 * @return \ewgraFramework\Form
		 */
		protected function createLoginForm()
		{
			$form = parent::createLoginForm();
			
			$form->getPrimitive('login')->setScopeKey('PHP_AUTH_USER');
			$form->getPrimitive('password')->setScopeKey('PHP_AUTH_PW');
			
			return $form;
		}
	}
?>