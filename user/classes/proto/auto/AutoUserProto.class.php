<?php
	namespace ewgraCmsModules;

	/**
	 * Generated by meta builder!
	 * Do not edit this class!
	 * @license http://www.opensource.org/licenses/bsd-license.php BSD
	 * @author Evgeniy Sokolov <ewgraf@gmail.com>
	 */
	abstract class AutoUserProto extends \ewgraFramework\ProtoObject
	{
		const MAX_LOGIN_LENGTH = 256;
		const MAX_EMAIL_LENGTH = 256;

		protected $dbFields = array(
			"id" =>  "id", 
			"login" =>  "login", 
			"password" =>  "password", 
			"passwordSalt" =>  "password_salt", 
			"changePasswordHash" =>  "change_password_hash", 
			"changePasswordSalt" =>  "change_password_salt", 
			"email" =>  "email", 
			"emailConfirmHash" =>  "email_confirm_hash", 
			"emailConfirmSalt" =>  "email_confirm_salt"
		);
	}
?>