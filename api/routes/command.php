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
   *       "error": "code error"
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
   * @api {get} /command/id_command/:id_command Récupération d'un commande par son ID.
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
   *       "error": "code error"
   *     }
   */
  $app->get('/id_command/{id_command}', function($request, $response, $id_command){
    try {
      $response = $response->withJson(Capsule::table('COMMAND')->where('id_command', $id_command)->first());
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
   *       "error": "code error"
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
    } catch(Illuminate\Database\QueryException $e) {
      $response = $response->withJson(array ("status"  => array("error" => $e->getMessage())), 400);
    }
    return $response;
  });

  /**
   * @api {put} /command/:id_command Modification d'une commande.
   * @apiName PutCommand
   * @apiGroup Command
   *
   * @apiParam {Number} id_command ID de la commande.
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
   *       "error": "code error"
   *     }
   */
  $app->put('/{id_command}', function ($request, $response, $id_command) use ($app){
    try {
      Capsule::table('PRODUCT')->where('id_command',$id_command)->update($request->getParsedBody());
      $response = $response->withJson(array ("status"  => array("success" => "ok")), 200);
    } catch(Illuminate\Database\QueryException $e) {
      $response = $response->withJson(array ("status"  => array("error" => $e->getMessage())), 400);
    }
    return $response;
  });

  /**
   * @api {delete} /command/:id_command Suppression d'une commande.
   * @apiName DeleteCommand
   * @apiGroup Command
   *
   * @apiSuccess {Number} id_command ID de la commande.
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
   *       "error": "code error"
   *     }
   */
  $app->delete('/{id_command}',function ($request, $response, $id_command) {
    try {
      Capsule::table('PRODUCT')->where('id_command',$id_command)->update(['state' => 3]);
      $response = $response->withJson(array ("status"  => array("success" => "ok")), 200);
    } catch(Illuminate\Database\QueryException $e) {
      $response = $response->withJson(array ("status"  => array("error" => $e->getMessage())), 400);
    }
    return $response;
  });
});