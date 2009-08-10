<?php

CobwebLoader::autoload(CACHE_APPLICATION_DIRECTORY, array(
	'DatabaseCacheEngine' => '/engine/database_cache_engine.class.php',
	'MemcachedCacheEngine' => '/engine/memcached_cache_engine.class.php',
));

CacheStore::initialize();
