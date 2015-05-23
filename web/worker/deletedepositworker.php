<?php

/** The worker for the deletition of deposits for SWCProspect. */

/** Bootstrap environment. */
require_once dirname(dirname(__DIR__)).'/src/bootstrap.php';

// Import nessesary classes
use SamLex\SWCProspect\Database\MySQLDatabaseInteractor as MySQL;
use SamLex\SWCProspect\Deposit;

// Sanitize and validate GET parameters
$cleanDepositID = filter_input(INPUT_GET, 'depositid', FILTER_SANITIZE_NUMBER_INT);
$validDepositID = filter_var($cleanDepositID, FILTER_VALIDATE_INT);

// Create DatabaseInteractor instance
$db = new MySQL($CONFIG['database_address'], $CONFIG['database_user'], $CONFIG['database_password'], $CONFIG['database_name']);

// Init the database
if ($db->init() === true) {
    // Check all passed data is of valid type
    if (is_int($validDepositID) === true) {
        // Get needed class instance
        $deposit = Deposit::getDeposit($db, $validDepositID);

        if (is_null($deposit) === false) {
            // Delete deposit from db
            $deposit->delete();
        }
    }
}

// Redirect back to main page
header('HTTP/1.1 301 Moved Permanently');
header('Location: '.'../index.php');
exit();
