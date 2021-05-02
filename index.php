<?php 
	require_once("Config/Config.php");
	require_once("Helpers/Helpers.php");
	//si existe la url, la url va a ser igual a ese mismo valor que estamos enviando, si no se manda al controlador home con el metodo home
	//l 'url' viene del archivo htaccess
	$url = !empty($_GET['url']) ? $_GET['url'] : 'home/home';
	//en la variable tipo arreglo $arrUrl guardo lo que obtengo de la funcion explode que tiene como parametros el delimitador y la url osea del controlador,metodo,parametros etc
	$arrUrl = explode("/", $url); 
	//$controller toma el valor del arreglo en la posicion 0
	$controller =$arrUrl[0];
	//$metodo toma el valor del controlador en controlador de que no exista un metodo $arrUrl[1];
	$method =$arrUrl[0];
	$params = "";

	//si existe la posicion 1 del arreglo
	if (!empty($arrUrl[1])) 
	{	//si la posicion 1 de arreglo es diferente vacio
		if ($arrUrl[1] != "") 
		{
			//lo que este en la posicion 1 lo paso a la variable $metodo
			$method =$arrUrl[1];
		}
	}

	if (!empty($arrUrl[2])) {
		if ($arrUrl[2] != "") 
		{
			//$i se inicia en la posicion 2 porque de ahi se inician los parametros y finaliza hasta el ultimo elemento del arreglo
			for ($i=2; $i < count($arrUrl); $i++)
			{ 
				$params .= $arrUrl[$i].',';
			}
			//trim() remueve el ultimo caracter de una cadena en este caso la coma ,
			$params = trim($params,',');
		}
	}

	require_once("Libraries/Core/Autoload.php");
	require_once("Libraries/Core/Load.php");

	

	










	// echo "controlador: ".$controller;
	// echo "<br>";
	// echo "metodo: ".$metodo;
	// echo "<br>";
	// echo "parametros:".$params;
	

 ?>