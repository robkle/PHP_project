<?php
	session_start();
	require_once "./config/setup.php";
	
	if(isset($_SESSION['username']))
		$username = $_SESSION['username'];
	else
		$username = null;
?>

<!DOCTYPE html>
<html>
<head>
	<title>Camagru</title>
</head>
<body>
	<?php include 'header.php'; ?>
</body>
</html>
