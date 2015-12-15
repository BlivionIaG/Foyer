<!DOCTYPE html>
<html>
<head>
  <title> Gestion des commandes </title>
        <!-- Meta -->
		<meta charset = "utf-8"/>

		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="js/bootstrap.js" rel="javascript">
		<script src="http://code.jquery.com/jquery.min.js"></script>
	    <script src="js/bootstrap.min.js"></script>
</head>
<body>
  <header>
  	
    
    
  </header>
  
	<nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
            </div>
            <div id="navbar" class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    <li><a href="?controller=pages&action=product">Gestion des produits</a></li>
                    <li><a href="?controller=pages&action=order">Gestion des commandes </a></li>
                    <li><a href="?controller=pages&action=client">Clients</a></li>
                </ul>
            </div>
        </div>
    </nav>
    
  <?php require_once('routes.php'); ?>

  <footer>
    
  </footer>
</body>
</html>