<?php
	require_once("../../include/config.php");
	require_once("../../include/connectpg.php");

	header('Content-Type: application/json');

	if(!isset($_POST)) {
		echo '{ "success": false, 
			"msg": "This Endpoint should be accessed by POST" }';
	} elseif (!isset($_POST['reguser']) || !isset($_POST['regpass'])) {
		echo '{ "success": false,
			"msg": "Please fill out username and password" }';
	} elseif (!ctype_alnum(str_replace(array("-", "_"), "", $_POST['reguser']))) {
		echo '{ "success": false,
			"msg": "Please only use alphanum, hypen and underscore in username" }';
	} elseif (isset($_POST['regmail']) 
		&& $_POST['regmail']
		&& !(filter_var($_POST['regmail'], FILTER_VALIDATE_EMAIL)))  {
		echo '{ "success": false,
			"msg": "This does not look like a valid email" }';
	} else {
		$cryptPass = crypt($_POST['regpass'], $Oneirros_Crypt_Scheme);
		
		/* Query for similar users */
		$query = $PGConnection->prepare("SELECT COUNT(*) FROM login WHERE name=:name OR email=:mail");
		$query->bindParam(':name', $_POST['reguser']);
		$query->bindParam(':mail', $_POST['regmail']);
		$query->execute();

		if($query->fetchColumn()) {
			echo '{ "success": false,
				"msg": "Account already registered with this email or username" }';
		} else {
			try {
				/* Register user in database */
				$query = $PGConnection->prepare("INSERT INTO login (name, pass, email) VALUES (:name, :pass, :mail)");
				$query->bindParam(':name', $_POST['reguser']);
				$query->bindParam(':pass', $cryptPass);
				$query->bindParam(':mail', $_POST['regmail']);

				$query->execute();
			} catch (Exception $e) {
				echo '{ "success": false,
					"msg": "Exception occured during registration" }';
				return;
			}

			echo '{ "success": true,
				"msg": "Registration succeeded !" }';
		}
	}
?>
