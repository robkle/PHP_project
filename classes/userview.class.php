<?php
include_once "users.class.php";

class UserView extends Users
{
	public function get_user($username, $email)
	{
		$result = $this->select_user($username, $email);
		return $result;
	}

	public function get_confirm($ckey)
	{
		$confirm = "No";
		$result = $this->select_confirm($ckey, $confirm);
		return $result;
	}

	public function get_token($selector)
	{
		$currentdate = date("U");
		$result = $this->select_token($selector, $currentdate);
		return $result;
	}
}
