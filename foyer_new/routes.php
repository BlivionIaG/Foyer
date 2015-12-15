<?php

//on fait appele aux contructeurs
function call($controller, $action)
{
  require_once('controllers/' . $controller . '_controller.php');

  switch($controller)
  {
    case 'pages':
    $controller = new PagesController();
    break;
    case 'home':
    $controller = new HomeController();
    break;
  }

  //on fait appele a la methode du l'objet
  $controller->{ $action }();
}

//liste des routes acceptées
$controllers = array(
  'pages' => ['product','command','home'],
  'home' => ['home', 'error']
);

//on regarde si le controller est dans le tableau
if (array_key_exists($controller, $controllers))
{
  //onregarde si l'action existe bien
  if (in_array($action, $controllers[$controller]))
  {
   call($controller, $action);
 }
 else
 {
   call('pages', 'error');
 }
}
else
{
  call('pages', 'error');
}
