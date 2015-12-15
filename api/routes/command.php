<?php

use Illuminate\Database\Capsule\Manager as Capsule;

$app->group('/command', function() use ($app) {

  //get all command
  $app->get('/', function($request, $response) {
    try {
      $response = $response->withJson(Capsule::table('COMMAND')->get());
    } catch(Illuminate\Database\QueryException $e) {
      $response = $response->withJson(array ("status"  => array("error" => $e->getMessage())), 400);
    }
    return $response;
  });

  //get by id_command
  $app->get('/id_command/{id_command}', function($request, $response, $id_command){
    try {
      $response = $response->withJson(Capsule::table('COMMAND')->where('id_command', $id_command)->first());
    } catch(Illuminate\Database\QueryException $e) {
      $response = $response->withJson(array ("status"  => array("error" => $e->getMessage())), 400);
    }
    return $response;
  });

  //add command
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

  //edit command
  $app->put('/{id_command}', function ($request, $response, $id_command) use ($app){
    try {
      Capsule::table('PRODUCT')->where('id_command',$id_command)->update($request->getParsedBody());
      $response = $response->withJson(array ("status"  => array("success" => "ok")), 200);
    } catch(Illuminate\Database\QueryException $e) {
      $response = $response->withJson(array ("status"  => array("error" => $e->getMessage())), 400);
    }
    return $response;
  });

  //delete command
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