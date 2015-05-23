<?php

/** The view planet page for SWCProspect. */

/** Bootstrap environment. */
require_once dirname(__DIR__).'/src/bootstrap.php';

// Import nessesary classes
use SamLex\SWCProspect\Page\ViewPlanetPage;
use SamLex\SWCProspect\Page\ErrorPage;
use SamLex\SWCProspect\Database\MySQLDatabaseInteractor as MySQL;

// Sanitize and validate GET parameters
$cleanPlanetID = filter_input(INPUT_GET, 'planetid', FILTER_SANITIZE_NUMBER_INT);
$validPlanetID = filter_var($cleanPlanetID, FILTER_VALIDATE_INT);

if (is_int($validPlanetID) === true) {
    $planetID = $validPlanetID;
} else {
    $planetID = -1;
}

// Create DatabaseInteractor instance
$db = new MySQL($CONFIG['database_address'], $CONFIG['database_user'], $CONFIG['database_password'], $CONFIG['database_name']);

// Init the database
if ($db->init() === true) {
    // Create ViewPlanetPage instance if database available
    $page = new ViewPlanetPage($db, $planetID);
} else {
    // Create ErrorPage instance if database not available
    $page = new ErrorPage();
}

// Init the page
if ($page->init() === false) {
    // Replace page with error page if it can't be init'ed
    $page = new ErrorPage();
}

// Output page
$page->outputPage();
