<?php

define('COBWEB_DIRECTORY', '/Users/gunderwonder/Sites/cobweb-rewrite');
define('COBWEB_PROJECT_DIRECTORY', realpath(dirname(__FILE__) . '/..'));


if (!defined('COBWEB_DIRECTORY') || 
    !file_exists(COBWEB_DIRECTORY . '/core/cobweb_bootstrap.inc.php')) {
	
	header("HTTP/1.1 500 Internal Server Error");
	
	echo <<<EOS
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>Internal Server Error</title>
</head>
<body>
<h1>Cobweb not found!</h1>
<p><tt>COBWEB_PATH</tt> set incorrectly maybe?</p>
</body>
</html>
EOS;

	die();
}

require_once COBWEB_DIRECTORY . '/core/cobweb_bootstrap.inc.php';

try {
	Cobweb::initialize();
	Cobweb::run();
} catch (Exception $e) {
	if (Cobweb::get('DEBUG')) {
		
		require_once COBWEB_DIRECTORY . '/applications/cobweb/controllers/debug.controller.php';
		try {
			$dispatcher = Cobweb::get('__DISPATCHER__');
			$request = Cobweb::get('__REQUEST__');
			
		} catch (Exception $_e) {
			throw $e;
		}
		
		
		$debug_controller = new DebugController($dispatcher, $request);
		$response = $debug_controller->debugger($e);
		
		
		$dispatcher->finalize($response);
		
	}
	
	
	
}

?>