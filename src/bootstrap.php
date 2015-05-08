<?php

/*
* Bootstraps the environment
* Defines runtime constants, loads the config file and sets up the class autoloading
*
* Class autoloading heavily influenced by example given in PSR-4
*/

// Define useful runtime constants
define('MAIN_DIR', dirname(__DIR__));
define('CONF_DIR', MAIN_DIR.'/conf');
define('SRC_DIR', MAIN_DIR.'/src');
define('WEB_DIR', MAIN_DIR.'/web');
define('PROJECT_NAMESPACE', 'SamLex\\SWCProspect');

// Load the config file and make available as a superglobal
$config = [];

// Check config file exists
if (file_exists(CONF_DIR.'/config.json')) {
    // Load file
    $config_file_content = file_get_contents(CONF_DIR.'/config.json');

    // Decode JSON
    $decoded = json_decode($config_file_content, true);

    // Check that JSON was valid
    if (!is_null($decoded)) {
        $config = $decoded;
    }
}

// Define config superglobal
$GLOBALS['CONFIG'] = $config;

// Setup the class autoloading using an anonymous function
spl_autoload_register(function ($class_name) {
    $namespace_len = strlen(PROJECT_NAMESPACE);

    // Check if class name starts with project name space
    if (strncmp(PROJECT_NAMESPACE, $class_name, $namespace_len) !== 0) {
        // Class name does not belong to project namespace
        return;
    }

    // Convert class name to file path, prepending src dir and appending file extension
    $class_file = SRC_DIR.'/'.str_replace('\\', '/', $class_name).'.php';

    // Check that file actually exists
    if (file_exists($class_file)) {
        // File exists, load it
        require_once $class_file;
    }
});
