<?php

class ImgValidator
{
	public $error = [];
	public $fileName;
	public $fileExt;
	public $fileType;
	public $fileTemp;
	public $fileError;
	public $fileSize;

	public function __construct($file)
	{
		if (array_key_exists('name', $file))
		{
			$this->fileName = $file['name'];
			$ext = explode(".", $this->fileName);
			$this->fileExt = strtolower(end($ext));
		}
		if (array_key_exists('type', $file))
		{
			$this->fileType = $file['type'];
		}
		if (array_key_exists('tmp_name', $file))
		{
			$this->fileTemp = $file["tmp_name"];
		}
		if (array_key_exists('error', $file))
		{
			$this->fileError = $file["error"];
		}
		if (array_key_exists('size', $file))
		{
			$this->fileSize = $file["size"];
		}
		
	}

	public function validate()
	{
		if ($this->fileError != 0)
		{
			$this->error['file_error'] = "An error occured.";
			return;
		}
		$this->validate_size();
		$this->validate_extension();
	}

	public function validate_size()
	{
		if ($this->fileSize > 3000000)
		{
			$this->error['file_size'] = "The file size exceeds the limit of 3 MB.";
		}
	}

	public function validate_extension()
	{
		$allowed = array("jpg", "jpeg", "png");
		if (in_array($this->fileExt, $allowed) == False)
		{
			$this->error['file_ext'] = "Invalid file extension.";
		}
	}
}
