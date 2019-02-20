<?php
	define('DIR_SEP', '/');
	define('EXPAND_DIR', __DIR__);
	define('APP_PATH', __DIR__);

	include(EXPAND_DIR . DIR_SEP. 'config' . DIR_SEP. 'config.php');
	include(EXPAND_DIR . DIR_SEP. 'helper' . DIR_SEP. 'constants.php');
	include(EXPAND_DIR . DIR_SEP. 'helper' . DIR_SEP. 'util_helper.php');
	include(APP_PATH . DIR_SEP . 'controller' .DIR_SEP. 'BaseController.php');
	include(APP_PATH . DIR_SEP . 'model' . DIR_SEP . 'BaseModel.php');
	include(APP_PATH . DIR_SEP . 'template' . DIR_SEP . 'PlotViewTemplate.php');
	include(APP_PATH . DIR_SEP . 'vendors' . DIR_SEP . 'Facebook' . DIR_SEP . 'autoload.php');
	include(APP_PATH . DIR_SEP . 'vendors' . DIR_SEP . 'Twitter' . DIR_SEP . 'autoload.php');
	include(APP_PATH . DIR_SEP . 'vendors' . DIR_SEP . 'payment' . DIR_SEP . 'autoload.php');

	$uri = current(explode('?', $_SERVER['REQUEST_URI']));
	$uri = explode('/', trim($uri, '/'));

	$ctr_name = array_shift($uri);

	if(!$ctr_name)
		$ctr_name = 'auth';
	$controller = _loadClass($ctr_name, 'controller' );

	if (!is_object($controller) || !($controller instanceof BaseController)) {
	    die('Missing Controller for '.$ctr_name);
	}

	if(empty($uri))
		array_unshift($uri, 'index');

	$method = array_shift($uri);

	if (!method_exists($controller, $method)) {
		 die('Missing Method '. $method);
	}

	session_start();

	if (method_exists($controller, 'beforeLoad')) {
		$controller->beforeLoad();
	}

	set_error_handler('__error_handler');

	$controller->__dispatch($method, $uri);

	function __error_handler($errno, $errstr, $errfile, $errline, $errcontext) {
		switch($errno) {
			case E_WARNING      :
			case E_USER_WARNING :
			case E_STRICT       :
			case E_NOTICE       :
			case E_USER_NOTICE  :
				$type = 'WARNING';
				$fatal = false;
				break;
			default             :
				$type = 'ERROR';
				$fatal = true;
				break;
		}
		
		$trace = array_reverse(debug_backtrace());
		
		error_log($errstr." in $errfile at line $errline");
		
		$line = 1;
		foreach($trace as $item) {
			if($line > 10)
				break;
			$msg = $line . "  in file " . (isset($item['file']) ? $item['file'] : '<unknown file>') .
				' at line ' . (isset($item['line']) ? $item['line'] : '<unknown line>') . 
				' in method ' . $item['function'] . '()';
			error_log($msg);
			$line++;
		}
	}
	function _loadClass($file_name, $type, $directory = false) {
		if(!$directory)
			$directory = $type;
		
		$class = camelize($file_name) . camelize($type);
		$class_obj = false;
		
		if (class_exists($class))
			$class_obj = new $class();
		
		if ($class_obj && is_object($class_obj)) {
			return $class_obj;
		} else {
			$file_path = APP_PATH . DIR_SEP. $directory . DIR_SEP . $file_name . '.php';
			if (file_exists($file_path)) {
				include_once($file_path);
				if (class_exists($class, false)) {
					return new $class();
				}
			}
		}
		return false;
	}

	function _loadHelper($helper_name) {
		$filename = APP_PATH . DIR_SEP . 'helper' . DIR_SEP. trim(strtolower($helper_name)) . '_helper.php';

		if (file_exists($filename)) {
			include_once($filename);		
		}

		return false;
	}

	function camelize($string) {
		return str_replace(' ', '', ucwords(str_replace(array('_', '-'), ' ', s($string))));
	}

	function s($data = '') {
		switch (gettype($data)) {
			case 'string':
				return $data;
			case 'boolean':
				return $data ? 'true' : 'false';
			case 'integer':
				return strval($data);
			case 'double':
				$data = strval($data);
				return strstr($data, '.') ? $data : "{$data}.0";
			case 'array':
				return var_export($data, true);
			case 'object':
				return (method_exists($data, '__toString'))
					? strval($data)
					: get_class($data);
			case 'resource':
				return get_resource_type($data);
			default:
				return gettype($data);
		}
	}
?>