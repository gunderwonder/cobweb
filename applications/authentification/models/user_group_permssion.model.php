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
class UsergroupPermission extends Doctrine_Record {
	
	public function setTableDefinition() {
		$this->hasColumn('permission_id', 'integer', NULL, array('primary' => true));
        $this->hasColumn('usergroup_id', 'integer', NULL, array('primary' => true));
    }	
}
