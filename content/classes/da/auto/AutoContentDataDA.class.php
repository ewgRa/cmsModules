<?php
	namespace ewgraCmsModules;

	/**
	 * Generated by meta builder!
	 * Do not edit this class!
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 */
	abstract class AutoContentDataDA extends \ewgraCms\DatabaseRequester
	{
		protected $tableAlias = 'ContentData';

		/**
		 * @return ContentData
		 */
		public function insert(ContentData $object)
		{
			$dbQuery = 'INSERT INTO '.$this->getTable().' SET ';
			$queryParts = array();
			$queryParams = array();

			if (!is_null($object->getContentId())) {
				$queryParts[] = '`content_id` = ?';
				$queryParams[] = $object->getContentId();
			}

			if (!is_null($object->getLanguageId())) {
				$queryParts[] = '`language_id` = ?';
				$queryParams[] = $object->getLanguageId();
			}

			if (!is_null($object->getText())) {
				$queryParts[] = '`text` = ?';
				$queryParams[] = $object->getText();
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
		 * @return AutoContentDataDA
		 */
		public function save(ContentData $object)
		{
			$dbQuery = 'UPDATE '.$this->getTable().' SET ';

			$queryParts = array();
			$whereParts = array();
			$queryParams = array();

			$queryParts[] = '`content_id` = ?';
			$queryParams[] = $object->getContentId();
			$queryParts[] = '`language_id` = ?';
			$queryParams[] = $object->getLanguageId();
			$queryParts[] = '`text` = ?';
			$queryParams[] = $object->getText();

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
		 * @return AutoContentDataDA
		 */
		public function delete(ContentData $object)
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
		 * @return ContentData
		 */
		public function build(array $array)
		{
			return
				ContentData::create()->
				setId($array['id'])->
				setContentId($array['content_id'])->
				setLanguageId($array['language_id'])->
				setText($array['text']);
		}
	}
?>