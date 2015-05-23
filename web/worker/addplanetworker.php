<?php

/** The worker for the add planet page for SWCProspect. */

/** Bootstrap environment. */
require_once dirname(dirname(__DIR__)).'/src/bootstrap.php';

// Import nessesary classes
use SamLex\SWCProspect\Database\MySQLDatabaseInteractor as MySQL;
use SamLex\SWCProspect\Planet;
use SamLex\SWCProspect\PlanetType;

// Sanitize and validate POST parameters
$validName = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

$cleanSize = filter_input(INPUT_POST, 'size', FILTER_SANITIZE_NUMBER_INT);
$validSize = filter_var($cleanSize, FILTER_VALIDATE_INT);

$cleanType = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_NUMBER_INT);
$validType = filter_var($cleanType, FILTER_VALIDATE_INT);

// Create DatabaseInteractor instance
$db = new MySQL($CONFIG['database_address'], $CONFIG['database_user'], $CONFIG['database_password'], $CONFIG['database_name']);

// Init the database
if ($db->init() === true) {
    // Check all passed data is of valid type
    if (is_string($validName) && is_int($validSize) && is_int($validType)) {
        // Get needed class instance
        $type = PlanetType::getType($db, $validType);

        if (is_null($type) === false) {
            // Create new planet instance and save to db
            $planet = new Planet($validName, $validSize, $type, $db);
            $planet->save();
        }
    }
}

// Redirect back to main page
header('HTTP/1.1 301 Moved Permanently');
header('Location: '.'../index.php');
exit();
