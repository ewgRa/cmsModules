<?php
	/**
	 * Generated by meta builder!
	 * Do not edit this file!
	*/
	
	ClassesAutoloader::me()->addSearchDirectory(dirname(__FILE__));
	
	User::da()->addLinkedCacher(UserRight::da());
?>