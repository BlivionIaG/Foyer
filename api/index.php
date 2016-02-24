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

$container = new \Slim\Container([
  'settings' => [
    'displayErrorDetails' => true,
  ],
]);
//modification de l'erreur 404
$container['notFoundHandler'] = function ($container) {
  return function ($request, $response) use ($container) {
      return $container['response']->withJson(array ("status"  => array("error" => "not found")), 404);
  };
};
//modification de l'erreur 500
$container['errorHandler'] = function ($container) {
  return function ($request, $response, $exception) use ($container) {
      return $container['response']->withJson(array ("status"  => array("error" => $exception)), 500);
  };
};

$app = new Slim\App($container);

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