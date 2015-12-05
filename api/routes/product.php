<?php 

use Illuminate\Database\Capsule\Manager as Capsule;  

$app->group('/product', function() use ($app) {

	//get all
	$app->get('/', function($request, $response) {
		try 
		{	
			$response = $response->withJson(Capsule::table('PRODUCT')->get());
		} catch(Illuminate\Database\QueryException $e) {
			$response = $response->withJson('{"error":{"text":'. $e->getMessage() .'}}', 400);
		}
		return $response;
	});

	//get by id_product
	$app->get('/id_product/{id_product}', function($request, $response, $id_product){
		try 
		{	
			$response = $response->withJson(Capsule::table('PRODUCT')->where('id_product' ,'=' , $id_product)->first());
		} catch(Illuminate\Database\QueryException $e) {
			$response = $response->withJson('{"error":{"text":'. $e->getMessage() .'}}', 400);
		}
		return $response;
	});

	$app->post('/',function ($request, $response)  use ($app) {

		var_dump( $app->isPost());
		//Capsule::table('PRODUCT')->insert($request);
		$response = $response->withStatus(400);
		return $response;
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
});

/*
CREATE TABLE IF NOT EXISTS `PRODUCT` (
`id_product` int(11) NOT NULL AUTO_INCREMENT,
`name` varchar(75) DEFAULT NULL,
`price` float DEFAULT NULL,
`description` varchar(150) DEFAULT NULL,
`available` tinyint(1) DEFAULT '1',
`date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id_product`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=26 ;

*/