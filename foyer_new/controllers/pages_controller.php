<?php
class PagesController
	{
 	public function error()
 		{
   		require_once('views/pages/error.php');
 		}
	public function product()
  		{
    	require_once('views/pages/product.php');
  		}
    public function order()
  		{
    	require_once('views/pages/order.php');
  		}
  	public function client()
  		{
    	require_once('views/pages/client.php');
  		}
}
