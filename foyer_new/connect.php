<?php
	$password = 'foyer';
	$login = 'foyer';
	$host = 'localhost';
	$bdd = 'foyer';
	$db = mysqli_connect($host, $login, $password, $bdd);
	mysqli_query($db, "SET NAMES UTF8"); 
?>
