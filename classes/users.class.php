<?php
include_once "dbs.class.php";

class Users extends Dbs
{
	protected function select_confirm($ckey, $confirm)
	{
		$pdo = $this->connect();
		$sql = $pdo->prepare("SELECT * FROM users WHERE ckey = :ckey AND confirm = :confirm");
		$sql->execute(array("ckey" => $ckey, "confirm" => $confirm));
		$result = $sql->fetch();
		return $result;
	}

	protected function select_token($selector, $currentdate)
	{
		$pdo = $this->connect();
		$sql = $pdo->prepare("SELECT * FROM pwdReset WHERE pwdResetSelector = :pwdResetSelector AND pwdResetExpires >= :pwdResetExpires");
		$sql->execute(array("pwdResetSelector" => $selector, "pwdResetExpires" => $currentdate));
		$result = $sql->fetch();
		return $result;
	}

	protected function select_user($username, $email)
	{
		$pdo = $this->connect();
		$sql = $pdo->prepare("SELECT * FROM users WHERE username = :username OR email = :email");
		$sql->execute(array("username" => $username, "email" => $email));
		$result = $sql->fetch();
		return $result;
	}
	
	protected function insert_pwdreset($email, $selector, $token, $expires)
	{
		$pdo = $this->connect();
		$sql = $pdo->prepare("INSERT INTO pwdReset (pwdResetEmail, pwdResetSelector, pwdResetToken, pwdResetExpires) VALUES (:pwdResetEmail, :pwdResetSelector, :pwdResetToken, :pwdResetExpires)");
		$sql->execute(array(
			"pwdResetEmail" => $email,
			"pwdResetSelector" => $selector,
			"pwdResetToken" => $token,
			"pwdResetExpires" =>$expires));
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

	protected function update_user($old_username, $new_username)
	{
		$pdo = $this->connect();
		$sql = $pdo->prepare("UPDATE users SET username = :new_username WHERE username = :old_username");
		$sql->execute(array(
			"new_username" => $new_username,
			"old_username" => $old_username));  
	}

	protected function update_email($old_email, $new_email)
	{
		$pdo = $this->connect();
		$sql = $pdo->prepare("UPDATE users SET email = :new_email WHERE username = :old_email");
		$sql->execute(array(
			"new_email" => $new_email,
			"old_email" => $old_email));  
	}


	protected function update_password($password, $email)
	{
		$pdo = $this->connect();
		$sql = $pdo->prepare("UPDATE users SET passwd = :passwd WHERE email = :email");
		$sql->execute(array(
			"passwd" => $password,
			"email" => $email));  
	}
	
	protected function delete_pwdreset($email)
	{
		$pdo = $this->connect();
		$sql = $pdo->prepare("DELETE FROM pwdReset WHERE pwdResetEmail= :pwdResetEmail");
		$sql->execute(array('pwdResetEmail' => $email));
	}

}
