<?php
	namespace ewgraCmsModules;

	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	abstract class BaseAuthController extends \ewgraCms\ActionChainController
	{
		const SUCCESS_LOGIN		= 1;
		const WRONG_LOGIN		= 2;
		const WRONG_PASSWORD	= 3;

		/**
		 * @return BaseAuthController
		 */
		public function __construct(\ewgraFramework\ChainController $controller = null)
		{
			$this->
				addAction('login', 'login')->
				addAction('logout', 'logout')->
				addAction('showLoginForm', 'showLoginForm')->
				setDefaultAction('showLoginForm');

			parent::__construct($controller);
		}

		protected function logout(
			\ewgraFramework\HttpRequest $request,
			\ewgraFramework\ModelAndView $mav
		) {
			\ewgraFramework\Session::me()->start();
			\ewgraFramework\Session::me()->drop('userId');

			return $this->continueHandleRequest($request, $mav);
		}

		protected function login(
			\ewgraFramework\HttpRequest $request,
			\ewgraFramework\ModelAndView $mav
		) {
			$this->attachBackurlForm($request, $mav);

			$user = null;
			$loginResult = null;

			$form = $this->createLoginForm();
			$this->importLoginForm($request, $form);

			if (!$form->getErrors()) {

				\ewgraFramework\Session::me()->start();
				\ewgraFramework\Session::me()->drop('userId');

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
					$request->setAttachedVar(\ewgraCms\AttachedAliases::USER, $user);

					\ewgraFramework\Session::me()->set('userId', $user->getId());

					if (
						$backurl =
							$mav->getModel()->get('backurlForm')->getValue('backurl')
					) {
						$request->getAttachedVar(\ewgraCms\AttachedAliases::PAGE_HEADER)->
							addRedirect(
								\ewgraFramework\HttpUrl::createFromString(base64_decode($backurl))
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

		protected function showLoginForm(
			\ewgraFramework\HttpRequest $request,
			\ewgraFramework\ModelAndView $mav
		) {
			$this->attachBackurlForm($request, $mav);

			return $this->continueHandleRequest($request, $mav);
		}

		/**
		 * @return Form
		 */
		protected function createLoginForm()
		{
			return
				\ewgraFramework\Form::create()->
				addPrimitive(
					\ewgraFramework\PrimitiveString::create('login')->setRequired()
				)->
				addPrimitive(
					\ewgraFramework\PrimitiveString::create('password')->setRequired()
				);
		}

		protected function importLoginForm(
			\ewgraFramework\HttpRequest $request,
			\ewgraFramework\Form $form
		) {
			$form->import($request->getPost());
			return $this;
		}

		/**
		 * @return \ewgraFramework\Form
		 */
		private function createBackurlForm()
		{
			return
				\ewgraFramework\Form::create()->
				addPrimitive(\ewgraFramework\PrimitiveString::create('backurl'));
		}

		private function attachBackurlForm(
			\ewgraFramework\HttpRequest $request,
			\ewgraFramework\ModelAndView $mav
		) {
			$backurlForm =
				$this->createBackUrlForm()->
				import($request->getPost() + $request->getGet());

			if ($backurlForm->getErrors())
				throw new \ewgraCms\BadRequestException();

			$mav->getModel()->set('backurlForm', $backurlForm);

			return $mav;
		}
	}
?>