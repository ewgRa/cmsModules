<?php
	namespace ewgraCmsModules;

	/**
	 * Generated by meta builder!
	 * Do not edit this class!
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 */
	abstract class AutoBillTransactionDA extends \ewgraCms\DatabaseRequester
	{
		protected $tableAlias = 'bill_transaction';

		/**
		 * @return BillTransaction
		 */
		public function insert(BillTransaction $object)
		{
			$dialect = $this->db()->getDialect();

			$dbQuery = 'INSERT INTO '.$this->getTable().' ';
			$fields = array();
			$fieldValues = array();
			$values = array();
			$fields[] = $dialect->escapeField('created');
			$fieldValues[] = '?';
			$values[] = $object->getCreated()->__toString();
			$fields[] = $dialect->escapeField('purpose');
			$fieldValues[] = '?';
			$values[] = $object->getPurpose();
			$fields[] = $dialect->escapeField('debit_id');
			$fieldValues[] = '?';
			$values[] = $object->getDebitId();
			$fields[] = $dialect->escapeField('credit_id');
			$fieldValues[] = '?';
			$values[] = $object->getCreditId();
			$fields[] = $dialect->escapeField('value');
			$fieldValues[] = '?';
			$values[] = $object->getValue();
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
		 * @return AutoBillTransactionDA
		 */
		public function save(BillTransaction $object)
		{
			$dialect = $this->db()->getDialect();
			$dbQuery = 'UPDATE '.$this->getTable().' SET ';

			$queryParts = array();
			$whereParts = array();
			$queryParams = array();

			$queryParts[] = $dialect->escapeField('created').' = ?';
			$queryParams[] = $object->getCreated()->__toString();
			$queryParts[] = $dialect->escapeField('purpose').' = ?';
			$queryParams[] = $object->getPurpose();
			$queryParts[] = $dialect->escapeField('debit_id').' = ?';
			$queryParams[] = $object->getDebitId();
			$queryParts[] = $dialect->escapeField('credit_id').' = ?';
			$queryParams[] = $object->getCreditId();
			$queryParts[] = $dialect->escapeField('value').' = ?';
			$queryParams[] = $object->getValue();

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
		 * @return AutoBillTransactionDA
		 */
		public function delete(BillTransaction $object)
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
		 * @return BillTransaction
		 */
		public function build(array $array)
		{
			return
				BillTransaction::create()->
				setId($array['id'])->
				setCreated(\ewgraFramework\DateTime::createFromString($array['created']))->
				setPurpose($array['purpose'])->
				setDebitId($array['debit_id'])->
				setCreditId($array['credit_id'])->
				setValue($array['value']);
		}
	}
?>