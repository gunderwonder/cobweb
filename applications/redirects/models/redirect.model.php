<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

/**
 * @package Cobweb
 * @subpackage Redirects application
 * @author Øystein Riiser Gundersen <oystein@upstruct.com>
 * @version $Revision$
 */
class Redirect extends Model {
	
	public function setTableDefinition() {
		$this->hasColumn('site_id', 'integer');
		$this->hasColumn('old_url', 'string', NULL, array('notnull' => true));
		$this->hasColumn('new_url', 'string');
		$this->index('old_url', array(
			'fields' => array('old_url', 'site_id'),
			'type' => 'unique'
		));
	}
	
	public function setUp() {
		if (class_exists('Site'))
			$this->hasOne('Site', array('local' => 'site_id', 'foreign' => 'id'));
	}
	
	public static function find($url, $site_id = NULL) {
		$redirect_query = Model::query('Redirect', 'r')
			->where('r.old_url = ?', $url);
		if (!is_null($site_id))
			$redirect_query->addWhere('r.site_id = ?', $site_id);
		else
			$redirect_query->addWhere('r.site_id IS NULL');
			
		if (class_exists('Site'))
			$redirect_query->leftJoin('r.Site');
		
		return $redirect_query->fetchOne();
	}
	
	public static function create($from, $to, $site_id = NULL, $overwrite_new = true) {
		
		if ($redirect = self::find($from, $site_id)) {
			if ($redirect->new_url == $to)
				return $redirect;
			
			if ($overwrite_new)
				$redirect->new_url = $to;
		}
		$redirect = $redirect ? $redirect : new Redirect();
		
		if (!$redirect->exists()) {
			$redirect->site_id = $site_id;
			$redirect->old_url = $from;
			$redirect->new_url = $to;
		}
		
		$redirect->save();
		return $redirect;
	}
	
}