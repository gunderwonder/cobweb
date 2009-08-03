<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

/**
 * @author     Øystein Riiser Gundersen <oystein@upstruct.com>
 * @version    $Rev$
 * @package    Cobweb
 * @subpackage Cobweb Application
 */
class TemplateController extends Controller {	
	
	public function render(
			$template_name,
			$bindings = array(),
			$code = HTTPResponse::OK,
			$mime_type = MIMEType::HTML,
			$template_adapter = NULL, 
			$loading = Template::RELATIVE_TEMPLATE_PATH) {
				
		return parent::render($template_name, $bindings, $code, $mime_type, $template_adapter, $loading);
	}
}

