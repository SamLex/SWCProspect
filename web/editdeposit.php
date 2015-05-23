<?php

/** The edit deposit page for SWCProspect. */

/** Bootstrap environment. */
require_once dirname(__DIR__).'/src/bootstrap.php';

// Import nessesary classes
use SamLex\SWCProspect\Page\EditDepositPage;
use SamLex\SWCProspect\Page\ErrorPage;
use SamLex\SWCProspect\Database\MySQLDatabaseInteractor as MySQL;

// Sanitize and validate GET parameters
$cleanDepositID = filter_input(INPUT_GET, 'depositid', FILTER_SANITIZE_NUMBER_INT);
$validDepositID = filter_var($cleanDepositID, FILTER_VALIDATE_INT);

if (is_int($validDepositID) === true) {
    $depositID = $validDepositID;
} else {
    $depositID = -1;
}

// Create DatabaseInteractor instance
$db = new MySQL($CONFIG['database_address'], $CONFIG['database_user'], $CONFIG['database_password'], $CONFIG['database_name']);

// Init the database
if ($db->init() === true) {
    // Create EditDepositPage instance if database available
    $page = new EditDepositPage($db, $depositID);
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
