<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

/**
 * @package Cobweb
 * @subpackage Core
 * @author Øystein Riiser Gundersen <oystein@upstruct.com>
 * @version $Revision$
 */
class CobwebLoader {
	
	protected static $cobweb_classes = array(
		
		// core
		'Configurable' => '/settings/configurable.interface.php',
		'CobwebConfiguration' => '/settings/cobweb_settings.class.php',
		'Dispatcher' => '/dispatch/dispatcher.class.php',
		'CobwebEvent' => '/dispatch/cobweb_event.class.php',
		'Request' => '/http/request.class.php',
		'Response' => '/http/response.class.php',
		'Resolver' => '/dispatch/resolver.class.php',
		'URLResolver' => '/dispatch/url_resolver.class.php',
		'Router' => '/dispatch/router.class.php',
		
		// HTTP
		'HTTPRequest' => '/http/http_request.class.php',
		'HTTPResponse' => '/http/http_response.class.php',
		'HTTPResponseRedirect' => '/http/http_response.class.php',
		'HTTPResponseNotModified' => '/http/http_response.class.php',
		'HTTPResponsePermanentRedirect' => '/http/http_response.class.php',
		'HTTPResponseMethodNotAllowed' => '/http/http_response.class.php',
		'HTTPResponseGone' => '/http/http_response.class.php',
		'HTTPResponseUnauthorized' => '/http/http_response.class.php',
		'HttpResponseBadRequest' => '/http/http_response.class.php',
		'HTTPQueryDictionary' => '/http/http_query_dictionary.class.php',
		'AJAXResponse' => '/http/ajax_response.class.php',
		'UploadedFilesArray' => '/http/utilities/uploaded_files_array.class.php',
		'UploadedFile' => '/http/utilities/uploaded_files_array.class.php',
		
		// collections
		'ImmutableArray' => '/utilities/immutable_array.class.php',
		'MutableArray' => '/utilities/mutable_array.class.php',
		
		// middleware
		'MiddlewareManager' => '/middleware/middleware_manager.class.php',
		'Middleware' => '/middleware/middleware.class.php',
		'RequestProcessor' => '/middleware/request_processor.interface.php',
		
		// actions
		'Controller' => '/controller/controller.class.php',
		'CobwebController' => '/applications/cobweb/controllers/cobweb.controller.php',
		'CallableAction' => '/action/callable_action.class.php',
		'Concealed' => '/action/callable_action.class.php',
		'ControllerAction' => '/action/controller_action.class.php',
		'Action' => '/action/action.interface.php',
		'ActionFactory' => '/action/action_factory.interface.php',
		'GenericActionFactory' => '/action/generic_action_factory.class.php',
		'IncludeURLConfigurationAction' => '/action/include_url_configuration_action.class.php',
		
		// caching
		'CacheEngine' => '/applications/cache/engine/cache_engine.class.php',
		'CacheStore' => '/applications/cache/engine/cache_store.class.php',
		'DatabaseCacheEngine' => '/applications/cache/engine/database_cache_engine.class.php',
		
		// annotations
		'Annotation' => '/vendor/addendum/annotations.php',
		'ReflectionAnnotatedClass' => '/vendor/addendum/annotations.php',
		'ReflectionAnnotatedFunction' => '/vendor/addendum/annotations.php',
		'ActionAnnotation' => '/middleware/action_annotation.class.php',
		'AllowedMethods' => '/applications/cobweb/annotations/allowed_methods.annotation.php',
		'CacheControl' => '/applications/cobweb/annotations/cache_control.annotation.php',
		'VaryOnHeaders' => '/applications/cobweb/annotations/vary_on_headers.annotation.php',
		
		// applications
		'ApplicationManager' => '/application/application_manager.class.php',
		'Application' => '/application/application.class.php',
		
		// templating
		'Template' => '/templating/template.class.php',
		'TemplateAdapter' => '/templating/template_adapter.class.php',
		'SmartyTemplateAdapter' => '/templating/smarty/smarty_template_adapter.class.php',
		'SmartyTemplate' => '/templating/smarty/smarty_template.class.php',
		'PHPTemplate' => '/templating/php_template/php_template.class.php',
		'PHPTemplatePlugin' => '/templating/php_template/php_template_plugin.interface.php',
		'SmartyCachesTemplate' => '/applications/cache/middleware/smarty_caching.middleware.php',
		
		// logging
		'Logger' => '/utilities/logging/logger.class.php',
		'LogFormatter' => '/utilities/logging/log_formatter.class.php',
		'FirePHPLogFormatter' => '/utilities/logging/firephp_log_formatter.class.php',
		'FirebugLogFormatter' => '/utilities/logging/firebug_log_formatter.class.php',
		'Console' => '/utilities/logging/console.class.php',
		'LoggingEnabled' => '/applications/cobweb/middleware/logging.middleware.php',
		'LoggingDisabled' => '/applications/cobweb/middleware/logging.middleware.php',

		// manager
		'CobwebManager' => '/manager/cobweb_manager.class.php',
		'CobwebManagerCommand' => '/manager/cobweb_manager_command.class.php',
		
		// forms
		'Form' => '/forms/form.class.php',
		'FormField' => '/forms/form_field.class.php',
		
		'TextField' => '/forms/fields/text_fields.inc.php',
		'EmailField' => '/forms/fields/text_fields.inc.php',
		'RegexField' => '/forms/fields/text_fields.inc.php',
		'DateField' => '/forms/fields/datetime_fields.inc.php',
		'NumericField' => '/forms/fields/numeric_fields.inc.php',
		'IntegerField' => '/forms/fields/numeric_fields.inc.php',
		'FloatField' => '/forms/fields/numeric_fields.inc.php',
		
		'FormWidget' => '/forms/form_widget.class.php',
		'InputWidget' => '/forms/widgets/input_widgets.inc.php',
		'TextInput' => '/forms/widgets/input_widgets.inc.php',
		
		// misc
		'Permalinkable' => '/core/permalinkable.interface.php',
		'CWDateTime' => '/utilities/datetime.inc.php',
		'JSON' => '/utilities/json.class.php',
		'MIMEType' => '/http/mime_type.class.php',
		
		'CobwebTestCase' => '/test/cobweb_test_case.class.php'
	);
	
	private static $external_classes = array();
	
	/**
	 * Cobweb's autoloader
	 * @param string $class name of class to load
	 * @return bool false if the class could be loaded, otherwise false
	 */
	public static function load($class) {
		
		if (isset(self::$cobweb_classes[$class])) {
			require COBWEB_DIRECTORY . self::$cobweb_classes[$class];
			return true;
		}
		
		if (isset(self::$external_classes[$class])) {
			require self::$external_classes[$class];
			return true;
		}
		
		return false;
	}
	
	/**
	 * Register a classname to be autoloaded from the specified path.
	 * @param string $class name of class to register
	 * @param  string $path path to the class in question
	 */
	public static function register($class, $path) {
		self::$external_classes[$class] = $path;
	}
	
	/**
	 * Register multiple classes with the autoloader. If the first argument is
	 * a string, it is used as a prefix for all the class paths (otherwise, it 
	 * is interpreted a mapping of classname => path pairs)
	 * 
	 * @param string|array $prefix path prefix or classmap
	 * @param array        $classmap classmap
	 */
	public static function autoload($prefix, $classmap = array()) {
		if (is_array($prefix)) {
			$classmap = $prefix;
		} else {
			foreach ($classmap as $class => $path)
				$classmap[$class] = $prefix . $path;
		}
		
		self::$external_classes = array_merge(self::$external_classes, $classmap);
	}
}