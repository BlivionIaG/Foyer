<?php
$passwd = 'foyer';
$login = 'foyer';
$host = 'localhost';
$bdd = 'foyer';
$db = mysqli_connect($host, $login, $passwd, $bdd);
mysqli_query($db, "SET NAMES UTF8"); 
?>
