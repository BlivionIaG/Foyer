<?php

use Illuminate\Database\Capsule\Manager as Capsule;

$app->group('/notification', function() use ($app) {

  /**
   * @api {get} /notification/ Récupération des notifications.
   * @apiName GetNotifications
   * @apiGroup Notification
   *
   * @apiSuccess {Number} id_notification ID de la notification.
   * @apiSuccess {Number} id_commande ID de la commande.
   * @apiSuccess {String} notification Message de la notification.
   * @apiSuccess {String} login Login de la personne qui va recevoir la notification.
   * @apiSuccess {Date} date Date de création de la notification.
   * @apiSuccess {Number} method Méthode d'envoi de la notification.

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
      $response = $response->withJson(Capsule::table('NOTIFICATION')->get());
    } catch(Illuminate\Database\QueryException $e) {
      $response = $response->withJson(array ("status"  => array("error" => $e->getMessage())), 400);
    }
    return $response;
  });

  /**
   * @api {get} /notification/id_notification/:id_notification Récupération d'un notification par son ID.
   * @apiName GetNotificationById
   * @apiGroup Notification
   *
   * @apiSuccess {Number} id_notification ID de la notification.
   * @apiSuccess {Number} id_commande ID de la commande.
   * @apiSuccess {String} notification Message de la notification.
   * @apiSuccess {String} login Login de la personne qui va recevoir la notification.
   * @apiSuccess {Date} date Date de création de la notification.
   * @apiSuccess {Number} method Méthode d'envoi de la notification.
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
  $app->get('/id_notification/{id_notification}', function($request, $response, $id_notification){
    try {
      $response = $response->withJson(Capsule::table('NOTIFICATION')->where('id_notification', $id_notification)->first());
    } catch(Illuminate\Database\QueryException $e) {
      $response = $response->withJson(array ("status"  => array("error" => $e->getMessage())), 400);
    }
    return $response;
  });

  /**
   * @api {get} /notification/login/:login Récupération des notifications par login (broadcast compris).
   * @apiName GetNotificationByLogin
   * @apiGroup Notification
   *
   * @apiSuccess {Number} id_notification ID de la notification.
   * @apiSuccess {Number} id_commande ID de la commande.
   * @apiSuccess {String} notification Message de la notification.
   * @apiSuccess {String} login Login de la personne qui va recevoir la notification.
   * @apiSuccess {Date} date Date de création de la notification.
   * @apiSuccess {Number} method Méthode d'envoi de la notification.
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
      $response = $response->withJson(Capsule::table('NOTIFICATION')->where('login', $login)->orWhere('id_command', -1)->get());
    } catch(Illuminate\Database\QueryException $e) {
      $response = $response->withJson(array ("status"  => array("error" => $e->getMessage())), 400);
    }
    return $response;
  });

  /**
   * @api {post} /notification/ Ajout d'une notification.
   * @apiName PostNotification
   * @apiGroup Notification
   *
   * @apiSuccess {Number} id_commande ID de la commande.
   * @apiSuccess {String} notification Message de la notification.
   * @apiSuccess {String} login Login de la personne qui va recevoir la notification.
   * @apiSuccess {Number} method Méthode d'envoi de la notification.
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
      //Si broadcast
      if($request->getParsedBody()['method'] == 1){
        $logins = Capsule::table('USER')->get();
        foreach ($logins as $key => $login) {
          Capsule::table('NOTIFICATION')->insert([
           'notification' => $request->getParsedBody()['notification'],
           'login' => $login->login,
           'method' => 1
          ]);
        }
      }
      //Si normal
      else{
        Capsule::table('NOTIFICATION')->insert($request->getParsedBody());
      }
      $response = $response->withJson(array ("status"  => array("success" => "ok")), 200);
    } catch(Illuminate\Database\QueryException $e) {
      $response = $response->withJson(array ("status"  => array("error" => $e->getMessage())), 400);
    }
    return $response;
  });

  /**
   * @api {put} /notification/:id_notification Modification d'une notification.
   * @apiName PutNotification
   * @apiGroup Notification
   *
   * @apiParam {Number} id_notification ID de la notification.
   *
   * @apiSuccess {Number} id_commande ID de la commande.
   * @apiSuccess {String} notification Message de la notification.
   * @apiSuccess {String} login Login de la personne qui va recevoir la notification.
   * @apiSuccess {Number} method Méthode d'envoi de la notification.
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
  $app->put('/{id_notification}', function ($request, $response, $id_notification) use ($app){
    try {
      Capsule::table('NOTIFICATION')->where('id_notification',id_notification)->update($request->getParsedBody());
      $response = $response->withJson(array ("status"  => array("success" => "ok")), 200);
    } catch(Illuminate\Database\QueryException $e) {
      $response = $response->withJson(array ("status"  => array("error" => $e->getMessage())), 400);
    }
    return $response;
  });

  /**
   * @api {delete} /notification/:id_notification Suppression d'une notification.
   * @apiName DeleteNotification
   * @apiGroup Notification
   *
   * @apiParam {Number} id_notification ID de la notification.
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
  $app->delete('/{id_notification}',function ($request, $response, $id_notification) {
    try {
      Capsule::table('NOTIFICATION')->where('id_notification',$id_notification)->delete();
      $response = $response->withJson(array ("status"  => array("success" => "ok")), 200);
    } catch(Illuminate\Database\QueryException $e) {
      $response = $response->withJson(array ("status"  => array("error" => $e->getMessage())), 400);
    }
    return $response;
  });

  /**
   * @api {delete} /notification/method/:id_method Suppression de notifications en fonction de la methode.
   * @apiName DeleteNotificationByMethod
   * @apiGroup Notification
   *
   * @apiParam {Number} id_method ID methode.
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
  $app->delete('/method/{id_method}',function ($request, $response, $id_method) {
    try {
      Capsule::table('NOTIFICATION')->where('method',$id_method)->delete();
      $response = $response->withJson(array ("status"  => array("success" => "ok")), 200);
    } catch(Illuminate\Database\QueryException $e) {
      $response = $response->withJson(array ("status"  => array("error" => $e->getMessage())), 400);
    }
    return $response;
  });

  /**
   * @api {delete} /notification/login/:login Suppression des notifications d'un utilisateur.
   * @apiName DeleteNotificationByLogin
   * @apiGroup Notification
   *
   * @apiParam {String} login ID login.
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
  $app->delete('/login/{login}',function ($request, $response, $login) {
    try {
      Capsule::table('NOTIFICATION')->where('login',$login)->delete();
      $response = $response->withJson(array ("status"  => array("success" => "ok")), 200);
    } catch(Illuminate\Database\QueryException $e) {
      $response = $response->withJson(array ("status"  => array("error" => $e->getMessage())), 400);
    }
    return $response;
  });
});