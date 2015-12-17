<?php

use Illuminate\Database\Capsule\Manager as Capsule;

$app->group('/product', function() use ($app) {

	/**
	 * @api {get} /product/ Récupération des produits.
	 * @apiName GetProducts
	 * @apiGroup Product
	 *
	 * @apiSuccess {Number} id_product ID du produit.
	 * @apiSuccess {String} name Nom du produit.
	 * @apiSuccess {Number} price Prix du produit.
	 * @apiSuccess {String} description Description du produit.
	 * @apiSuccess {Number} available Disponibilité du produit.
	 * @apiSuccess {Date} date Date de création du produit.
	 * @apiSuccess {String} hash_image Hash de l'image pour check le cache.
	 *
	 * @apiSuccessExample Success-Response:
	 *     HTTP/1.1 200 OK
	 *
	 * @apiErrorExample Error-Response:
	 *     HTTP/1.1 404 Not Found
	 *     {
	 *       "error": code error
	 *     }
	 */
	$app->get('/', function($request, $response) {
		try {
			$products = Capsule::table('PRODUCT')->get();
			foreach ($products as $key => $value) {
				if (!empty($products[$key]))
					if(file_exists('files/product/img/'.$value->id_product.'.jpeg'))
						$products[$key]->hash_image = md5_file('files/product/img/'.$value->id_product.'.jpeg');
					else
						$products[$key]->hash_image = false;
			}
			$response = $response->withJson($products);
		} catch(Illuminate\Database\QueryException $e) {
			$response = $response->withJson(array ("status"  => array("error" => $e->getMessage())), 400);
		}
		return $response;
	});

	/**
	 * @api {get} /product/:id_product Récupération d'un produit en fonction de son ID.
	 * @apiName GetProductByIdProduct
	 * @apiGroup Product
	 *
	 * @apiParam {Number} id Product unique ID.
	 *
	 * @apiSuccess {Number} id_product ID du produit.
	 * @apiSuccess {String} name Nom du produit.
	 * @apiSuccess {Number} price Prix du produit.
	 * @apiSuccess {String} description Description du produit.
	 * @apiSuccess {Number} available Disponibilité du produit.
	 * @apiSuccess {Date} date Date de création du produit.
	 * @apiSuccess {String} hash_image Hash de l'image pour check le cache.
	 *
	 * @apiSuccessExample Success-Response:
	 *     HTTP/1.1 200 OK
	 *
	 * @apiErrorExample Error-Response:
	 *     HTTP/1.1 404 Not Found
	 *     {
	 *       "error": code error
	 *     }
	 */
	$app->get('/id_product/{id_product}', function($request, $response, $id_product){
		try {
			$product = Capsule::table('PRODUCT')->where('id_product', $id_product)->first();
			if (!empty($product))
				if(file_exists('files/product/img/'.$product->id_product.'.jpeg'))
					$product->hash_image = md5_file('files/product/img/'.$product->id_product.'.jpeg');
				else
					$product->hash_image = false;
			$response = $response->withJson($product);
		} catch(Illuminate\Database\QueryException $e) {
			$response = $response->withJson(array ("status"  => array("error" => $e->getMessage())), 400);
		}
		return $response;
	});

	/**
	 * @api {get} /product/:id_product Récupération des produits en fonction de son état.
	 * @apiName GetProductByAvailable
	 * @apiGroup Product
	 *
	 * @apiParam {Number} available Product state.
	 *
	 * @apiSuccess {Number} id_product ID du produit.
	 * @apiSuccess {String} name Nom du produit.
	 * @apiSuccess {Number} price Prix du produit.
	 * @apiSuccess {String} description Description du produit.
	 * @apiSuccess {Number} available Disponibilité du produit.
	 * @apiSuccess {Date} date Date de création du produit.
	 * @apiSuccess {String} hash_image Hash de l'image pour check le cache.
	 *
	 * @apiSuccessExample Success-Response:
	 *     HTTP/1.1 200 OK
	 *
	 * @apiErrorExample Error-Response:
	 *     HTTP/1.1 404 Not Found
	 *     {
	 *       "error": code error
	 *     }
	 */
	$app->get('/available/{available}', function($request, $response, $available){
		try {
			$response = $response->withJson(Capsule::table('PRODUCT')->where('available', $available)->get());
		} catch(Illuminate\Database\QueryException $e) {
			$response = $response->withJson(array ("status"  => array("error" => $e->getMessage())), 400);
		}
		return $response;
	});

	/**
	 * @api {post} /product/ Ajout d'un nouveau produit.
	 * @apiName PostProduct
	 * @apiGroup Product
	 *
	 * @apiParam {String} name Nom du produit.
	 * @apiParam {Number} price Prix du produit.
	 * @apiParam {String} description Description du produit.
	 * @apiParam {Number} available Disponibilité du produit.
	 *
	 * @apiSuccessExample Success-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	 *       "succes": "ok"
	 *     }
	 *
	 * @apiErrorExample Error-Response:
	 *     HTTP/1.1 404 Not Found
	 *     {
	 *       "error": code error
	 *     }
	 */
	$app->post('/',function ($request, $response)  use ($app) {
		try {
			Capsule::table('PRODUCT')->insert($request->getParsedBody());
			$response = $response->withJson(array ("status"  => array("success" => "ok")), 200);
		} catch(Illuminate\Database\QueryException $e) {
			$response = $response->withJson(array ("status"  => array("error" => $e->getMessage())), 400);
		}
		return $response;
	});

	/**
	 * @api {put} /product/:id_product Modification d'un produit.
	 * @apiName PutProduct
	 * @apiGroup Product
	 *
	 * @apiParam {Number} id_product ID du produit.
	 *
	 * @apiParam {String} name Nom du produit.
	 * @apiParam {Number} price Prix du produit.
	 * @apiParam {String} description Description du produit.
	 * @apiParam {Number} available Disponibilité du produit.
	 *
	 * @apiSuccessExample Success-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	 *       "succes": "ok"
	 *     }
	 *
	 * @apiErrorExample Error-Response:
	 *     HTTP/1.1 404 Not Found
	 *     {
	 *       "error": code error
	 *     }
	 */
	$app->put('/{id_product}', function ($request, $response, $id_product) use ($app){
		try {
			var_dump($request->getParsedBody());
			Capsule::table('PRODUCT')->where('id_product',$id_product)->update($request->getParsedBody());
			$response = $response->withJson(array ("status"  => array("success" => "ok")), 200);
		} catch(Illuminate\Database\QueryException $e) {
			$response = $response->withJson(array ("status"  => array("error" => $e->getMessage())), 400);
		}
		return $response;
	});

	/**
	 * @api {delete} /product/:id_product Suppression d'un produit.
	 * @apiName DeleteProduct
	 * @apiGroup Product
	 *
	 * @apiParam {Number} id_product ID du produit.
	 *
	 * @apiSuccessExample Success-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	 *       "succes": "ok"
	 *     }
	 *
	 * @apiErrorExample Error-Response:
	 *     HTTP/1.1 404 Not Found
	 *     {
	 *       "error": code error
	 *     }
	 */
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