<?php

/*
* The worker for the deletition of planets for SWCProspect
*/

// Bootstrap environment
require_once dirname(__DIR__).'/src/bootstrap.php';

// Import nessesary classes
use SamLex\SWCProspect\Database\MySQLDatabaseInteractor as MySQL;

// Connect to database
$db = new MySQL($CONFIG['database_address'], $CONFIG['database_user'], $CONFIG['database_password'], $CONFIG['database_name']);

// Sanitize and validate GET parameters
$cleanPlanetID = filter_input(INPUT_GET, 'planetid', FILTER_SANITIZE_NUMBER_INT);
$validPlanetID = filter_var($cleanPlanetID, FILTER_VALIDATE_INT);

if ($validPlanetID) {
    $planet = $db->getPlanet($validPlanetID);

    if ($planet) {
        $planet->delete();
    }
}

// Redirect back to main page
header('HTTP/1.1 301 Moved Permanently');
header('Location: '.'index.php');
exit();
