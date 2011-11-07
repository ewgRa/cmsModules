<?php
	namespace ewgraCmsModules;

	/**
	 * Generated by meta builder!
	 * Do not edit this class!
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 */
	abstract class AutoPageRightDA extends \ewgraCms\DatabaseRequester
	{
		protected $tableAlias = 'page_right';

		/**
		 * @return PageRight
		 */
		public function insert(PageRight $object)
		{
			$dialect = $this->db()->getDialect();

			$dbQuery = 'INSERT INTO '.$this->getTable().' ';
			$fields = array();
			$fieldValues = array();
			$values = array();
			$fields[] = $dialect->escapeField('page_id');
			$fieldValues[] = '?';
			$values[] = $object->getPageId();
			$fields[] = $dialect->escapeField('right_id');
			$fieldValues[] = '?';
			$values[] = $object->getRightId();
			$fields[] = $dialect->escapeField('redirect_page_id');
			$fieldValues[] = '?';
			$values[] = $object->getRedirectPageId();
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
		 * @return AutoPageRightDA
		 */
		public function save(PageRight $object)
		{
			$dialect = $this->db()->getDialect();
			$dbQuery = 'UPDATE '.$this->getTable().' SET ';

			$queryParts = array();
			$whereParts = array();
			$queryParams = array();

			$queryParts[] = $dialect->escapeField('page_id').' = ?';
			$queryParams[] = $object->getPageId();
			$queryParts[] = $dialect->escapeField('right_id').' = ?';
			$queryParams[] = $object->getRightId();
			$queryParts[] = $dialect->escapeField('redirect_page_id').' = ?';
			$queryParams[] = $object->getRedirectPageId();

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
		 * @return AutoPageRightDA
		 */
		public function delete(PageRight $object)
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
		 * @return PageRight
		 */
		public function build(array $array)
		{
			return
				PageRight::create()->
				setId($array['id'])->
				setPageId($array['page_id'])->
				setRightId($array['right_id'])->
				setRedirectPageId($array['redirect_page_id']);
		}
	}
?>