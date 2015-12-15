<?php
class PagesController
{
  public function home()
  {
   require_once('views/pages/home.php');
 }

 public function error()
 {
   require_once('views/pages/error.php');
 }
   public function product()
  {
    $products = json_decode(file_get_contents(API_URL.'/product/'));
    require_once('views/pages/product.php');
  }
}
