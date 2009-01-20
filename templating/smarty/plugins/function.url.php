<?php

function smarty_function_url($parameters, &$smarty) {
	return Cobweb::get('__RESOLVER__')->reverse($parameters['name'], array_slice($parameters, 1));
}