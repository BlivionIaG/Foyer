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
 include ('connect.php');
//recherche des résultats dans la base de données
if($_GET['search']=="name_asc"){
    $requete = mysqli_query($db,"select * from PRODUCT ORDER BY name") or die(mysqli_error($db));
}
else if($_GET['search']=="name_desc"){
    $requete = mysqli_query($db,"select * from PRODUCT ORDER BY name DESC") or die(mysqli_error($db));
}
else if($_GET['search']=="prix_desc"){
    $requete = mysqli_query($db,"select * from PRODUCT ORDER BY price ") or die(mysqli_error($db));
}
else if($_GET['search']=="prix_asc"){
    $requete = mysqli_query($db,"select * from PRODUCT ORDER BY price DESC") or die(mysqli_error($db));
}
else if($_GET['search']=="disp_desc"){
    $requete = mysqli_query($db,"select * from PRODUCT ORDER BY available") or die(mysqli_error($db));
}
else if($_GET['search']=="disp_asc"){
    $requete = mysqli_query($db,"select * from PRODUCT ORDER BY available DESC") or die(mysqli_error($db));
}
else if($_GET['produit']!=""){

	$var = mysqli_real_escape_string($db,$_GET['produit']);
	$var = addcslashes($var, '%_');
	$var = trim($var);
	$var = htmlspecialchars($var);
 
     $requete = mysqli_query($db,"select * from PRODUCT  WHERE name LIKE '".$var."%' ORDER BY available DESC") or die(mysqli_error($db));


}
// affichage d'un message "pas de résultats"

                echo '<div id="results"><table class="table table-striped">
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
				        <td >';
							if($tab['available']){
                                echo '<a role="button" class="btn btn-danger" href="liste.php?id_product='.$tab['id_product'].'">Supprimer</a>';
                            }
               
                            else{
                                echo '<p><code>Le produit est non disponible</code></p>';
                            }
                            echo '<td><a role="button" class="btn btn-primary" href="modifier.php?id_product='.$tab['id_product'].'">Modifier</a>
                        </td>
						
                    </tr>
                    ';
                }
                echo '</table>';
                mysqli_free_result($requete);
                mysqli_close($db);

?>