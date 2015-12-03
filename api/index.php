<?php
require 'Slim/Slim.php';
\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();

$app->get('/', function(){
    echo 'test';
});

$app->post('/post',function () {
    echo 'This is a POST route';
});

$app->put('/put',function () {
    echo 'This is a PUT route';
});

$app->patch('/patch', function () {
    echo 'This is a PATCH route';
});

$app->delete('/delete',function () {
    echo 'This is a DELETE route';
});

$app->run();
