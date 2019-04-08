<?php
	require_once("../../include/config.php");
	require_once("../../include/connectpg.php");

	session_start();

	header('Content-Type: application/json');

	if(!isset($_POST['user']) || !isset($_POST['password'])) {
		echo '{ "success": false,
			"msg": "Please fill out username and password" }';
		return;
	}

	/* Hash now to avoid infering usernames by timing */
	$cryptPass = crypt($_POST['password'], $Oneirros_Crypt_Scheme);

	/* Query for user */
	$query = $PGConnection->prepare("SELECT * FROM login WHERE name=:name AND pass=:pass");
	$query->bindParam(":name", $_POST["user"]);
	$query->bindParam(":pass", $cryptPass);
	$query->execute();

	$foundUsers = $query->fetchAll();

	if (!$query->rowCount()) {
		echo '{ "success": false,
			"msg": "Credentials invalid" }';
		return;
	}

	/* Log in as first found user (they're not supposed to be several anyway ! */
	$_SESSION['loginId'] = $foundUsers[0]['uid'];
	$_SESSION['loginName'] = $foundUsers[0]['name'];
	$_SESSION['loginMail'] = $foundUsers[0]['email'];
	$_SESSION['loggedIn'] = true;

	echo '{ "success": true,
		"msg": "Login successful, redirecting!",
		"redirect": "/" }';
?>
