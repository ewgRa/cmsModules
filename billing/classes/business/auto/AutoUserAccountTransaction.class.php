<?php
	namespace ewgraCmsModules;

	/**
	 * Generated by meta builder!
	 * Do not edit this class!
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 */
	abstract class AutoUserAccountTransaction
	{
		private $id = null;

		private $accountId = null;

		/**
		 * @var UserAccount
		 */
		private $account = null;

		/**
		 * @var \ewgraFramework\DateTime
		 */
		private $created = null;

		private $value = null;

		/**
		 * @return UserAccountTransactionDA
		 */
		public static function da()
		{
			return UserAccountTransactionDA::me();
		}

		/**
		 * @return UserAccountTransactionProto
		 */
		public static function proto()
		{
			return UserAccountTransactionProto::me();
		}

		/**
		 * @return AutoUserAccountTransaction
		 */
		public function setId($id)
		{
			$this->id = $id;

			return $this;
		}

		public function getId()
		{
			\ewgraFramework\Assert::isNotNull($this->id);
			return $this->id;
		}

		/**
		 * @return AutoUserAccountTransaction
		 */
		public function setAccountId($accountId)
		{
			$this->account = null;
			$this->accountId = $accountId;

			return $this;
		}

		public function getAccountId()
		{
			return $this->accountId;
		}

		/**
		 * @return AutoUserAccountTransaction
		 */
		public function setAccount(UserAccount $account)
		{
			$this->accountId = $account->getId();
			$this->account = $account;

			return $this;
		}

		/**
		 * @return UserAccount
		 */
		public function getAccount()
		{
			if (!$this->account && $this->getAccountId())
				$this->account = UserAccount::da()->getById($this->getAccountId());

			return $this->account;
		}

		/**
		 * @return AutoUserAccountTransaction
		 */
		public function setCreated(\ewgraFramework\DateTime $created)
		{
			$this->created = $created;

			return $this;
		}

		/**
		 * @return \ewgraFramework\DateTime
		 */
		public function getCreated()
		{
			return $this->created;
		}

		/**
		 * @return AutoUserAccountTransaction
		 */
		public function setValue($value)
		{
			$this->value = $value;

			return $this;
		}

		public function getValue()
		{
			return $this->value;
		}
	}
?>