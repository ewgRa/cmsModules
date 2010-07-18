<?php
	if (
		!isset($loginResult)
		|| $loginResult != LoginModule::SUCCESS_LOGIN
	) {
		$backurl =
			isset($backurl)
				? $backurl
				: null;
?>
	<form method="<?=$source?>">
		<input type="hidden" name="backurl" value="<?=$backurl?>" /><br />
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