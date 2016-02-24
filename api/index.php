<?php

require 'vendor/autoload.php';
require 'inc/config.php';
require 'inc/functions.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use GuzzleHttp\Client as Client;
use GuzzleHttp\Psr7\Request;

$capsule = new Capsule;
$capsule->addConnection(array(
	'driver'    => DB_SGBD,
	'host'      => DB_HOST,
	'database'  => DB_BASE,
	'username'  => DB_USER,
	'password'  => DB_PASSWORD,
	'charset'   => DB_CHARSET,
	'collation' => DB_COLLATION,
	'prefix'    => DB_PREFIX
));
$capsule->setAsGlobal();
$capsule->bootEloquent();

$app = new Slim\App();

//ajout des routes
require 'routes/product.php';
require 'routes/command.php';
require 'routes/notification.php';
require 'routes/user.php';
require 'routes/other.php';

//redirection vers la doc
$app->get('/', function($request, $response) use ($app){
	return $response->withRedirect('doc/');
});

$app->get('/cas/', function($request, $response) use ($app){

  //Utilisation de http://guzzle.readthedocs.org/en/latest/
  $client = new Client();

  //On récupère la page
  $responseGet = $client->request('GET', 'https://web.isen-bretagne.fr/cas/login?service=https://web.isen-bretagne.fr/uPortal/Login');

  if($responseGet->getStatusCode() == 200){
    //récupération du champ lt
    preg_match('/name="lt" value="([a-zA-Z0-9-_]+)"/', $responseGet->getBody(), $lt, PREG_OFFSET_CAPTURE);
    $lt = $lt[1][0];
    //récupération du cookie JSESSIONID
    preg_match('/JSESSIONID=([A-Z0-9]+)/', $responseGet->getHeader('Set-Cookie')[0], $cookie, PREG_OFFSET_CAPTURE);
    $cookie = $cookie[1][0];

    //Si on à bien récupéré le cookie et le lt
    if(isset($lt) && isset($cookie) && !empty($lt) && !empty($cookie)){
      //On envoi le form
      $responsePost = $client->request('POST', 'https://web.isen-bretagne.fr/cas/login;jsessionid='.$cookie.'?service=https://web.isen-bretagne.fr/uPortal/Login',[
        'form_params' => [
          'lt' => $lt,
          'username' => 'ksidor18',
          'password' => 's3curit3',
          '_eventId' => 'submit'
        ],
        'allow_redirects' => false
      ]);

      //Bon mot de passe = 302
      if($responsePost->getStatusCode() == 302){
        $response = $response->withJson(array ("status"  => array("ok" => "root")), 200);
      }else{
        $response = $response->withJson(array ("status"  => array("error" => "Mauvais identifiants")), 400);
      }
    }else{
      $response = $response->withJson(array ("status"  => array("error" => "Erreur lors de la récupération de la page de connexion au CAS")), 400);
    }
  }else{
    $response = $response->withJson(array ("status"  => array("error" => "Erreur de connexion avec le CAS")), 400);
  }

  return $response;
/*
  echo '<pre>';
  var_dump($responsePost->getStatusCode());
  var_dump($responsePost->getBody());
  var_dump($lt);
  var_dump($cookie);



  $ch = curl_init('https://web.isen-bretagne.fr/cas/login?service=https://web.isen-bretagne.fr/uPortal/Login');
  //récupération du body
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
  //Récupération du header pour le cookie
  curl_setopt($ch, CURLOPT_HEADER, true);

  //Execute the CURL session
  $result = curl_exec($ch);

  //récupération du cookie
  preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $result, $cookie);
  $cookie = $cookie[1][0];
  preg_match('/JSESSIONID=([A-Z0-9]+)/', $cookie, $cookie, PREG_OFFSET_CAPTURE);
  $cookie = $cookie[1][0];
  //récupération du lt
  preg_match('#name="lt" value="([a-zA-Z0-9-_]+)"#', substr($result,3), $lt, PREG_OFFSET_CAPTURE);
  $lt = $lt[1][0];

  //ajout des champs
  $fields = array(
    'lt' => $lt,
    'username' => 'ksidor18',
    'password' => 'S3curit3',
    '_eventId' => 'submit'
  );

  //url-ify the data for the POST
  $fields_string = null;
  foreach($fields as $key=>$value){
    $fields_string .= $key.'='.$value.'&';
  }
  rtrim($fields_string, '&');

  //set the url, number of POST vars, POST data
  curl_setopt($ch,CURLOPT_URL, 'https://web.isen-bretagne.fr/cas/login;jsessionid='.$cookie.'?service=https://web.isen-bretagne.fr/uPortal/Login');
  //ajout des posts
  curl_setopt($ch,CURLOPT_POST, count($fields));
  curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
  //récupération de la requete
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
  curl_setopt($ch, CURLOPT_HEADER, true);

  //execute post
  //!\ je dois surement fermer la connexion ici /!\
  $result = curl_exec($ch);

  echo '<pre>';
  var_dump($result);
  var_dump(curl_getinfo ($ch));

  //Fermeture de la session
  curl_close($ch);


/*
//url-ify the data for the POST
foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
rtrim($fields_string, '&');
//set the url, number of POST vars, POST data
curl_setopt($ch,CURLOPT_URL, $url);
curl_setopt($ch,CURLOPT_POST, count($fields));
curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

//execute post
$result = curl_exec($ch);


curl_close($ch);

  /*$url = 'https://web.isen-bretagne.fr/cas/login';
  //Récupération du lt
  preg_match('#name="lt" value="([a-zA-Z0-9-_]+)"#', substr(file_get_contents($url),3), $matches, PREG_OFFSET_CAPTURE);

  $data['lt'] = $matches[1][0];
  $data['username'] = 'ksidor18';
  $data['password'] = 'S3curit3';
  $data['_eventId'] = 'submit';

  //on créer la requete post
  $params = array(
    'http' => array(
      'method' => 'POST',
      'content' => $data
    ),
    'headers' => array(
      'test' => 'test'
    )
  );

var_dump($_COOKIE);
$context = stream_context_create($opts);
# Get the response (you can use this for GET)
$result = file_get_contents($url.';jsessionid='.$_COOKIE["iu4_session"].'?service=https://web.isen-bretagne.fr/uPortal/Login', false, $context);
var_dump($result);
/*
  $fp = @fopen($url.';jsessionid='.$_COOKIE["iu4_session"].'?service=https://web.isen-bretagne.fr/uPortal/Login', 'rb', false, stream_context_create($params));

  $response = @stream_get_contents($fp);
  var_dump($response);
  if ($response === false) {
    echo("Problem reading data from $url, $php_errormsg");
  }*/
});

$app->run();