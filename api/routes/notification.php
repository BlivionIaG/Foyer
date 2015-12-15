<?php

use Illuminate\Database\Capsule\Manager as Capsule;

$app->group('/notification', function() use ($app) {

  //get all notification
  $app->get('/', function($request, $response) {
    try {
      $response = $response->withJson(Capsule::table('NOTIFICATION')->get());
    } catch(Illuminate\Database\QueryException $e) {
      $response = $response->withJson(array ("status"  => array("error" => $e->getMessage())), 400);
    }
    return $response;
  });

  //get by id_notification
  $app->get('/id_notification/{id_notification}', function($request, $response, $id_notification){
    try {
      $response = $response->withJson(Capsule::table('NOTIFICATION')->where('id_notification', id_notification)->first());
    } catch(Illuminate\Database\QueryException $e) {
      $response = $response->withJson(array ("status"  => array("error" => $e->getMessage())), 400);
    }
    return $response;
  });

  //add notification
  $app->post('/',function ($request, $response)  use ($app) {
    try {
      Capsule::table('NOTIFICATION')->insert($request->getParsedBody());
      $response = $response->withJson(array ("status"  => array("success" => "ok")), 200);
    } catch(Illuminate\Database\QueryException $e) {
      $response = $response->withJson(array ("status"  => array("error" => $e->getMessage())), 400);
    }
    return $response;
  });

  //edit notification
  $app->put('/{id_notification}', function ($request, $response, $id_notification) use ($app){
    try {
      Capsule::table('NOTIFICATION')->where('id_notification',id_notification)->update($request->getParsedBody());
      $response = $response->withJson(array ("status"  => array("success" => "ok")), 200);
    } catch(Illuminate\Database\QueryException $e) {
      $response = $response->withJson(array ("status"  => array("error" => $e->getMessage())), 400);
    }
    return $response;
  });

  //delete notification
  $app->delete('/{id_notification}',function ($request, $response, $id_notification) {
    try {
      Capsule::table('NOTIFICATION')->where('id_notification',$id_notification)->delete();
      $response = $response->withJson(array ("status"  => array("success" => "ok")), 200);
    } catch(Illuminate\Database\QueryException $e) {
      $response = $response->withJson(array ("status"  => array("error" => $e->getMessage())), 400);
    }
    return $response;
  });
});