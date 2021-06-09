<?php
	session_start();
	require_once "./config/setup.php";
	require "./classes/userview.class.php";
?>

<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
	<link rel="stylesheet" href="./css/user_input.css">
</head>
<body>
	<div class="userform">
		<h2>Camagru<h2>
		<h3>Login</h3>
		<form method="post">
			<input type="text" name="login" placeholder="Username">
			<input type="password" name="passwd" placeholder="Password">
        	<button type="submit">Login</button>
    	</form>
		<a href="pswd_request.php">Forgot password?</a>
		<a href="signup.php">Signup</a>
	</div>
</body>
</html>

<?php
	if($_SERVER['REQUEST_METHOD'] == "POST")
	{
		$DbUser = new UserView($DB_DSN, $DB_USER, $DB_PASSWORD);
		$result = $DbUser->get_user($_POST['login'], '');
		if (!$result)
		{
			echo "Username does not exist!";
			exit;
		}
		else if ($result['confirm'] == "No")
		{
			echo "Please confirm acccount!";
			exit;
		}
		else if (password_verify($_POST['passwd'], $result['passwd']))
		{
			$_SESSION['username'] = $result['username'];
			header("Location: index.php");
		}
		else
		{
			echo "Incorrect username or password!";
			exit;
		}
	}	
?>
