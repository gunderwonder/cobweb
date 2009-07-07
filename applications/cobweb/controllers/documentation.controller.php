<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */


/**
 * @author     Øystein Riiser Gundersen <oystein@upstruct.com>
 * @package    Cobweb
 * @subpackage Cobweb Application
 * @version    $Revision$
 */
class DocumentationController extends Controller {
	
	/**
	 * @param  $slug string
	 * @return HTTPResponse
	 */
	public function manual($slug = NULL) {
		return self::invoke('cobweb.markdown.generate',
			array(
				'slug' => is_null($slug) ? 'index' : $slug,
				'base_path' => COBWEB_DIRECTORY . '/documentation/manual',
				'base_template' => '/documentation/cobweb_manual.tpl' 
			)
		);
	}
	
	
}