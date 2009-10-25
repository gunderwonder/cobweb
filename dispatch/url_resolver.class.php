<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

/**
 * @author     Øystein Riiser Gundersen <oystein@upstruct.com>
 * @package    Cobweb
 * @subpackage Dispatch
 * @version    $Rev$
 */
class URLResolver implements Resolver {
	
	const PATTERN_LEFT_SENTINEL = '{';
	const PATTERN_RIGHT_SENTINEL = '}';
	
	private $rules = NULL;
	protected $attempted_patterns;
	protected $matching_pattern;
	
	/**
	 * Instantiates a URL resolver.
	 * 
	 * @param Dispatcher $dispatcher
	 * @param array      $rule
     * @param array      $include_options
     * @param array      $include_matches
     * @param array      $prefix_pattern
	 */
	public function __construct(Dispatcher $dispatcher,
		                        array $rules, 
		                        array $include_options = array(), 
		                        array $include_matches = array(),
		                        $prefix_pattern = '',
		                        Resolver $parent_resolver = NULL) { 
		
		$this->dispatcher      = $dispatcher;	
		$this->include_matches = $include_matches;
		$this->include_options = $include_options;
		$this->prefix_pattern  = $prefix_pattern; 
		$this->rules  = $rules; 
		$this->reverse_map = NULL;
		$this->parent_resolver = $parent_resolver;

	}
	
	/**
	 * Resolves the {@link Action} by URL
	 * 
	 * @see    URLResolver::resolve()
	 * @param  Request $request        request to resolve
	 * @param  string  $url            URL string to resolve
	 * @return Action                  resolved action
	 */
	protected function resolveURL(Request $request, $url) {
		
		foreach ($this->rules as $pattern => $action) {
			
			$this->attempted_patterns[] = $pattern;
			
			if (!is_string($pattern))
				throw new CobwebConfigurationException(
					'Invalid URL dispatch rule. ' .
					'Expected a pattern string, got ' . gettype($this->rules)
				);

			if (($matches = $this->match($pattern, $url)) !== false) {
				$this->matching_pattern = $pattern;
				Cobweb::info("Request URI %o resolved to action %o using pattern %o",  
					$request->URI(), $action, $pattern);
				
				if (is_callable($action)) {
					$action = is_array($action) ? $action : array($action);
					return CallableAction::create(
						$request, $this->dispatcher, $this, $pattern, $matches, $action
					);
				} else if ($action instanceof IncludeURLConfigurationAction) {
					return $this->resolveInclude($request, $action, $pattern, $matches, $url);
				} else if ($action instanceof Action) {
					return $action;
				} else if ($action instanceof ActionFactory) {
					return $action->createAction($request, $this->dispatcher, $this, $matches, array(
						'pattern' => $pattern,
					));
				}
			}
		}
		
 		return new ControllerAction(
						$request, 
						$this->dispatcher,
						$this,
						empty($pattern) ? '' : $pattern,
						array(),
						array('cobweb.cobweb.not_found_404'));
	}
	
	protected function query(Request $request) {
		return ltrim($request->path(), '/');
	}
	
	/**
	 * Resolves the {@link Action} of a request
	 * 
	 * @param  Request request to be resolved
	 * @return Action  resolved action
	 */
	public function resolve(Request $request) {

		return $this->resolveURL($request, $this->query($request));
	}
	
	
	/**
	 * Resolve an included URL configuration
	 * 
	 * @param  Request                       $request
	 * @param  IncludeURLConfigurationAction $action
	 * @param  string                        $pattern
	 * @param  array                         $matches
	 * @param  string                        $url
	 * 
	 * @return Action                        resolved action 
	 */
	private function resolveInclude(Request $request, 
		                            IncludeURLConfigurationAction $action, 
		                            $pattern, $matches, $url) {
		
		
		$include_resolver = new URLResolver(
			$this->dispatcher,
			$action->rules(),
			$action->options(),
			array_merge($this->include_matches, $matches),
			$pattern,
			$this
		);
		
		return $include_resolver->resolveURL($request, ltrim($this->trimPattern($pattern, $url), '/'));
	}
	
	/**
	 * Returns false if the specified pattern doesn't match the subject, 
	 * otherwise an array, empty or populated of matching subpatterns.
	 * 
	 * @param  string $pattern
	 * @param  string $subject
	 * @return mixed           an array of matches or false 
	 */
	private function match($pattern, $subject) {
		if (empty($pattern))
			return false;
			
		$matches = array();
		if (preg_match($this->patternize($pattern), $subject, $matches) === 0)
			return false;
			
		return $this->sanitizeMatches($matches);
	}
	
	/**
	 * Cleans up an array of matches returned by {@link preg_match()}, stripping
	 * away the matched string and duplicate submatches.
	 * 
	 * @param  array $matches
	 * @param  array $chop
	 * @return array          sanitized array of matches
	 */
	private function sanitizeMatches(array $matches, $chop = false) {
		if (count($matches) >= 1 && !$chop)
			$matches = array_splice($matches, 1);
		
		$unset_next = false;
		foreach ($matches as $key => $value) {
			if (is_string($key))
				$unset_next = true;
			else if (is_int($key) && $unset_next) {
				unset($matches[$key]);
				$unset_next = false;
			}
		}
		return array_merge($matches);
	}
	
	/**
	 * Trims the leading part an url that matches the specified pattern
	 */
	private function trimPattern($pattern, $url) {
		
		$split = preg_split($this->patternize($pattern), 
		                    $url, -1, PREG_SPLIT_NO_EMPTY);
		
		return end($split);
	}
	
	/**
	 * Concatencates two regular expression patterns used when including
	 * router rules recursively.
	 * 
	 * @param  string $prefix_pattern prefix
	 * @param  string $pattern        pattern to be prefixed
	 * 
	 * @return tring  the concatencated pattern
	 */
	private function concatencatePatterns($prefix_pattern, $pattern) {
		if ($prefix_pattern == '')
			return $pattern;
		
		return $prefix_pattern . ltrim($pattern, '^');
	}
	
	/**
	 * Processes a regular expression pattern to use with PCRE functions.
	 * 
	 * Cobweb's practice is to use only the 'u' modifier to treat patterns as
	 * UTF-8 strings and otherwise leave things to the default.
	 * 
	 * @param  string $pattern the pattern to process
	 * @return string the processed pattern
	 */
	private function patternize($pattern) {
		return self::PATTERN_LEFT_SENTINEL . $pattern . 
		       self::PATTERN_RIGHT_SENTINEL . 'u';
	}
	
	// REVERSE RESOLVE
	
	/**
	 * Attempts to resolve a regular expression pattern with respect to the 
	 * specified arguments.
	 * 
	 * <code>
	 * // resolves to 'blog/2000/politics'
	 * $resolver->reverseResolve('^blog/(\d{4})/(?<category>\w+)', 
	 *                           array('2000', 'category' => 'politics'));
	 * </code>
	 * 
	 * @param string $pattern   pattern to resolve
	 * @param array  $arguments arguments for which to use when resolving the pattern
	 * 
	 * @param string the resolved string
	 */
	public function reverseResolve($pattern, array $arguments) {
		
		// temporary variables used in reverseResolveCallback() helper
		$this->reverse_arguments = $arguments;
		$this->current_argument  = 0;
		$this->reverse_resolve_pattern = $pattern;
		
		try {
			$resolved = preg_replace_callback(
				$this->patternize('\(([^)]+)\)'),
				array($this, 'reverseResolveCallback'),
				$pattern
			);
			
		} catch (CobwebException $e) {
			unset($this->reverse_arguments, 
				  $this->current_argument, 
				  $this->reverse_resolve_pattern);
			throw $e;
		}
		
		unset($this->reverse_arguments, 
			  $this->current_argument, 
			  $this->reverse_resolve_pattern);
		
		$resolved = str_replace('^', '', $resolved);
		$resolved = str_replace('$', '', $resolved);
		$resolved = str_replace('?', '', $resolved);
		
		if ($this->prefix_pattern)
			$resolved = str_replace('^', '', $this->prefix_pattern) . '/' . $resolved;
		
		return $resolved;
	}
	
	/**
	 * Callback for {@link preg_preplace_callback()} in {@link URLResolver::reverseResolve}
	 * 
	 * Keys passed in as arguments will have their value replace named 
	 * subpatterns. Positional arguments will replace unnamed subpatterns
	 * according to the value of {@link URLResolver::$current_argument}.
	 * 
	 * @throws CobwebResolverException
	 * 
	 * @param  array  $matches matching subpatterns to replace, as 
	 * @return string the replacement string
	 */
	private function reverseResolveCallback($matches) {
		$pattern = $matches[1]; // pattern to reverse
		$value = NULL;          // the replacement value 
		$test_regex = NULL;
		
		// match named/positional subpatterns
		$pattern_matches = array();
		preg_match($this->patternize('^\?P?<(\w+)>(.*?)$'), $pattern, $pattern_matches);
		
		/* named subpattern:
		 * $pattern_matches[1] is the group, 
		 * $pattern_matches[2] is the regex itself)
		 */
		if (isset($pattern_matches[1]))
			if (isset($this->reverse_arguments[$pattern_matches[1]])) {
				$value = $this->reverse_arguments[$pattern_matches[1]];
				$test_regex = $pattern_matches[2];
			} else
				throw new CobwebException(
					"Could not reverse resolve '{$this->reverse_resolve_pattern}' " .
					"Named group '{$pattern_matches[1]}' is undefined"
				);
				
		// positional subpattern ($pattern is the regex itself)
		else
			if (isset($this->reverse_arguments[$this->current_argument])) {
				$value = $this->reverse_arguments[$this->current_argument];
				$this->current_argument++; // move to the next pattern position
				$test_regex = $pattern;
			} else
				throw new CobwebException(
					"Could not reverse resolve '{$this->reverse_resolve_pattern}' " .
					"Positional argument number {$this->current_argument} is undefined"
				);
		
		// test if the regex matches the found value
		if (preg_match($this->patternize("^{$test_regex}$"), $value) === 0)
			throw new CobwebException(
				"Could not reverse resolve '{$this->reverse_resolve_pattern}' " .
				"The value '$value' did not match the pattern '$test_regex'"
			);
		
		
		return $value;
	}
	
	public function reverseMap() {
		
		if (!is_null($this->reverse_map))
			return $this->reverse_map;
		
		$this->reverse_map = array();
		$name = NULL;
		foreach ($this->rules as $pattern => $action) {
			
			if (is_string($action))
				$name = $action;
			else if (is_array($action)) {
				$last = end($action);
				if (is_array($last) && isset($last['name']))
					$name = $last['name'];
				else if (isset($action[0]) && is_string($action[0]))
					$name = $action[0];
			
			} else if ($action instanceof IncludeURLConfigurationAction) {
				
				// TODO: get resolver from action
				$resolver = new URLResolver($this->dispatcher,
		                        $action->rules(), // load URL patterns
		                        $this->include_matches,          
		                        $this->include_options,
		                        $pattern,
		                        $this);
				
				$reverse = $resolver->reverseMap();
				foreach ($reverse as $a => $p)
					$this->reverse_map[$a] = $this->concatencatePatterns($pattern . '/', $p);
			} else if (is_object($action)) 
				;
			
			if (!is_null($name))
				$this->reverse_map[$name] = $pattern;
			$name = NULL;
		}
		
		// if (!is_null($this->parent_resolver))
		// 	return $this->parent_resolver->reverseMap();
		return $this->reverse_map;
		
	}
	
	public function reverse($name, array $arguments = array()) {
		
		$reverse_map = $this->reverseMap();
		
		// if (!is_null($this->parent_resolver))
		// 	return $this->parent_resolver->reverse($name, $arguments);

		if (!isset($reverse_map[$name]))
			throw new CobwebException("No URL mapped to action {$name}");
			
		return '/' . Cobweb::get('URL_PREFIX', '') . $this->reverseResolve($reverse_map[$name], $arguments);
	}
	
	public function attemptedPatterns() {
		return $this->attempted_patterns;
	}
	
	public function matchingPattern() {
		return $this->matching_pattern;
	}
	
}