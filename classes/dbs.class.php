<?php

class Dbs
{
	private $dsn;
	private $usr;
	private $password;

	public function __construct($dsn, $user, $password)
	{
		$this->dsn = $dsn;
		$this->usr = $user;
		$this->password = $password;
	}

	protected function connect()
	{
		$pdo = new PDO($this->dsn, $this->usr, $this->password);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return $pdo;
	}
}
