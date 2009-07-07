<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

/**
 * @author Øystein Riiser Gundersen <oystein@upstruct.com>
 * @package Cobweb
 * @subpackage Configuration
 */
interface Configurable {
	
	public function get($key, $default_value = NULL);
	public function set($key, $value);
	       
	public function configure(array $settings);
	
	public function load($file = NULL);
	
}