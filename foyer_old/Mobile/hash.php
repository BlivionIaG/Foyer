<?php
	require("../connect.php");
	$req_cmd = mysqli_query ($db, 'SELECT * FROM PRODUCT') or die(mysqli_error($db));
	$req_nb = mysqli_query($db, 'SELECT count(*) FROM PRODUCT') or die(mysqli_error($db));
	$i = 0; //A l'arrache
	$nbr = mysqli_fetch_array($req_nb);
	while($tab = mysqli_fetch_array($req_cmd)){
		$i++;
        echo $tab['id_product'].'%;'.md5_file('../Produits/'.$tab['id_product'].'.jpg');
		if($nbr['count(*)']==$i);
		else echo '%+';	
	}
?>