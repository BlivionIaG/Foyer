	<?php
	
	$url='http://konstantin-sidorenko.fr/Foyer/Produits/';
	
	require("../connect.php");
	$req_cmd = mysqli_query ($db, 'SELECT * FROM PRODUCT WHERE available=1') or die(mysqli_error($db));
	$req_nb = mysqli_query($db, 'SELECT count(*) FROM PRODUCT WHERE available=1') or die(mysqli_error($db));
	$i = 0; //A l'arrache
	$nbr = mysqli_fetch_array($req_nb);
	while($tab = mysqli_fetch_array($req_cmd))
		{
		$i++;
		if($tab['available']){
		    //echo $tab['id_product'].'%;'.$tab['name'].'%;http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'Produits/'.$tab['id_product'].'.jpg%;'.$tab['description'].'%;'.$tab['price'];
		    echo $tab['id_product'].'%;'.$tab['name'].'%;'.$tab['description'].'%;'.$tab['price'];
		    if($nbr['count(*)']==$i);
		    else echo '%+';	
		}
		}
		
	?>

