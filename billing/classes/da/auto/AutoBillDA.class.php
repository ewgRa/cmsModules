<?php
	namespace ewgraCmsModules;

	/**
	 * Generated by meta builder!
	 * Do not edit this class!
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 */
	abstract class AutoBillDA extends \ewgraCms\DatabaseRequester
	{
		protected $tableAlias = 'bill';

		public function getTag()
		{
			return '\ewgraCmsModules\Bill';
		}

		/**
		 * @return array
		 */
		public function getTagList()
		{
			return array($this->getTag());
		}

		/**
		 * @return Bill
		 */
		public function insert(Bill $object)
		{
			$result = $this->rawInsert($object);
			$this->dropCache();
			return $result;
		}

		/**
		 * @return Bill
		 */
		public function rawInsert(Bill $object)
		{
			$dialect = $this->db()->getDialect();

			$dbQuery = 'INSERT INTO '.$this->getTable().' ';
			$fields = array();
			$fieldValues = array();
			$values = array();

			if ($object->hasId()) {
				$fields[] = $dialect->escapeField('id');
				$fieldValues[] = '?';
				$values[] = $object->getId();
			}

			$fields[] = $dialect->escapeField('created');
			$fieldValues[] = '?';
			$values[] = $object->getCreated()->__toString();
			$fields[] = $dialect->escapeField('alias');
			$fieldValues[] = '?';
			$values[] = $object->getAlias();
			$fields[] = $dialect->escapeField('balance');
			$fieldValues[] = '?';
			$values[] = $object->getBalance();
			$dbQuery .= '('.join(', ', $fields).') VALUES ';
			$dbQuery .= '('.join(', ', $fieldValues).')';

			$dbResult =
				$this->db()->insertQuery(
					\ewgraFramework\DatabaseInsertQuery::create()->
					setPrimaryField('id')->
					setQuery($dbQuery)->
					setValues($values)
				);

			if (!$object->hasId())
				$object->setId($dbResult->getInsertedId());

			return $object;
		}

		/**
		 * @return AutoBillDA
		 */
		public function save(Bill $object)
		{
			$result = $this->rawSave($object);
			$this->dropCache();
			return $result;
		}

		/**
		 * @return AutoBillDA
		 */
		public function rawSave(Bill $object)
		{
			$dialect = $this->db()->getDialect();
			$dbQuery = 'UPDATE '.$this->getTable().' SET ';

			$queryParts = array();
			$whereParts = array();
			$queryParams = array();

			$queryParts[] = $dialect->escapeField('created').' = ?';
			$queryParams[] = $object->getCreated()->__toString();
			$queryParts[] = $dialect->escapeField('alias').' = ?';
			$queryParams[] = $object->getAlias();
			$queryParts[] = $dialect->escapeField('balance').' = ?';
			$queryParams[] = $object->getBalance();

			$whereParts[] = 'id = ?';
			$queryParams[] = $object->getId();
			\ewgraFramework\Assert::isNotEmpty($whereParts);

			$dbQuery .= join(', ', $queryParts).' WHERE '.join(' AND ', $whereParts);

			$this->db()->query(
				\ewgraFramework\DatabaseQuery::create()->
				setQuery($dbQuery)->
				setValues($queryParams)
			);

			return $object;
		}

		/**
		 * @return AutoBillDA
		 */
		public function delete(Bill $object)
		{
			$result = $this->rawDelete($object);
			$this->dropCache();
			return $result;
		}

		/**
		 * @return AutoBillDA
		 */
		public function rawDelete(Bill $object)
		{
			$dbQuery =
				'DELETE FROM '.$this->getTable().' WHERE id = '.$object->getId();

			$this->db()->query(
				\ewgraFramework\DatabaseQuery::create()->setQuery($dbQuery)
			);

			$object->setId(null);

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
		 * @return Bill
		 */
		public function build(array $array)
		{
			return
				Bill::create()->
				setId($array['id'])->
				setCreated(\ewgraFramework\DateTime::createFromString($array['created']))->
				setAlias($array['alias'])->
				setBalance($array['balance']);
		}
	}
?>