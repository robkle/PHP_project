<?php
	session_start();
	require_once "./config/setup.php";
	
	if(isset($_SESSION['username']))
		$username = $_SESSION['username'];
	else
		header("Location: login.php");
?>

<!DOCTYPE html>
<html>
<head>
	<title>Camagru</title>
</head>
<body>
	<a href="logout.php">Logout</a>
	<br>
	Hello <?php echo $username; ?>
</body>
</html>
