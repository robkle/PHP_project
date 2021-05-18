<?php
	session_start();
	require_once "./config/setup.php";
	require_once "./config/email.php";
	require_once "Mail.php";
?>

<!DOCTYPE html>
<html>
<head>
	<title>password_reset</title>
</head>
<body>
	<h2>Password reset request</h2>
	<div>
		<form method="post">
			Username: <input type="text" name="login">
			<br />
			Email: <input type="text" name="email">
        	<br />
        	<input type="submit" value="Send">
			<a href="login.php">Login</a>
    	</form>
	</div>
</body>
</html>

<?php
	#if (isset($_POST["Send"])) #alternative to try out
	if ($_SERVER['REQUEST_METHOD'] == "POST")
	{
		$user = $_POST['login'];
		$user_email = $_POST['email'];
		$db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = $db->prepare("SELECT * FROM users WHERE username = :username AND email = :email");
		$sql->execute(array("username" => $user, "email" => $user_email));
		$result = $sql->fetch();
		if (!$result)
		{
			echo "No such user, or username and email do not match";
			exit;
		}
		$sql = $db->prepare("DELETE FROM pwdReset WHERE pwdResetEmail= :pwdResetEmail");
		$sql->execute(array('pwdResetEmail' => $user_email));
		
		$selector = bin2hex(random_bytes(8));
		$token = random_bytes(32);
		$expires = date("U") + 1800;
		$hashedToken = password_hash($token, PASSWORD_DEFAULT);
		$sql = $db->prepare("INSERT INTO pwdReset (pwdResetEmail, pwdResetSelector, pwdResetToken, pwdResetExpires) VALUES (:pwdResetEmail, :pwdResetSelector, :pwdResetToken, :pwdResetExpires)");
		$sql->execute(array(
			"pwdResetEmail" => $user_email, 
			"pwdResetSelector" => $selector, 
			"pwdResetToken" => $hashedToken, 
			"pwdResetExpires" =>$expires));
		echo "so far so good";	

		$from = $EM_USER;
		$subject = "Camagru password reset";
		$body = "http://127.0.0.1:8080/camagru/pswd_reset.php?selector=" . $selector . "&validator=" . bin2hex($token);

  		$headers = array ('From' => $from, 'To' => $user_email,'Subject' => $subject);
  		$smtp = Mail::factory('smtp',
    	array ('host' => $EM_HOST,
      		'port' => $EM_PORT,
      		'auth' => true,
      		'username' => $EM_USER,
      		'password' => $EM_PASSWD));
		$mail = $smtp->send($user_email, $headers, $body);
		if (PEAR::isError($mail))
		{
    		echo($mail->getMessage());
		} 
		else
		{
			header("Location: index.php");
		}
	}
?>


