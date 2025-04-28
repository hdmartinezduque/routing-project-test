<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Routing\Route;

$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

require __DIR__ . '/../routes.php';

Route::dispatch($method, $uri);