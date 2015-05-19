<?php

/*
* The worker for the deletition of deposits for SWCProspect
*/

// Bootstrap environment
require_once dirname(dirname(__DIR__)).'/src/bootstrap.php';

// Import nessesary classes
use SamLex\SWCProspect\Database\MySQLDatabaseInteractor as MySQL;

// Connect to database
$db = new MySQL($CONFIG['database_address'], $CONFIG['database_user'], $CONFIG['database_password'], $CONFIG['database_name']);

// Sanitize and validate GET parameters
$cleanDepositID = filter_input(INPUT_GET, 'depositid', FILTER_SANITIZE_NUMBER_INT);
$validDepositID = filter_var($cleanDepositID, FILTER_VALIDATE_INT);

if ($validDepositID) {
    $deposit = $db->getDeposit($validDepositID);

    if ($deposit) {
        $deposit->delete();
    }
}

// Redirect back to main page
header('HTTP/1.1 301 Moved Permanently');
header('Location: '.'../index.php');
exit();
