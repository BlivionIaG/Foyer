<?php
class PagesController 
{
  public function home() 
  {
   $products = file_get_contents(API_URL.'/product/');
   require_once('views/pages/home.php');
 }

 public function error() 
 {
   require_once('views/pages/error.php');
 }
}
?>