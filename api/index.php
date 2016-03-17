<?php

require 'vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use Symfony\Component\Yaml\Parser;

$yaml = new Parser();
$config = $yaml->parse(file_get_contents('config/config.yml'));

$capsule = new Capsule;
$capsule->addConnection($config['database']);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$container = new \Slim\Container([
  'settings' => [
    'displayErrorDetails' => true
  ]
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
    return $container['response']->withJson(array ("status"  => array("error" =>
      ['code' => $exception->getCode(),
      'message' => $exception->getMessage(),
      'file' => $exception->getFile(),
      'line' => $exception->getLine(),
      'trace' => explode("\n", $exception->getTraceAsString())]
    )), 500);
  };
};

$app = new Slim\App($container);

$app->add(new \Slim\Middleware\HttpBasicAuthentication([
    'path' => ['/command/', '/product/', '/user/', '/banniere/'],
    'secure' => false,
    'relaxed' => $config['hotsts_allows'],
    'users' => $config['api_user']
]));

//ajout des routes
require 'routes/product.php';
require 'routes/command.php';
require 'routes/notification.php';
require 'routes/user.php';
require 'routes/login.php';
require 'routes/other.php';

//redirection vers la doc
$app->get('/', function($request, $response) use ($app){
	return $response->withRedirect('doc/');
});

$app->run();