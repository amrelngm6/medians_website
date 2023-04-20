<?php



/*
// Return 
// List of side menu
*/
function getMenuList()
{
	$data = array(
		array('title'=>'Dashboard', 'link'=>'/adminPanel'),
		array('title'=>'Pages', 'link'=>'/adminPanel/pages'),
		array('title'=>'Settings', 'link'=>'/settings'),
		array('title'=>'Logout', 'link'=>'/logout'),
	);

	return $data;
}

function getProviderMenuList()
{
	$data = array(
		array('title'=>'Dashboard', 'link'=>'/provider_area'),
	);

	return $data;
}




/*
// Params
// @Object $twig, @Object $app 
//
// Return 
// Page not found template 
*/
function Page404($twig, $app)
{
    return $twig->render('views/404.html.twig', [
        'title' => 'Page not found',
        'app' => $app
    ]);
}