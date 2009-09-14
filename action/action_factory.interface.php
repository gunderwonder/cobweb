<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

/**
 * @author Øystein Riiser Gundersen <oystein@upstruct.com>
 * @package Cobweb
 * @subpackage Dispatch
 */
interface ActionFactory  {
	
	/**
	 * @return Action
	 */
	public function createAction(
		Request $request, 
		Dispatcher $dispatcher, 
		Resolver $resolver, 
		array $specification, 
		array $options
	);
}