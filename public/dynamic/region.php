<?php 

require_once '../../vendor/autoload.php';
require_once '../../include/config.php';
require_once '../../include/connectmongo.php';

session_start();

$loader = new Twig_Loader_Filesystem('../../templates');
$twig = new Twig_Environment($loader, array(
    'cache' => '../../cache',
));

$regionresult = $mongodb->regions->find( [ 'regionid' => intval($_GET['id']) ] );
foreach ($regionresult as $foundregion) { 
	$foundone = true;
}

$twig->addGlobal('session', $_SESSION);
$twig->addGlobal('foundone', $foundone);
$twig->addGlobal('region', $foundregion);
$twig->display('region.html');

?>
