<?php
header('Access-Control-Allow-Origin: http://10.112.20.49:3000');
header('Access-Control-Allow-Headers: *');
date_default_timezone_set('America/Sao_Paulo');

// autoloader.php e config.php
require_once __DIR__.'/Vendor/autoload.php';

// Despachante
$Dispatch = new Classes\ClassDispatch();