<?php

/*
* The worker for the add deposit page for SWCProspect
*/

// Bootstrap environment
require_once dirname(dirname(__DIR__)).'/src/bootstrap.php';

// Import nessesary classes
use SamLex\SWCProspect\Database\MySQLDatabaseInteractor as MySQL;
use SamLex\SWCProspect\Deposit;

// Connect to database
$db = new MySQL($CONFIG['database_address'], $CONFIG['database_user'], $CONFIG['database_password'], $CONFIG['database_name']);

// Sanitize and validate GET parameters
$cleanSize = filter_input(INPUT_POST, 'size', FILTER_SANITIZE_NUMBER_INT);
$validSize = filter_var($cleanSize, FILTER_VALIDATE_INT);
$cleanLocX = filter_input(INPUT_POST, 'locx', FILTER_SANITIZE_NUMBER_INT);
$validLocX = filter_var($cleanLocX, FILTER_VALIDATE_INT);
$cleanLocY = filter_input(INPUT_POST, 'locy', FILTER_SANITIZE_NUMBER_INT);
$validLocY = filter_var($cleanLocY, FILTER_VALIDATE_INT);
$cleanPlanetID = filter_input(INPUT_POST, 'planetid', FILTER_SANITIZE_NUMBER_INT);
$validPlanetID = filter_var($cleanPlanetID, FILTER_VALIDATE_INT);
$cleanType = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_NUMBER_INT);
$validType = filter_var($cleanType, FILTER_VALIDATE_INT);

if ($validSize && $validLocX && $validLocY && $validPlanetID && $validType) {
    $type = $db->getDepositType($validType);

    if ($type) {
        $planet = $db->getPlanet($validPlanetID);
        if ($planet) {
            $deposit = new Deposit($validSize, $validLocX, $validLocY, $planet, $type, $db);
            $deposit->save();
        }
    }
}

// Redirect back to main page
header('HTTP/1.1 301 Moved Permanently');
header('Location: '.'../index.php');
exit();
