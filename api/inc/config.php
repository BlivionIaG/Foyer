<?php

//Log pour la db
define('DB_SGBD','mysql');
define('DB_HOST','localhost');
define('DB_BASE','Foyer');
define('DB_USER','root');
define('DB_PASSWORD','korsi29yk');
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