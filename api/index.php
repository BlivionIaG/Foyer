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
    'password'  => 's3curit3',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => ''
));

// Set the event dispatcher used by Eloquent models... (optional)
use Illuminate\Container\Container;

// Make this Capsule instance available globally via static methods... (optional)
$capsule->setAsGlobal();

// Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
$capsule->bootEloquent();


//ajout de slim pour les routes
$app = new Slim\App();

$_ENV['SLIM_MODE'] = 'developper';

//ajout des routes
require 'routes/product.php';

$app->run();