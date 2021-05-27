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

	public function confirm_user()
	{
		$confirm = "Yes";
		$this->update_confirm($confirm);
	}
}
