<?php
	session_start();
	require "./config/setup.php";
	require "./config/email.php";
	require "./classes/validator.class.php";
	require "./classes/userview.class.php";
	require "./classes/userctrl.class.php";
	require "./classes/mail.class.php";
?>

<!DOCTYPE html>
<html>
<head>
	<title>Signup</title>
</head>
<body>
	<h2>Sign up</h2>
	<div>
		<form method="post">
			Username: <input type="text" name="login">
			<br />
			Email: <input type="text" name="email">
			<br />
			Password: <input type="password" name="passwd">
        	<br />
        	Re-enter password: <input type="password" name="passwd2">
        	<br />
        	<input type="submit" value="Signup">
			<br />
			<a href="login.php">Login</a>
		</form>
	</div>
</body>
</html>

<?php
	if ($_SERVER['REQUEST_METHOD'] == "POST")
	{
		$NewUser = new validator($_POST);
		$DbUser = new UserView($DB_DSN, $DB_USER, $DB_PASSWORD);
		$result = $DbUser->get_user($NewUser->username, $NewUser->email);
		$NewUser->validate($result);
		if ($NewUser->error)
		{
			echo json_encode($NewUser->error);
			exit;
		}
		$CreateUser = new UserCtrl($DB_DSN, $DB_USER, $DB_PASSWORD);
		$CreateUser->create_user($NewUser);
		$confirm = new SendMail($EM_HOST, $EM_PORT, $EM_USER, $EM_PASSWD);
		$confirm->confirmation($NewUser);
		if ($confirm->error == true)
		{
			exit;
		}
		else
		{
			header("Location: login.php");
		}
	}
?>
