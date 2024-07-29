<?php
use App\kernel\Kernel;
use App\http\Route;

ini_set('display_errors', 1);
session_start();
require '../vendor/autoload.php';
require '../app/routes/router.php';

Kernel::dispatch(Route::routes());
