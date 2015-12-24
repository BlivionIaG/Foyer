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

$app->add(new Slim\Middleware\HttpBasicAuthentication([
    "path" => "/",
    "realm" => "Protected",
    "users" => [
        API_USER => API_PASSWORD
    ]
]));

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
