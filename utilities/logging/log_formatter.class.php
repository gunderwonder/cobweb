<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

abstract class LogFormatter {
	
	protected $logger;
	
	public function __construct(Logger $logger) {
		$this->logger = $logger;
	}
	
	abstract public function format(Response $response);
	
}