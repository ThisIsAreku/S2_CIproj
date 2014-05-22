<?php
class Command implements JsonSerializable
{
	private $date;
	private $user;
	private $items;
	private $comments;

	function Command($arr)
	{
		$this->date = $arr['date'];
		$this->user = $arr['user'];

		$this->items = array();		
		foreach($arr['items'] as $k => $v)
		{
			if(is_array($v)){
				$this->items[$k] = new Item($v);
			}else{
				$this->items[$k] = $v;
			}
		}

		$this->total = floatval($arr['total']);
	}

	function getUser()
	{
		return $this->user;
	}
	function getDate()
	{
		return $this->date;
	}
	function getItems()
	{
		return $this->items;
	}
	function getTotal()
	{
		return $this->total;
	}
	function getTotalFormatted()
	{
		return number_format ($this->total, 2, ',', ' ');
	}
	function getComments()
	{
		return $this->comments;
	}
	function setComments($c)
	{
		$this->comments = $c;
	}

	public function jsonSerialize ()
	{
		return array(
			'date' => $this->date,
			'user' => $this->user,
			'items' => $this->items,
			'total' => $this->total,
			'comments' => $this->comments
			);
	}
}