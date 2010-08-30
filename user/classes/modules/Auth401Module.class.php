<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class Auth401Module extends BaseAuthModule
	{
		protected function importLoginForm(Form $form)
		{
			if ($this->getRequest()->hasServerVar('PHP_AUTH_USER'))
				$form->import($this->getRequest()->getServer());
			
			return $this;
		}	
		
		/**
		 * @return Form
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