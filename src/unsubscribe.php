<?php
require_once 'functions.php';

// TODO: Implement the unsubscription logic.
if (isset($_GET['email'])) {
	$email = $_GET['email'];
	unsubscribeEmail($email);
}
?>

<!DOCTYPE html>
<html>
<head>
	<!-- Implement Header ! -->
	<title>Unsubscribe from Task Updates</title>
</head>
<body>
	<!-- Do not modify the ID of the heading -->
	<h2 id="unsubscription-heading">Unsubscribe from Task Updates</h2>
	<!-- Implemention body -->
	 <p>Unsubscribed successfully.</p>
</body>
</html>
