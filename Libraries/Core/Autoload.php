<?php 

	spl_autoload_register(function($class){
		if(file_exists("Libraries/".'Core/'.$class.".php")){
			require_once("Libraries/".'Core/'.$class.".php");
		}
	});



/*	//funcion para crear las clases de forma automatica 
	//recibe la clase que venga de libaries/core
	spl_autoload_register(function($class){
		//echo LIBS.'Core/'.$class.".php";
		//Libraries/Core/Home.php - extends controllers
		//si existe este archivo requiero ese mismo archivo
		if(file_exists(LIBS.'Core/'.$class.".php")){
			require_once(LIBS.'Core/'.$class.".php");
		}
	}); */

 ?>