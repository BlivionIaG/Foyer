<?php

require 'vendor/autoload.php';
require 'config/config.php';

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

//on se connecte à l'api
$user = NULL;
if(isset($_SERVER['PHP_AUTH_USER'])){
  $API_USER = json_decode(API_USER);
  foreach ($API_USER as $key => $login) {
    if($login->user == $_SERVER['PHP_AUTH_USER'] && $login->password == $_SERVER['HTTP_AUTHORIZATION']){
      $user = $login;
      break;
    }
  }
}

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

$app->run();
