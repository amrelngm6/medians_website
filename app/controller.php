<?php 

use chillerlan\QRCode\QROptions;
use chillerlan\QRCode\QRCode;



// invoke a fresh QRCode instance
$qrcode = new QRCode();

// and dump the output

// quick and simple:

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

// TWIG template engine
use Twig\Environment;

use Medians\Application as apps;
use Shared\dbaser;


$app->menuList = getMenuList();
$app->currentPage = $request->getPathInfo();



/*
// Return list of device 
*/
$app->match('', function () use ($twig, $app) 
{

    return (new apps\Pages\Page)->home($app, $twig);

});



/*
// = Login page = 
//
// Return  Login page in case if not authorized 
*/
$app->match('login', function () use ($twig, $app) 
{

    return  $twig->render('views/admin/forms/login.html.twig', [
        'title' => 'Login page ',
        'app' => $app,
        'formAction' => $app->CONF['url'].'/',
    ]);

});



/*
// = Homepage = 
//
// Return list of device 
*/
$app->match('adminPanel', function () use ($twig, $app) 
{
    
    return $twig->render('views/admin/intro.html.twig', [
        'title' => 'Dashboard',
        'app' => $app,
    ]);

});


/**
* = AdminPanel = 
* Return list of device 
*/
$app->match('adminPanel/{model}', function ($model) use ($twig, $app) 
{
    switch ($model) 
    {
        case 'pages':
            return (new apps\Pages\Page)->admin($app, $twig);
            break;
        case 'services':
            return (new apps\Services\Service)->admin($app, $twig);
            break;
        default:
            return $twig->render('views/admin/intro.html.twig', [
                'title' => 'Dashboard',
                'app' => $app,
            ]);
            break;
    }
});




$app->match('adminPanel/edit/{model}/{slug}', function ($model, $slug) use ($twig, $app) 
{
    switch ($model) 
    {
        case 'pages':
            return (new apps\Pages\Page)->update($slug, $app, $twig);
            break;
        case 'services':
            return (new apps\Services\Service)->update($slug, $app, $twig);
            break;
        default:
            return $twig->render('views/admin/intro.html.twig', [
                'title' => 'Dashboard',
                'app' => $app,
            ]);
            break;
    }
});


$app->match('adminPanel/confirm_edit/{model}/{slug}', function ($model, $slug) use ($twig, $app) 
{

    switch ($model) 
    {   
        case 'pages':
            return (new apps\Pages\Page)->edit($slug, $app, $twig);
            break;
        case 'services':
            return (new apps\Services\Service)->admin($app, $twig);
            break;
        default:
            return $twig->render('views/admin/intro.html.twig', [
                'title' => 'Dashboard',
                'app' => $app,
            ]);
            break;
    }
});


/**
* Return list of services 
*/
$app->match('pages/{slug}', function ($slug) use ($twig, $app) 
{
    return (new apps\Pages\Page)->index($slug, $app, $twig);

});


/**
* Return list of services 
*/
$app->match('services', function () use ($twig, $app) 
{
    return (new apps\Services\Service)->index(0, $app, $twig);

});


$app->match('services/{slug}', function ($slug) use ($twig, $app) 
{
    return (new apps\Services\Service)->index($slug, $app, $twig);

});


/*
// Return Settings page
*/
$app->match('settings', function () use ($twig, $app) 
{
});







// Logout and remoce cookies and session
$app->match('logout', function () use ($twig, $request, $app) 
{
    $app->authService->unsetSession();

    return $app->redirect('./');
});




$app->run();


    





/*
// Async methods
*/

// use Spatie\Async\Pool;

// $pool = Pool::create();

// $pool[] = async(function () use ($app) {
//    $output = ' Amr ';
//    return $output;
// })->then(function (String $output) {
//    echo $output . "\n";
// });

// await($pool);

