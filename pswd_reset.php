<?php
	require_once "./config/setup.php";
	require_once "./config/email.php";
	require "./classes/userview.class.php";
	require "./classes/userctrl.class.php";

	$selector = $_GET['selector'];
	$validator = $_GET['validator'];
	if (empty($selector) || empty($validator) || ctype_xdigit($selector) === false || ctype_xdigit($validator) === false)
	{
		echo "We could not validate your request!";
		exit();
	}
?>

<html>
<head>
	<title>password reset</title>
</head>
<body>
	<h2>Reset password</h2>
	<div>
		<form method="post">
			New password: <input type="password" name="passwd"><br />
			Re-enter new password: <input type="password" name="passwd2"><br />
        	<input type="submit" value="Reset password">
			<a href="signup.php">Signup</a>
			<a href="login.php">Login</a>
    	</form>
	</div>
</body>
</html>

<?php
	if($_SERVER['REQUEST_METHOD'] == "POST")
	{
		$NewPasswd = new validator($_POST);
		$NewPasswd->validate_password();
		if ($NewPasswd->error)
		{
			echo json_encode($NewPasswd->error);
			exit;
		}
		$TokenCheck = new UserView($DB_DSN, $DB_USER, $DB_PASSWORD);
		$result = $TokenCheck->get_token($selector);
		if (!$result['pwdResetToken'])
		{
			echo "Please re-submit a password reset request.";
			exit;
		}
		$tokenBin = hex2bin($validator);
		$tokenVerify = password_verify($tokenBin, $result["pwdResetToken"]);
		if ($tokenVerify === false)
		{
			echo "Please re-submit a password reset request.";
			exit;
		}
		elseif ($tokenVerify === true)
		{
			$tokenEmail = $result['pwdResetEmail'];
			$ResetPasswd = new UserCtrl($DB_DSN, $DB_USER, $DB_PASSWORD);
			$ResetPasswd->reset_password($NewPasswd->passwd, $token_email);
			$ResetPasswd->clear_pwdreset($token_email);
		}
		header("Location: login.php");

		/*$passwd = $_POST["passwd"];
		$passwd2 = $_POST["passwd2"];
		if ($passwd != $passwd2)
		{
			echo "Passwords do not match";
			exit ();
		}
		if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{6,}$/", $passwd))
		{
			echo "Password does not fulfull the requirements";
			exit ();
		}
		$currentDate = date("U");
		$db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);	
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = $db->prepare("SELECT * FROM pwdReset WHERE pwdResetSelector = :pwdResetSelector AND pwdResetExpires >= :pwdResetExpires");
		$sql->execute(array("pwdResetSelector" => $selector, "pwdResetExpires" => $currentDate));
		$result = $sql->fetch();
		$tokenBin = hex2bin($validator);
		if (!$result['pwdResetToken'])
		{
			echo "Please re-submit a password reset request.";
			exit ();
		}
		$tokenCheck = password_verify($tokenBin, $result["pwdResetToken"]);
		if ($tokenCheck === false)
		{
			echo "Please re-submit a password reset request.";
			#exit();
		}
		elseif ($tokenCheck === true)
		{
			$tokenEmail = $result['pwdResetEmail'];
			$encr_passwd = password_hash($passwd, PASSWORD_DEFAULT, ['cost'=>12]);
			echo "FOUR";
			$sql = $db->prepare("UPDATE users SET passwd = :passwd WHERE email = :email");
			$sql->execute(array(
				"passwd" => $encr_passwd,
				"email" => $tokenEmail));
			echo "FIVE";
			$sql = $db->prepare("DELETE FROM pwdReset WHERE pwdResetEmail= :pwdResetEmail");
			$sql->execute(array('pwdResetEmail' => $tokenEmail));
			echo "SIX";
		}
		header("Location: login.php");*/
	}
?>
