<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

/**
 * @author     Øystein Riiser Gundersen
 * @package    Cobweb
 * @subpackage Authentification
 * @version    $Revision$
 */
class Permission extends Doctrine_Record {
	
	public function setTableDefinition() {
		$this->hasColumn('credential', 'string', 255, 
			array(
				'notnull' => true,
				'unique' => true			
			)
		);
    }

	public function setUp() {
	
		$this->hasMany('Usergroup',
			array(
				'local' => 'permission_id',
				'foreign' => 'usergroup_id',
				'refClass' => 'UsergroupPermission'
			)
		);
		
		$this->hasMany(Cobweb::get('AUTHENTIFICATION_USER_CLASSNAME', 'User'),
			array(
				'local' => 'permission_id',
				'foreign' => 'user_id',
				'refClass' => 'UserPermission'
			)
		);
	}
	
	public function __toString() {
		return $this->credential;
	}
	
}
