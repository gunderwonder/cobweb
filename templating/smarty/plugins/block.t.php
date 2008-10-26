<?php

function smarty_block_t($parameters, $content, &$smarty, &$repeat) {
    if (!$repeat && isset($content))
		return Locale::translate($content);
}