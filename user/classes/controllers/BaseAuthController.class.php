<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	abstract class BaseAuthController extends CmsActionChainController
	{
		const SUCCESS_LOGIN		= 1;
		const WRONG_LOGIN		= 2;
		const WRONG_PASSWORD	= 3;

		/**
		 * @return BaseAuthController
		 */
		public function __construct(ChainController $controller = null)
		{
			$this->
				addAction('login', 'login')->
				addAction('logout', 'logout')->
				setDefaultAction('showLoginForm');
			
			parent::__construct($controller);
		}
		
		protected function logout(HttpRequest $request, ModelAndView $mav)
		{
			Session::me()->start();
			Session::me()->drop('userId');
			Session::me()->save();
			
			return $this->continueHandleRequest($request, $mav);
		}
		
		protected function login(HttpRequest $request, ModelAndView $mav)
		{
			$this->attachBackurlForm($request, $mav);
						
			$user = null;
			$loginResult = null;

			$form = $this->createLoginForm();
			$this->importLoginForm($request, $form);
			
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
					$request->setAttachedVar(AttachedAliases::USER, $user);
	
					Session::me()->set('userId', $user->getId());
					Session::me()->save();
					
					if (
						$backurl = 
							$mav->getModel()->get('backurlForm')->getValue('backurl')
					) {
						$request->getAttachedVar(AttachedAliases::PAGE_HEADER)->
							addRedirect(
								HttpUrl::createFromString(base64_decode($backurl))
							);
					}
				}
			}

			$mav->getModel()->
				set('form', $form)->
				set('user', $user)->
				set('loginResult', $loginResult);
			
			return $this->continueHandleRequest($request, $mav);
		}
		
		protected function showLoginForm(HttpRequest $request, ModelAndView $mav)
		{
			$this->attachBackurlForm($request, $mav);
			
			return parent::handleRequest($request, $mav);
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

		protected function importLoginForm(HttpRequest $request, Form $form)
		{
			$form->import($request->getPost());
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

		private function attachBackurlForm(HttpRequest $request, ModelAndView $mav)
		{
			$backurlForm = 
				$this->createBackUrlForm()->
				import($request->getPost() + $request->getGet());
				
			if ($backurlForm->getErrors())
				throw new BadRequestException();

			$mav->getModel()->set('backurlForm', $backurlForm);
			
			return $mav;
		}
	}
?>