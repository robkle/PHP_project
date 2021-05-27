<?php
	session_start();
	require_once "./config/setup.php";
	require "./classes/userview.class.php";
?>

<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
</head>
<body>
	<h2>Login</h2>
	<div>
		<form method="post">
			Username: <input type="text" name="login">
			<br />
			Password: <input type="password" name="passwd">
        	<br />
        	<input type="submit" value="Login">
			<a href="signup.php">Signup</a>
			<a href="pswd_request.php">Forgot password</a>
    	</form>
	</div>
</body>
</html>

<?php
	if($_SERVER['REQUEST_METHOD'] == "POST")
	{
		$DbUser = new UserView($DB_DSN, $DB_USER, $DB_PASSWORD);
		$result = $DbUser->get_user($_POST['login'], '');
		if (!$result)
			echo "Username does not exist!";
		else if ($result['confirm'] == "No")
			echo "Please confirm acccount!";
		else if (password_verify($_POST['passwd'], $result['passwd']))
		{
			$_SESSION['username'] = $result['username'];
			header("Location: index.php");
		}
		else
			echo "Incorrect username or password!";
	}	
?>
