<?php

	class HomeController 
	  	{
	    public function test() 
	    	{
	      	$first_name = 'Paul';
	      	$last_name = 'Michaud';
	      	require_once('views/pages/home.php');
	    	}
	    public function variable() 
	    	{
	      	$first_name = $_GET['name'];
	      	$last_name = '';
	      	$products = file_get_contents(API_URL.'/products');
	      	require_once('views/pages/home.php');
	    	}
	  	}
  	
?>