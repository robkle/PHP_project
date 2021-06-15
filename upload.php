<?php
	session_start();
	require "./classes/imgvalidator.class.php";
	require "./config/setup.php";
	require "./classes/imgctrl.class.php";
?>

<!DOCTYPE html>
<html>
<head>
	<title>Create</title>
	<link rel="stylesheet" href="./css/user_input.css">
</head>
<body>
	<div class="userform">
		<form method="post" enctype="multipart/form-data">
			<input type="text" name="desc" placeholder="Description">
			<input type="file" name="file">
			<button type="submit">Upload</button>
		</form>
	</div>
</body>
</html>
		

<?php
	if ($_SERVER['REQUEST_METHOD'] == "POST")
	{
		$NewImg = new ImgValidator($_FILES['file']);
		$NewImg->validate();
		if ($NewImg->error)
		{
			echo json_encode($NewImg->error);
			exit();
		}
		$fileDest = "./img/" . "img." . uniqid("", true) . "." . $NewImg->fileExt;
		move_uploaded_file($NewImg->fileTemp, $fileDest);
		$CreateImg = new ImgCtrl($DB_DSN, $DB_USER, $DB_PASSWORD);
		$CreateImg->create_img($_SESSION['user_id'], $fileDest, $_POST['desc']);
		header("Location: index.php");
	}
?>
