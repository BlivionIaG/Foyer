<?php 

use Illuminate\Database\Capsule\Manager as Capsule;  

$app->group('/product', function() use ($app) {

	//get all
	$app->get('/', function($request, $response) {
		try {	
			$response = $response->withJson(Capsule::table('PRODUCT')->get());
		} catch(Illuminate\Database\QueryException $e) {
			$response = $response->withJson(array ("status"  => array("error" => $e->getMessage())), 400);
		}
		return $response;
	});

	//get by id_product
	$app->get('/id_product/{id_product}', function($request, $response, $id_product){
		try {	
			$response = $response->withJson(Capsule::table('PRODUCT')->where('id_product', $id_product)->first());
		} catch(Illuminate\Database\QueryException $e) {
			$response = $response->withJson(array ("status"  => array("error" => $e->getMessage())), 400);
		}
		return $response;
	});

	//add product
	$app->post('/',function ($request, $response)  use ($app) {
		try {
			Capsule::table('PRODUCT')->insert(json_decode($app->request()));
			$response = $response->withJson(array ("status"  => array("success" => "ok")), 200);
		} catch(Illuminate\Database\QueryException $e) {
			$response = $response->withJson(array ("status"  => array("error" => $e->getMessage())), 400);
		}
		return $response;
	});

	//edit product
	$app->put('/{id_product}', function ($request, $response, $id_product) use ($app){
		try {
			var_dump($app->request());
			Capsule::table('PRODUCT')->where('id_product',$id_product)->update(json_decode($app->request()));
			$response = $response->withJson(array ("status"  => array("success" => "ok")), 200);
		} catch(Illuminate\Database\QueryException $e) {
			$response = $response->withJson(array ("status"  => array("error" => $e->getMessage())), 400);
		}
		return $response;
	});

	//delete product
	$app->delete('/{id_product}',function ($request, $response, $id_product) {
		try {
			Capsule::table('PRODUCT')->where('id_product',$id_product)->update(['available' => 0]);
			$response = $response->withJson(array ("status"  => array("success" => "ok")), 200);
		} catch(Illuminate\Database\QueryException $e) {
			$response = $response->withJson(array ("status"  => array("error" => $e->getMessage())), 400);
		}
		return $response;
	});
});