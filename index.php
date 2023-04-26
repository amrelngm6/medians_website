<?php

require_once __DIR__.'/vendor/autoload.php';


spl_autoload_register(function ($name) {

	// $name = $name;
// 	is_file(getcwd().'/app/'.$name.'.php') ? include (getcwd().'/app/'.$name.'.php') : '';
	    $name2 = str_replace('\\', '/', __DIR__.'/app/'.$name.'.php');
	is_file($name2) ? include ($name2) : '';

});

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

// TWIG template engine
use Twig\Environment;

use Medians\Application as apps;
use Medians\Application\Configuration;

use Shared\dbaser;

use Medians\Infrastructure\Administrators\AdminRepository;
use Medians\Infrastructure\Customers\CustomerRepository;
use Medians\Infrastructure\Providers\ProviderRepository;

session_start();
error_reporting(E_ALL);






$request = Request::createFromGlobals();

$app = new Silex\Application();

$app->currency = 'EGP';
    

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\DatabaseManager;

CONST db_name = 'medians_website';
CONST db_username = 'root';
CONST db_password = 'root';

$capsule = new Capsule;

$capsule->addConnection([
    'driver' => 'mysql',
    'host' => 'localhost',
    'database' => db_name,
    'username' => db_username,
    'password' => db_password,
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix' => '',
]);

$capsule->setAsGlobal();

$capsule->bootEloquent();

$app->CONF = (new apps\Configuration())->create('localhost', db_username, db_password, db_name)->getCONFArray();

$mysqli = new mysqli($app->CONF['host'], $app->CONF['user'], $app->CONF['pass'], $app->CONF['name']);

if (!empty($mysqli->connect_errno) ) 
{
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error; 
    exit();
}

$mysqli->set_charset("utf8");

// Alternative mysql class for faster queries
$app->dbaser = new Shared\dbaser\MysqliDb ($mysqli);

$app->debug = true;

$loader = new \Twig\Loader\FilesystemLoader('./app');
$twig = new \Twig\Environment($loader, [
    //'cache' => '/app/cache',
    'debug' => true,
]);

$twig->addFilter(new \Twig\TwigFilter('html_entity_decode', 'html_entity_decode'));



$app->authServiceAdmin = new apps\Auth\AuthService( new AdminRepository() );

$app->authService = new apps\Auth\AuthService( new CustomerRepository() );

$app->userSession = empty($app->authService->checkSession()) ? null : (new apps\Customers\Customer( $app->authService->checkSession() ) )->getItem();


// Set Settings options
$app->Settings = new apps\Settings\Settings(null);


include('app/helper/methods.php');

if (empty($_POST))
{
	include('app/controller.php');

} else {
	include('app/controller_post.php');
}


