<?php session_start();
if($_SESSION['login']== ""){
    header('Location: index.php');
}
if(!isset($_GET["id_product"])) {
    header('Location: produit.php');
}
else{
    include ('connect.php');
    $modifier = $_GET["id_product"];
    $requete = mysqli_query($db, "SELECT * FROM PRODUCT where id_product = '$modifier'") or die(mysqli_error($db));
    $tab = mysqli_fetch_array($requete);
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
            
            <h2 style="text-align:center;">Modifier un produit</h2>
        <br><br>
                
<?php echo'<form style="margin-left:10%;margin-right:10%;" action="produit.php?modification='.$_GET["id_product"].'" method="post" enctype="multipart/form-data">'; ?> 
        <input type="hidden" name="id_product" value="<?php echo $tab["id_product"]; ?>" required />
        <div class="form-group">
            <label for="exampleInputEmail1">Nom</label>
            <input type="text" name="name"  class="form-control" placeholder="Nom" value="<?php echo $tab["name"]; ?>" required/>
          </div>
          <div class="form-group">
            <label for="exampleInputPassword1">Description</label>
            <textarea class="form-control" name="description" placeholder="Description" maxlength="150" required><?php echo $tab["description"]; ?></textarea>
            <p class="help-block">150 caractères maximum.</p>
          </div>
          <div class="form-group">
            <label for="exampleInputPassword1">Prix</label>
            <input class="form-control" type="number" name="price" placeholder="Prix" value="<?php echo $tab["price"]; ?>" required/>
          </div>
          <div class="form-group">
            <label for="exampleInputPassword1">Disponible</label>
            <br>
            Oui<input style="margin-left:5px;" checked type="radio" name="available" value="1" <?php if($tab["available"]) echo "checked"; ?> required/>
            Non<input style="margin-left:5px;" type="radio" name="available" value="0" <?php if(!$tab["available"]) echo "checked"; ?> required/> 
        </div>
          <div class="form-group">
            <label for="exampleInputFile">Image</label>
            <input type="file" name="image" accept="image/*"/>
            <p class="help-block"><img class="img-thumbnail" width=150px src="Produits/<?php echo $tab["id_product"];?>.jpg"/> Image actuelle.</p>
          </div>
          <button type="submit" class="btn btn-default">Modifier le produit</button>
        </form>
       
        </section>
        <br><br><br>
    </body>
</html>
<?php 
    mysqli_free_result($requete);
     mysqli_close($db);
 ?>
