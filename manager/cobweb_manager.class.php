<?php
/**
 * @version $Id$
 * @licence http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @copyright Upstruct Berlin Oslo
 */

/**
 * Implements a simple command line tool for the Cobweb framework.
 * 
 * @author     Øystein Riiser Gundersen <oystein@upstruct.com>
 * @package    Cobweb
 * @subpackage Management
 * @version    $Revision$
 */
class CobwebManager {
	
	/**
	 * Creates an instance of a manager with the specified arguments.
	 * 
	 * @param   array  $arguments the command line arguments (without argv[0])
	 */
	public function __construct(array $arguments) {		
		list ($this->command, 
			  $this->arguments,
			  $this->flags) = $this->parseArguments(array_slice($arguments, 1));
			
		if (is_null($this->command)) {
			if (in_array('--version', $this->flags)) {
				echo 'This is Cobweb ' . Cobweb::VERSION;
				exit;
			}
				
			$this->fail('Specify a command to invoke');
		}
		
		$this->command = $this->loadCommand($this->command);
	}
	
	/**
	 * Executes the manager's command.
	 */
	public function execute() {
		$this->command->execute($this->arguments, $this->flags);
	}
	
	/**
	 * Parses command line arguments into a command, optional flags and the 
	 * arguments to the command.
	 * 
	 * <code>
	 *	// returns array('command', array('arg1', 'arg2'), array('--h'))
	 *	$this->parseArguments(array('command', 'arg1, '--h' 'arg2')) 
	 * </code>
	 * 
	 * The method calls {@link CommandManager::fail()} if the tool was called
	 * with no arguments.
	 * 
	 * @param  array $raw_arguments the command line arguments
	 * @return array an array containing the command name, the arguments and
	 *               the flags in that order
	 */
	private function parseArguments(array $raw_arguments) {
		$flags = array();
		$arguments = array();
		foreach ($raw_arguments as $argument)
			if (str_starts_with($argument, '-') || str_starts_with($argument, '--'))
				$flags[] = $argument;
			else
				$arguments[] = $argument;
				
		$command = empty($arguments) ? NULL : $arguments[0];
		return array($command, array_slice($arguments, 1), $flags);
		
	}
	
	/**
	 * Loads and instantiates a command specified by its label.
	 * 
	 * The command is loaded by looking for a file corresponding to the 
	 * command label in the `/management/commands/' directories of the 
	 * installed applications or in the directory containing builtin commands.
	 * 
	 * The method calls {@link CommandManager::fail()} if the command 
	 * could not be loaded.
	 * 
	 * @param   string   $command   the label of the command (e.g. 'create-project')
	 * 
	 * @see     CobwebManager::pathify()
	 * @see     CobwebManager::classify()
	 * @return  CobwebManagerCommand the loaded command
	 */
	protected function loadCommand($command) {
		
		$classname = self::classify($command);
		$builtin_path = COBWEB_DIRECTORY . '/manager/builtins/' . self::pathify($command);
		
		if ($command == 'shell')
			define('COBWEB_PROJECT_DIRECTORY', getcwd());
			
		if (!defined('COBWEB_WWW_ROOT'))
			define('COBWEB_WWW_ROOT', getcwd());
		
		if (file_exists($builtin_path)) {
			Cobweb::initialize();
			require_once $builtin_path;
			
			if (class_exists($classname))
				return new $classname($this, $this->command, $this->arguments, $this->flags);
		}
		
		
		define('COBWEB_PROJECT_DIRECTORY', getcwd());
		Cobweb::initialize();
		
		foreach (Cobweb::get('INSTALLED_APPLICATIONS', array()) as $application) {
			$application_path = Cobweb::loadApplication($application);
			$path = "{$application_path->path()}/management/commands/" . self::pathify($command);
			
			if (file_exists($path)) {
				Cobweb::loadApplication($application);
				require_once $path;
			}
				
			if (class_exists($classname))
				return new $classname($this, $this->command, $this->arguments, $this->flags);
		}
			
		$this->fail("Unknown command '{$command}'");
	}
	
	/**
	 * Exits the manager script with the specified message.
	 * 
	 * @param  string   $message  message to display
	 */
	public function fail($message) {
		die('cobweb: ' . $message . "\n");	
	}
	
	/**
	 * Print usage information for the command line tool.
	 */
	public function usage() {
		return "usage: cobweb [flags] [command] [arguments]\n";
	}
	
	
	/**
	 * Translates a command label to the name of a file containing a command class
	 * implementation.
	 * 
	 * <code>
	 * 	CobwebManager::pathify('create-project') // => create_project.command.php
	 * </code>
	 * 
	 * @param  string $command  the command to pathify
	 * @return string           the filename of the command
	 */
	public static function pathify($command) {
		$path = str_replace('-', '_', $command);
		return $path . '.command.php';
	}
	
	/**
	 * Translates a command label to the name of its class.
	 * <code>
	 * 	CobwebManager::classify('create-project') // => CreateProjectCommand
	 * </code>
	 * 
	 * @param  string $command  the command to pathify
	 * @return string           the filename of the command
	 */
	public static function classify($command) {
		return str_classify($command) . 'Command';
	}
	
	/** 
     * Outputs a debug message.
     * 
     * @param string $message the message to output
     */
	public function debug($message) {
		if ($this->debug)
			echo "cobweb: DEBUG {$message}\n";
	}
	
	/** 
     * Outputs an informational message.
     * 
     * @param string $message the message to output
     */
	public function info($message) {
		echo "{$message}\n";
	}
	
	public function prompt($prompt) {
		echo "{$prompt}: ";
		return trim(fgets(STDIN));
	}
	
	/**
	 * Run the manager tool with the specified arguments.
	 * 
	 * @param array  $arguments  the arguments (without *argv[0]) to use
	 */
	public static function run(array $arguments) {
		$manager = new CobwebManager($arguments);
		$manager->execute();
	}
}