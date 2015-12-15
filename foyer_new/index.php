<?php
require_once('config/config.php');

//on check si les gets sont bons
if (isset($_GET['controller']) && isset($_GET['action']))
{
  $controller = $_GET['controller'];
  $action = $_GET['action'];
}
//sinon on redirige vers la home
else
{
  $controller = 'pages';
  $action = 'home';
}

require_once('views/layout.php');