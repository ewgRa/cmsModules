<?php
	namespace ewgraCmsModules;

	foreach ($contentList as $content)
		echo $replaceFilter->apply($contentDataList[$content->getId()]->getText());
?>