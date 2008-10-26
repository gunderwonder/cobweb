<?php


class HTTPRequestTest extends CobwebCoreTestCase {
	
	protected $request;
	
	public function setUp() {
		$this->initialize();
		
		$meta = $_SERVER;
		$meta['REQUEST_URI'] = '/cobweb-test/path/?page=1#hash';
		$meta['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';

		$this->request = new HTTPRequest(
			new Dispatcher(),
			array('page' => '1'),
			array('username' => 'gunderwonder', 'password' => 'xxx', 'empty' => ''),
			$meta,
			array());
	}
	
	public function testRequestAccessors() {

		$this->assertEqual($this->request->path(), '/path/',
			'URL_PREFIX should be left-trimmed from request path');
		
		$this->assertEqual($this->request->URI(), '/path/?page=1#hash',
			'URL_PREFIX should be left-trimmed from request URL');
		
		$this->assertEqual($this->request->hash(), 'hash');

		$this->assertEqual($this->request->query(), 'page=1');
		$this->assertEqual($this->request->bits(), array('path'));
		
		$this->assertEqual($this->request['X-Requested-With'], 'XMLHttpRequest');
		$this->assertTrue($this->request->isAJAX());
	}
	
	public function testQueryDictionaryAccessors() {
		$this->assertEqual($this->request->GET->toArray(),
			array('page' => '1'));
		$this->assertEqual($this->request->POST->toArray(), 
			array('username' => 'gunderwonder', 'password' => 'xxx', 'empty' => ''));
		
		$this->assertEqual($this->request->POST, 
			'username=gunderwonder&password=xxx&empty');
			
			
		$this->assertEqual($this->request->POST['username'], 'gunderwonder');
		$this->assertEqual($this->request->POST['password'], 'xxx');
		
		$this->assertEqual($this->request->GET['page'], '1');
		
		$this->expectError();
		$this->request->POST['some_arg'];

		$this->assertEqual($this->request->POST->get('some_arg', 1), 1);
		$this->assertEqual($this->request->GET->get('some_arg', 1), 1);
		
		$this->assertEqual($this->request->POST->get('empty', 1), 1);
		
	}
}