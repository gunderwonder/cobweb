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
		'Configurable' => '/settings/configurable.interface.php',
		'CobwebConfiguration' => '/settings/cobweb_settings.class.php',
		'Dispatcher' => '/dispatch/dispatcher.class.php',
		'Request' => '/http/request.class.php',
		'Response' => '/http/response.class.php',
		'HTTPRequest' => '/http/http_request.class.php',
		'HTTPResponse' => '/http/http_response.class.php',
		'ImmutableArray' => '/utilities/immutable_array.class.php',
		'MutableArray' => '/utilities/mutable_array.class.php',
		'Resolver' => '/dispatch/resolver.class.php',
		'URLResolver' => '/dispatch/url_resolver.class.php',
		'HTTPQueryDictionary' => '/http/http_query_dictionary.class.php',
		'MiddlewareManager' => '/middleware/middleware_manager.class.php',
		'Action' => '/controller/action.class.php',
		'Controller' => '/controller/controller.class.php',
		'CallableAction' => '/action/callable_action.class.php',
		'ControllerAction' => '/action/controller_action.class.php',
		'Action' => '/action/action.interface.php',
		'Annotation' => '/vendor/addendum/annotations.php',
		'ReflectionAnnotatedClass' => '/vendor/addendum/annotations.php',
		'ReflectionAnnotatedFunction' => '/vendor/addendum/annotations.php',
		'ApplicationManager' => '/application/application_manager.class.php',
		'Application' => '/application/application.class.php',
		'Middleware' => '/middleware/middleware.class.php',
		'RequestProcessor' => '/middleware/request_processor.interface.php',
		'Router' => '/dispatch/router.class.php',
		'IncludeURLConfigurationAction' => '/action/include_url_configuration_action.class.php',
		'Template' => '/templating/template.class.php',
		'TemplateAdapter' => '/templating/template_adapter.class.php',
		'SmartyTemplateAdapter' => '/templating/smarty/smarty_template_adapter.class.php',
		'Logger' => '/utilities/logging/logger.class.php',
		'LogFormatter' => '/utilities/logging/log_formatter.class.php',
		'FirebugLogFormatter' => '/utilities/logging/firebug_log_formatter.class.php',
		'JSON' => '/utilities/json.class.php',
		'MIMEType' => '/http/mime_type.class.php',
		'CobwebEvent' => '/dispatch/cobweb_event.class.php',
		'CobwebManager' => '/manager/cobweb_manager.class.php',
		'CobwebManagerCommand' => '/manager/cobweb_manager_command.class.php',
		'HTTPResponseRedirect' => '/http/http_response.class.php',
		'HTTPResponseNotModified' => '/http/http_response.class.php',
		'HTTPResponsePermanentRedirect' => '/http/http_response.class.php',
		'HTTPResponseMethodNotAllowed' => '/http/http_response.class.php',
		'Permalinkable' => '/core/permalinkable.interface.php',
		'Form' => '/forms/form.class.php',
		'FormField' => '/forms/form_field.class.php',
		'FormWidget' => '/forms/form_widget.class.php',
		'FormException' => '/forms/form_exceptions.inc.php',
		'FormValidationException' => '/forms/form_exceptions.inc.php',
		'IntegerField' => '/forms/fields/integer_field.class.php',
		'TextField' => '/forms/fields/text_field.class.php',
		'TextInput' => '/forms/widgets/text_input.class.php',
		'EmailField' => '/forms/fields/email_field.class.php',
		'PositiveIntegerField' => '/forms/fields/positive_integer_field.class.php',
		'ConcreteForm' => '/forms/concrete_form.class.php',
		'Console' => '/utilities/logging/console.class.php',
		'AJAXResponse' => '/http/ajax_response.class.php',
		'Time' => '/utilities/date.class.php',
	);
	
	private static $external_classes = array();
	
	public static function load($class) {
		
		if (isset(self::$cobweb_classes[$class])) {
			require_once COBWEB_DIRECTORY . self::$cobweb_classes[$class];
			return class_exists($class);
		}
		
		if (isset(self::$external_classes[$class])) {
			require_once self::$external_classes[$class];
			return class_exists($class);
		}
		
		return false;
	}
	
	
	public static function register($class, $path) {
		
		self::$external_classes[$class] = $path;
		
	}
	
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