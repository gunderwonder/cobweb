<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

/**
 * @author Øystein Riiser Gundersen <oystein@upstruct.com>
 * @package Cobweb
 * @subpackage Forms
 * @version $Revision$
 */
final class ConcreteForm extends Form {
	
	public function __construct(array $specification, $data = NULL) {
		foreach ($specification as $key => $field)
			$this->__set($key, $field);
			
		parent::__construct($data);
	}
	
	public function configure() { }
	
}