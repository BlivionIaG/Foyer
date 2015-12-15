<?php

use Illuminate\Database\Capsule\Manager as Capsule;

/**
* @api {get} /date/ Obtention de la date du serveur.
* @apiName GetDate
* @apiGroup Others
*
* @apiSuccess {Date} date Date du serveur, "d m Y H:i".
*
* @apiSuccessExample Success-Response:
*     HTTP/1.1 200 OK
*
*/
$app->get('/date/', function($request, $response) {
	date_default_timezone_set('Europe/Paris');
	$response = $response->write(date("d m Y H:i"));
	return $response;
});