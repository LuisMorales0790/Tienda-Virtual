<?php 
 //esta es la clase padre de los controladores el cual ejecuta el metodo loadModel el cual crea una ruta del archivo homeModel el cual es un mdelo
	/**
	 * 
	 */
	class Controllers
	{
		
		public function __construct()
		{
			$this->views = new Views();
			$this->loadModel();
		}
		//funcion para cargar los modelos
		public function loadModel()
		{
			//homeModel.php
			//$model va a guardar la clase que obtengo por medio de el metodo get_class() por medio de $this (homeModel)y le concatenamos .Model
			$model = get_class($this)."Model";
			//$routClass guarda la ruta del modelo con la variable $model creada y le concatenamos la extencion .php
			$routClass = "Models/".$model.".php";
			//si existe la ruta
			if (file_exists($routClass))
			{
				//requiero la ruta
				require_once($routClass);
				//creo un objeto del modelo
				$this->model = new $model();

			}
		}
	}


 ?>