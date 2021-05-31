<?php
	session_start();
	require "./config/setup.php";
	require "./classes/validator.class.php";
	require "./classes/userview.class.php";
	require "./classes/userctrl.class.php";

	if(isset($_SESSION['username']))
	{
		$DbUser = new UserView($DB_DSN, $DB_USER, $DB_PASSWORD);
		$result = $DbUser->get_user($_SESSION['username'], "");
		if (!$result)
		{
			header("Location: logout.php");
		}
	}
	else
	{
		header("Location: login.php");
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Signup</title>
</head>
<body>
	<h2>Modify profile</h2>
	<h3>Fill the fields you want to change</h3>
	<div>
		<form method="post">
			New username: <input type="text" name="login">
			<br />
			New mail: <input type="text" name="email">
			<br />
			New password: <input type="password" name="passwd">
        	<br />
        	Re-enter new  password: <input type="password" name="passwd2">
        	<br />
        	<input type="submit" value="Modify">
			<br />
			<a href="index.php">Cancel</a>
		</form>
	</div>
</body>
</html>

<?php
	if ($_SERVER['REQUEST_METHOD'] == "POST")
	{
		$Changes = new validator($_POST);
		$exist = $DbUser->get_user($Changes->username, $Changes->email);
		$ModifyUser = new UserCtrl($DB_DSN, $DB_USER, $DB_PASSWORD);
		if ($Changes->passwd != "")
		{
			$Changes->validate_password();
			if (!$Changes->error)
			{
				$ModifyUser->reset_password($Changes->passwd, $result['email']);
			}
			else
			{
				echo json_encode($Changes->error);
				exit;
			}
		}
		if ($Changes->username != "")
		{
			$Changes->validate_user($exist);
			if (!$Changes->error)
			{
				$ModifyUser->modify_user($result['username'], $Changes->username);
			}
			else
			{
				echo json_encode($Changes->error);
				exit;
			}
		}
		if ($Changes->email != "")
		{
			$Changes->validate_email($exist);
			if (!$Changes->error)
			{
				$ModifyUser->modify_user($result['email'], $Changes->email);
			}
			else
			{
				echo json_encode($Changes->error);
				exit;
			}
		}
		header("Location: login.php");
	}
