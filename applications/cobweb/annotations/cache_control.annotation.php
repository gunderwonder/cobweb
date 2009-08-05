<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

/**
 * @author     Øystein Riiser Gundersen <oystein@upstruct.com>
 * @package    Cobweb
 * @subpackage HTTP
 * @version    $Revision$
 */
class CacheControl extends ActionAnnotation {
	
	protected $CACHE_CONTROL_DIRECTIVES = array(
		'private' => 'private',
		'no_cache' => 'no-cache',
		'no_transform' => 'no-transform',
		'must_revalidate' => 'must-revalidate',
		'proxy_revalidate' => 'proxy-revalidate',
		'max_age' => 'max-age',
		's_maxage' => 's-maxage'
	);
	
	public function processResponse(Request $request, Response $response) {
		
		// TODO: check if the request is cachable
		$cache_control_header = array();
		foreach ($this->CACHE_CONTROL_DIRECTIVES as $directive_name => $directive)
			if (isset($this->value[$directive_name]) || in_array($directive_name, $this->value)) {
				$cache_control_header[] = $directive . 
					(isset($this->value[$directive_name]) ? "={$this->value[$directive_name]}" : '');
				if ($directive_name == 'no_cache')
					$response['Pragma'] = 'no-cache';
			}
		
		if (!in_array('no_cache', $this->value))
			$response['Pragma'] = '';
				
		$response['Cache-Control'] = implode(',', $cache_control_header);
		return $response;
	}
	
	protected function checkConstraints($target) {
		if (!is_array($this->value))
			throw new CobwebException('Cache control settings must be an array `Cache-Control` directives');

	}
	
}