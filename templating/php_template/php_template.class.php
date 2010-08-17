<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

require_once 'php_template_plugin.interface.php';

/**
 * @package Cobweb
 * @subpackage Templating
 * @author Øystein Riiser Gundersen <oystein@upstruct.com>
 */
class PHPTemplate extends TemplateAdapter {
	
	const ESCAPE_HTML = 'html';
	const ESCAPE_URL = 'url';
	
	/** @var array */
	protected $plugins;
	
	protected function initialize() {
		$this->plugins = array();
		$this->loaded_plugins = array();
	}
	
	/**
	 * Renders the specified template as a "raw" PHP template using the bindings
	 * of this template.
	 */
	public function interpolate($template, $interpolation_mode = TemplateAdapter::INTERPOLATE_FILE) {
		
		foreach ($this->bindings as $binding => $value)
			$$binding = $value;
		$this_alias = Cobweb::get('PHP_TEMPLATE_THIS_ALIAS', '_');
		$$this_alias = $this;
		unset($this_alias);
		$request = Cobweb::request();
		
		ob_start();
		try {
			switch ($interpolation_mode) {
				case self::INTERPOLATE_FILE:
					require_once $template; break;
				case self::INTERPOLATE_RESOURCE: /* fallthru */
					// eval(stream_get_contents($template)); break;
				case self::INTERPOLATE_STRING:
					// eval($template); break;
					throw new NotImplementedException();
			}
		} catch (Exception $e) {
			ob_end_clean();
			throw $e;
		}
		$result = ob_get_contents();
		ob_end_clean();
		return $result;
	}
	
	/**
	 * Escape the specified string for HTML or URL use.
	 * @param string $string the string to escape
	 * @param string $type   escape mode ({@link PHPTemplate::ESCAPE_HTML}
	 *                       or {@link PHPTemplate::ESCAPE_URL})
	 */
	protected function escape($string, $type = self::ESCAPE_HTML) {
		switch ($type) {
			case self::ESCAPE_HTML: return htmlspecialchars($string);
			case self::ESCAPE_URL: return urlencode($string);
		}
		return $string;
	}
	
	/**
	 * Include the specified template in this template.
	 * 
	 * @param string $template the template to include
	 * @param array  $bindings values to bind to the template
	 * @param bool   $output   if false, don't `echo` the interpolated template
	 */
	protected function include_template($template, $bindings = array(), $output = true) {
		$result = Template::create(__CLASS__)
			->bind(array_merge($this->bindings, $bindings))
			->render($template);
		if ($output) echo $result;
		return $result;
	}
	
	/**
	 * Loads a particular template plugin for use in this template.
	 * 
	 * @param string $plugin
	 * @return void
	 */
	protected function load($plugin) {
		if (in_array($plugin, $this->loaded_plugins))
			return;
		$plugin_class = str_classify($plugin) . 'TemplatePlugin';
		if (!class_exists($plugin_class))
			$this->loadUnloadedPlugin($plugin, $plugin_class);
			
		$plugin_object = new $plugin_class();
		foreach ($plugin_object->__plugins($this) as $plugin_method => $actual_method)
			$this->plugins[$plugin_method] = array($plugin_object, $actual_method);
			
		$this->loaded_plugins[] = $plugin;	
	}
	
	/**
	 * @needsdocumentation
	 */
	protected function loadUnloadedPlugin($plugin, $plugin_class) {
		foreach (Cobweb::get('PHP_TEMPLATE_PLUGIN_PATH', 
			array(COBWEB_DIRECTORY . '/templating/php_template/plugins')) as $directory) {
			$file = $directory . '/' . $plugin . '.template_plugin.php';
			if (is_file($file))
				require_once $file;
			return true;
		}
		if (!class_exists($plugin_class))
			throw new CobwebException("Unable to load template plugin {$plugin}: Class '{$plugin_class}' does not exist.");
	}
	
	/**
	 * Calls a plugin method, if present.
	 * 
	 * @param string $method     the plugin method to invoke
	 * @param array  $arguments  plugin method arguments
	 * @return mixed
	 */
	public function __call($method, $arguments) {
		if (isset($this->plugins[$method]))
			return call_user_func_array($this->plugins[$method], $arguments);

		throw new CobwebException("No template plugin responds to {$method}");
	}
}

