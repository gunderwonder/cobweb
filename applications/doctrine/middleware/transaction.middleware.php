<?php

class TransactionMiddleware extends Middleware {
	
	public function processRequest(Response $response) {
		foreach (CobwebDoctrineManager::connections() as $connection)
			$connection->beginTransaction();
	}
	
	public function processException(Request $request, Response $response, Exception $e) {
		foreach (CobwebDoctrineManager::connections() as $connection)
			$connection->rollback();
	}
	
}