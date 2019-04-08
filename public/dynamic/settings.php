<?php 

require_once '../../vendor/autoload.php';
require_once '../../include/config.php';
require_once '../../include/connectpg.php';

session_start();

if (!$_SESSION['loggedIn']) {
	header('Location: /');
	return;
}

$loader = new Twig_Loader_Filesystem('../../templates');
$twig = new Twig_Environment($loader, array(
    'cache' => '../../cache',
));

$query = $PGConnection->prepare("SELECT * FROM rivalcheck WHERE uid=:uid");
$query->bindParam(":uid", $_SESSION['loginId']);
$query->execute();
$otpkey = $query->fetch()["otp"];

$query = $PGConnection->prepare("SELECT * FROM rivalmapping WHERE uid=:uid");
$query->bindParam(":uid", $_SESSION['loginId']);
$query->execute();
$charmapinfo = $query->fetchAll();

$twig->addGlobal('session', $_SESSION);
$twig->addGlobal('otpkey', $otpkey);
$twig->addGlobal('charmap', $charmapinfo);
$twig->display('settings.html');
?>
