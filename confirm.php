<?php
	session_start();
	require_once "./config/setup.php";
	require "./classes/userview.class.php";
	require "./classes/userctrl.class.php";
?>
	
<!DOCTYPE html>
<html>
<head>
	<title>Confirmation</title>
</head>
<body>
	<h2>Account confirmation</h2>
	<div>
		<a href="login.php">Login</a>
	</div>
</body>
</html>

<?php
	if (isset($_GET['ckey']))
	{
		$DbConfirm = new UserView($DB_DSN, $DB_USER, $DB_PASSWORD);
		$result = $DbConfirm->get_confirm($_GET['ckey']);
		if ($result)
		{
			$ConfirmUser = new UserCtrl($DB_DSN, $DB_USER, $DB_PASSWORD);
			$ConfirmUser->confirm_user();
			header("Location: login.php");
		}
		else
		{
			echo "This account is invalid or already confirmed";
		}
	}
	else
	{
		die("Oops! Something went wrong.");
	}
?>
