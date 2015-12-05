<?php session_start();
if($_SESSION['login']== ""){
    header('Location: index.php');
}
?>
<!DOCTYPE html>
<html>
	<head>
		<title> Gestion des commandes </title>
        <!-- Meta -->
		<meta charset = "utf-8"/>
		<!-- Style 
		<link rel="stylesheet" href="style.css"> -->
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="js/bootstrap.js" rel="javascript">
		<script src="http://code.jquery.com/jquery.min.js"></script>
	    <script src="js/bootstrap.min.js"></script>
        <script type="text/javascript">
            $(document).ready( function() {
          // détection de la saisie dans le champ de recherche
          $('#pseudo').keyup( function(){
            $field = $(this);
        
            if( $field.val().length > 0 )
            {
              $.ajax({
          	type : 'GET', // envoi des données en GET ou POST
        	url : 'ajax-search-commande.php' , // url du fichier de traitement
        	data : 'utilisateur='+$(this).val() , // données à envoyer en  GET ou POST
        	success : function(data){ // traitements JS à faire APRES le retour d'ajax-search.php
        		$('#results').html(''); // affichage des résultats dans le bloc
        		$('#results').html(data); // affichage des résultats dans le bloc
        	}
              });
            }
            else
            {
              $.ajax({
          	type : 'GET', // envoi des données en GET ou POST
        	url : 'ajax-search-commande.php' , // url du fichier de traitement
        	data : 'search=id_commande_desc', // données à envoyer en  GET ou POST
        	success : function(data){ // traitements JS à faire APRES le retour d'ajax-search.php
        		$('#results').html(''); // affichage des résultats dans le bloc
        		$('#results').html(data); // affichage des résultats dans le bloc
        	}
              });
            }
            
          });
        });
        </script>
	</head>

	<body>
        <section>
            <br><br>
 

        <h1 style="margin-left:15%;">Application Foyer <small>By Digital Design</small></h1>

    
<br><br>
                      
             <!-- Static navbar -->
              <nav class="navbar navbar-default">
                <div class="container-fluid">
                  <div style="height:75px;" class="navbar-header">
                    <img  style="height:75px;" class="navbar-brand" src="img/logo.png">
                  </div>
                  <div id="navbar" class="navbar-collapse collapse">
                    <ul class="nav navbar-nav">
                      <li ><a style="height:75px;padding-top:28px;" href="produit.php">Gestion des produits</a></li>
                      <li class="active"><a style="height:75px;padding-top:28px;" href="commande.php">Gestion des commandes </a></li>
                      <li><a style="height:75px;padding-top:28px;" href="client.php">Clients</a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right ">
                        <li><a style="height:75px;padding-top:28px;"><?php echo $_SESSION['login']; ?></a></li>
                        <a href="index.php"><button style="margin-top:18px;" type="submit" class="btn btn-default" >Déconnexion</button></a>
                     </ul>
                  </div><!--/.nav-collapse -->
                </div><!--/.container-fluid -->
              </nav>
                        <h2 style="text-align:center;">Gestion des commandes</h2>
            <br><br>
            <?php if($_POST["Payer"] == "Payer") echo '<div class="alert alert-warning" role="alert"><p style="text-align:center;"> La commande a été payée.</p></div>'; ?>
            <?php if($_POST["Supprimer"] == "Supprimer") echo '<div class="alert alert-danger" role="alert"><p style="text-align:center;"> La commande à été supprimée.</p></div>'; ?>
            <?php if($_POST["Valider"] == "Valider") echo '<div class="alert alert-success" role="alert"><p style="text-align:center;"> La commande à été validée.</p></div>'; ?>
            <br>
            <div class="input-group">
              <span class="input-group-addon" id="sizing-addon2">Chercher un client</span>
              <input type="text" id="pseudo" class="form-control" placeholder="Nom du Client" aria-describedby="sizing-addon2">
            </div>
            <br><br>
<?php
    require('connect.php');
    if($_POST['Message'] == "Envoyer")
        {
        mysqli_query($db, 'DELETE FROM NOTIFICATION WHERE id_command = "'.$_POST['id_command'].'" AND method = "2" ') or die(mysqli_error($db));
        echo '<div class="alert alert-success" role="alert"><p style="text-align:center;"> Le message à bien été envoyé </p></div>';
        mysqli_query($db,'INSERT INTO NOTIFICATION (login, notification, id_command, method) VALUES ("'.$_POST['Destinataire'].'", "'.$_POST['message'].'", "'.$_POST['id_command'].'", "2")') or die(mysqli_error($db));
        }
    if($_POST["Valider"] == "Valider")
        {
        mysqli_query($db, 'DELETE FROM NOTIFICATION WHERE id_command = "'.$_POST['id_command'].'" AND method = "0" ') or die(mysqli_error($db));
        mysqli_query($db,'UPDATE COMMAND SET state="2" WHERE id_commande="'.$_POST['id_command'].'"') or die(mysqli_error($db)); 
        mysqli_query($db,'INSERT INTO NOTIFICATION (login, notification, id_command, method) VALUES ("'.$_POST['login'].'", "Votre commande a été validée", "'.$_POST['id_command'].'", "0")') or die(mysqli_error($db));
       
        }
        if($_POST["Payer"] == "Payer")
        {
        mysqli_query($db, 'DELETE FROM NOTIFICATION WHERE id_command = "'.$_POST['id_command'].'" AND method = "0" ') or die(mysqli_error($db));
        mysqli_query($db,'UPDATE COMMAND SET state="3" WHERE id_commande="'.$_POST['id_command'].'"') or die(mysqli_error($db));    
        mysqli_query($db,'INSERT INTO NOTIFICATION (login, notification, id_command, method) VALUES ("'.$_POST['login'].'", "Votre commande a été payée", "'.$_POST['id_command'].'", "0")') or die(mysqli_error($db));

        }
    if($_POST["Supprimer"] == "Supprimer")
        {
        mysqli_query($db, 'DELETE FROM NOTIFICATION WHERE id_command = "'.$_POST['id_command'].'" AND method = "0" ') or die(mysqli_error($db));
        mysqli_query($db,'UPDATE COMMAND SET state="0" WHERE id_commande="'.$_POST['id_command'].'"') or die(mysqli_error($db));  
        mysqli_query($db,'INSERT INTO NOTIFICATION (login, notification, id_command, method) VALUES ("'.$_POST['login'].'", "Votre commande a été annulée", "'.$_POST['id_command'].'", "0" )') or die(mysqli_error($db));

        }
   
?>
<div id="results">
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
    
    $reqCmd = mysqli_query($db,'SELECT login, id_commande, state, periode_debut, periode_fin FROM COMMAND WHERE DateDiff(now(), time) < 10 AND state!=0 ORDER BY id_commande DESC') or die(mysqli_error($db));
    
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
</table> </div>
<br><br>
</section>
</body>
</html>
