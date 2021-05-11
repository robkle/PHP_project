<?php
	session_start();
	require_once "./config/setup.php";
	require_once "./config/email.php";
  	require_once "Mail.php";
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
		$username = $_POST['login'];
		$email = $_POST['email'];
		$passwd = $_POST['passwd'];
		$passwd2 = $_POST['passwd2'];
		$db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);	
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = $db->prepare("SELECT * FROM users WHERE username = :username OR email = :email");
		$sql->execute(array("username" => $username, "email" => $email));
		$result = $sql->fetch();
		if ($result['username'] == $username)
			$errors['user_exists'] = "Username already exists.";
		if ($result['email'] == $email)
			$errors['email_exists'] = "Email already exists.";
		if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{6,}$/", $passwd))
			$errors['invalid_passwd'] = "Password does not fulfill requirements.";
		if ($passwd != $passwd2)
			$errors['passwd_conflict'] = "Passwords don't match.";
		if ($errors)
		{
			echo json_encode($errors);
			exit;
		}
		$encr_passwd = password_hash($passwd, PASSWORD_DEFAULT, ['cost'=>12]);
		$ckey = md5(time() . $username);
		$sql = $db->prepare("INSERT INTO users (username, email, passwd, ckey) VALUES (:username, :email, :passwd, :ckey)");
		$sql->execute(array(
			"username" => $username,
			"email" => $email,
			"passwd" => $encr_passwd,
			"ckey" => $ckey));
		$from = $EM_USER;
		$subject = "Camagru account confirmation";
		$body = "http://127.0.0.1:8080/camagru/confirm.php?ckey=$ckey";

  		$headers = array ('From' => $from, 'To' => $email,'Subject' => $subject);
  		$smtp = Mail::factory('smtp',
    	array ('host' => $EM_HOST,
      		'port' => $EM_PORT,
      		'auth' => true,
      		'username' => $EM_USER,
      		'password' => $EM_PASSWD));
		$mail = $smtp->send($email, $headers, $body);
		if (PEAR::isError($mail))
		{
    		echo($mail->getMessage());
		} 
		else
		{
			header("Location: login.php");
		}
	}
?>
