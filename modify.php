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
		header("Location: index.php");
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Modify</title>
	<link rel="stylesheet" href="./css/user_input.css">
</head>
<body>
	<div class="userform">
		<h2>Camagru</h2>
		<h3>Modify profile</h3>
		<p>Fill the fields you want to change</p>
		<form method="post">
			<input type="text" name="login" placeholder="New username">
			<input type="text" name="email" placeholder="New email">
			<input type="password" name="passwd" placeholder="New password">
        	<input type="password" name="passwd2" placeholder="Re-enter new password">
        	<button type="submit">Confirm changes</button>
		</form>
			<a href="index.php">Cancel</a>
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
