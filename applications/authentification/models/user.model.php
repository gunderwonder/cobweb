<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

require_once COBWEB_DIRECTORY . '/vendor/phpass/PasswordHash.php';

/**
 * @author     Øystein Riiser Gundersen
 * @package    Cobweb
 * @subpackage Authentification
 * @version    $Revision$
 */
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
		$this->hasColumn('username', 'string', 255, array(
			'notblank' => true,
			'notnull' => true,
			'unique' => true
		));
		
        $this->hasColumn('password', 'string', 255, array('notnull' => true));
		$this->hasColumn('email', 'string', 255, array(
			'notnull' => true,
			'notblank' => true
		))
		;
		$this->hasColumn('is_staff', 'boolean', array('default' => false));
		$this->hasColumn('is_superuser', 'boolean', array('default' => false));
		$this->hasColumn('usergroup_id', 'integer');
		
		if (Cobweb::get('AUTHENTIFICATION_USER_HAS_IS_ACTIVE_FLAG', false))
			$this->hasColumn('is_active', 'boolean', array('default' => false));

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
	
	public function hasPermission($permission) {
		if (!$this->isAuthenticated())
			return false;
		
		if ($this->is_superuser)
			return true;
		
		if ($permission == 'is_staff' && $this->is_staff)
			return true;
		
		if (in_array($permission, $this->Permissions->toKeyValueArray('id', 'credential')))
			return true;
				
		return $this->Usergroup->hasPermission($permission);
	}
	
	public function hasPermissions(array $permissions) {
		foreach ($permissions as $permission)
			if (!$this->hasPermission($permission))
				return false;
				
		return true;
	}
	
	public function permissions	() {
		return array_merge(
			$this->Permissions->toKeyValueArray('id', 'credential'), 
			$this->Usergroup->permissions()
		);
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

	/**
	 * @deprecated
	 */
	public static function table() {
		return Doctrine::getTable(Cobweb::get('AUTHENTIFICATION_USER_CLASSNAME', 'User'));
	}
	
	public function __toString() {
		return $this->username;
	}

}





