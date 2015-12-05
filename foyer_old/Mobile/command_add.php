<?php
    require('../connect.php');
    /*
    $_POST['login'] = 'pmicha18';
    $_POST['date'] = '8h29%;8h49';
    $_POST['command'][0]['quantity'] = 1;
    $_POST['command'][1]['quantity'] = 11;
    $_POST['command'][0]['id_product'] = 2;
    $_POST['command'][1]['id_product'] = 20;
    */
    $periode = explode("%;", $_POST['date']);
    
    
    
	mysqli_query($db, 'INSERT INTO COMMAND (login, state, periode_debut, periode_fin) VALUES ("'.$_POST['login'].'", "1", "'.$periode[0].'", "'.$periode[1].'")') or die(mysqli_error($db));
	$req_id_cmd = mysqli_query($db,'SELECT id_commande FROM COMMAND WHERE login = \''.$_POST['login'].'\' ORDER BY time DESC') or die(mysqli_error($db));
	 

    $id_cmd =  mysqli_fetch_array($req_id_cmd);
	

	
	for($i = 0; $i < count($_POST['command']) ;$i++)
	    {
	    mysqli_query($db, 'INSERT INTO PRODUCT_COMMAND (quantity, id_product, id_commande) VALUES ("'.$_POST['command'][$i]['quantity'].'", "'.$_POST['command'][$i]['id_product'].'",  "'.$id_cmd['id_commande'].'")') or die(mysqli_error($db));
	    }
    
?>