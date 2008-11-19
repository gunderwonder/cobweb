<?php
/**
 * @version $Id$
 */

error_reporting(E_ALL);

/**
 * @package     Cobweb
 * @subpackage  Core
 * @author      Øystein Riiser Gundersen <oystein@upstruct.com>
 * @version     $Revision$
 */
class Cobweb implements CobwebDeclaration {
	
	const VERSION = '0.2';
	
	private static $cobweb = NULL;
	private static $logger = NULL;
	
	private static $initialized = false;
	
	protected 
		$configuration, 
		$request;
	
	public function __construct() {
		self::$cobweb = $this;
		
		Cobweb::info('This is Cobweb %s...', self::VERSION);
		$this->dispatcher = $this->createDispatcher(array());
		$this->configuration = $this->createConfiguration(array('dispatcher' => $this->dispatcher));
		
		
		Cobweb::log('Loading settings...');
		Cobweb::set('__COBWEB_START_TIME__', microtime(true));
		$this->configuration->configure(
			$this->configuration->load(COBWEB_DIRECTORY . '/settings/settings.conf.php'));
		
		if (defined('COBWEB_PROJECT_DIRECTORY')) {
			
			$this->configuration->configure(
				$this->configuration->load(COBWEB_PROJECT_DIRECTORY . '/settings/settings.conf.php'));
			
			Router::connect($this->configuration->load(
				COBWEB_PROJECT_DIRECTORY . '/settings/urls.conf.php'));
		}
		
		Cobweb::info('Proceeding with settings %o', $this->configuration->settings());
		
		$this->request = $this->createRequest(array('dispatcher' => $this->dispatcher));
		$this->application_manager = $this->createApplicationManager(
			array('dispatcher' => $this->dispatcher, 'request' => $this->request)
		);
		
		$this->middleware_manager = $this->createMiddlewareManager(
			array(
				'dispatcher' => $this->dispatcher,
				'application_manager' => $this->application_manager
			)
		);
		$this->resolver = $this->createResolver(array('dispatcher' => $this->dispatcher));
	}
	
	public static function initialize(CobwebDeclaration $cobweb = NULL) {
		if (self::$initialized)
			return;
		
		self::$logger = new Logger('Cobweb');
		
		if (is_null(self::$cobweb))
			self::$cobweb = is_null($cobweb) ? new Cobweb() : $cobweb;
		self::$initialized = true;
	}
	
	public static function run() {
		self::$cobweb->setup();
		self::$cobweb->dispatch();
	}
	
		
	public static function get($key, $default_value = NULL) {
		return self::$cobweb->configuration()->get($key, $default_value);
	}
	
	public static function set($key, $value) {
		return self::$cobweb->configuration()->set($key, $value);
	}
	
	public static function configure(array $settings) {
		self::$cobweb->configuration()->configure($settings);
	}
	
	
	public static function loadApplication($application_name) {
		$application = self::$cobweb->applicationManager()->load($application_name);
		return $application;
		
	}
	
	protected function setup() {
		
		// error_reporting(Cobweb::get('ERROR_REPORTING'));
		date_default_timezone_set(Cobweb::get('TIMEZONE'));
		
		Cobweb::set('__REQUEST__', $this->request);
		Cobweb::set('__MIDDLEWARE_MANAGER__', $this->middleware_manager);
		Cobweb::set('__DISPATCHER__', $this->dispatcher);
		Cobweb::set('__RESOLVER__', $this->resolver);
		
		Cobweb::log('Loading middleware...');
		$this->middleware()->load();
		
		$this->dispatcher->fire('middleware.loaded', array('manager' => $this->middleware()));
		$this->dispatcher->fire('logging.register_logger', array('logger' => self::$logger));
		$this->dispatcher->fire('logging.register_logger', array('logger' => Console::logger()));

	}
	
	protected function dispatch() {

		Cobweb::log('Resolving URI...');
		$action = $this->resolver()->resolve($this->request, Cobweb::get('URL_CONFIGURATION'));
		
		Cobweb::log('Dispatching request...');
		$response = $this->dispatcher->dispatch($this->request, $action, $this->middleware());
		
		$this->dispatcher->fire('dispatcher.dispatched_request', array('response' => $response));
		$this->dispatcher->finalize($response);
		$this->dispatcher->fire('dispatcher.finalized_response', array('response' => $response));
		
		
	}
	
	public function createConfiguration(array $settings) {
		return new CobwebConfiguration($settings['dispatcher']);
	}
	
	public function createMiddlewareManager(array $settings) {
		return new MiddlewareManager($settings['dispatcher'], $settings['application_manager']);
	}
	
	public function createDispatcher(array $settings) {
		return new Dispatcher();
	}
	
	public function createRequest(array $settings) {
		return new HTTPRequest(
			$settings['dispatcher'],
			strip_magic_quotes($_GET),
			strip_magic_quotes($_POST),
			$_SERVER,
			$_COOKIE);
	}
	
	public function createResolver(array $settings) {
		return new URLResolver(
			$this->dispatcher, Cobweb::get('URL_CONFIGURATION', array()));
	}
	
	public function createApplicationManager(array $settings) {
		return new ApplicationManager($settings['dispatcher'], $settings['request']);
	}
	
	public function configuration() {
		return $this->configuration;
	}
	
	public function request() {
		return $this->request;
	}
	
	public function dispatcher() {
		return $this->dispatcher;
	}
	
	public function middleware() {
		return $this->middleware_manager;
	}
	
	public function resolver() {
		return $this->resolver;
	}
	
	public function applicationManager() {
		return $this->application_manager;
	}
	
	public static function load($class) {
		return CobwebLoader::load($class);
	}
	
	public static function processTemplate(Request $request) {
		return array(
			'REQUEST' => $request, 
			'URL_PREFIX' => Cobweb::get('URL_PREFIX')
		);
	}
	
	public static function handleError(
		$error_number, 
		$error_message, 
		$error_file, 
		$error_line_number, 
		$error_context) {
			
		if (error_reporting() == 0)
    		return false;

  		if (error_reporting() & $error_number) {
			throw new CobwebErrorException(
				$error_number,
				$error_message,
				$error_file,
				$error_line_number,
				$error_context,
				array_slice(debug_backtrace(), 1)
			);
		}

		return false;
	}
	
	public static function log() {
		return self::$logger->log(func_get_args());
	}
	
	public static function warn() {
		return self::$logger->warn(func_get_args());
	}
	
	public static function info() {
		return self::$logger->info(func_get_args());
	}
	
	public static function assert() {
		return self::$logger->info(func_get_args());
	}
	
	public static function error() {
		return self::$logger->error(func_get_args());
	}
	
	/**
	 * @deprecated
	 */
	public static function setting($key) {
		return self::get($key);
	}
}