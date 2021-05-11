<?php
	session_start();
	require_once "./config/setup.php";
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
    	</form>
	</div>
</body>
</html>

<?php
	if($_SERVER['REQUEST_METHOD'] == "POST")
	{
		$username = $_POST['login'];
		$password = $_POST['passwd'];
		$db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);	
		//$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "SELECT * FROM users WHERE username = :username";
		$stmt = $db->prepare($sql);
		$stmt->execute(array('username' => $username));
		$entry = $stmt->fetch();
		if (!$entry)
			echo "Username does not exist!";
		else if ($entry['confirm'] == "No")
			echo "Please confirm acccount!";
		else if (password_verify($password, $entry['passwd']))
		{
			$_SESSION['username'] = $username;
			echo "session created";
			header("Location: index.php");
			//die;
		}
		else
			echo "Incorrect username or password!";
	}	

?>
