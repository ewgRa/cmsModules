<?php
	namespace ewgraCmsModules;

	/**
	 * Generated by meta builder!
	 * Do not edit this class!
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 */
	abstract class AutoUserDA extends \ewgraCms\DatabaseRequester
	{
		protected $tableAlias = 'user';

		/**
		 * @return User
		 */
		public function insert(User $object)
		{
			$dialect = $this->db()->getDialect();

			$dbQuery = 'INSERT INTO '.$this->getTable().' ';
			$fields = array();
			$fieldValues = array();
			$values = array();
			$fields[] = $dialect->escapeField('login');
			$fieldValues[] = '?';
			$values[] = $object->getLogin();
			$fields[] = $dialect->escapeField('password');
			$fieldValues[] = '?';
			$values[] = $object->getPassword();
			$fields[] = $dialect->escapeField('change_password_hash');
			$fieldValues[] = '?';

			if ($object->getChangePasswordHash() === null)
				$values[] = null;
			else {
				$values[] = $object->getChangePasswordHash();
			}

			$fields[] = $dialect->escapeField('email');
			$fieldValues[] = '?';
			$values[] = $object->getEmail();
			$fields[] = $dialect->escapeField('email_confirm_hash');
			$fieldValues[] = '?';

			if ($object->getEmailConfirmHash() === null)
				$values[] = null;
			else {
				$values[] = $object->getEmailConfirmHash();
			}

			$dbQuery .= '('.join(', ', $fields).') VALUES ';
			$dbQuery .= '('.join(', ', $fieldValues).')';

			$dbResult =
				$this->db()->insertQuery(
					\ewgraFramework\DatabaseInsertQuery::create()->
					setPrimaryField('id')->
					setQuery($dbQuery)->
					setValues($values)
				);

			$object->setId($dbResult->getInsertedId());

			$this->dropCache();

			return $object;
		}

		/**
		 * @return AutoUserDA
		 */
		public function save(User $object)
		{
			$dialect = $this->db()->getDialect();
			$dbQuery = 'UPDATE '.$this->getTable().' SET ';

			$queryParts = array();
			$whereParts = array();
			$queryParams = array();

			$queryParts[] = $dialect->escapeField('login').' = ?';
			$queryParams[] = $object->getLogin();
			$queryParts[] = $dialect->escapeField('password').' = ?';
			$queryParams[] = $object->getPassword();

			if ($object->getChangePasswordHash() === null)
				$queryParts[] = $dialect->escapeField('change_password_hash').' = NULL';
			else {
				$queryParts[] = $dialect->escapeField('change_password_hash').' = ?';
				$queryParams[] = $object->getChangePasswordHash();
			}

			$queryParts[] = $dialect->escapeField('email').' = ?';
			$queryParams[] = $object->getEmail();

			if ($object->getEmailConfirmHash() === null)
				$queryParts[] = $dialect->escapeField('email_confirm_hash').' = NULL';
			else {
				$queryParts[] = $dialect->escapeField('email_confirm_hash').' = ?';
				$queryParams[] = $object->getEmailConfirmHash();
			}


			$whereParts[] = 'id = ?';
			$queryParams[] = $object->getId();
			\ewgraFramework\Assert::isNotEmpty($whereParts);

			$dbQuery .= join(', ', $queryParts).' WHERE '.join(' AND ', $whereParts);

			$this->db()->query(
				\ewgraFramework\DatabaseQuery::create()->
				setQuery($dbQuery)->
				setValues($queryParams)
			);

			$this->dropCache();

			return $object;
		}

		/**
		 * @return AutoUserDA
		 */
		public function delete(User $object)
		{
			$dbQuery =
				'DELETE FROM '.$this->getTable().' WHERE id = '.$object->getId();

			$this->db()->query(
				\ewgraFramework\DatabaseQuery::create()->setQuery($dbQuery)
			);

			$object->setId(null);

			$this->dropCache();

			return $this;
		}

		public function getById($id)
		{
			return $this->getCachedByQuery(
				\ewgraFramework\DatabaseQuery::create()->
				setQuery('SELECT * FROM '.$this->getTable().' WHERE id = ?')->
				setValues(array($id))
			);
		}

		public function getByIds(array $ids)
		{
			if (!$ids)
				return array();

			return $this->getListCachedByQuery(
				\ewgraFramework\DatabaseQuery::create()->
				setQuery('SELECT * FROM '.$this->getTable().' WHERE id IN(?)')->
				setValues(array($ids))
			);
		}

		/**
		 * @return User
		 */
		public function build(array $array)
		{
			return
				User::create()->
				setId($array['id'])->
				setLogin($array['login'])->
				setPassword($array['password'])->
				setChangePasswordHash($array['change_password_hash'])->
				setEmail($array['email'])->
				setEmailConfirmHash($array['email_confirm_hash']);
		}
	}
?>