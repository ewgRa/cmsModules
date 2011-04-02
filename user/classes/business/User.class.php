<?php
	namespace ewgraCmsModules;

	/**
	 * Generated by meta builder, you can edit this class
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 */
	final class User extends AutoUser
	{
		/**
		 * @return User
		 */
		public static function create()
		{
			return new self;
		}

		public function getRights()
		{
			return UserRight::da()->getByUser($this);
		}

		public function hasRightAlias($alias)
		{
			foreach ($this->getRights() as $userRight) {
				if ($userRight->getRight()->getAlias() == $alias)
					return true;
			}

			return false;
		}

		public function checkAccess(array $rights = null)
		{
			if (!$rights)
				return true;

			$result = true;

			$inheritanceRights =
				Right::da()->getByInheritanceIds(array_keys($rights));

			$nextInheritanceRights = $inheritanceRights;

			while ($nextInheritanceRights) {
				$inheritanceIds = array();

				foreach ($inheritanceRights as $right) {
					if (!isset($this->inheritanceRights[$right->getId()])) {
						$inheritanceRights[$right->getId()] = $right;
						$inheritanceIds[] = $right->getId();
					}
				}

				$nextInheritanceRights =
					Right::da()->getByInheritanceIds($inheritanceIds);
			}

			$intersectRights = array();

			$userRightIds = UserRight::da()->getRightIdsByUser($this);

			$intersectRights = array_intersect(
				array_merge(array_keys($rights), array_keys($inheritanceRights)),
				$userRightIds
			);

			if (!count($intersectRights))
				$result = false;

			return $result;
		}
	}
?>