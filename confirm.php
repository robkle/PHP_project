<?php
	session_start();
	require_once "./config/setup.php";
?>
	
<!DOCTYPE html>
<html>
<head>
	<title>Confirmation</title>
</head>
<body>
	<h2>Account confirmation</h2>
	<div>
		<a href="login.php">Login</a>
	</div>
</body>
</html>

<?php
	if (isset($_GET['ckey']))
	{
		$ckey = $_GET['ckey'];
		$confirm = "No";
		$db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);	
		$sql = $db->prepare("SELECT * FROM users WHERE ckey = :ckey AND confirm = :confirm");
		$sql->execute(array('ckey' => $ckey, 'confirm' => $confirm));
		$result =$sql->fetch();
		if ($result)
		{
			$confirm = "Yes";
			$sql = $db->prepare("UPDATE users SET confirm = :confirm");
			$sql->execute(array('confirm' => $confirm));
			header("Location: login.php");
		}
		else
		{
			echo "This account is invalid or already confirmed";
		}
	}
	else
	{
		die("Oops! Something went wrong.");
	}
?>
