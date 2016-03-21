<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Symfony\Component\Yaml\Parser;

$app->group('/command', function() use ($app) {

  /**
   * @api {get} /command/ Récupération des commandes.
   * @apiDescription Sécuriser Mobile Admin.
   * @apiName GetCommands
   * @apiGroup Command
   *
   * @apiSuccess {Number} id_command ID de la commande.
   * @apiSuccess {String} login Login de la commande de l'utilisateur.
   * @apiSuccess {Number} state Etat de la commande.
   * @apiSuccess {Date} time Date de la prise de commande.
   * @apiSuccess {Date} date Date de la commande.
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
      $commandes = Capsule::table('COMMAND')->orderBy('date', 'desc')->get();
      $commande_products = Capsule::table('PRODUCT_COMMAND')->get();
      foreach ($commandes as $key_commandes => $commande) {
        $commandes[$key_commandes]->product = "";
        $commandes[$key_commandes]->total = 0;
        foreach ($commande_products as $key_commande_products => $commande_product) {
          if($commande_product->id_command == $commande->id_command){
            $product = Capsule::table('PRODUCT')->where('id_product',$commande_product->id_product)->first();
            $product->quantity = $commande_product->quantity;
            $commandes[$key_commandes]->total += $product->price * $commande_product->quantity;
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
   * @apiDescription Sécuriser Mobile Admin.
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
   * @api {get} /command/id_command/:id_command Récupération d'un commande par son ID.
   * @apiDescription Sécuriser Mobile Admin.
   * @apiName GetCommandsByIdCommand
   * @apiGroup Command
   *
   * @apiParam {Number} id Commande unique ID.
   *
   * @apiSuccess {Number} id_command ID du commande.
   * @apiSuccess {String} login Login de la commande de l'utilisateur.
   * @apiSuccess {Number} state Etat de la commande.
   * @apiSuccess {Date} time Date de la prise de commande.
   * @apiSuccess {Date} date Date de la commande.
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
  $app->get('/id_command/{id_command}', function($request, $response, $id_command){
    try {
      $commande = Capsule::table('COMMAND')->where('id_command', $id_command)->first();
      $commande_products = Capsule::table('PRODUCT_COMMAND')->where('id_command', $id_command)->get();
      $commande->product = "";
      $commande->total = 0;
      foreach ($commande_products as $key_commande_products => $commande_product) {
        $product = Capsule::table('PRODUCT')->where('id_product',$commande_product->id_product)->first();
        $product->quantity = $commande_product->quantity;
        $commande->product[] = $product;
        $commande->total += $product->price * $commande_product->quantity;
      }
      $response = $response->withJson($commande);
    } catch(Illuminate\Database\QueryException $e) {
      $response = $response->withJson(array ("status"  => array("error" => $e->getMessage())), 400);
    }
    return $response;
  });

  /**
   * @api {get} /command/login/:login Récupération d'un commande par son login.
   * @apiDescription Sécuriser Mobile Admin.
   * @apiName GetCommandsByLogin
   * @apiGroup Command
   *
   * @apiParam {String} user Login du user.
   *
   * @apiSuccess {Number} id_command ID du commande.
   * @apiSuccess {String} login Login de la commande de l'utilisateur.
   * @apiSuccess {Number} state Etat de la commande.
   * @apiSuccess {Date} time Date de la prise de commande.
   * @apiSuccess {Date} date Date de la commande.
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
  $app->get('/login/{login}', function($request, $response, $login){
    try {
      $commandes = Capsule::table('COMMAND')->where('login', $login)->orderBy('date', 'desc')->get();
      $commande_products = Capsule::table('PRODUCT_COMMAND')->get();
      foreach ($commandes as $key_commandes => $commande) {
        $commandes[$key_commandes]->product = "";
        $commandes[$key_commandes]->total = 0;
        foreach ($commande_products as $key_commande_products => $commande_product) {
          if($commande_product->id_command == $commande->id_command){
            $product = Capsule::table('PRODUCT')->where('id_product',$commande_product->id_product)->first();
            $product->quantity = $commande_product->quantity;
            $commandes[$key_commandes]->total += $product->price * $commande_product->quantity;
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
   * @api {get} /command/state/:state Récupération des commandes en fonction de son état.
   * @apiDescription Sécuriser Mobile Admin.
   * @apiName GetCommandsByState
   * @apiGroup Command
   *
   * @apiParam {Number} state Etat de la commande.
   *
   * @apiSuccess {Number} id_command ID du commande.
   * @apiSuccess {String} login Login de la commande de l'utilisateur.
   * @apiSuccess {Number} state Etat de la commande.
   * @apiSuccess {Date} time Date de la prise de commande.
   * @apiSuccess {Date} date Date de la commande.
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
   * @apiDescription Sécuriser Mobile Admin.
   * @apiName PostCommand
   * @apiGroup Command
   *
   * @apiParam {String} login Login de la commande de l'utilisateur.
   * @apiParam {Number} state Etat de la commande.
   * @apiParam {String} periode_debut Heure de début de la commande.
   * @apiParam {String} periode_fin Heure de fin de la commande.
   * @apiParam {Date} date Date de la commande.
   * @apiParam {Array} product Tableau contenant les produits : l'id du produit et la quantité en object.
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
      $date = new DateTime($request->getParsedBody()['date']);
      $id_command = Capsule::table('COMMAND')->insertGetId([
       'login' => $request->getParsedBody()['login'],
       'state' => $request->getParsedBody()['state'],
       'periode_debut' => $request->getParsedBody()['periode_debut'],
       'periode_fin' => $request->getParsedBody()['periode_fin'],
       'date' => $date->format('Y-m-d')
       ],'id_command');

      //si on recoit un string plutot qu'un objet
      if(is_string($request->getParsedBody()['product'])) $products = json_decode($request->getParsedBody()['product']);
      else $products = $request->getParsedBody()['product'];
      //on lui ajoute les produits
      foreach($products as $key => $commande_product) {
        //on passe le tableau en objet
        if (!is_object($commande_product)) {
          $commande_product_old = $commande_product;
          $commande_product = new stdClass();
          foreach ($commande_product_old as $key => $value)
            $commande_product->$key = $value;
        }
        Capsule::table('PRODUCT_COMMAND')->insert([
         'quantity' =>  $commande_product->quantity,
         'id_product' => $commande_product->id_product,
         'id_command' => $id_command
         ]);
      }
      //On récupère les messages des notifications
      $yaml = new Parser();
      $config = $yaml->parse(file_get_contents('config/config.yml'));
      //on lui envoie la notification
      if($request->getParsedBody()['state'] == 1) $notification = $config['parameters']['notification']['command']['state_1'];
      elseif($request->getParsedBody()['state'] == 2) $notification = $config['parameters']['notification']['command']['state_2'];
      elseif($request->getParsedBody()['state'] == 3) $notification = $config['parameters']['notification']['command']['state_3'];
      else $notification = $config['parameters']['notification']['command']['state_0'];

      Capsule::table('NOTIFICATION')->insert([
       'login' => $request->getParsedBody()['login'],
       'method' => 0,
       'id_command' => $id_command,
       'notification' => $notification
       ]);

      $response = $response->withJson(array ("status"  => array("ok" => "success")), 200);
    } catch(Illuminate\Database\QueryException $e) {
      $response = $response->withJson(array ("status"  => array("error" => $e )), 400);
    }
    return $response;
  });

  /**
   * @api {put} /command/:id_command Modification d'une commande.
   * @apiDescription Sécuriser Mobile Admin.
   * @apiName PutCommand
   * @apiGroup Command
   *
   * @apiParam {Number} id_command ID de la commande.
   *
   * @apiParam {String} login Login de la commande de l'utilisateur.
   * @apiParam {Number} state Etat de la commande.
   * @apiParam {String} periode_debut Heure de début de la commande.
   * @apiParam {String} periode_fin Heure de fin de la commande.
   * @apiParam {Date} date Date de la commande.
   * @apiParam {Array} product Tableau contenant les produits : id_product et quantity, en objet ou dans un tableau.
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
  $app->put('/{id_command}', function ($request, $response, $id_command) use ($app){
    try {
          //on update la commande
      $date = new DateTime($request->getParsedBody()['date']);
      Capsule::table('COMMAND')->where('id_command',$id_command)->update([
       'login' => $request->getParsedBody()['login'],
       'state' => $request->getParsedBody()['state'],
       'periode_debut' => $request->getParsedBody()['periode_debut'],
       'periode_fin' => $request->getParsedBody()['periode_fin'],
       'date' => $date->format('Y-m-d')
       ]);

      //si on recoit un string plutot qu'un objet
      if(is_string($request->getParsedBody()['product'])) $products = json_decode($request->getParsedBody()['product']);
      else $products = $request->getParsedBody()['product'];
      //On supprime tout les anciens produits
      Capsule::table('PRODUCT_COMMAND')->where('id_command',$id_command)->delete();
      //on lui ajoute les produits
      foreach ( $products as $key => $commande_product) {
        //on passe le tableau en objet
        if (!is_object($commande_product)) {
          $commande_product_old = $commande_product;
          $commande_product = new stdClass();
          foreach ($commande_product_old as $key => $value)
            $commande_product->$key = $value;
        }
        Capsule::table('PRODUCT_COMMAND')->insert([
          'quantity' => $commande_product->quantity,
          'id_product' => $commande_product->id_product,
          'id_command' => $request->getParsedBody()['id_command']
          ]);
      }

      //On récupère les messages des notifications
      $yaml = new Parser();
      $config = $yaml->parse(file_get_contents('config/config.yml'));
      //on lui envoie la notification
      if($request->getParsedBody()['state'] == 1) $notification = $config['parameters']['notification']['command']['state_1'];
      elseif($request->getParsedBody()['state'] == 2) $notification = $config['parameters']['notification']['command']['state_2'];
      elseif($request->getParsedBody()['state'] == 3) $notification = $config['parameters']['notification']['command']['state_3'];
      else $notification = $config['parameters']['notification']['command']['state_0'];

      Capsule::table('NOTIFICATION')->insert([
       'login' => $request->getParsedBody()['login'],
       'method' => 0,
       'id_command' => $request->getParsedBody()['id_command'],
       'notification' => $notification
       ]);

      $response = $response->withJson(array ("status"  => array("success" => "ok")), 200);
    } catch(Illuminate\Database\QueryException $e) {
      $response = $response->withJson(array ("status"  => array("error" => $e->getMessage())), 400);
    }
    return $response;
  });

  /**
   * @api {put} /command/:id_command/state/:id_state Modification d'état d'une commande.
   * @apiDescription Sécuriser Mobile Admin.
   * @apiName PutCommandByIdAndState
   * @apiGroup Command
   *
   * @apiParam {Number} id_command ID de la commande.
   * @apiParam {Number} id_state ID de l'état de la commande.
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
  $app->put('/{id_command}/state/{id_state}', function ($request, $response, $values) use ($app){
    try {
      $login = Capsule::table('COMMAND')->where('id_command',$values['id_command'])->value('login');
      //on update la commande
      Capsule::table('COMMAND')->where('id_command', $values['id_command'])->update([
       'state' => $values['id_state']
       ]);

      //On récupère les messages des notifications
      $yaml = new Parser();
      $config = $yaml->parse(file_get_contents('config/config.yml'));
      //on lui envoie la notification
      if($values['id_state'] == 1) $notification = $config['parameters']['notification']['command']['state_1'];
      elseif($values['id_state'] == 2) $notification = $config['parameters']['notification']['command']['state_2'];
      elseif($values['id_state'] == 3) $notification = $config['parameters']['notification']['command']['state_3'];
      else $notification = $config['parameters']['notification']['command']['state_0'];

      Capsule::table('NOTIFICATION')->insert([
       'login' => $login,
       'method' => 0,
       'id_command' => $values['id_command'],
       'notification' => $notification
       ]);

      $response = $response->withJson(array ("status"  => array("success" => "ok")), 200);
    } catch(Illuminate\Database\QueryException $e) {
      $response = $response->withJson(array ("status"  => array("error" => $e->getMessage())), 400);
    }
    return $response;
  });

  /**
   * @api {delete} /command/:id_command Suppression d'une commande.
   * @apiDescription Sécuriser Mobile Admin.
   * @apiName DeleteCommand
   * @apiGroup Command
   *
   * @apiParam {Number} id_command ID de la commande.
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
      Capsule::table('COMMAND')->where('id_command',$id_command)->update(['state' => 0]);
      //on lui envoie la notification
      $login = Capsule::table('COMMAND')->where('id_command',$id_command)->value('login');
      
      //On récupère les messages des notifications
      $yaml = new Parser();
      $config = $yaml->parse(file_get_contents('config/config.yml'));

      Capsule::table('NOTIFICATION')->insert([
       'login' => $login,
       'method' => 0,
       'id_command' => $id_command,
       'notification' => $config['parameters']['notification']['command']['state_0']
       ]);
      $response = $response->withJson(array ("status"  => array("success" => "ok")), 200);
    } catch(Illuminate\Database\QueryException $e) {
      $response = $response->withJson(array ("status"  => array("error" => $e->getMessage())), 400);
    }
    return $response;
  });
});
