<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

interface Resolver {
	
	/**
	 * @param  Request $request
	 * @return Action
	 */
	public function resolve(Request $request);
	
}