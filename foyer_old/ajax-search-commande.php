            <script type="text/javascript">
	    $(document).ready( function() {
            $('.ajax').click( function(){
            $field = $(this).attr("name");
            $.ajax({
              	type : 'GET', // envoi des données en GET ou POST
            	url : 'ajax-search-commande.php' , // url du fichier de traitement
            	data : 'search='+$field , // données à envoyer en  GET ou POST
            	success : function(data){ // traitements JS à faire APRES le retour d'ajax-search.php
            	    $('#results').html(''); // on vide les resultats
            		$('#results').html(data); // affichage des résultats dans le bloc
        	    }
            });
          });
        });
        </script>

<?php
 include ('connect.php');
//recherche des résultats dans la base de données
if($_GET['search']=="client_desc"){
    
 $reqCmd = mysqli_query($db,'SELECT login, id_commande, state, periode_debut, periode_fin FROM COMMAND WHERE DateDiff(now(), time) < 10 
 AND state!=0 ORDER BY login DESC') or die(mysqli_error($db));
    
}
else if($_GET['search']=="client_asc"){
    
 $reqCmd = mysqli_query($db,'SELECT login, id_commande, state, periode_debut, periode_fin FROM COMMAND WHERE DateDiff(now(), time) < 10 AND state!=0 ORDER BY login ASC') or die(mysqli_error($db));

}
else if($_GET['search']=="etat_asc"){
    
 $reqCmd = mysqli_query($db,'SELECT login, id_commande, state, periode_debut, periode_fin FROM COMMAND WHERE DateDiff(now(), time) < 10 AND state!=0 ORDER BY state DESC') or die(mysqli_error($db));

}
else if($_GET['search']=="etat_desc"){
    
 $reqCmd = mysqli_query($db,'SELECT login, id_commande, state, periode_debut, periode_fin FROM COMMAND WHERE DateDiff(now(), time) < 10 AND state!=0 ORDER BY state') or die(mysqli_error($db));

}
else if($_GET['search']=="date_asc"){
    
 $reqCmd = mysqli_query($db,'SELECT login, id_commande, state, periode_debut, periode_fin FROM COMMAND WHERE DateDiff(now(), time) < 10
 AND state!=0  ORDER BY time DESC') or die(mysqli_error($db));

}
else if($_GET['search']=="date_desc"){
    
 $reqCmd = mysqli_query($db,'SELECT login, id_commande, state, periode_debut, periode_fin FROM COMMAND WHERE DateDiff(now(), time) < 10
 AND state!=0 ORDER BY time') or die(mysqli_error($db));

}
else if($_GET['search']=="id_commande_asc"){
    
 $reqCmd = mysqli_query($db,'SELECT login, id_commande, state, periode_debut, periode_fin FROM COMMAND WHERE DateDiff(now(), time) < 10 AND state!=0 ORDER BY id_commande') or die(mysqli_error($db));

}
else if($_GET['search']=="id_commande_desc"){
    
 $reqCmd = mysqli_query($db,'SELECT login, id_commande, state, periode_debut, periode_fin FROM COMMAND WHERE DateDiff(now(), time) < 10 AND state!=0 ORDER BY id_commande DESC') or die(mysqli_error($db));

}
else if($_GET['utilisateur']!=""){

	$var = mysqli_real_escape_string($db,$_GET['utilisateur']);
	$var = addcslashes($var, '%_');
	$var = trim($var);
	$var = htmlspecialchars($var);
    
 $reqCmd = mysqli_query($db,"SELECT login, id_commande, state, periode_debut, periode_fin FROM COMMAND WHERE login LIKE '".$var."%' ORDER BY id_commande DESC") or die(mysqli_error($db));

}

$reqProductCmd = mysqli_query($db,'SELECT quantity, id_product FROM PRODUCT_COMMAND WHERE id_commande= "'.$tabCmd['id_commande'].'" ') or die(mysqli_error($db)); 
$reqProduct = mysqli_query($db, 'SELECT name, price FROM PRODUCT WHERE id_product = "'.$tabProductCmd['id_product'].'"');

    
      echo 
                '
                <table class="table table-striped">
                    <tr>
                        <th>Date 
                        <button name="date_asc" value="" class="ajax glyphicon glyphicon-chevron-up" style="background-color:#f9f9f9;border:0;"></button>
                        <button name="date_desc" value="" class="ajax glyphicon glyphicon-chevron-down" style="background-color:#f9f9f9;border:0;"></button>
                        </th>
                        <th>ID Commande
                        <button name="id_commande_asc" value="" class="ajax glyphicon glyphicon-chevron-up" style="background-color:#f9f9f9;border:0;"></button>
                        <button name="id_commande_desc" value="" class="ajax glyphicon glyphicon-chevron-down" style="background-color:#f9f9f9;border:0;"></button>
                        </th>
                        <th>Client
                        <button name="client_asc" value="" class="ajax glyphicon glyphicon-chevron-up" style="background-color:#f9f9f9;border:0;"></button>
                        <button name="client_desc" value="" class="ajax glyphicon glyphicon-chevron-down" style="background-color:#f9f9f9;border:0;"></button>
                        </th>
                        <th>Etat
                        <button name="etat_asc" value="" class="ajax glyphicon glyphicon-chevron-up" style="background-color:#f9f9f9;border:0;"></button>
                        <button name="etat_desc" value="" class="ajax glyphicon glyphicon-chevron-down" style="background-color:#f9f9f9;border:0;"></button>
                        </th>
                        <th>Détails</th>
                        <th>Prix total</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>';
                    
    while($tabCmd = mysqli_fetch_array($reqCmd))
        {
        $reqProductCmd = mysqli_query($db,'SELECT quantity, id_product FROM PRODUCT_COMMAND WHERE id_commande= "'.$tabCmd['id_commande'].'" ') or die(mysqli_error($db)); 
        
        
        
        
            
            echo '<tr>
                    <td>'.$tabCmd['periode_debut'].' - '.$tabCmd[periode_fin].'</td>
                    <td>'.$tabCmd['id_commande'].'</td>
                    <td>'.$tabCmd['login'].'</td>
                    <td>';
                    if($tabCmd['state'] == 0) echo 'Annulée';
                    if($tabCmd['state'] == 1) echo 'En cours de validation';
                    if($tabCmd['state'] == 2) echo 'Valider';
                    if($tabCmd['state'] == 3) echo 'Payé';
            echo '
                    </td><td>';
                    $prixtotal=0;
                    while($tabProductCmd = mysqli_fetch_array($reqProductCmd)){
                        $reqProduct = mysqli_query($db, 'SELECT name, price FROM PRODUCT WHERE id_product = "'.$tabProductCmd['id_product'].'"');
                     $tabProduct = mysqli_fetch_array($reqProduct);
                     echo $tabProduct['name'].' x'.$tabProductCmd['quantity'].'<br>';
                     $prixtotal+=$tabProductCmd['quantity']*$tabProduct['price'];
                    }
                    echo '</td><td>'.$prixtotal.'€</td>
                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    <td>';
                    if($tabCmd['state'] == 1)
                        {
                        echo '
                        <form style="float: left; margin-right: 50px" action="commande.php" method="post" >
                        <input type="hidden" name="id_command" value="'.$tabCmd['id_commande'].'"/>
                        <input type="hidden" name="login" value="'.$tabCmd['login'].'"/>

                        <input class="btn btn-success" type="submit" name="Valider" value="Valider" />
                        </form>
                        ';
                        }
                        else if ($tabCmd['state'] == 2){
                            echo '
                        <form style="float: left; margin-right: 50px" action="commande.php" method="post" >
                        <input type="hidden" name="id_command" value="'.$tabCmd['id_commande'].'"/>
                        <input type="hidden" name="login" value="'.$tabCmd['login'].'"/>
                        <input class="btn btn-warning" type="submit" name="Payer" value="Payer" />
                        </form>
                        ';
                        }
                    echo '</td><td>';
                    if($tabCmd['state'] == 1  || $tabCmd['state']==2)
                        {
                        echo '
                        <form style="float: left; margin-right: 50px" action="commande.php" method="post" >
                        <input type="hidden" name="id_command" value="'.$tabCmd['id_commande'].'"/>
                        <input type="hidden" name="login" value="'.$tabCmd['login'].'"/>
                        <input class="btn btn-danger" type="submit" name="Supprimer" value="Supprimer" />
                        </form>
                        ';  
                        }
                    
                    
                echo '</td>
                    
                    <td>
                    <button type="button" class="btn btn-primary glyphicon glyphicon-envelope" data-toggle="modal" data-target="#exampleModal'.$tabCmd['id_commande'].'" data-whatever="'.$tabCmd['login'].'"></button>
                    </td>
                    <td></td>
                </tr>
           
          
            <div class="modal fade" id="exampleModal'.$tabCmd['id_commande'].'" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="exampleModalLabel">Nouveau message à '.$tabCmd['login'].'</h4>
                  </div>
                  <div class="modal-body">
                    <form action="commande.php" method="post" >
                        
                      <div class="form-group">
                        <input type="hidden" name="Destinataire" value="'.$tabCmd['login'].'"/>
                        <input type="hidden" name="id_command" value="'.$tabCmd['id_commande'].'"/>
                        <label for="message-text" class="control-label">Message:</label>
                        <textarea class="form-control" data-whatever="message" name="message"></textarea>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                        <input role="button" type="submit" class="btn btn-primary" value="Envoyer" name="Message" >
                      </div>
                    </form>
                  </div>
                </div>
               </div>
            </div>
            ';
            }
        
    
?>
</table>