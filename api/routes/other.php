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
* @api {get} /banniere/ Récupération de l'url de la bannière.
* @apiName GetBanniere
* @apiGroup Others
*
* @apiSuccess {String} url Url de la bannière dans /files/mobile/.
*
* @apiSuccessExample Success-Response:
*     HTTP/1.1 200 OK
*
*/
$app->get('/banniere/', function($request, $response) {
  if($filename = glob(DIR_FILES.'/mobile/banniere_mobile.*', GLOB_NOESCAPE  ))
    return $response->withJson(array ("url"  => basename($filename[0])), 200);
  else
    return $response->withJson(array ("url"  =>  "Not Found"), 400);
});

/**
* @api {post} /banniere/ Modifier la bannière mobile.
* @apiDescription Sécuriser Admin.
* @apiName PostBanniere
* @apiGroup Others
*
* @apiParam {File} file Image de la bannière.
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
  if(isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['HTTP_AUTHORIZATION'])){
    $user = checkAuth($_SERVER['PHP_AUTH_USER'], $_SERVER['HTTP_AUTHORIZATION']);
    if($user && $user->access == 1){
      if(isset($_FILES['file']))
        if($_FILES['file']['name'])
          if(!$_FILES['file']['error']){
            $extensions_valides = array( 'jpg' , 'jpeg' , 'png', 'JPG' , 'JPEG' , 'PNG' );
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
    }else{
      $response = $response->withJson(array ("status"  => array("error" => "connexion")), 400);
    }
  }else{
    $response = $response->withJson(array ("status"  => array("error" => "connexion")), 400);
  }
  return $response;
});