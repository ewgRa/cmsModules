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
				setDefaultAction('showLoginForm');
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
			$model = $this->showLoginForm();
			
			$user = null;
			$loginResult = null;

			$form = $this->createLoginForm();
			$this->importLoginForm($form);
			
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
					
					if ($backurl = $model->get('backurlForm')->getValue('backurl')) {
						$this->getPageHeader()->addRedirect(
							HttpUrl::createFromString(base64_decode($backurl))
						);
					}
				}
			}

			return 
				$model->
				set('form', $form)->
				set('user', $user)->
				set('loginResult', $loginResult);
		}
		
		protected function showLoginForm()
		{
			$backurlForm = 
				$this->createBackUrlForm()->
				import(
					$this->getRequest()->getPost()
					+ $this->getRequest()->getGet()
				);
				
			if ($backurlForm->getErrors())
				throw new BadRequestException();

			return 
				Model::create()->
				set('backurlForm', $backurlForm);
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