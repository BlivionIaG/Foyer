<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use GuzzleHttp\Client as Client;
use GuzzleHttp\Psr7\Request;

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
* @apiSuccess {String} login Login de connexion.
* @apiSuccess {String} key Code Basic Auth.
*
* @apiSuccessExample Success-Response:
*     HTTP/1.1 200 OK
*
*/
$app->get('/login/', function($request, $response) {
  session_start();
  if(isset($_SESSION['uid'])){
    $API_USER = json_decode(API_USER);
    return $response->withJson(array ("login" => $_SESSION['login'], "key" => $API_USER[0]->password), 200);
  }
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
* @api {post} /login/ Connexion à l'interface admin. Sécuriser Admin.
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
* @api {post} /banniere/ Modifier la bannière mobile. Sécuriser Admin.
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

/**
* @api {post} /cas/ Connexion au cas.
* @apiName PostConnexionCas
* @apiGroup Others
*
* @apiParam {String} username Login CAS.
* @apiParam {String} password Mot de passe CAS.
*
* @apiSuccess {String} key Code Basic Auth.
*
* @apiSuccessExample Success-Response:
*     HTTP/1.1 200 OK
*
*/
$app->post('/cas/', function($request, $response) use ($app){

  //Utilisation de http://guzzle.readthedocs.org/en/latest/
  $client = new Client();
  try{
    //On récupère la page avec 6s de timeout
    $responseGet = $client->request('GET', 'https://web.isen-bretagne.fr/cas/login?service=https://web.isen-bretagne.fr/uPortal/Login', ['connect_timeout' => 15]);

    //récupération du champ lt
    preg_match('/name="lt" value="([a-zA-Z0-9-_]+)"/', $responseGet->getBody(), $lt, PREG_OFFSET_CAPTURE);
    $lt = $lt[1][0];
    //récupération du cookie JSESSIONID
    preg_match('/JSESSIONID=([A-Z0-9]+)/', $responseGet->getHeader('Set-Cookie')[0], $cookie, PREG_OFFSET_CAPTURE);
    $cookie = $cookie[1][0];

    //On envoi le form
    $responsePost = $client->request('POST', 'https://web.isen-bretagne.fr/cas/login;jsessionid='.$cookie.'?service=https://web.isen-bretagne.fr/uPortal/Login',[
      'form_params' => [
        'lt' => $lt,
        'username' => $request->getParsedBody()['username'],
        'password' => $request->getParsedBody()['password'],
        '_eventId' => 'submit'
      ],
      //pas de redirection
      'allow_redirects' => false
    ]);

    //Bon mot de passe = 302
    if($responsePost->getStatusCode() == 302){
      $API_USER = json_decode(API_USER);
      $response = $response->withJson(array ("status"  => array("user" => "ksidor18", "key" => $API_USER[1]->password)), 200);
    }else{
      $response = $response->withJson(array ("status"  => array("error" => "Mauvais identifiants")), 400);
    }
  }catch(\Exception $e){
    $response = $response->withJson(array ("status"  => array("error" => "Erreur de connexion avec le CAS")), 400);
  }
  return $response;
});