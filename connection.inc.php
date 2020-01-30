<?php
	//Variablen
	$db_host 		= 'localhost';
	$db_user		= 'root';
	$db_pass 		= '';
	$db_name 		= 'bewerbertool';

	//DB Connection
	$db 			= mysqli_connect($db_host, $db_user, $db_pass);
	//Selection
	mysqli_select_db($db, $db_name) or die(mysqli_error());
	// Charset
	mysqli_set_charset($db, 'utf8');

	// DB-Connection schließen:
	mysqli_close($db);
?>