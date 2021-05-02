<?php 
// codigo del archivo Load.php
	
	//funcion para convertir la primera letra del controlador en mayuscula
	$controller = ucwords($controller);
	//se crea la variable $controllerFile la cual contendra el archivo de la ruta hacia el controlador
	$controllerFile = "Controllers/".$controller.".php";
	//si existe el archivo  $controllerFile 
	if (file_exists($controllerFile))
	 {
	 	//lo requiero en mi archivo
		require_once($controllerFile);
		//creo un objeto del controlador que esta dentro del archivo
		$controller = new $controller();
		//si dentro del controlador existe un metodo
		if (method_exists($controller, $method))
		{
			//a ese metodod le agrego la variable $params para recibir parametros
			$controller->{$method}($params);
		}
		 else	
		{
			// si no existe el metodo
			require_once("Controllers/Error.php");
		}
	}
	else

	{
		// si no existe el controlador
		require_once("Controllers/Error.php");
	}

/////////////////////////////////////////////////////////////////////////////////////////


?>