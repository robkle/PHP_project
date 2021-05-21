<?php

require "database.php";
require $_SERVER['DOCUMENT_ROOT'] . "/camagru/classes/dbs.class.php";

/*
** Creates new database if it does not already exists.
*/

$newDB = new class
{
	public function create($host, $user, $password, $name)
	{
		try
		{
			$pdo = new PDO('mysql:host=' . $host, $user, $password);
			$pdo->exec("CREATE DATABASE IF NOT EXISTS `$name`;");
		}
		catch(PDOException $e)
		{
			echo "Connection failed: " . $e->getMessage();
		}
	}
};

$newDB->create($DB_HOST, $DB_USER, $DB_PASSWORD, $DB_NAME);	

/*
** Creates tables in the database if they don't already exist.
*/

$newTable = new class ($DB_DSN, $DB_USER, $DB_PASSWORD) extends Dbs
{
	public function create($sql)
	{
		try
		{
			$stmt = $this->connect();
			$stmt->exec($sql);
		}
		catch(PDOException $e)
		{
			echo "Connection failed: " . $e->getMessage();
		}
	}
};

$table = "users";
$sql = "CREATE TABLE IF NOT EXISTS $table(
		id_user INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
		username VARCHAR(32) NOT NULL UNIQUE, 
		email VARCHAR(64) NOT NULL UNIQUE, 
		passwd VARCHAR(255) NOT NULL,
		ckey VARCHAR(255) NOT NULL,
		confirm VARCHAR(4) DEFAULT 'No' 
		)";
$newTable->create($sql);


$table = "pwdReset";
$sql = "CREATE TABLE IF NOT EXISTS $table(
		pwdResetID INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL, 
		pwdResetEmail TEXT NOT NULL, 
		pwdResetSelector TEXT NOT NULL, 
		pwdResetToken LONGTEXT NOT NULL,
		pwdResetExpires TEXT NOT NULL
		)";
$newTable->create($sql);
