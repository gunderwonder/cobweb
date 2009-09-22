<?php

CobwebLoader::autoload(COBWEB_APPLICATION_DIRECTORY, array(
    
    'Session' => '/library/session.class.php',
    'SessionNotificationManager' => '/middleware/session_notification.middleware.php',
	'SessionNotification' => '/middleware/session_notification.middleware.php'
));

