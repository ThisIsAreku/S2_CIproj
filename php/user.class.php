<?php
class User implements JsonSerializable
{
	private $username;
	private $email;
	private $pass;
	private $is_admin;

	function User($arr)
	{
		$this->username = $arr['username'];
		$this->email = $arr['email'];
		$this->pass = strtolower($arr['pass']);
		$this->style = $arr['style'];
		$this->is_admin = (isset($arr['admin']) && $arr['admin'] == true);
	}

	function getUsername()
	{
		return $this->username;
	}
	function getEmail()
	{
		return $this->email;
	}
	function getPass()
	{
		return $this->pass;
	}
	function isAdmin()
	{
		return $this->is_admin;
	}
	function getStyle()
	{
		return $this->style;
	}
	function setStyle($style)
	{
		$this->style = $style;
	}

	public function jsonSerialize ()
	{
		$r =  array(
			'username' => $this->username,
			'email' => $this->email,
			'pass' => $this->pass,
			'style' => $this->style
			);
		if($this->isAdmin())
			$r['admin'] = true;

		return $r;
	}
}