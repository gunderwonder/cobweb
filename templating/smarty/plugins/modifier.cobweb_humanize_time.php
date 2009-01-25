<?php

function smarty_modifier_cobweb_humanize_time($string) {
	$time = new Time($string);
	
	$now = new Time();
	
	$week_ago = $now->copy()->modify('- 6 days');
	
	$prefix = $time->format('j.n.Y');
	
	if ($time->year == $now->year && $time->month == $now->month)
		if ($time->day == $now->day)
			$prefix = 'Today';
		else if ($time->day - 1 == $now->day)
			$prefix = 'Yesterday';
		else if ($time->compare($week_ago) == 1)
			$prefix = $time->format('l');
			
	return $prefix . ' ' . $time->format('H:i');
	
	
}