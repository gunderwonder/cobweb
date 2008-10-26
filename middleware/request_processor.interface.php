<?php

interface RequestProcessor {
	
	public function __construct(Dispatcher $dispatcher);
	
	public function processRequest(Request $request);
	public function processResponse(Request $request, Response $response);
	public function processAction(Request $request, Action $action);
	public function processException(Request $request, Exception $exception);
}
