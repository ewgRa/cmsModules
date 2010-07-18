<?php
	/**
	 * Generated by meta builder!
	 * Do not edit this class!
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 */
	abstract class AutoUserDA extends CmsDatabaseRequester
	{
		protected $tableAlias = 'User';
		
		/**
		 * @return User
		 */
		public function insert(User $object)
		{
			$dbQuery = 'INSERT INTO '.$this->getTable().' SET ';
			$queryParts = array();
			$queryParams = array();
			
			if (!is_null($object->getLogin())) {
				$queryParts[] = 'login = ?';
				$queryParams[] = $object->getLogin();
			}
			
			if (!is_null($object->getPassword())) {
				$queryParts[] = 'password = ?';
				$queryParams[] = $object->getPassword();
			}
			
			$dbQuery .= join(', ', $queryParts);
			
			$this->db()->query(
				DatabaseQuery::create()->
				setQuery($dbQuery)->
				setValues($queryParams)
			);
			
			$object->setId($this->db()->getInsertedId());
			
			$this->dropCache();
			
			return $object;
		}

		/**
		 * @return AutoUserDA
		 */
		public function save(User $object)
		{
			$dbQuery = 'UPDATE '.$this->getTable().' SET ';
			
			$queryParts = array();
			$whereParts = array();
			$queryParams = array();
			
			$queryParts[] = 'login = ?';
			$queryParams[] = $object->getLogin();
			$queryParts[] = 'password = ?';
			$queryParams[] = $object->getPassword();
			
			$whereParts[] = 'id = ?';
			$queryParams[] = $object->getId();
			Assert::isNotEmpty($whereParts);
			
			$dbQuery .= join(', ', $queryParts).' WHERE '.join(' AND ', $whereParts);

			$this->db()->query(
				DatabaseQuery::create()->
				setQuery($dbQuery)->
				setValues($queryParams)
			);
			 
			$this->dropCache();
			
			return $object;
		}

		/**
		 * @return User
		 */
		protected function build(array $array)
		{
			return
				User::create()->
				setId($array['id'])->
				setLogin($array['login'])->
				setPassword($array['password']);
		}
	}
?>