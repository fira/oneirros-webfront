<?php
	require_once("../../include/config.php");
	require_once("../../include/connectpg.php");

	session_start();

	# Remove old key
	try {
		$query = $PGConnection->prepare("DELETE FROM rivalcheck WHERE uid=:uid");
		$query->bindParam(":uid", $_SESSION['loginId']);
		$query->execute();
	} catch (Exception $e) {
	}

	# Generate new key
	$key = sha1(random_int(1000,999999999));

	# Insert key
	try {
		$query = $PGConnection->prepare("INSERT INTO rivalcheck (uid, otp) VALUES (:uid, :otp)");
		$query->bindParam(":uid", $_SESSION['loginId']);
		$query->bindParam(":otp", $key);
		$query->execute();
	} catch (Exception $e) {
	} 

	header('Location: /dynamic/settings#charmap');
?>
