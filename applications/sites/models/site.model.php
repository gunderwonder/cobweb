<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

/**
 * @package Cobweb
 * @subpackage Sites application
 * @author Øystein Riiser Gundersen <oystein@upstruct.com>
 * @version $Revision$
 */
class Site extends Model {
	
	protected static $current_site = NULL;
	
	public function setTableDefinition() {
		$this->hasColumn('domain', 'string', 512, array('notnull' => true));
		$this->hasColumn('name', 'string', 512, array('notnull' => true));
	}
	
	public function postSave($event) {
		self::$current_site = $this;
	}
	
	public static function current() {
		if (self::$current_site)
			return self::$current_site;
		
		if (!($site_id = Cobweb::get('SITE_ID', false)))
			throw new ConfigurationException('No SITE_ID defined in settings.conf.php');
		self::$current_site = Model::table(__CLASS__)->find($site_id);
		
		if (!self::$current_site)
			throw new ConfigurationException('No site found for the current SITE_ID');
		return self::$current_site;
	}
	
	
}