<?php

class DoctrineLoggingMiddleware extends Middleware {
	
	/** @var Doctrine_Manager */
	private $manager;
	
	/** @var Doctrine_Connection_Profiler */
	private $profiler;
	
	public function initialize() {
		if (Cobweb::get('DEBUG')) {
			$this->profiler = new Doctrine_Connection_Profiler();
			foreach (CobwebDoctrineManager::connections() as $connection)
				$connection->setListener($this->profiler);
		}
	}
		
	public function processResponse(Request $request, Response $response) {
		
		if (!Cobweb::get('DEBUG') || !Cobweb::get('DOCTRINE_LOGGING', false))
			return $response;
		
		$logger = new Logger('Queries');
		$this->dispatcher->fire('logging.register_logger', array('logger' => $logger));
		
		$query_count = 0;
		$time = 0;
		if (Cobweb::get('DEBUG')) {
			foreach ($this->profiler as $event) {
					
				$time += $event->getElapsedSecs();
				if ($event->getName() == 'query' || $event->getName() == 'execute') {
					
					$query_count++;
					$logger->log(array('Executed query in %o seconds:', $event->getElapsedSecs()));
					$logger->info(array('%o', 
						$this->formatSQL($event->getQuery())));
				} else
					$logger->log(array('Executed event %o in %o seconds',
						$event->getName(), $event->getElapsedSecs()));
					
			}
		}

		$logger->info(array('Doctrine spent %o seconds executing %o queries', $time, $query_count));
		
		return $response;
	}
	
	public function formatSQL($sql) {
		
		$sql = preg_replace('/(\s)?(SELECT|FROM|WHERE|AND|OR|LEFT JOIN|ORDER BY|LIMIT|VALUES|INSERT INTO)(\s)/', "\n$2\n\t", $sql);
		$sql = preg_replace('/,\s/', ",\n\t", $sql);
		return trim($sql);
	}

	
}