<?php
require_once 'functions.php';

// TODO: Implement verification logic.
if (isset($_GET['email']) && isset($_GET['code'])) {
	$email = urldecode(trim($_GET['email']));
	$code = urldecode(trim($_GET['code']));
	verifySubscription($email, $code);
}
?>

<!DOCTYPE html>
<html>
<head>
	<!-- Implement Header ! -->
	 <title>Subscription Verification</title>
</head>
<body>
	<!-- Do not modify the ID of the heading -->
	<h2 id="verification-heading">Subscription Verification</h2>
	<!-- Implemention body -->
	<p> verification succesfull! </p>
</body>
</html>