<?php
    include('../connect.php');
    $reqState = mysqli_query($db, 'SELECT state FROM COMMAND WHERE id_commande = "'.$_GET['id_command'].'"') or die(mysqli_error($db));
    $state = mysqli_fetch_array($reqState);
    echo $state['state'];
?>