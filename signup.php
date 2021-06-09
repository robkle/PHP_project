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
	<link rel="stylesheet" href="./css/user_input.css">
</head>
<body>
	<div class="userform">
		<h2>Camagru</h2>
		<h3>Signup</h3>
		<form method="post">
			<input type="text" name="login" placeholder="Username">
			<input type="text" name="email" placeholder="Email">
			<input type="password" name="passwd" placeholder="Password">
			<p>*Minimum of 6 characters. Should include at least one capital letter and one digit!</p>
        	<input type="password" name="passwd2" placeholder="Re-enter password">
        	<button type="submit">Signup</button>
		</form>
			<a href="login.php">Login</a>
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
