<?php

/*
* The main page for SWCProspect
*/

// Bootstrap environment
require_once dirname(__DIR__).'/src/bootstrap.php';

use SamLex\SWCProspect\Page\MainPage;

$page = new MainPage();

$page->outputPage('Main Page');
