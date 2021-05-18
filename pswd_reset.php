<?php
	require_once "./config/setup.php";
	require_once "./config/email.php";

	$selector = $_GET['selector'];
	$validator = $_GET['validator'];
	if (empty($selector) || empty($validator) || ctype_xdigit($selector) === false || ctype_xdigit($validator) === false)
	{
		echo "We could not validate your request!";
		exit();
	}
	echo "SO FAR SO GOOD";
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
		$passwd = $_POST["passwd"];
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
	
		echo "ONE";
	
		$currentDate = date("U");
		$tokenBin = hex2bin($validator);
		
		echo "TWO";

		$db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);	
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = $db->prepare("SELECT * FROM pwdReset WHERE pwdResetSelector = :pwdResetSelector AND pwdResetExpires >= :pwdResetExpires");
		$sql->execute(array("pwdResetSelector" => $selector, "pwdResetExpires" => $currentDate));
		$result = $sql->fetch();
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
			echo "THREE";
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
		header("Location: login.php");
	}
?>
