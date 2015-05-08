<?php

/*
* The main page for SWCProspect
*/

// Bootstrap
require_once dirname(__DIR__).'/src/bootstrap.php';

use SamLex\SWCProspect\Test;

$test = new Test();

$test::printMsg('Hello world from test');
echo "\n";

echo $CONFIG['test'];
echo "\n";
