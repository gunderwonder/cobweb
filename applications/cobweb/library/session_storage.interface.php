<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

/**
 * Represents the current session
 * 
 * This class is a simple wrapper around the PHP builtin session functionality.
 * If <var>INSTALLED_MIDDLEWARE</var> includes <var>cobweb.session</var>, a session 
 * object is automatically added to the request object.
 * 
 * @package    Cobweb
 * @subpackage Cobweb Application
 */

interface SessionStorage extends ArrayAccess {
    
    /**
     * Ends the current session
     */
    public function end();
    
    /**
     * Flushes the current session, deleteting all stored session data.
     */
    public function flush();
    
    /**
     * Regenerate the session (i.e construct a new session ID)
     */
    public function regenerate();
    
    /**
     * Set the time to live for the current session
     */
    public function expire($exiry);
    
    /**
     * Retrieve a session value; if undefined, the specified default value is
     * returned.
     * 
     * @param string $key
     * @param mixed $default_value
     */
    public function get($key, $default_value = NULL);
}