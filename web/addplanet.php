<?php

/** The add planet page for SWCProspect. */

/** Bootstrap environment. */
require_once dirname(__DIR__).'/src/bootstrap.php';

// Import nessesary classes
use SamLex\SWCProspect\Page\AddPlanetPage;
use SamLex\SWCProspect\Page\ErrorPage;
use SamLex\SWCProspect\Database\MySQLDatabaseInteractor as MySQL;

// Create DatabaseInteractor instance
$db = new MySQL($CONFIG['database_address'], $CONFIG['database_user'], $CONFIG['database_password'], $CONFIG['database_name']);

// Init the database
if ($db->init() === true) {
    // Create AddPlanetPage instance if database available
    $page = new AddPlanetPage($db);
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
