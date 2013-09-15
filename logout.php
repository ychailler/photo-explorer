<?php
	session_destroy();
	header("WWW-Authenticate: Basic"); 
	header('status: 401 Unauthorized');
?>
<html>
	<head>
		<meta http-equiv="refresh" content="0; url=index.php">
	</head>
</html>
