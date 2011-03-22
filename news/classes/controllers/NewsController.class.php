<?php
	namespace ewgraCmsModules;
	
	/**
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	*/
	final class NewsController extends \ewgraCms\ActionChainController
		implements \ewgraCms\PageHeadReplacerInterface
	{
		const NEWS_LIMIT = 3;
		
		private $newsData = null;
		
		/**
		 * @return NewsController
		 */
		public function __construct(
			\ewgraFramework\ChainController $controller = null
		) {
			$this->
				addAction('list', 'getList')->
				addAction('getByUri', 'getByUri')->
				setDefaultAction('list');
			
			parent::__construct($controller);
		}

		public function replacePageData(\ewgraCms\PageData $pageData)
		{
			if ($this->newsData) {
				$pageData->setTitle(
					str_replace(
						'%TITLE%',
						$this->newsData->getTitle(),
						$pageData->getTitle()
					)
				);
			}
				
			return $this;
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

		protected function getByUri(
			\ewgraFramework\HttpRequest $request,
			\ewgraFramework\ModelAndView $mav
		) {
			$language =
				$request->getAttachedVar(\ewgraCms\AttachedAliases::LOCALIZER)->
				getRequestLanguage();
			
			$page =$request->getAttachedVar(\ewgraCms\AttachedAliases::PAGE);
			
			$urlMatches = $page->getUrlMatches($request->getUrl());
			
			if (!isset($urlMatches['newsUri']))
				throw \ewgraFramework\BadRequestException::create();
			
			$news = News::da()->getByUri($urlMatches['newsUri']);
			
			if (!$news)
				throw \ewgraCms\PageNotFoundException::create();
			
			$newsData = NewsData::da()->get($news, $language);
			
			$this->newsData = $newsData;
			
			$mav->getModel()->
				set('news', $news)->
				set('newsData', $newsData);
			
			return $this->continueHandleRequest($request, $mav);
		}
	}
?>