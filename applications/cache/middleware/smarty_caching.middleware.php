<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

/**
 * @author     Øystein Riiser Gundersen
 * @package    Cobweb
 * @subpackage Cache
 * @version    $Revision$
 */
class SmartyCachesTemplate extends Annotation { }

/**
 * @author     Øystein Riiser Gundersen
 * @package    Cobweb
 * @subpackage Cache
 * @version    $Revision$
 */
class SmartyCachingMiddleware extends Middleware {
	
	public function processAction(Request $request, Action $action) {
		$template_name = NULL;
		if ($action->hasAnnotation('SmartyCachesTemplate'))
			$template_name = $action->annotation('SmartyCachesTemplate')->value;
			
		if (!$template_name)
			return NULL;
		
		$smarty = new SmartyTemplate();
		if (!$smarty->caching)
			return NULL;
		
		$template_path = Template::loadTemplate($template_name);
		$smarty->template_dir = dirname($template_path);
		$smarty->compile_id = dirname($template_path); 
		if ($smarty->is_cached(basename($template_path))) {
			Cobweb::info("Smarty cache middleware: Smarty has cached the '%s' template; " .
			             "returning cache contents before invoking action...", $template_name);
			return new HTTPResponse($smarty->renderFile(Template::loadTemplate($template_name)));	
		}
		
		Cobweb::info("Smarty cache middleware: Smarty has not cached the '%s' template", $template_name);
		return NULL;
			
	}
	
}