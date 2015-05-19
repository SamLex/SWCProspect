<?php

/*
* The edit deposit page for SWCProspect
*/

// Bootstrap environment
require_once dirname(__DIR__).'/src/bootstrap.php';

// Import nessesary classes
use SamLex\SWCProspect\Page\EditDepositPage;
use SamLex\SWCProspect\Database\MySQLDatabaseInteractor as MySQL;

// Connect to database
$db = new MySQL($CONFIG['database_address'], $CONFIG['database_user'], $CONFIG['database_password'], $CONFIG['database_name']);

// Sanitize and validate GET parameters
$cleanDepositID = filter_input(INPUT_GET, 'depositid', FILTER_SANITIZE_NUMBER_INT);
$validDepositID = filter_var($cleanDepositID, FILTER_VALIDATE_INT);

if ($validDepositID) {
    $depositID = $validDepositID;
} else {
    $depositID = -1;
}

// Create page and output
$page = new EditDepositPage($db, $depositID);
$page->outputPage();
