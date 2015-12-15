<?php

require 'vendor/autoload.php';

//connexion a la db
use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;

$capsule->addConnection(array(
    'driver'    => 'mysql',
    'host'      => 'localhost',
    'database'  => 'Foyer',
    'username'  => 'root',
    'password'  => 'korsi29yk',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => ''
));

$capsule->setAsGlobal();
$capsule->bootEloquent();

//ajout de slim pour les routes
$app = new Slim\App();

//ajout des routes
require 'routes/product.php';
require 'routes/command.php';
require 'routes/notification.php';
require 'routes/other.php';

$app->get('/', function($request, $response) use ($app){
  $response = $response->withRedirect('doc/');
  return $response;
});

$app->run();