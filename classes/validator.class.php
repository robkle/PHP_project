<?php

class validator
{
	public $error = [];
	public $username = "";
	public $email = "";
	public $passwd = "";
	public $passwd2= "";

	public function __construct($post)
	{
		if (array_key_exists('login', $post))
		{
			$this->username = $post['login'];
		}
		if (array_key_exists('email', $post))
		{
			$this->email = $post['email'];
		}
		if (array_key_exists('passwd', $post))
		{
			$this->passwd = $post['passwd'];
		}
		if (array_key_exists('passwd2', $post))
		{
			$this->passwd2 = $post['passwd2'];
		}
	}
		
	public function validate($exist)
	{
		$this->validate_email($exist['email'] ?? '');
		$this->validate_user($exist['username'] ?? '');
		$this->validate_password();
	}

	public function validate_email($exist)
	{
		if (!filter_var($this->email, FILTER_VALIDATE_EMAIL))
		{
			$this->error['email_format'] = "Invalid email format.";
		}
		if ($exist == $this->email)
		{
			$this->error['email_exists'] = "Email already exists.";
		}
	}

	public function validate_user($exist)
	{
		if ($exist == $this->username)
		{
			$this->error['user_exists'] = "Username already exists.";
		}
	}

	public function validate_password()
	{
		if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{6,}$/", $this->passwd))
		{
			$this->error['invalid_passwd'] = "Password does not fulfill requirements.    ";
		}
		if ($this->passwd != $this->passwd2)
		{
			$this->error['passwd_conflict'] = "Passwords don't match.";
		}
	}

}
