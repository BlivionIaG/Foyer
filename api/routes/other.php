<?php

use Illuminate\Database\Capsule\Manager as Capsule;

/**
* @api {get} /date/ Obtention de la date du serveur.
* @apiName GetDate
* @apiGroup Others
*
* @apiSuccess {Date} date Date du serveur, "d m Y H:i".
*
* @apiSuccessExample Success-Response:
*     HTTP/1.1 200 OK
*
*/
$app->get('/date/', function($request, $response) {
  date_default_timezone_set('Europe/Paris');
  return $response->withJson(array ("date"  => date("d m Y H:i")), 400);
});

/**
* @api {get} /login/ Check la connexion à l'interface admin.
* @apiName GetCheckConnexion
* @apiGroup Others
*
* @apiSuccess {String} etat Etat de connexion.
* @apiSuccess {String} login Login de connexion.
* @apiSuccess {String} uid ID de connexion.
*
* @apiSuccessExample Success-Response:
*     HTTP/1.1 200 OK
*
*/
$app->get('/login/', function($request, $response) {
  session_start();
  if(isset($_SESSION['uid']))
    return $response->withJson(array ("status" => "ok", "login" => $_SESSION['login'], "uid" => $_SESSION['uid']), 200);
  else
    return $response->withJson(array ("status"  => array("error" => "ok")), 400);
});

/**
* @api {get} /logout/ Déconnexion de l'interface admin.
* @apiName GetDeconnexion
* @apiGroup Others
*
* @apiSuccess {String} etat Etat de la déconnexion.
*
* @apiSuccessExample Success-Response:
*     HTTP/1.1 200 OK
*
*/
$app->get('/logout/', function($request, $response) {
  session_start();
  if(!session_unset())
    return $response->withJson(array ("status"  => array("succes" => "ok")), 200);
  else
    return $response->withJson(array ("status"  => array("error" => "ok")), 400);
});

/**
* @api {post} /login/ Connexion à l'interface admin.
* @apiName PostConnexion
* @apiGroup Others
*
* @apiParam {String} login Login club.
* @apiParam {String} password Mot de passe.
*
* @apiSuccess {String} etat Etat de connexion.
*
* @apiSuccessExample Success-Response:
*     HTTP/1.1 200 OK
*
*/
$app->post('/login/',function ($request, $response)  use ($app) {
  try {
    if(Capsule::table('USER_CLUB')->where($request->getParsedBody())->first()){
      session_start();
      $_SESSION['uid'] = uniqid();
      $_SESSION['login'] = $request->getParsedBody()['login'];
      $response = $response->withJson(array ("status"  => array("succes" => uniqid())), 200);
    }
    else
      $response = $response->withJson(array ("status"  => array("error" => "false")), 400);
  } catch(Illuminate\Database\QueryException $e) {
    $response = $response->withJson(array ("status"  => array("error" => $e->getMessage())), 400);
  }
  return $response;
});

/**
* @api {get} /banniere/ Récupération de l'rul de la bannière.
* @apiName GetBanniere
* @apiGroup Others
* 
* @apiSuccess {String} etat Url de la bannière dans /files/mobile/.
*
* @apiSuccessExample Success-Response:
*     HTTP/1.1 200 OK
*
*/
$app->get('/banniere/', function($request, $response) {
  if($filename = glob(DIR_FILES.'/mobile/banniere_mobile.*', GLOB_NOESCAPE  ))
    return $response->withJson(array ("url"  =>  basename($filename[0])), 200);
  else
    return $response->withJson(array ("url"  =>  "Not Found"), 400);
});

/**
* @api {post} /banniere/ Modifier la bannière mobile.
* @apiName PostBanniere
* @apiGroup Others
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
$app->post('/banniere/',function ($request, $response)  use ($app) {
  if(isset($_FILES['file']))
    if($_FILES['file']['name'])
      if(!$_FILES['file']['error']){
        $extensions_valides = array( 'jpg' , 'jpeg' , 'gif' , 'png' );
        $extension_upload = strtolower( substr( strrchr($_FILES['file']['name'], '.') ,1) );
        if(in_array($extension_upload,$extensions_valides))
          if(is_dir(DIR_FILES.'mobile/') && is_writable(DIR_FILES.'mobile/')){
            foreach(glob(DIR_FILES.'/mobile/banniere_mobile.*', GLOB_NOESCAPE) as $file_banniere) unlink(DIR_FILES.'mobile/'.basename($file_banniere));
            if(move_uploaded_file($_FILES['file']['tmp_name'], DIR_FILES.'mobile/banniere_mobile.'.$extension_upload))
              $response = $response->withJson(array ("status"  => array("success" => "fichier upload")), 200);
            else $response = $response->withJson(array ("status"  => array("error" => DIR_FILES."impossible d'uploader le fichier")), 400);
          }else $response = $response->withJson(array ("status"  => array("error" => "product/ impossible d'uploader dans ce dossier")), 240);
        else $response = $response->withJson(array ("status"  => array("error" => "mauvaise extension")), 400);
     }else $response = $response->withJson(array ("status"  => array("error" => $_FILES)), 400);
    else $response = $response->withJson(array ("status"  => array("error" => "erreur avec le fichier")), 400);
  else $response = $response->withJson(array ("status"  => array("error" => "aucun fichier uploader")), 400);
  return $response;
});

