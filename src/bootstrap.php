<?php

/**
 * Bootstraps the environment.
 *
 * Sets up the environment in several ways:
 * - Enables error reporting
 * - Defines useful runtime constants, mainly file paths
 * - Loads, and makes available, the config file
 * - Sets up class autoloading
 *
 * Class autoloading heavily influenced by example given in PSR-4
 */

// Enable reporting of all errors (Testing purposes only)
error_reporting(-1);
ini_set('display_errors', 'On');

// Define useful runtime constants

/* Main directory, containing all needed files and directories. */
define('MAIN_DIR', dirname(__DIR__));

/* Config directory, containing config.json file. */
define('CONF_DIR', MAIN_DIR.'/conf');

/* Source directory, containing all class files and this file. */
define('SRC_DIR', MAIN_DIR.'/src');

/* Web directory, containing all publicly visible files. */
define('WEB_DIR', MAIN_DIR.'/web');

/* Project class namespace. */
define('PROJECT_NAMESPACE', 'SamLex\\SWCProspect');

// Load the config file and make available as a superglobal
$config = [];

// Check config file exists
if (file_exists(CONF_DIR.'/config.json') === true) {
    // Load file
    $config_file_content = file_get_contents(CONF_DIR.'/config.json');

    // Decode JSON
    $decoded = json_decode($config_file_content, true);

    // Check that JSON was valid
    if (is_null($decoded) === false) {
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
    if (file_exists($class_file) === true) {
        // File exists, load it
        require_once $class_file;
    }
});
