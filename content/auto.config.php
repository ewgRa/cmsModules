<?php
	ClassesAutoloader::me()->addSearchDirectories(array(dirname(__FILE__)));
	
	Language::da()->addLinkedCacher(ContentData::da());
?>