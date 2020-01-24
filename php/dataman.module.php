<?php
class DataManager
{

	private $itemsData;
	private $usersData;
	private $commandsData;
	private $path;

	private $loaded;

	private $currentUser;

	function DataManager($path)
	{
		$this->path = $path;
		
		$this->loaded = array(
			'products' => false,
			'users' => false,
			'commands' => false
			);

		$this->itemsData = array();
		$this->usersData = array();
		$this->commandsData = array();

		if(isset($_SESSION['loggedin']))
			$this->currentUser = $_SESSION['user'];

		//$this->save();
	}

	function load($part = 'all', $force = false)
	{
		if( ( ($part == 'products'||$part == 'all') && !$this->loaded['products']) || $force)
		{
			if(file_exists($this->path.'products.json'))
			{
				$fileContent = file_get_contents($this->path.'products.json');
				$this->itemsData = json_decode($fileContent, true);
				if($this->itemsData == null) 
					$this->itemsData = array();

				foreach($this->itemsData as $k => $v)
					$this->itemsData[$k] = new Item($v);
			}else{
				$this->itemsData = array();
			}

			$this->loaded['products'] = true;
		}

		if( ( ($part == 'users'||$part == 'all') && !$this->loaded['users']) || $force)
		{
			if(file_exists($this->path.'users.json'))
			{
				$fileContent = file_get_contents($this->path.'users.json');
				$this->usersData = json_decode($fileContent, true);
				if($this->usersData == null) 
					$this->usersData = array();

				foreach($this->usersData as $k => $v)
					$this->usersData[$k] = new User($v);
			}else{
				$this->usersData = array();
			}

			$this->loaded['users'] = true;
		}

		if( ( ($part == 'commands'||$part == 'all') && !$this->loaded['commands']) || $force)
		{
			if(file_exists($this->path.'commands.json'))
			{
				$fileContent = file_get_contents($this->path.'commands.json');
				$this->commandsData = json_decode($fileContent, true);
				if($this->commandsData == null) 
					$this->commandsData = array();

				foreach($this->commandsData as $k => $v)
					$this->commandsData[$k] = new Command($v);
			}else{
				$this->commandsData = array();
			}

			$this->loaded['commands'] = true;
		}
	}

	function save($part = 'all')
	{
		if($part == 'products'||$part == 'all' && $this->loaded['commands'])
		{
			$fileContent = json_encode($this->itemsData, JSON_PRETTY_PRINT);
			file_put_contents($this->path.'products.json', $fileContent);
		}

		if($part == 'users'||$part == 'all' && $this->loaded['users'])
		{
			$fileContent = json_encode($this->usersData, JSON_PRETTY_PRINT);
			file_put_contents($this->path.'users.json', $fileContent);
		}

		if($part == 'commands'||$part == 'all' && $this->loaded['commands'])
		{
			$fileContent = json_encode($this->commandsData, JSON_PRETTY_PRINT);
			file_put_contents($this->path.'commands.json', $fileContent);
		}
	}

	function getItems()
	{
		$this->load('products');
		return $this->itemsData;
	}
	function getUsers()
	{
		$this->load('users');
		return $this->usersData;
	}
	function getCommands()
	{
		$this->load('commands');
		return $this->commandsData;
	}
	function getCurrentUser()
	{
		$users = $this->getUsers();
		if (!isset($users[$this->currentUser])) {
			$this->doLogout();
			throw new \Exception('Missinguser');
		}

		return $users[$this->currentUser];
	}

	function addItem($itm, $autosave = true)
	{
		$this->load('products');
		$this->itemsData[$itm->getHex()] = $itm;
		if($autosave) $this->save('products');
	}
	function addUser($username, $pass, $email)
	{
		if($this->hasUser($username))
			return false;

		$this->load('users');

		$userinfo = array(
			'username' => $username,
			'email' => $email,
			'pass' => md5($pass),
			'style' => 'main'
			);
		$this->usersData[$username] = new User($userinfo);
		$this->save('users');
		return true;
	}
	function doCommand($command, $price)
	{
		if(!isset($_SESSION['loggedin']) || $this->currentUser == null)
			return false;

		$this->load('commands');

		$this->commandsData[] = new Command(array(
			'date' => time(),
			'user' => $this->currentUser,
			'items' => $command,
			'total' => $price
			));

		$this->save('commands');

		return true;
	}

	function deleteItem($hex)
	{
		$hex = strtoupper($hex);
		if(!$this->hasItem($hex))
			return false;

		$this->load('products');
		unset($this->itemsData[$hex]);
		$this->save('products');
		return true;
	}

	function getCommandsByUser($username)
	{
		$r = array();
		foreach($this->getCommands() as $itm)
		{
			if($itm->getUser() != $username)
				continue;
			$r[] = $itm;
		}
		return $r;
	}


	function hasItem($hex)
	{
		$hex = strtoupper($hex);
		return isset($this->getItems()[$hex]);
	}

	function searchItemsByName($q)
	{
		$r = array();
		foreach($this->getItems() as $itm)
		{
			if(stripos($itm->getName(), $q) === false) continue;
			$r[] = $itm;
		}
		return $r;
	}
	function searchItemsByNameEx($q)
	{
		$r = array();
		foreach($this->getItems() as $itm)
		{
			if(stripos($itm->getName(), $q) === false && stripos($itm->getText(), $q) === false) continue;
			$r[] = $itm;
		}
		return $r;
	}

	function getItem($i)
	{
		return array_values($this->getItems())[$i];
	}
	function getShowcase()
	{
		$r = array();
		foreach($this->getItems() as $itm)
		{
			if($itm->isShowcase())
				$r[] = $itm;
		}
		return $r;
	}
	function getByHex($hex)
	{
		$hex = strtoupper($hex);
		if(!isset($this->getItems()[$hex]))
			return null;
		return $this->getItems()[$hex];
	}
	function getNumItems()
	{
		return count($this->getItems());
	}

	function isLoggedIn()
	{
		return isset($_SESSION['loggedin']);
	}

	function doLogin($user, $pass)
	{
		if($this->isLoggedIn())
			return true;

		if(!$this->hasUser($user))
			return false;

		if($this->getUsers()[$user]->getPass() == md5($pass))
		{
			$_SESSION['user'] = $this->getUsers()[$user]->getUsername();
			$this->currentUser = $_SESSION['user'];
			$_SESSION['loggedin'] = true;
			return true;
		}else{
			unset($_SESSION['loggedin']);
			unset($_SESSION['user']);
			return false;
		}
	}
	function doLogout()
	{
		unset($_SESSION['loggedin']);
		unset($_SESSION['user']);
		$this->currentUser = null;
	}
	function hasUser($user)
	{
		return isset($this->getUsers()[$user]);
	}
	
}