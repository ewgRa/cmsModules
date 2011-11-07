<?php
	namespace ewgraCmsModules;

	/**
	 * Generated by meta builder!
	 * Do not edit this class!
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 */
	abstract class AutoNewsDA extends \ewgraCms\DatabaseRequester
	{
		protected $tableAlias = 'news';

		/**
		 * @return News
		 */
		public function insert(News $object)
		{
			$dialect = $this->db()->getDialect();

			$dbQuery = 'INSERT INTO '.$this->getTable().' ';
			$fields = array();
			$fieldValues = array();
			$values = array();
			$fields[] = $dialect->escapeField('uri');
			$fieldValues[] = '?';
			$values[] = $object->getUri();
			$fields[] = $dialect->escapeField('created');
			$fieldValues[] = '?';
			$values[] = $object->getCreated();
			$fields[] = $dialect->escapeField('modified');
			$fieldValues[] = '?';
			$values[] = $object->getModified();
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
		 * @return AutoNewsDA
		 */
		public function save(News $object)
		{
			$dialect = $this->db()->getDialect();
			$dbQuery = 'UPDATE '.$this->getTable().' SET ';

			$queryParts = array();
			$whereParts = array();
			$queryParams = array();

			$queryParts[] = $dialect->escapeField('uri').' = ?';
			$queryParams[] = $object->getUri();
			$queryParts[] = $dialect->escapeField('created').' = ?';
			$queryParams[] = $object->getCreated();
			$queryParts[] = $dialect->escapeField('modified').' = ?';
			$queryParams[] = $object->getModified();

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
		 * @return AutoNewsDA
		 */
		public function delete(News $object)
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
		 * @return News
		 */
		public function build(array $array)
		{
			return
				News::create()->
				setId($array['id'])->
				setUri($array['uri'])->
				setCreated($array['created'])->
				setModified($array['modified']);
		}

		public function dropCache()
		{
			NewsData::da()->dropCache();
			return parent::dropCache();
		}
	}
?>