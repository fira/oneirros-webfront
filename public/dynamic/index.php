<?php 

require_once '../../vendor/autoload.php';

session_start();

$loader = new Twig_Loader_Filesystem('../../templates');
$twig = new Twig_Environment($loader, array(
    'cache' => '../../cache',
));

$twig->addGlobal('session', $_SESSION);
$twig->display('index.html');
?>
