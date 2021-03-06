<?php
	namespace ewgraCmsModules;

	/**
	 * Generated by meta builder, you can edit this class
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 */
	final class UserRightDA extends AutoUserRightDA
	{
		/**
		 * @return UserRightDA
		 * method needed for methods hinting
		 */
		public static function me()
		{
			return parent::me();
		}

		public function getRightIdsByUser(User $user)
		{
			$userRights = $this->getByUser($user);

			$rightIds = array();

			foreach ($userRights as $userRight)
				$rightIds[] = $userRight->getRightId();

			return $rightIds;
		}

		public function getByUser(User $user)
		{
			return $this->getListCachedByQuery(
				\ewgraFramework\DatabaseQuery::create()->
				setQuery("SELECT * FROM ".$this->getTable()." WHERE user_id = ?")->
				setValues(array($user->getId()))
			);
		}

		public function getByUserRight(User $user, Right $right)
		{
			$query = "SELECT * FROM ".$this->getTable()." WHERE user_id = ? AND right_id = ?";
			$values = array($user->getId(), $right->getId());

			return $this->getCachedByQuery(
				\ewgraFramework\DatabaseQuery::create()->
				setQuery($query)->
				setValues($values)
			);
		}
	}
?>