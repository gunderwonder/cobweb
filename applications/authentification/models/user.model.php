<?php
/**
 * @version $Id$
 */

require_once COBWEB_DIRECTORY . '/vendor/phpass/PasswordHash.php';


class User extends Model {
	
	private $hasher;
	private $authenticated;
	
	public function construct() {
		$this->authenticated = false;
		$this->hasher = new PasswordHash(8, false);
	}
	
    public function setTableDefinition() {
		$this->hasColumn('firstname', 'string', 255, array('notnull' => true));
		$this->hasColumn('lastname', 'string', 255, array('notnull' => true));
		$this->hasColumn('username', 'string', 255,
			array(
				'notblank' => true,
				'notnull' => true,
				'unique' => true
			)
		);
		
        $this->hasColumn('password', 'string', 255, array('notnull' => true));
		$this->hasColumn('email', 'string', 255,
			array(
				'notnull' => true,
				'notblank' => true
			)
		);
		$this->hasColumn('is_staff', 'boolean', array('default' => false));
		$this->hasColumn('is_superuser', 'boolean', array('default' => false));
		
		$this->hasColumn('usergroup_id', 'integer');

    }

    public function setUp() {
        $this->hasOne('Usergroup', 
			array(
				'local' => 'usergroup_id',
				'foreign' => 'id',
			)
		);
		
		$this->hasMany('Permission as Permissions', 
			array(
				'local' => 'user_id',
				'foreign' => 'permission_id',
				'refClass' => 'UserPermission'
			)
		);
	
    }

	public function fullname() {
		return $this->firstname . ' ' . $this->lastname;
	}
	
	public function isAuthenticated() {
		return $this->authenticated;
	}

	public function setPassword($password) {
		$this->password = $this->hasher->HashPassword($password);
	}
	
	public function hasPassword($password) {
		return $this->hasher->CheckPassword($password, $this->password);			
	}
	
	public function setAuthenticated($is_authenticated) {
		$this->authenticated = $is_authenticated;
	}
	
	public function hasPermission($credential) {
		if (!$this->isAuthenticated())
			return false;
		
		if ($this->is_superuser)
			return true;
		
		$user = Model::query('User', 'u')
			->leftJoin('u.Permissions p')
			->where('u.id = ?', $this->id)
			->addWhere('p.credential = ?', $credential)
			->fetchOne();
		
		if ($user)
			return true;
				
		return $this->Usergroup->hasPermission($credential);
	}
	
	public function hasPermissions(array $credentials) {
		foreach ($credentials as $c)
			if (!$this->hasPermission($c))
				return false;
				
		return true;
	}
	
	public function permissions	() {
		$permissions = array();
		foreach ($this->Permissions as $permission)
			$permissions[] = $permission->credential;

		return array_merge($permissions, $this->Usergroup->permissions());
	}
	
	
	public static function authenticate($username, $password) {
		if (!$username)
			return NULL;
		
		$user = self::table()->findOneByUsername($username);
		if (!$user)
			return NULL;

		if ($user->hasPassword($password)) {
			$user->authenticated = true;
			return $user;
		}	
		return NULL;
	}

	public static function table() {
		return Doctrine::getTable('User');
	}
	
	public function __toString() {
		return $this->username;
	}

}





