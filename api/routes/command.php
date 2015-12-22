<?php

use Illuminate\Database\Capsule\Manager as Capsule;

$app->group('/command', function() use ($app) {

  /**
   * @api {get} /command/ Récupération des commandes.
   * @apiName GetCommands
   * @apiGroup Command
   *
   * @apiSuccess {Number} id_commande ID de la commande.
   * @apiSuccess {String} login Login de la commande de l'utilisateur.
   * @apiSuccess {Number} state Etat de la commande.
   * @apiSuccess {Date} time Date de la commande.
   * @apiSuccess {String} periode_debut Heure de début de la commande.
   * @apiSuccess {String} periode_fin Heure de fin de la commande.
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
      $response = $response->withJson(Capsule::table('COMMAND')->get());
    } catch(Illuminate\Database\QueryException $e) {
      $response = $response->withJson(array ("status"  => array("error" => $e->getMessage())), 400);
    }
    return $response;
  });

  /**
   * @api {get} /command/id_commande/:id_commande Récupération d'un commande par son ID.
   * @apiName GetCommandsByIdCommand
   * @apiGroup Command
   *
   * @apiParam {Number} id Commande unique ID.
   *
   * @apiSuccess {Number} id_commande ID du commande.
   * @apiSuccess {String} login Login de la commande de l'utilisateur.
   * @apiSuccess {Number} state Etat de la commande.
   * @apiSuccess {Date} time Date de la commande.
   * @apiSuccess {String} periode_debut Heure de début de la commande.
   * @apiSuccess {String} periode_fin Heure de fin de la commande.
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
  $app->get('/id_commande/{id_commande}', function($request, $response, $id_commande){
    try {
      $response = $response->withJson(Capsule::table('COMMAND')->where('id_commande', $id_commande)->first());
    } catch(Illuminate\Database\QueryException $e) {
      $response = $response->withJson(array ("status"  => array("error" => $e->getMessage())), 400);
    }
    return $response;
  });

  /**
   * @api {get} /command/state/:state Récupération des commandes en fonction de son état.
   * @apiName GetCommandsByState
   * @apiGroup Command
   *
   * @apiParam {Number} state Etat de la commande.
   *
   * @apiSuccess {Number} id_commande ID du commande.
   * @apiSuccess {String} login Login de la commande de l'utilisateur.
   * @apiSuccess {Number} state Etat de la commande.
   * @apiSuccess {Date} time Date de la commande.
   * @apiSuccess {String} periode_debut Heure de début de la commande.
   * @apiSuccess {String} periode_fin Heure de fin de la commande.
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
  $app->get('/state/{state}', function($request, $response, $state){
    try {
      $response = $response->withJson(Capsule::table('COMMAND')->where('state', $state)->get());
    } catch(Illuminate\Database\QueryException $e) {
      $response = $response->withJson(array ("status"  => array("error" => $e->getMessage())), 400);
    }
    return $response;
  });

  /**
   * @api {post} /command/ Ajout d'une commande.
   * @apiName PostCommand
   * @apiGroup Command
   *
   * @apiParam {String} login Login de la commande de l'utilisateur.
   * @apiParam {Number} state Etat de la commande.
   * @apiParam {String} periode_debut Heure de début de la commande.
   * @apiParam {String} periode_fin Heure de fin de la commande.
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
      //on creer la commande
      Capsule::table('COMMAND')->insert($request->getParsedBody());
      //on lui ajoute les produits
      foreach ($request->getParsedBody()->products as $key => $product) {
        Capsule::table('PRODUCT_COMMAND')->insert($product);
      }
      $response = $response->withJson(array ("status"  => array("ok" => "succes")), 200);
    } catch(Illuminate\Database\QueryException $e) {
      $response = $response->withJson(array ("status"  => array("error" => $e->getMessage())), 400);
    }
    return $response;
  });

  /**
   * @api {put} /command/:id_commande Modification d'une commande.
   * @apiName PutCommand
   * @apiGroup Command
   *
   * @apiParam {Number} id_commande ID de la commande.
   *
   * @apiParam {String} login Login de la commande de l'utilisateur.
   * @apiParam {Number} state Etat de la commande.
   * @apiParam {String} periode_debut Heure de début de la commande.
   * @apiParam {String} periode_fin Heure de fin de la commande.
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
  $app->put('/{id_commande}', function ($request, $response, $id_commande) use ($app){
    try {
      Capsule::table('COMMAND')->where('id_commande',$id_commande)->update($request->getParsedBody());
      $response = $response->withJson(array ("status"  => array("success" => "ok")), 200);
    } catch(Illuminate\Database\QueryException $e) {
      $response = $response->withJson(array ("status"  => array("error" => $e->getMessage())), 400);
    }
    return $response;
  });

  /**
   * @api {delete} /command/:id_commande Suppression d'une commande.
   * @apiName DeleteCommand
   * @apiGroup Command
   *
   * @apiParam {Number} id_commande ID de la commande.
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
  $app->delete('/{id_commande}',function ($request, $response, $id_commande) {
    try {
      Capsule::table('COMMAND')->where('id_commande',$id_commande)->update(['state' => 3]);
      $response = $response->withJson(array ("status"  => array("success" => "ok")), 200);
    } catch(Illuminate\Database\QueryException $e) {
      $response = $response->withJson(array ("status"  => array("error" => $e->getMessage())), 400);
    }
    return $response;
  });
});