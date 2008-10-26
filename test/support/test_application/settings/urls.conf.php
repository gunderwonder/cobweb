<?php

Cobweb::connect(array(
	'^$' => array('controller', 'action', array('requires_login' => true))
	
));