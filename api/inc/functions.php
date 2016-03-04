<?php

//fonction pour check la connexion à l'api
function checkAuth($user, $password){
  $API_USER = json_decode(API_USER);
  foreach ($API_USER as $login){
    if($login->user == $user && $login->password == $password){
       return $login;
    }
  }
  return false;
}