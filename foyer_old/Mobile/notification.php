<?php
    require('../connect.php');
    $reqNotif = mysqli_query($db, 'SELECT * FROM NOTIFICATION') or die(mysqli_error($db));
    $reqNb = mysqli_query($db, 'SELECT count(*) FROM NOTIFICATION') or die(mysqli_error($db));
    
    $i = 0;
    $nb = mysqli_fetch_array($reqNb);
    while($tabNotif = mysqli_fetch_array($reqNotif))
        {
        $i++;
        echo $tabNotif['method'].'%;';
        if($tabNotif['id_command'] == -1);
        else echo $tabNotif['id_command'].'%;';
        echo $tabNotif['notification'];
        if($nb['count(*)']==$i);
        else echo '%+';
            
        }
?>