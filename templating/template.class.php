<?php


/**
 * @author     Øystein Riiser Gundersen <oystein@upstruct.com>
 * @package    Cobweb
 * @subpackage Templating
 * @version    $Revision$
 */
class Template implements ArrayAccess {
	
	/** @var TemplateAdapter */
	private $adapter;
		
	/** @var string */
	private $buffer;
	
	protected static $template_directories = NULL;
	
	/**  @var integer */
	const RELATIVE_TEMPLATE_PATH = 10;
	
	/**  @var integer */
	const ABSOLUTE_TEMPLATE_PATH = 20;
	
	/**  @var integer */
	const DEFER_TEMPLATE_LOADING = 30;
	
	/**
	 * Creates a template object.
	 * 
	 * @param  string $template_adapter name of class to use as adapter for this template
	 * @return Template
	 */
	public function __construct($template_adapter = NULL) {
		$template_adapter = is_null($template_adapter) ?
			Cobweb::get('TEMPLATE_ADAPTER', 'SmartyTemplateAdapter') :
			$template_adapter;
		
		$this->adapter = new $template_adapter();
		$this->buffer = '';
		
		foreach (Cobweb::get('TEMPLATE_PROCESSORS', array()) as $processor)
			$this->bind(call_user_func($processor, Cobweb::get('__REQUEST__')));

	}
	
	/**
	 * Renders the specified file using this template
	 * 
	 * Pass in {@link Template::ABSOLUTE_TEMPLATE_PATH} as the argument to 
	 * <var>$loading</var> to use an absolute template path
	 * 
	 * @param  string  $filename path to file to render
	 * @param  integer $loading  template loading mode
	 * @return Template          this template object
	 */
	public function render($filename, $loading = self::RELATIVE_TEMPLATE_PATH) {
		
		if ($loading == self::RELATIVE_TEMPLATE_PATH)
			$template = self::loadTemplate($filename);
		else {
			if ($loading != self::DEFER_TEMPLATE_LOADING && !file_exists($filename))
				throw new FileNotFoundException("Could not find template file '$filename'");
				
			$template = $filename;
		}
			
		$this->write($this->adapter->renderFile($template));
		return $this;
	}
	
	/**
	 * Write the specified data to this template
	 * 
	 * @param  string   $string  string to append
	 * @return Template          this template object 
	 */
	public function write($string) {
		$this->buffer .= $string;
		return $this;
	}
	
	/**
	 * Empties the internal buffer of the template
	 * 
	 * Returns the flushed buffer
	 * 
	 * @return string template buffer
	 */
	public function flush() {
		$render = $this->buffer;
		$this->buffer = '';
		return $render;
	}
	
	/**
	 * Returns the internal buffer of this template
	 * 
	 * @return string template buffer
	 */
	public function __toString() {
		return $this->buffer; 
	}
	
	/**
	 * Returns the absolute path to the specified template file using the
	 * <var>TEMPLATE_DIRECTORIES</var> setting
	 * 
	 * @param  filename template to load
	 * @throws FileNotFoundException if the file could not be found
	 */
	public static function loadTemplate($filename) {
		
		foreach (self::templateDirectories() as $directory) {
			$template = $directory . '/' . $filename;	
			if (file_exists($template))
				return $template;
		}
		
		throw new FileNotFoundException(
			"Could not find template file '$filename'. Check your 'TEMPLATE_DIRECTORIES' setting");
	}
	
	public static function templateDirectories() {
		// memoize application template directories
		if (!self::$template_directories) {
			self::$template_directories = Cobweb::get('TEMPLATE_DIRECTORIES', array());
			$applications = Cobweb::instance()->applicationManager()->applications();
			foreach ($applications as $application) {
				$template_directory = $application->path() . '/templates';
				if (file_exists($template_directory))
					self::$template_directories[] = $template_directory;
			}
		}
		return self::$template_directories;
	}
	
	/**
	 * Returns the bindings of this tempalte
	 * 
	 * @return array bindings
	 */
	public function bindings() {
		return $this->adapter->bindings();
	}
	
	/**
	 * Binds the specified values to this template
	 * 
	 * @param  array    $bindings values to bind
	 * @return Template this template object
	 */
	public function bind(array $bindings) {
		$this->adapter->bind($bindings);
		return $this;
	}
	
	public static function create($template_adapter = NULL) {
		return new Template($template_adapter);
	}
	
	// ARRAY ACCESS IMPLEMENTATION
	/**#@+ @ignore */
	public function offsetExists($key) {
		return $this->adapter->offsetExists($key);
	}
	
	public function offsetGet($key) {
		return $this->adapter[$key];
	}
	
	public function offsetSet($key, $value) {
		$this->adapter[$key] = $value;
	}
	
	public function offsetUnset($key) {
		unset($this->adapter[$key]);
	}
	/**#@-*/
	
	
}

/**
 * @deprecated
 * @see Controller::render()
 */
function render_response($template_name, $bindings = array(), $code = HTTPResponse::OK) {
	$template = new Template();
	$template->bind($bindings);
	$template->render($template_name);
	return new HTTPResponse($template, $code);
}

/**
 * @deprecated
 * @see Template::render()
 */
function render_template($template_name, $bindings = array()) {
	$template = new Template();
	return $template->bind($bindings)->render($template_name);
}
