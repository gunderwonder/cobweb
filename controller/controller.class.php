<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

/**
 * Base class for Cobweb controller implementations.
 * 
 * A controller consists of actions that are invoked upon request. Actions are
 * simply public methods of the controller class. An action must comply with
 * simple contract; they must return a {@link Response} object containg a response
 * body (if the request type requires it). If the signature of an action includes
 * parameters, Cobweb will invoke it using arguments from submatches in the
 * URL pattern or (if provided) from the action invokation specification in the
 * URL configuration. Overriding the constructor if a controller is not allowed,
 * but subclasses may override the {@link ControllerAction::initialize()} method
 * that will be called when a controller is instantiated.
 * 
 * In the Cobweb request-response cycle controller action are represented
 * with a metaclass {@link ControllerAction}. Refer to its documentation for
 * more information.
 * 
 * A controller's job is move data from the model layer to the view layer (i.e.
 * the template) and return the rendered view as a response. Action methods may
 * dispatch a request by either,redirecting to another URL using an 
 * {@link HTTPRedirectResponse} directly, invoking another controller action 
 * with {@link Controller::invoke()} or redirecting the client to another
 * controller action's URL with {@link Controller::redirect()}.
 * 
 * Controller actions are named with labels in the URL configuration and other
 * places using the pattern
 *  
 * 	<application_name>.<controller_name>.<action_name>.
 * 
 * Here is an example of a controller action that renders the string 'Hello, world'
 * to the user agent. If this action belonged to an application 'hello' it would
 * have been labeled `hello.hello_world.greet`.
 * 
 * 	class HelloWorldController extends Controller {
 * 	    public function greet() {
 * 	         return new HTTPResponse('Hello, world!');
 * 	    }
 * 	}
 * 
 * {@link ReflectionClass::getConstants()}
 * 
 * @package    Cobweb
 * @subpackage Dispatch
 * @since      0.1
 * @author     Øystein Riiser Gundersen <oystein@upstruct.com>
 * @version    $Revision$
 * 
 * @see        Response
 * @see        ControllerAction
 */
abstract class Controller implements RequestProcessor {
	
	/**
	 * The request object of this controller instance
	 * @var Request
	 **/
	protected $request;
		
	/**
	 * The POST parameters of the request
	 * @var HTTPQueryDictionary 
	 **/
	protected $POST;
		
	/**
	 * The GET parameters of the request
	 * @var HTTPQueryDictionary
	 **/
	protected $GET;
	
	/**
	 * The {@link Action} instance which represents the invocation of this controller
	 * @var Action
	 **/
	private $action;
	
	/**
	 * Instantiates a Controller
	 * 
	 * @param Dispatcher $dispatcher  the request dispatcher
	 * @param Request    $request     the request object
	 */
	public function __construct(
			Dispatcher $dispatcher, 
			Request $request, 
			Resolver $resolver, 
			Action $action = NULL) {
				
		$this->request = $request;
		$this->dispatcher = $dispatcher;
		$this->action = $action;
		
		// proxy request parameters
		$this->POST = $request->POST;
		$this->GET = $request->GET;
		$this->resolver = $resolver;
		
		$this->initialize();
	}
	
	protected function action() {
		return $this->action;
	}
	
	/**
	 * {@link Controller} subclasses may override this method for object
	 * initialization code. It is called after the controller is instantiated.
	 */
	protected function initialize() { }
	
	/**
	 * Invoke a Controller's method specified by its label
	 * 
	 * @param string $label      the controller action's label
	 * @param array  $arguments  the arguments to apply to the action
	 * 
	 * @return Response          the controller action's response
	 */
	public static function invoke($label, array $arguments = array()) {
		return ControllerAction::invokeControllerAction($label, $arguments);
	}
	
	/**
	 * Returns an {@link HTTPResponseRedirect} response that links to the 
	 * specified controller action.
	 * 
	 * @param string $label     the controller action's label
	 * @param string $arguments the arguments to apply to the action
	 * @Concealed
	 */
	protected function redirect($label, array $arguments = array()) {
		if (str_starts_with($label, '@'))
			$url = Cobweb::instance()->resolver()->reverse(substr($label, 1), $arguments);
		else
			$url = $label;
		return new HTTPResponseRedirect($url);
	}
	
	
	/**
	 * Returns an {@link Response} containing a rendered template with the
	 * specified bindings, response code and MIME type header.
	 * 
	 * @param  string  $template_name the relative path of the template to render
	 * @param  array   $bindings      an array of bindings to apply to the template
	 * @param  int     $code          the HTTP response code of the response
	 * @param  string  $mime_type     the content type HTTP header of the response
	 * 
	 * @return Response               a {@link Response} object containing the 
	 *                                rendered template with the specified
	 *                                status code and MIME type
	 */
	public function render(
			$template_name,
			$bindings = array(),
			$code = HTTPResponse::OK,
			$mime_type = MIMEType::HTML,
			$template_adapter = NULL,
			$loading = Template::RELATIVE_TEMPLATE_PATH) {
				
		$template = new Template($template_adapter);
		$template->bind($bindings);
		$template->render($template_name, $loading);
		return $this->respond($template, $code, $mime_type);
	}
	
	protected function respond($response_text, $code = HTTPResponse::OK, $mime_type = MIMEType::HTML) {
		return new HTTPResponse($response_text, $code, $mime_type);
	}
	
	/**
	 * Proxy for the request object's {@link Request::method()} method. Returns
	 * one of `HEAD`, `GET`, `POST`, `DELETE` or `UPDATE`
	 * 
	 * @see    Request::method()
	 * @return string the HTTP method of the request
	 */
	protected function method() {
		return $this->request->method();
	}
	
	/**
	 * Proxy for the request object's {@link Request::isPOST()} method. Returns
	 * true if the method of the request is a `POST`, false otherwise.
	 * 
	 * @see    Request::isPOST()
	 * @return bool whether or not the request was made using the 'POST' method
	 */
	protected function isPOST() {
		return $this->request->isPOST();
	}
	
	/**
	 * Proxy for the request object's {@link Request::isGET()} method. Returns
	 * true if the method of the request is a `GET`, false otherwise.
	 * 
	 * @see    Request::isGET()
	 * @return bool whether or not the request was made using the 'GET' method
	 */
	protected function isGET() {
		return $this->request->isGET();
	}
	
	/**
	 * Proxy for the request object's {@link Request::isDELETE()} method. Returns
	 * true if the method of the request is a `DELETE`, false otherwise.
	 * 
	 * @see    Request::isDELETE()
	 * @return bool whether or not the request was made using the 'DELETE' method
	 */
	protected function isDELETE() {
		return $this->request->isDELETE();
	}
	
	/**
	 * Proxy for the request object's {@link Request::isPUT()} method. Returns
	 * true if the method of the request is a `PUT`, false otherwise.
	 * 
	 * @see    Request::isPUT()
	 * @return bool whether or not the request was made using the 'PUT' method
	 */
	protected function isPUT() {
		return $this->request->isPUT();
	}
	
	/**
	 * @param mixed $value
	 * @return mixed the value passed to this function
	 * @throws HTTP404 if the specified value evaluates to false
	 */
	protected function ensure($value) {
		if (!$value) throw new HTTP404();
		return $value;
	}
	
	// REQUEST PROCESSOR IMPLEMENTATION
	/** 
	 * @return HTTPResponse
	 * @Concealed
	 **/
	public function processRequest(Request $request) {
		return NULL;
	}
	
	/** @Concealed */
	public function processResponse(Request $request, Response $response) {
		return $response;
	}
	
	/** @Concealed */
	public function processAction(Request $request, Action $action) {
		return NULL;
	}
	
	/** @Concealed */
	public function processException(Request $request, Exception $exception) {
		return NULL;
	}
	
}