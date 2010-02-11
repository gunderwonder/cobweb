<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

/**
 * @author     Øystein Riiser Gundersen
 * @package    Cobweb
 * @subpackage Doctrine
 * @version    $Revision$
 */
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
				$logger->log(array('Executed event %o in %o ms',
						$event->getName(), round($event->getElapsedSecs() * 1000, 4)));
				if ($event->getName() == 'execute' || $event->getName() == 'query') {
					$query_count++;
					$logger->info(array(
						"%o\nwith parameters %o", 
						$this->formatSQL($event->getQuery()), 
						$event->getParams()
					));
				}
			}
		}

		$logger->info(array('Doctrine spent %o ms executing %o queries', round($time * 1000, 4), $query_count));
		return $response;
	}
	
	public function formatSQL($sql) {
		$sql = preg_replace('/(\s)?(SELECT|FROM|WHERE|AND|OR|LEFT JOIN|ORDER BY|LIMIT|VALUES|INSERT INTO|SET)(\s)/', "\n$2\n\t", $sql);
		$sql = preg_replace('/,\s/', ",\n\t", $sql);
		return trim($sql);
	}

	
}