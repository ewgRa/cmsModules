<?php
	namespace ewgraCmsModules;
	
	/**
	 * Generated by meta builder!
	 * Do not edit this class!
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 */
	abstract class AutoRightDA extends \ewgraCms\DatabaseRequester
	{
		protected $tableAlias = 'Right';
		
		/**
		 * @return Right
		 */
		public function insert(Right $object)
		{
			$dbQuery = 'INSERT INTO '.$this->getTable().' SET ';
			$queryParts = array();
			$queryParams = array();
			
			if (!is_null($object->getAlias())) {
				$queryParts[] = 'alias = ?';
				$queryParams[] = $object->getAlias();
			}
			
			if (!is_null($object->getName())) {
				$queryParts[] = 'name = ?';
				$queryParams[] = $object->getName();
			}
			
			if (!is_null($object->getRole())) {
				$queryParts[] = 'role = ?';
				$queryParams[] = $object->getRole();
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
		 * @return AutoRightDA
		 */
		public function save(Right $object)
		{
			$dbQuery = 'UPDATE '.$this->getTable().' SET ';
			
			$queryParts = array();
			$whereParts = array();
			$queryParams = array();
			
			$queryParts[] = 'alias = ?';
			$queryParams[] = $object->getAlias();
			$queryParts[] = 'name = ?';
			$queryParams[] = $object->getName();
			$queryParts[] = 'role = ?';
			$queryParams[] = $object->getRole();
			
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
		 * @return Right
		 */
		protected function build(array $array)
		{
			return
				Right::create()->
				setId($array['id'])->
				setAlias($array['alias'])->
				setName($array['name'])->
				setRole($array['role'] == true);
		}
	}
?>