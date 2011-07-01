<?php
	namespace ewgraCmsModules;

	/**
	 * Generated by meta builder, you can edit this class
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 */
	final class NewsDataDA extends AutoNewsDataDA
	{
		/**
		 * @return NewsDataDA
		 * method needed for methods hinting
		 */
		public static function me()
		{
			return parent::me();
		}

		/**
		 * @return NewsData
		 */
		public function get(News $news, \ewgraCms\Language $language)
		{
			$dbQuery = "
				SELECT * FROM " . $this->getTable() . "
				WHERE news_id = ? AND language_id = ?
			";

			return $this->getCachedByQuery(
				\ewgraFramework\DatabaseQuery::create()->
				setQuery($dbQuery)->
				setValues(array($news->getId(), $language->getId()))
			);
		}
	}
?>