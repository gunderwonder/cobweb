<?php

function smarty_modifier_floatformat($string, $decimals = 0) {
	return sprintf("%.{$decimals}f", floatval($string));
	
}