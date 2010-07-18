<?php
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class LoginModule extends CmsModule
	{
		const SUCCESS_LOGIN		= 1;
		const WRONG_LOGIN		= 2;
		const WRONG_PASSWORD	= 3;
		
		private $source = null;
		
		private $mode = null;
		
		/**
		 * @return LoginModule
		 */
		public function setMode($mode)
		{
			$this->mode = $mode;
			return $this;
		}
		
		public function getMode()
		{
			return $this->mode;
		}
				
		/**
		 * @return LoginModule
		 */
		public function setSource($source)
		{
			$this->source = $source;
			return $this;
		}
		
		public function getSource()
		{
			return $this->source;
		}
				
		/**
		 * @return LoginModule
		 */
		public function importSettings(array $settings = null)
		{
			$this->setSource($settings['source']);

			$this->setMode(
				isset($settings['mode'])
					? $settings['mode']
					: null
			);
			
			return $this;
		}
		
		/**
		 * @return Model
		 */
		public function getModel()
		{
			$model = Model::create();
			
			switch ($this->getMode()) {
				case 'logout':
						Session::me()->start();
						Session::me()->drop('userId');
						Session::me()->save();
					break;
				default:
					$user = null;
					$loginResult = null;
		
					try {
						$requestModel = $this->getRequestModel();
					} catch (BadRequestException $e) {
						$requestModel = Model::create();
					}
					
					if ($requestModel->has('login')) {
						Session::me()->start();
						Session::me()->drop('userId');
						Session::me()->save();
		
						$loginResult = self::SUCCESS_LOGIN;
						
						$user = User::da()->getByLogin($requestModel->get('login'));
						
						if (!$user)
							$loginResult = self::WRONG_LOGIN;
						
						if ($user && $user->getPassword() != md5($requestModel->get('password')))
							$loginResult = self::WRONG_PASSWORD;
						
						if ($loginResult == self::SUCCESS_LOGIN) {
							$this->getRequest()->setAttachedVar(AttachedAliases::USER, $user);
			
							Session::me()->set('userId', $user->getId());
							Session::me()->save();
							
							if ($requestModel->has('backurl')) {
								$this->getPageHeader()->addRedirect(
									HttpUrl::createFromString(
										base64_decode($requestModel->get('backurl'))
									)
								);
							}
						}
					}
		
					$backurl =
						$requestModel->has('backurl')
							? $requestModel->get('backurl')
							: null;
							
					if (!$backurl) {
						$backurl =
							$this->getRequest()->hasGetVar('backurl')
								? $this->getRequest()->getGetVar('backurl')
								: null;
					}
					
					$model->
						set('source', $this->getSource())->
						set('backurl', $backurl)->
						set('user', $user)->
						set('loginResult', $loginResult);
				break;
			}
			
			return $model;
		}

		/**
		 * @return Model
		 * TODO: Form and primitives
		 */
		private function getRequestModel()
		{
			$result = Model::create();
			
			$keys = array(
				'login' => 'login',
				'password' => 'password',
				'backurl' => 'backurl'
			);
			
			$function = null;
			
			switch ($this->getSource()) {
				case 'get':
					$function = 'getGetVar';
					break;
				case 'server':
					$function = 'getServerVar';
					
					$keys = array(
						'login' => 'PHP_AUTH_USER',
						'password' => 'PHP_AUTH_PW'
					);
					
					break;
				case 'post':
					$function = 'getPostVar';
					break;
				default:
					Assert::isUnreachable();
					break;
			}
			
			foreach ($keys as $key => $varKey) {
				try {
					$result->set($key, $this->getRequest()->{$function}($varKey));
				} catch (MissingArgumentException $e) {
					if ($key != 'backurl')
						throw BadRequestException::create();
				}
			}
			
			return $result;
		}
	}
?>