<?php
include_once "dbs.class.php";

class Users extends Dbs
{
	protected function select_user($username, $email)
	{
		$pdo = $this->connect();
		$sql = $pdo->prepare("SELECT * FROM users WHERE username = :username OR email = :email");
		$sql->execute(array("username" => $username, "email" => $email));
		$result = $sql->fetch();
		return $result;
	}
	
	protected function select_confirm($ckey, $confirm)
	{
		$pdo = $this->connect();
		$sql = $pdo->prepare("SELECT * FROM users WHERE ckey = :ckey AND confirm = :confirm");
		$sql->execute(array("ckey" => $ckey, "confirm" => $confirm));
		$result = $sql->fetch();
		return $result;

	}
	
	protected function insert_user($username, $email, $passwd, $ckey)
	{
		$pdo = $this->connect();
		$sql = $pdo->prepare("INSERT INTO users (username, email, passwd, ckey) VALUES (:username, :email, :passwd, :ckey)");
		$sql->execute(array(
			"username" => $username,
			"email" => $email,
			"passwd" => $passwd,
			"ckey" => $ckey));
	}

	protected function update_confirm($confirm)
	{
		$pdo = $this->connect();
		$sql = $pdo->prepare("UPDATE users SET confirm = :confirm");
		$sql->execute(array('confirm' => $confirm));
	}	
}
