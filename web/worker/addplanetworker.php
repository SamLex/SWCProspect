<?php

/*
* The worker for the add planet page for SWCProspect
*/

// Bootstrap environment
require_once dirname(dirname(__DIR__)).'/src/bootstrap.php';

// Import nessesary classes
use SamLex\SWCProspect\Database\MySQLDatabaseInteractor as MySQL;
use SamLex\SWCProspect\Planet;

// Connect to database
$db = new MySQL($CONFIG['database_address'], $CONFIG['database_user'], $CONFIG['database_password'], $CONFIG['database_name']);

// Sanitize and validate GET parameters
$validName = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$cleanSize = filter_input(INPUT_POST, 'size', FILTER_SANITIZE_NUMBER_INT);
$validSize = filter_var($cleanSize, FILTER_VALIDATE_INT);
$cleanType = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_NUMBER_INT);
$validType = filter_var($cleanType, FILTER_VALIDATE_INT);

if ($validName && $validSize && $validType) {
    $type = $db->getPlanetType($validType);

    if ($type) {
        $planet = new Planet($validName, $validSize, $type, $db);
        $planet->save();
    }
}

// Redirect back to main page
header('HTTP/1.1 301 Moved Permanently');
header('Location: '.'../index.php');
exit();
