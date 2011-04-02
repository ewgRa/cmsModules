<?php
	namespace ewgraCmsModules;

	if (!isset($loginResult) || $loginResult != BaseAuthController::SUCCESS_LOGIN) {
?>
	<form method="post">
		<input type="hidden" name="backurl" value="<?=$backurlForm->getValue('backUrl')?>" /><br />
		Login: <input type="text" name="login" /><br />
		Password: <input type="password" name="password" />
		<input type="submit" />
	</form>
<?php
	} else {
?>
	Success login
<?php
	}
?>