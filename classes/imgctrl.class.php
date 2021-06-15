<?php
include_once "images.class.php";

class ImgCtrl extends Images
{
	public function create_img($user_id, $file_dest, $img_desc)
	{
		$this->insert_img($user_id, $file_dest, $img_desc);
	}
}
