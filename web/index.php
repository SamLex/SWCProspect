<?php

/*
* The main page for SWCProspect
*/

// Bootstrap environment
require_once dirname(__DIR__).'/src/bootstrap.php';

// Import nessesary classes
use SamLex\SWCProspect\Page\MainPage;
use SamLex\SWCProspect\Database\MySQLDatabaseInteractor as MySQL;

// Connect to database
$db = new MySQL($CONFIG['database_address'], $CONFIG['database_user'], $CONFIG['database_password'], $CONFIG['database_name']);

// Create page and output
$page = new MainPage($db);
$page->outputPage('Main Page');
