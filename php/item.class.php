<?php
class Item implements JsonSerializable
{
	private $hex;
	private $price;
	private $name;
	private $text;
	private $comp;
	private $showcase;

	private function loadRGB()
	{
        $this->comp = hexToRgb($this->hex);
    }

	function Item($arr)
	{
		$this->hex = strtoupper($arr['hex']);
		$this->name = $arr['name'];
		$this->price = floatval($arr['price']);
		$this->text = $arr['text'];
		$this->showcase = (isset($arr['showcase']) && $arr['showcase'] == true);
		$this->loadRGB();
	}

	function getHex()
	{
		return $this->hex;
	}
	function getPrice()
	{
		return $this->price;
	}
	function setPrice($price)
	{
		if($this->price == floatval($price))
			return false;

		$this->price = floatval($price);
		return true;		
	}
	function getPriceFormatted()
	{
		return number_format ($this->price, 2, ',', ' ');
	}
	function getName()
	{
		return $this->name;
	}
	function setName($name)
	{
		if($this->name == $name)
			return false;

		$this->name = $name;
		return true;
	}
	function getText()
	{
		return $this->text;
	}
	function setText($text)
	{
		$text = str_replace("\r", "\n", $text);
		$text = str_replace("\n\n", "\n", $text);
		$text = str_replace("\n", "\r\n", $text);
		
		if($this->text == $text)
			return false;

		$this->text = $text;
		return true;
	}
	function getTextFormatted()
	{
		return '<p>'.str_replace("\r\n", '</p><p>', $this->text).'</p>';
	}
	function getR()
	{
		return $this->comp['r'];
	}
	function getG()
	{
		return $this->comp['g'];
	}
	function getB()
	{
		return $this->comp['b'];
	}

	function isShowcase()
	{
		return $this->showcase;
	}
	function setShowcase($s)
	{
		$this->showcase = $s;
	}

	public function __sleep()
    {
        return array('hex', 'price', 'name', 'text', 'showcase');
    }

	public function __wakeup()
    {
        $this->loadRGB();
    }

	public function jsonSerialize ()
	{
		$r = array(
			'hex' => $this->hex,
			'price' => $this->price,
			'name' => $this->name,
			'text' => $this->text
			);
		if($this->isShowcase())
			$r['showcase'] = true;
		return $r;
	}
}