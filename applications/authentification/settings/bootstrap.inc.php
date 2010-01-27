<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

class RequiresAuthentification extends Annotation {
	protected $login_url = NULL;
	protected $permissions = array();
	
	public function loginURL($default = NULL) {
		return $this->login_url ? $this->login_url : $default;
	}
}
class RequiresPermission extends Annotation { }

CobwebLoader::autoload(AUTHENTIFICATION_APPLICATION_DIRECTORY, array(
	'RequiresHTTPAuthentification' => '/annotations/authentification_annotations.inc.php',
	'UsergroupPermission' => '/models/user_group_permission.model.php'
));