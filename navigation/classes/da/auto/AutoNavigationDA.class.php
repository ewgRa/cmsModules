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
		protected $tableAlias = 'Navigation';
		
		/**
		 * @return Navigation
		 */
		public function insert(Navigation $object)
		{
			$dbQuery = 'INSERT INTO '.$this->getTable().' SET ';
			$queryParts = array();
			$queryParams = array();
			
			if (!is_null($object->getCategoryId())) {
				$queryParts[] = 'category_id = ?';
				$queryParams[] = $object->getCategoryId();
			}
			
			if (!is_null($object->getUri())) {
				$queryParts[] = 'uri = ?';
				$queryParams[] = $object->getUri()->__toString();
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
		 * @return AutoNavigationDA
		 */
		public function save(Navigation $object)
		{
			$dbQuery = 'UPDATE '.$this->getTable().' SET ';
			
			$queryParts = array();
			$whereParts = array();
			$queryParams = array();
			
			$queryParts[] = 'category_id = ?';
			$queryParams[] = $object->getCategoryId();
			$queryParts[] = 'uri = ?';
			$queryParams[] = $object->getUri()->__toString();
			
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
		 * @return Navigation
		 */
		protected function build(array $array)
		{
			return
				Navigation::create()->
				setId($array['id'])->
				setCategoryId($array['category_id'])->
				setUri(\ewgraFramework\HttpUrl::createFromString($array['uri']));
		}

		public function dropCache()
		{
			NavigationData::da()->dropCache();
			return parent::dropCache();
		}
	}
?>