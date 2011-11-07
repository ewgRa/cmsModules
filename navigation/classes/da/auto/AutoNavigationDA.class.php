<?php
	namespace ewgraCmsModules;

	/**
	 * Generated by meta builder!
	 * Do not edit this class!
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 */
	abstract class AutoNavigationDA extends \ewgraCms\DatabaseRequester
	{
		protected $tableAlias = 'navigation';

		/**
		 * @return Navigation
		 */
		public function insert(Navigation $object)
		{
			$dialect = $this->db()->getDialect();

			$dbQuery = 'INSERT INTO '.$this->getTable().' ';
			$fields = array();
			$fieldValues = array();
			$values = array();
			$fields[] = $dialect->escapeField('category_id');
			$fieldValues[] = '?';
			$values[] = $object->getCategoryId();
			$fields[] = $dialect->escapeField('uri');
			$fieldValues[] = '?';
			$values[] = $object->getUri()->__toString();
			$fields[] = $dialect->escapeField('position');
			$fieldValues[] = '?';
			$values[] = $object->getPosition();
			$fields[] = $dialect->escapeField('status');
			$fieldValues[] = '?';
			$values[] = $object->getStatus()->getId();
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
		 * @return AutoNavigationDA
		 */
		public function save(Navigation $object)
		{
			$dialect = $this->db()->getDialect();
			$dbQuery = 'UPDATE '.$this->getTable().' SET ';

			$queryParts = array();
			$whereParts = array();
			$queryParams = array();

			$queryParts[] = $dialect->escapeField('category_id').' = ?';
			$queryParams[] = $object->getCategoryId();
			$queryParts[] = $dialect->escapeField('uri').' = ?';
			$queryParams[] = $object->getUri()->__toString();
			$queryParts[] = $dialect->escapeField('position').' = ?';
			$queryParams[] = $object->getPosition();
			$queryParts[] = $dialect->escapeField('status').' = ?';
			$queryParams[] = $object->getStatus()->getId();

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
		 * @return AutoNavigationDA
		 */
		public function delete(Navigation $object)
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
			return $this->getListCachedByQuery(
				\ewgraFramework\DatabaseQuery::create()->
				setQuery('SELECT * FROM '.$this->getTable().' WHERE id IN(?)')->
				setValues(array($ids))
			);
		}

		/**
		 * @return Navigation
		 */
		public function build(array $array)
		{
			return
				Navigation::create()->
				setId($array['id'])->
				setCategoryId($array['category_id'])->
				setUri(\ewgraFramework\HttpUrl::createFromString($array['uri']))->
				setPosition($array['position'])->
				setStatus(NavigationStatus::create($array['status']));
		}

		public function dropCache()
		{
			NavigationData::da()->dropCache();
			return parent::dropCache();
		}
	}
?>