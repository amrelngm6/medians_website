<?php

session_start(); error_reporting(E_ALL);
date_default_timezone_set('Africa/Cairo');

file_exists(__DIR__.'/app/config/database.php') ?  require_once __DIR__.'/app/config/database.php' : header('Location: ./installer/index.php');

require_once __DIR__.'/vendor/autoload.php';

spl_autoload_register(function ($name) {

    $name2 = str_replace('\\', '/', __DIR__.'/app/'.$name.'.php');
    is_file($name2) ? include ($name2) : '';

});
// use phpseclib3\Net\SSH2;

// if (isset($_GET['command']))
// {

// $ssh = new SSH2('41.36.145.38');
// if (!$ssh->login('desktop-ddtq7ab\m ewais', '1')) {
//     exit('Login Failed');
// }

// $ssh->exec('"C:/nircmd.exe" '.$_GET['command']);
// // $ssh->exec('shutdown /r /t 0');
// echo $_GET['command'];
// $ssh->disconnect();

// }

/**
 * Set the database connection using 
 * @var Illuminate\Database\Capsule\Manager 
 * library for all models
 */ 
use Illuminate\Database\Capsule\Manager as Capsule;
$capsule = new Capsule;
$capsule->addConnection([
    'driver' => 'mysql',
    'host' => db_host,
    'database' => db_name,
    'username' => db_username,
    'password' => db_password,
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix' => '',
]);
$capsule->setAsGlobal();
$capsule->bootEloquent();


include('app/helper/methods.php');
include('app/config/route.php');



