<?php

/*
* The view planet page for SWCProspect
*/

// Bootstrap environment
require_once dirname(__DIR__).'/src/bootstrap.php';

// Import nessesary classes
use SamLex\SWCProspect\Page\ViewPlanetPage;
use SamLex\SWCProspect\Database\MySQLDatabaseInteractor as MySQL;

// Connect to database
$db = new MySQL($CONFIG['database_address'], $CONFIG['database_user'], $CONFIG['database_password'], $CONFIG['database_name']);

// Sanitize and validate GET paramters
$cleanPlanetID = filter_input(INPUT_GET, 'planetid', FILTER_SANITIZE_NUMBER_INT);
$validPlanetID = filter_var($cleanPlanetID, FILTER_VALIDATE_INT);

if ($validPlanetID) {
    $planetID = $validPlanetID;
} else {
    $planetID = 1;
}

// Create and output page
$page = new ViewPlanetPage($db, $planetID);
$page->outputPage('View Planet');
