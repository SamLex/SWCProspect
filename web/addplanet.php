<?php

/*
* The add planet page for SWCProspect
*/

// Bootstrap environment
require_once dirname(__DIR__).'/src/bootstrap.php';

use SamLex\SWCProspect\Page\AddPlanetPage;
use SamLex\SWCProspect\Database\MySQLDatabaseInteractor as MySQL;

$db = new MySQL($CONFIG['database_address'], $CONFIG['database_user'], $CONFIG['database_password'], $CONFIG['database_name']);

$page = new AddPlanetPage($db);

$page->outputPage('Add Planet');
