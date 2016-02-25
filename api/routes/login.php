<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use GuzzleHttp\Client as Client;
use GuzzleHttp\Psr7\Request;

/**
* @api {get} /login/ Check la connexion à l'interface admin.
* @apiName GetCheckConnexion
* @apiGroup Login
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
* @api {post} /login/ Connexion à l'interface admin.
* @apiName PostConnexion
* @apiGroup Login
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
* @api {post} /cas/ Connexion au CAS (timeout de 15s).
* @apiName PostConnexionCAS
* @apiGroup Login
*
* @apiParam {String} username Login CAS.
* @apiParam {String} password Mot de passe CAS.
*
* @apiSuccess {String} username De connexion au CAS.
* @apiSuccess {String} key Code Basic Auth.
*
* @apiError {String} Message d'erreur.
*
* @apiSuccessExample Success-Response:
*     HTTP/1.1 200 OK
*
* @apiErrorExample Error-Response:
*     HTTP/1.1 401 Mauvais identifiants
*
* @apiErrorExample Error-Response:
*     HTTP/1.1 400 Erreur de connexion avec le CAS
*/
$app->post('/cas/', function($request, $response) use ($app){
  try{
    $client = new Client();
    //On récupère la page avec 15s de timeout
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
      //pas de redirection pour garder le code 302
      'allow_redirects' => false
    ]);

    //Bon mot de passe = 302
    if($responsePost->getStatusCode() == 302){
      $API_USER = json_decode(API_USER);
      //On regarde si l'user existe déjà sinon on l'ajoute à la db
      if(!Capsule::table('USER')->where('login', $request->getParsedBody()['username'])->first()){
        Capsule::table('USER')->insert(['login' => $request->getParsedBody()['username']]);
      }
      $response = $response->withJson(array ("status"  => array("username" => $request->getParsedBody()['username'], "key" => $API_USER[1]->password)), 200);
    }else{
      $response = $response->withJson(array ("status"  => array("error" => "Mauvais identifiants")), 401);
    }
  }catch(\Exception $e){
    $response = $response->withJson(array ("status"  => array("error" => "Erreur de connexion avec le CAS")), 400);
  }
  return $response;
});