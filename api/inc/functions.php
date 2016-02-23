<?php

//fonction pour check la connexion à l'api
function checkAuth($user, $key){
  $API_USER = json_decode(API_USER);
  foreach ($API_USER as $key => $login)
    if($login->user == $user && $login->password == $key)
      return $login;

  return false;
}