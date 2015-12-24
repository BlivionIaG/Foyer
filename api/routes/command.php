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
   * @apiSuccess {Array} product+quantity Produit de la commande avec la quantité.
   * @apiSuccess {Number} total Total de la commande.
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
      $commandes = Capsule::table('COMMAND')->get();
      $commande_products = Capsule::table('PRODUCT_COMMAND')->get();
      foreach ($commandes as $key_commandes => $commande) {
        $commandes[$key_commandes]->product = "";
        $commandes[$key_commandes]->total = 0;
        foreach ($commande_products as $key_commande_products => $commande_product) {
          if($commande_product->id_commande == $commande->id_commande){
            $product = Capsule::table('PRODUCT')->where('id_product',$commande_product->id_product)->first();
            $product->quantity = $commande_product->quantity;
            $commandes[$key_commandes]->total += $product->price;
            $commandes[$key_commandes]->product[] = $product;
          }
        }
      }
      $response = $response->withJson($commandes);
    } catch(Illuminate\Database\QueryException $e) {
      $response = $response->withJson(array ("status"  => array("error" => $e->getMessage())), 400);
    }
    return $response;
  });

  /**
   * @api {get} /command/stats/ Récupération les statistiques des commandes.
   * @apiName GetCommandsStats
   * @apiGroup Command
   *
   * @apiSuccess {Date} date Date, année plus mois des commandes.
   * @apiSuccess {Number} nb_command Nombre de commande sur une journée.
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
  $app->get('/stats/', function($request, $response) {
    try {
      $response = $response->withJson(Capsule::table('COMMAND')->select(Capsule::raw("COUNT(*) as nb_command, DATE_FORMAT(time,'%Y-%m-%d') AS time_new"))->groupBy('time_new')->get());
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
   * @apiSuccess {Array} product+quantity Produit de la commande avec la quantité.
   * @apiSuccess {Number} total Total de la commande.
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
      $commande = Capsule::table('COMMAND')->where('id_commande', $id_commande)->first();
      $commande_products = Capsule::table('PRODUCT_COMMAND')->where('id_commande', $id_commande)->get();
      $commande->product = "";
      $commande->total = 0;
      foreach ($commande_products as $key_commande_products => $commande_product) {
        $product = Capsule::table('PRODUCT')->where('id_product',$commande_product->id_product)->first();
        $product->quantity = $commande_product->quantity;
        $commande->product[] = $product;
        $commande->total += $product->price;
      }
      $response = $response->withJson($commande);
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
   * @apiParam {Array} product Tableau contenant les produits : l'id du produit et la quantité.
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
      $commande_products = $request->getParsedBody()['product'];
      //on creer la commande
      $id_commande = Capsule::table('COMMAND')->insertGetId([
         'login' => $request->getParsedBody()['login'],
         'state' => $request->getParsedBody()['state'],
         'periode_debut' => $request->getParsedBody()['periode_debut'],
         'periode_fin' => $request->getParsedBody()['periode_fin']
        ],'id_commande');

      //on lui ajoute les produits
      foreach ( $commande_products as $key => $commande_product) {
        Capsule::table('PRODUCT_COMMAND')->insert([
         'quantity' => $commande_product['quantity'],
         'id_product' => $commande_product['id_product'],
         'id_commande' => $id_commande
        ]);
      }
      
      //on lui envoie la notification
      Capsule::table('NOTIFICATION')->insert([
         'login' => $request->getParsedBody()['login'],
         'method' => 2,
         'id_command' => $id_commande,
         'notification' => 'Votre commande a été crée'
      ]);

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
   * @apiParam {Array} product Tableau contenant les produits : l'id du produit et la quantité.
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
      $commande_products = $request->getParsedBody()['product'];
      //on update la commande
      Capsule::table('COMMAND')->where('id_commande',$id_commande)->update([
         'login' => $request->getParsedBody()['login'],
         'state' => $request->getParsedBody()['state'],
         'periode_debut' => $request->getParsedBody()['periode_debut'],
         'periode_fin' => $request->getParsedBody()['periode_fin'],
         'id_commande'=> $id_commande
        ]);

      //on lui ajoute les produits
      Capsule::table('PRODUCT_COMMAND')->where('id_commande',$id_commande)->delete();
      foreach ( $commande_products as $key => $commande_product) {
        Capsule::table('PRODUCT_COMMAND')->insert([
         'quantity' => $commande_product['quantity'],
         'id_product' => $commande_product['id_product'],
         'id_commande' => $id_commande
        ]);
      }
      
      $response = $response->withJson(array ("status"  => array("success" => "ok")), 200);
    } catch(Illuminate\Database\QueryException $e) {
      $response = $response->withJson(array ("status"  => array("error" => $e->getMessage())), 400);
    }
    return $response;
  });
  
  /**
   * @api {put} /command/:id_commande/state/:id_state Modification d'état d'une commande.
   * @apiName PutCommandByIdAndState
   * @apiGroup Command
   *
   * @apiParam {Number} id_commande ID de la commande.
   * @apiParam {Number} id_state ID de l'état de la commande.

   * @apiParam {String} login Login de la commande de l'utilisateur.
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
  $app->put('/{id_commande}/state/{id_state}', function ($request, $response, $id_commande, $id_state) use ($app){
    try {
      $commande_products = $request->getParsedBody()['product'];
      //on update la commande
      Capsule::table('COMMAND')->where('id_commande',$id_commande)->update([
         'login' => $request->getParsedBody()['login'],
         'state' => $id_state,
         'id_commande'=> $id_commande
        ]);
  
      //on lui envoie la notification
      if($id_state == 1) $notification = "ok";
      elseif($id_state == 2) $notification = "pas ok";
      else $notification = "ko";
      
      Capsule::table('NOTIFICATION')->insert([
         'login' => $request->getParsedBody()['login'],
         'method' => 2,
         'id_command' => $id_commande,
         'notification' => $notification
      ]);
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
      Capsule::table('COMMAND')->where('id_commande',$id_commande)->update(['state' => 0]);
      $response = $response->withJson(array ("status"  => array("success" => "ok")), 200);
    } catch(Illuminate\Database\QueryException $e) {
      $response = $response->withJson(array ("status"  => array("error" => $e->getMessage())), 400);
    }
    return $response;
  });
});
