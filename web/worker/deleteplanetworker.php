<?php

/** The worker for the deletition of planets for SWCProspect. */

/** Bootstrap environment. */
require_once dirname(dirname(__DIR__)).'/src/bootstrap.php';

// Import nessesary classes
use SamLex\SWCProspect\Database\MySQLDatabaseInteractor as MySQL;
use SamLex\SWCProspect\Planet;

// Sanitize and validate GET parameters
$cleanPlanetID = filter_input(INPUT_GET, 'planetid', FILTER_SANITIZE_NUMBER_INT);
$validPlanetID = filter_var($cleanPlanetID, FILTER_VALIDATE_INT);

// Create DatabaseInteractor instance
$db = new MySQL($CONFIG['database_address'], $CONFIG['database_user'], $CONFIG['database_password'], $CONFIG['database_name']);

// Init the database
if ($db->init() === true) {
    // Check all passed data is of valid type
    if (is_int($validPlanetID) === true) {
        // Get needed class instance
        $planet = Planet::getPlanet($db, $validPlanetID);

        if (is_null($planet) === false) {
            // Delete planet from db
            $planet->delete();
        }
    }
}

// Redirect back to main page
header('HTTP/1.1 301 Moved Permanently');
header('Location: '.'../index.php');
exit();
