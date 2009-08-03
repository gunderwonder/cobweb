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
interface Action  {
	
	/**
	 * Invoke this action, returning a {@link Response} object
	 * @param array $arguments the arguments to invoke the actio with
	 * @return Response
	 */
	public function invoke(array $arguments = NULL);
	
	/**
	 * Returns true if this action is annotated with the specified annotation,
	 * false otherwise
	 * 
	 * @param string $annotation the name of the annotation
	 * @return bool if this action is annotated with the specified annotation
	 */
	public function hasAnnotation($annotation);
	
	/**
	 * Returns the value of the specified annotation. Throws {@link CobwebErrorException}
	 * if this action is not annotated with the annotation.
	 * 
	 * @param string $annotation the name of the annotation to retrieve
	 * @return mixed the value of the annotation
	 */
	public function annotation($annotation);
	
	
	/**
	 * Return an array of all the annotations of this action
	 * 
	 * @return array
	 */
	public function allAnnotations();
}