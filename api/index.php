<?php

require 'vendor/autoload.php';
require 'inc/config.php';
require 'inc/functions.php';

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;
$capsule->addConnection(array(
	'driver'    => DB_SGBD,
	'host'      => DB_HOST,
	'database'  => DB_BASE,
	'username'  => DB_USER,
	'password'  => DB_PASSWORD,
	'charset'   => DB_CHARSET,
	'collation' => DB_COLLATION,
	'prefix'    => DB_PREFIX
));
$capsule->setAsGlobal();
$capsule->bootEloquent();

$app = new Slim\App();

//ajout des routes
require 'routes/product.php';
require 'routes/command.php';
require 'routes/notification.php';
require 'routes/user.php';
require 'routes/other.php';

//redirection vers la doc
$app->get('/', function($request, $response) use ($app){
	return $response->withRedirect('doc/');
});

//connexion au CAS
$app->get('/cas/', function($request, $response) use ($app){
  $file_handle = fopen("https://web.isen-bretagne.fr/cas/login", "r");
  while (!feof($file_handle)) {
    $line = fgets($file_handle);
    if(preg_match('#name="lt" value="([a-zA-Z0-9-_]+)"#',$line))
      echo $line;
  }
  fclose($file_handle);
});

$app->run();