<?php session_start();
if($_SESSION['login']== ""){
    header('Location: index.php');
}
            include ('connect.php');
				
            $carac_interdit = array("%;", "%+");
				
            //Modification
            if(isset($_GET["modification"]) && isset($_POST["name"]) && isset($_POST["price"]) && isset($_POST["description"]) && isset($_POST["available"])) {
                $name = stripslashes($_POST["name"]);
                $name = str_replace($carac_interdit, "", $name);
            	$name = mysqli_real_escape_string($db,$name);
            	
            	 $price = stripslashes($_POST["price"]);
                $price = str_replace($carac_interdit, "", $price);
            	$price = mysqli_real_escape_string($db,$price);
            	
            	 $description = stripslashes($_POST["description"]);
                $description = str_replace($carac_interdit, "", $description);
            	$description = mysqli_real_escape_string($db,$description);
                mysqli_query($db, 'UPDATE PRODUCT SET available="'.$_POST["available"].'", name = "'.$name.'", price = "'.$price.'", description = "'.$description.'" where id_product = "'.$_GET['modification'].'" ') or die(mysqli_error($db));
                $modif = 1;
            if($_FILES["image"]["tmp_name"]!=""){
                
                //On suppr l'ancienne image
                unlink('Produits/'.$_POST['id_product'].'.jpg');
               // echo $_POST['id_product'].'.jpg'." a été modifiée";
                $tmp_name = $_FILES["image"]["tmp_name"];
                //redimensionnement
                $ImageChoisie = imagecreatefromjpeg($_FILES['image']['tmp_name']);
                $TailleImageChoisie = getimagesize($_FILES['image']['tmp_name']);
               
                $NouvelleLargeur = 400;
                $Reduction = ( ($NouvelleLargeur * 100)/$TailleImageChoisie[0] );
                $NouvelleHauteur = ( ($TailleImageChoisie[1] * $Reduction)/100 );
               
                $NouvelleImage = imagecreatetruecolor($NouvelleLargeur , $NouvelleHauteur) or die ("Erreur");
                imagecopyresampled($NouvelleImage , $ImageChoisie, 0, 0, 0, 0, $NouvelleLargeur, $NouvelleHauteur, $TailleImageChoisie[0],$TailleImageChoisie[1]);  
               
                imagedestroy($ImageChoisie);
                $new_name = $_POST["id_product"];
                imagejpeg($NouvelleImage , 'Produits/'.$new_name.'.jpg', 100);
                
                
                $new_name = $_POST['id_product'].'.jpg';        
                $image = move_uploaded_file($tmp_name, "Produits/".$new_name);
                
            }
            }
            
            // Supression à revoir
            elseif(isset($_GET["id_product"])) {
                $supprimer = $_GET["id_product"];
               /* mysqli_query($db,"delete from PRODUCT_COMMAND where id_product = '$supprimer'") or die(mysqli_error($db));
                mysqli_query($db,"delete from PRODUCT where id_product = '$supprimer'") or die(mysqli_error($db));
                unlink('Produits/'.$_GET["id_product"].'.jpg');*/
                mysqli_query($db, 'UPDATE PRODUCT SET available=0 WHERE id_product = "'.$supprimer.'" ') or die(mysqli_error($db));

                $suppr = 1;
            }
            
            //Ajout
            elseif(isset($_POST["name"]) && isset($_POST["price"]) && isset($_POST["description"])) {
                
                $name = stripslashes($_POST["name"]);
                $name = str_replace($carac_interdit, "", $name);
            	$name = mysqli_real_escape_string($db,$name);
            	
            	 $price = stripslashes($_POST["price"]);
                $price = str_replace($carac_interdit, "", $price);
            	$price = mysqli_real_escape_string($db,$price);
            	
            	 $description = stripslashes($_POST["description"]);
                $description = str_replace($carac_interdit, "", $description);
            	$description = mysqli_real_escape_string($db,$description);

                mysqli_query($db, "INSERT INTO PRODUCT (`name`, `price`, `description`) VALUES ('".$name."', '".$price."', '".$description."')") or die(mysqli_error($db));
                    
                //Getionde l'image 
                //On récupère l'id_product du produit ajouté pour renommer l'image
                
                $req_id = mysqli_query($db,'SELECT id_product FROM PRODUCT WHERE name = "'.$name.'"') or die(mysqli_error($db));
                $id_name = mysqli_fetch_array($req_id);
                $tmp_name = $_FILES["image"]["tmp_name"];
                //Redim de l'img 400*400 px http://openclassrooms.com/courses/gd-redimensionner-une-image-sans-la-deformer
                $ImageChoisie = imagecreatefromjpeg($_FILES['image']['tmp_name']);
                $TailleImageChoisie = getimagesize($_FILES['image']['tmp_name']);
               
                $NouvelleLargeur = 400;
                $Reduction = ( ($NouvelleLargeur * 100)/$TailleImageChoisie[0] );
                $NouvelleHauteur = ( ($TailleImageChoisie[1] * $Reduction)/100 );
               
                $NouvelleImage = imagecreatetruecolor($NouvelleLargeur , $NouvelleHauteur) or die ("Erreur");
                imagecopyresampled($NouvelleImage , $ImageChoisie, 0, 0, 0, 0, $NouvelleLargeur, $NouvelleHauteur, $TailleImageChoisie[0],$TailleImageChoisie[1]);  
               
                imagedestroy($ImageChoisie);
                $new_name = $id_name["id_product"];
                imagejpeg($NouvelleImage , 'Produits/'.$new_name.'.jpg', 100);
                
                $ajout =1;
            }
        ?>
<!DOCTYPE html>
<html>
	<head>
		<title> Gestion des produits </title>
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
          $('#produit').keyup( function(){
            $field = $(this);
        
            if( $field.val().length > 0 )
            {
              $.ajax({
          	type : 'GET', // envoi des données en GET ou POST
        	url : 'ajax-search-liste.php' , // url du fichier de traitement
        	data : 'produit='+$(this).val() , // données à envoyer en  GET ou POST
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
        	url : 'ajax-search-liste.php' , // url du fichier de traitement
        	data : 'search=disp_desc', // données à envoyer en  GET ou POST
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
                      <li class="active"><a style="height:75px;padding-top:28px;" href="produit.php">Gestion des produits</a></li>
                      <li><a style="height:75px;padding-top:28px;" href="commande.php">Gestion des commandes </a></li>
                      <li><a style="height:75px;padding-top:28px;" href="client.php">Clients</a></li>
                    </ul>
                     <ul class="nav navbar-nav navbar-right ">
                        <li><a style="height:75px;padding-top:28px;"><?php echo $_SESSION['login']; ?></a></li>
                        <a href="index.php"><button style="margin-top:18px;" type="submit" class="btn btn-default" >Déconnexion</button></a>
                     </ul>
                  </div><!--/.nav-collapse -->
                </div><!--/.container-fluid -->
              </nav>
            
            <h2 style="text-align:center;">Gestion des produits</h2>
            <br><br>
            <?php if(isset($modif)) echo '<div class="alert alert-info" role="alert"><p style="text-align:center;"> Le produit a bien été modifié.</p></div>'; ?>
            <?php if(isset($suppr)) echo '<div class="alert alert-danger" role="alert"><p style="text-align:center;"> Le produit a bien été supprimé.</p></div>'; ?>
            <?php if(isset($ajout)) echo '<div class="alert alert-success" role="alert"><p style="text-align:center;"> Le produit a bien été ajouté.</p></div>'; ?>
            <br>
            <div style="text-align:center;">
              <a role="button" class="btn btn-success" href="ajouter_produit.php">Ajouter un produit</a>
            </div>
            <br>
            <div class="input-group">
              <span class="input-group-addon" id="sizing-addon2">Chercher un produit</span>
              <input type="text" id="produit" class="form-control" placeholder="Nom du produit" aria-describedby="sizing-addon2">
            </div>
            <br><br>
            <div id="results">
            <script type="text/javascript">
	    $(document).ready( function() {
            $('.ajax').click( function(){
            $field = $(this).attr("name");
            $.ajax({
              	type : 'GET', // envoi des données en GET ou POST
            	url : 'ajax-search-liste.php' , // url du fichier de traitement
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
                $requete = mysqli_query($db,"select * from PRODUCT ORDER BY date DESC") or die(mysqli_error($db));
                echo 
                '
                <table class="table table-striped">
                    <tr>
                        <th>Disp.
                        <button name="disp_asc" value="" class="ajax glyphicon glyphicon-chevron-up" style="background-color:#f9f9f9;border:0;"></button>
                        <button name="disp_desc" value="" class="ajax glyphicon glyphicon-chevron-down" style="background-color:#f9f9f9;border:0;"></button>
                        </th>
                        <th>Image</th>
                        <th>Nom 
                        <button name="name_asc" value="" class="ajax glyphicon glyphicon-chevron-up" style="background-color:#f9f9f9;border:0;"></button>
                        <button name="name_desc" value="" class="ajax glyphicon glyphicon-chevron-down" style="background-color:#f9f9f9;border:0;"></button>
                        </th>
                        <th>Description</th>
                        <th>Prix
                        <button name="prix_asc" value="" class="ajax glyphicon glyphicon-chevron-up" style="background-color:#f9f9f9;border:0;"></button>
                        <button name="prix_desc" value="" class="ajax glyphicon glyphicon-chevron-down" style="background-color:#f9f9f9;border:0;"></button>
                        </th>
                        <th></th>
                        <th></th>
                        
                    </tr>';
                
                while($tab = mysqli_fetch_array($requete)) {
                echo '
                    
                    <tr>
                        <td style="padding-left:25px;">';
                        
                            if($tab['available']){
                            echo '<span style="color: green; margin-left: 15px;" class="glyphicon glyphicon-ok" aria-hidden="true"></span>';
                            }
                            else {
                            echo '<span style="color: red; margin-left: 15px;" class="glyphicon glyphicon-remove" aria-hidden="true"></span>';
                            }
                echo '
                        </td>
                        <td>
                            <img class="img-thumbnail" src="Produits/'.$tab['id_product'].'.jpg" width="50px">
                        </td>
                        <td>
                            <h3>'.$tab['name'].'</h3>
                        </td>
                        <td style="max-width:500px;">
                            <p>'.$tab['description'].'</p>
                        </td>
                        <td>
                            <p>'.$tab['price'].'€</p>
						</td>
					';
				echo '
				        <td>';
							if($tab['available']){
                                echo '<a role="button" class="btn btn-danger" href="produit.php?id_product='.$tab['id_product'].'">Supprimer</a>';
                            }
               
                            else{
                                echo '<p><code>Le produit est non disponible</code></p>';
                            }
                            echo '<td><a role="button" class="btn btn-primary" href="modifier_produit.php?id_product='.$tab['id_product'].'">Modifier</a>
                        </td>
						
                    </tr>
                    ';
                }
                echo '</table></div>';
                mysqli_free_result($requete);
                mysqli_close($db);
            ?>
        </section>
	</body>
</html>