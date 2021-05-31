<?php
include_once "users.class.php";

class UserCtrl extends Users
{
	public function create_user($NewUser)
	{
		$encr_passwd = password_hash($NewUser->passwd, PASSWORD_DEFAULT, ['cost'=>12]);
		$ckey = md5(time() . $NewUser->username);
		$this->insert_user($NewUser->username, $NewUser->email, $encr_passwd, $ckey);
	}

	public function modify_user($old_username, $new_username)
	{
		$this->update_user($old_username, $new_username);
	}

	public function modify_email($old_email, $new_email)
	{
		$this->update_user($old_username, $new_username);
	}

	public function create_pwdreset($email, $selector, $token)
	{
		$expires = date("U") + 3600;
		$hashedToken = password_hash($token, PASSWORD_DEFAULT);
		$this->insert_pwdreset($email, $selector, $hashedToken, $expires);
	}
	
	public function reset_password($password, $email)
	{
		$encr_passwd = password_hash($password, PASSWORD_DEFAULT, ['cost'=>12]);
		$this->update_password($encr_passwd, $email);
	}

	public function confirm_user()
	{
		$confirm = "Yes";
		$this->update_confirm($confirm);
	}

	public function clear_pwdreset($email)
	{
		$this->delete_pwdreset($email);
	}

}
