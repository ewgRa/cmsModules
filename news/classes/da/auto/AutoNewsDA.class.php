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
		protected $tableAlias = 'News';

		/**
		 * @return News
		 */
		public function insert(News $object)
		{
			$dbQuery = 'INSERT INTO '.$this->getTable().' SET ';
			$queryParts = array();
			$queryParams = array();

			if (!is_null($object->getUri())) {
				$queryParts[] = '`uri` = ?';
				$queryParams[] = $object->getUri();
			}

			if (!is_null($object->getCreated())) {
				$queryParts[] = '`created` = ?';
				$queryParams[] = $object->getCreated();
			}

			if (!is_null($object->getModified())) {
				$queryParts[] = '`modified` = ?';
				$queryParams[] = $object->getModified();
			}

			$dbQuery .= join(', ', $queryParts);

			$this->db()->query(
				\ewgraFramework\DatabaseQuery::create()->
				setQuery($dbQuery)->
				setValues($queryParams)
			);

			$object->setId($this->db()->getInsertedId());

			$this->dropCache();

			return $object;
		}

		/**
		 * @return AutoNewsDA
		 */
		public function save(News $object)
		{
			$dbQuery = 'UPDATE '.$this->getTable().' SET ';

			$queryParts = array();
			$whereParts = array();
			$queryParams = array();

			$queryParts[] = '`uri` = ?';
			$queryParams[] = $object->getUri();
			$queryParts[] = '`created` = ?';
			$queryParams[] = $object->getCreated();
			$queryParts[] = '`modified` = ?';
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