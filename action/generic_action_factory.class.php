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
class GenericActionFactory  implements ActionFactory {
	
	protected $action_classname = NULL;
	protected $options = NULL;
	
	public function __construct($action_classname, array $options = array()) {
		$this->action_classname = $action_classname;
		$this->options = $options;
		if (!in_array('Action', class_implements($this->action_classname)))
			throw new CobwebException("{$this->action_classname} does not implement Action interface");
	}
	
	public function createAction(
			Request $request, 
			Dispatcher $dispatcher, 
			Resolver $resolver, 
			array $specification, 
			array $options) {
		return new $this->action_classname(
			$request,
			$dispatcher,
			$resolver,
			$specification,
			array_merge($this->options, $options)
		);
	}
}