<?php

use App\Actions\PostgreSQLDatabase;
use App\Actions\XMLProcessor;

require_once 'PostgreSQLDatabase.php';
require_once 'XMLProcessor.php';

$database = new PostgreSQLDatabase();
$database->connect();

$xmlProcessor = new XMLProcessor($database);
$xmlProcessor->processDirectory('/path/to/start/folder');
