<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	abstract class BaseAuthModule extends CmsActionModule
	{
		const SUCCESS_LOGIN		= 1;
		const WRONG_LOGIN		= 2;
		const WRONG_PASSWORD	= 3;

		public function __construct()
		{
			$this->
				addAction('login', 'login')->
				addAction('logout', 'logout')->
				setDefaultAction('login');
		}
		
		protected function logout()
		{
			Session::me()->start();
			Session::me()->drop('userId');
			Session::me()->save();
			
			return Model::create();
		}
		
		protected function login()
		{
			$user = null;
			$loginResult = null;

			$backurlForm = 
				$this->createBackUrlForm()->
				import(
					$this->getRequest()->getPost()
					+ $this->getRequest()->getGet()
				);
				
			if ($backurlForm->getErrors())
				throw new BadRequestException();
				
			$form = $this->createLoginForm();
			$this->importLoginForm($form);
			
			if ($form->isImported()) {
				
				if (!$form->getErrors()) {
					
					Session::me()->start();
					Session::me()->drop('userId');
					Session::me()->save();
		
					$loginResult = self::SUCCESS_LOGIN;
					
					$user = User::da()->getByLogin($form->getValue('login'));
					
					if (!$user)
						$loginResult = self::WRONG_LOGIN;
					
					if (
						$user 
						&& $user->getPassword() != md5($form->getValue('password'))
					)
						$loginResult = self::WRONG_PASSWORD;
					
					if ($loginResult == self::SUCCESS_LOGIN) {
						$this->getRequest()->setAttachedVar(AttachedAliases::USER, $user);
		
						Session::me()->set('userId', $user->getId());
						Session::me()->save();
						
						if ($backurl = $backurlForm->getValue('backurl')) {
							$this->getPageHeader()->addRedirect(
								HttpUrl::createFromString(base64_decode($backurl))
							);
						}
					}
				}
			}

			return 
				Model::create()->
				set('form', $form)->
				set('backurlForm', $backurlForm)->
				set('user', $user)->
				set('loginResult', $loginResult);
		}
		
		/**
		 * @return Form
		 */
		protected function createLoginForm()
		{
			return
				Form::create()->
				addPrimitive(
					PrimitiveString::create('login')->setRequired()
				)->
				addPrimitive(
					PrimitiveString::create('password')->setRequired()
				);
		}

		protected function importLoginForm(Form $form)
		{
			$form->import($this->getRequest()->getPost());
			return $this;
		}	

		/**
		 * @return Model
		 */
		private function createBackurlForm()
		{
			return
				Form::create()->
				addPrimitive(PrimitiveString::create('backurl'));
		}
	}
?>