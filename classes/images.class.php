<?php
include_once "dbs.class.php";

class Images extends Dbs
{
	protected function insert_img($user_id, $file_dest, $img_desc)
	{
		$pdo = $this->connect();
		$sql = $pdo->prepare("INSERT INTO images (img_owner, img_file, img_desc, img_date) VALUES(:img_owner, :img_file, :img_desc, :img_date)");
		$sql->execute(array(
			"img_owner" => $user_id,
			"img_file" => $file_dest,
			"img_desc" => $img_desc,
			"img_date" => date("Y-m-d H:i:s")));
	}
}
