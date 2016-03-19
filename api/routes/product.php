<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Symfony\Component\Yaml\Parser;

$app->group('/product', function() use ($app) {

	/**
	 * @api {get} /product/ Récupération des produits.
	 * @apiDescription Sécuriser Mobile Admin.
	 * @apiName GetProducts
	 * @apiGroup Product
	 *
	 * @apiSuccess {Number} id_product ID du produit.
	 * @apiSuccess {String} name Nom du produit.
	 * @apiSuccess {Number} price Prix du produit.
	 * @apiSuccess {String} description Description du produit.
	 * @apiSuccess {Number} available Disponibilité du produit.
	 * @apiSuccess {Date} date Date de création du produit.
	 * @apiSuccess {String} image Nom fichier de l'image.
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
			$products = Capsule::table('PRODUCT')->orderBy('name', 'asc')->get();
			foreach ($products as $key => $value) {
				$products[$key]->first_letter = strtoupper($value->name[0]);
			}
			$response = $response->withJson($products);
		} catch(Illuminate\Database\QueryException $e) {
			$response = $response->withJson(array ("status"  => array("error" => $e->getMessage())), 400);
		}
		return $response;
	});

	/**
	 * @api {get} /product/:id_product Récupération d'un produit en fonction de son ID.
	 * @apiDescription Sécuriser Mobile Admin.
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
	 * @apiSuccess {String} image Nom fichier de l'image.
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
			$response = $response->withJson($product);
		} catch(Illuminate\Database\QueryException $e) {
			$response = $response->withJson(array ("status"  => array("error" => $e->getMessage())), 400);
		}
		return $response;
	});

	/**
	 * @api {get} /product/:id_product Récupération des produits en fonction de son état.
	 * @apiDescription Sécuriser Mobile Admin.
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
	 * @apiSuccess {String} image Nom fichier de l'image.
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
	 * @apiDescription Sécuriser Mobile Admin.
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
			$id_product = Capsule::table('PRODUCT')->insertGetId($request->getParsedBody());
			$response = $response->withJson(array ("status"  => array("success" => $id_product)), 200);
		} catch(Illuminate\Database\QueryException $e) {
			$response = $response->withJson(array ("status"  => array("error" => $e->getMessage())), 400);
		}
		return $response;
	});

	/**
	 * @api {post} /product/img/:id_product Ajouter une image à un produit.
	 * @apiDescription Sécuriser Mobile Admin.
	 * @apiName PostProductImg
	 * @apiGroup Product
	 *
	 * @apiParam {Number} id_product ID du produit.
	 * @apiParam {File} file Image du produit.
	 *
	 * @apiSuccessExample Success-Response:
	 *     HTTP/1.1 200 OK
	 *     {
	 *       "succes": "fichier upload"
	 *     }
	 *
	 * @apiErrorExample Error-Response:
	 *     HTTP/1.1 404 Not Found
	 *     {
	 *       "error": code error
	 *     }
	 */
	$app->post('/img/{id_product}',function ($request, $response, $id_product)  use ($app) {
		try {
			$yaml = new Parser();
			$config = $yaml->parse(file_get_contents('config/config.yml'));

			$storage = new \Upload\Storage\FileSystem($config['parameters']["dir_files"].'product');
			$file = new \Upload\File('file', $storage);

			//on passe son id en nom
			$file->setName($id_product["id_product"]);

			//fichier valide
			$file->addValidations(array(
				new \Upload\Validation\Mimetype(array('image/png', 'image/jpeg', 'image/pjpeg')),
				new \Upload\Validation\Size('5M')
			));

			//check la validité du fichier pour supprimer le/les ancienne(s) image(s)
			if($file->validate()) {
				foreach (glob($config['parameters']["dir_files"].'product/'.$id_product["id_product"].'.*') as $oldFile) {
					unlink($oldFile);
				}
			}
			//on upload le fichier
			$file->upload();
			//on ajoute son nom en base
			Capsule::table('PRODUCT')->where('id_product',$id_product["id_product"])->update(['image' => $file->getNameWithExtension()]);
			$response = $response->withJson(array ("status"  => array("succes" => "fichier upload")), 200);
		} catch (\Exception $e) {
			if(!empty($file)){
				$response = $response->withJson(array ("status"  => array("error" => $file->getErrors())), 400);
			}else{
				$response = $response->withJson(array ("status"  => array("error" => $e->getMessage())), 400);
			}
		}
		return $response;
	});

	/**
	 * @api {put} /product/:id_product Modification d'un produit.
	 * @apiDescription Sécuriser Mobile Admin.
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
			Capsule::table('PRODUCT')->where('id_product',$id_product)->update($request->getParsedBody());
			$response = $response->withJson(array ("status"  => array("success" => "ok")), 200);
		} catch(Illuminate\Database\QueryException $e) {
			$response = $response->withJson(array ("status"  => array("error" => $e->getMessage())), 400);
		}
		return $response;
	});

	/**
	 * @api {put} /product/:id_product/available/:available Changement d'état d'un produit.
	 * @apiDescription Sécuriser Mobile Admin.
	 * @apiName PutProductAvailable
	 * @apiGroup Product
	 *
	 * @apiParam {Number} id_product ID du produit.
	 * @apiParam {Number} available Etat du produit.
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
	$app->put('/{id_product}/available/{available}',function ($request, $response, $value) {
		try {
			Capsule::table('PRODUCT')->where('id_product',$value['id_product'])->update(['available' => $value['available']]);
			$response = $response->withJson(array ("status"  => array("success" => "ok")), 200);
		} catch(Illuminate\Database\QueryException $e) {
			$response = $response->withJson(array ("status"  => array("error" => $e->getMessage())), 400);
		}
		return $response;
	});

	/**
	 * @api {delete} /product/:id_product Suppression d'un produit.
	 * @apiDescription Sécuriser Mobile Admin.
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
