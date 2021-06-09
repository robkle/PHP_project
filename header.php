<?
	if (isset($_SESSION['username']))
		$username = $_SESSION['username'];
	else
		$username = NULL

<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="./css/header.css">
</head>
<body>
	<nav>
		<p>Camagru</p>
		<?php
			if ($username != NULL)
			{
				?>
				<ul>
					<li><a href="#"><?php echo str_pad($username, 24); ?></a>
						<ul>
							<li><a href="modify.php">Modify profile</a></li>
							<li><a href="logout.php">Logout</a></li>
						</ul>
					</li>
				</ul>
			<?php
		}
		else
		{
			?>
			<ul>
				<li><a href="login.php">Login</a></li>
				<li><a href="signup.php">Signup<a></li>
			</ul>
			<?php
		}
		?>
	</nav>
</body>
</html>
