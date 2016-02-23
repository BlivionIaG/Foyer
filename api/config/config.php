<?php

//Log pour la db
define('DB_SGBD','mysql');
define('DB_HOST','localhost');
define('DB_BASE','foyer');
define('DB_USER','foyer');
define('DB_PASSWORD','foyer');
define('DB_CHARSET','utf8');
define('DB_COLLATION','utf8_unicode_ci');
define('DB_PREFIX','');
//dossier des fichiers
define('DIR_FILES','./files/');
//log de l'api
define('API_USER','[{
  "user": "root",
  "password": "cm9vdDpzM2N1cml0Mw==",
  "access": 1
}, {
  "user": "mobile",
  "password": "bW9iaWxlOnMzY3VyaXQz",
  "access": 2
}]');
//message de notifs
define('NOTIF_COMMAND_STATE_0','Delete');
define('NOTIF_COMMAND_STATE_1','En cours de validation');
define('NOTIF_COMMAND_STATE_2','Validée');
define('NOTIF_COMMAND_STATE_3','Payée');


//focntion pour check la connexion à l'api
function checkAuth($user, $key){
  $API_USER = json_decode(API_USER);
  foreach ($API_USER as $key => $login)
    if($login->user == $user && $login->password == $key)
      return $login;

  return false;
}
