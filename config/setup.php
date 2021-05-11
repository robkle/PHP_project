<?php

require_once "database.php";

try
{
	$conn = new PDO('mysql:host=localhost', $DB_USER, $DB_PASSWORD);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$conn->exec("CREATE DATABASE IF NOT EXISTS `$DB_NAME`;");
	$conn = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);	
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$table = "users";
	$sql = "CREATE TABLE IF NOT EXISTS $table(
		id_user INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
		username VARCHAR(32) NOT NULL UNIQUE, 
		email VARCHAR(64) NOT NULL UNIQUE, 
		passwd VARCHAR(255) NOT NULL,
		ckey VARCHAR(255) NOT NULL,
		confirm VARCHAR(4) DEFAULT 'No' 
	)";
	$conn->exec($sql);
}
catch(PDOException $e)
{
	echo "Connection failed: " . $e->getMessage();
}
