<?php
	namespace ewgraCmsModules;
	
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class NewsController extends \ewgraCms\ActionChainController
	{
		const NEWS_LIMIT = 3;
		
		/**
		 * @return NewsController
		 */
		public function __construct(
			\ewgraFramework\ChainController $controller = null
		) {
			$this->
				addAction('list', 'getList')->
				setDefaultAction('list');
			
			parent::__construct($controller);
		}

		protected function getList(
			\ewgraFramework\HttpRequest $request,
			\ewgraFramework\ModelAndView $mav
		) {
			$newsList = News::da()->getList(self::NEWS_LIMIT);
			
			$newsDataList = array();
			
			$language =
				$request->getAttachedVar(\ewgraCms\AttachedAliases::LOCALIZER)->
				getRequestLanguage();
				 
			foreach ($newsList as $news) {
				$newsDataList[$news->getId()] =
					NewsData::da()->get($news, $language);
			}
			
			$mav->getModel()->
				set('newsList', $newsList)->
				set('newsDataList', $newsDataList);
				
			return $this->continueHandleRequest($request, $mav);
		}
	}
?>