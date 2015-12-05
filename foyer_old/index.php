<?php session_start();
require('connect.php');
$_SESSION['login']= "";
if($_POST['login']!="" && $_POST['password']!=""){
	// recuparation des identifiants grace au form
	$user=$_POST['login'];
	$password=$_POST['password'];
    // protection aux injections mysql
    $user = stripslashes($user);
    $password = stripslashes($password);
    $user = mysqli_real_escape_string($db,$user);
    $password = mysqli_real_escape_string($db,$password);
    $requete=mysqli_query($db,"SELECT * FROM USER_CLUB WHERE login='$user' AND password='$password'");
    
    // recherche si il y a un ligne de la table qui comporte le mdp et l'identifiant
    $count=mysqli_num_rows($requete);
    
    // si il en a trouvé une 1 c'est ok il est connedcté
    if($count==1){
    	$_SESSION['login']= $user;
    	header('Location: produit.php');
    }
    // mauvais identifiants avec redirection sur l'index
    else {
        $wrong_password = true;
    }
    }
?>


<!DOCTYPE html>
<html>
	<head>
		<title> Connexion </title>
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
                      <li class="active"><a style="height:75px;padding-top:28px;" href="index.php">Se connecter</a></li>
                    </ul>
                  </div><!--/.nav-collapse -->
                </div><!--/.container-fluid -->
              </nav>
            
            <h2 style="text-align:center;">Se connecter</h2>
        <br><br>
            <?php if(isset($wrong_password)) echo '<div class="alert alert-danger" role="alert"><p style="text-align:center;"> Mauvais mot de passe.</p></div>'; ?>
            <br>
        
        <form action="index.php" method="post" style="margin-left:10%;margin-right:10%;">
          <div class="form-group">
            <label for="exampleInputEmail1">Utilisateur</label>
            <input type="text" name="login"  class="form-control" placeholder="Utilisateur" required/>
          </div>
          <div class="form-group">
            <label for="exampleInputEmail1">Mot de Passe</label>
            <input type="password" name="password"  class="form-control" placeholder="Mot de Passe" required/>
          </div>
          <button type="submit" class="btn btn-default">Se connecter</button>
        </form>
       
        </section>
        <br><br><br>
    </body>
</html>

