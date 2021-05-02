<?php 
 	/**
 	 * 
 	 */
 	class CategoriasModel extends mysql
 	{
 		public $intIdcategoria;
 		public $strCategoria;
 		public $strDescripcion;
 		public $intStatus;
 		public $strPortdada;
 		public $strRuta;
 		
 		public function __construct()
 		{
 			parent::__construct();
 		}

 		public function insertCategoria(string $nombre, string $descripcion, string $portada, string $ruta, int $status)
 		{
 			$return = 0;
 			$this->strCategoria = $nombre;
 			$this->strDescripcion = $descripcion;
 			$this->strPortdada = $portada;
 			$this->strRuta = $ruta;
 			$this->intStatus = $status;
 			
 			//consulta si existe la categoria
			$sql = "SELECT * FROM categoria WHERE nombre = '{$this->strCategoria}' ";
			//selecciona todos los filas(registros) donde este esa categoria
			$request = $this->select_all($sql);

			//si esta vacio es que no existe la categoria
			if (empty($request))
			{
				//entoces inserto los valores para crear la categoria en la base de datos
				$query_insert = "INSERT INTO categoria(nombre,descripcion,portada,ruta,status) values(?,?,?,?,?)";
				// creo un arreglo con los valores
				$arraData = array($this->strCategoria,
								  $this->strDescripcion,
								  $this->strPortdada,
								  $this->strRuta,
								   $this->intStatus);
				//envio al metodo insert las dos variables para insertar los datos a la BD
				$request_insert = $this->insert($query_insert,$arraData);
				//retorno el id del registro insertado
				$return = $request_insert;	
			}
			else
			{
				$return = "exist";
			}
			return $return;
 		}

 		public function selectCategorias()
 		{
 			$sql = "SELECT * FROM categoria  
				WHERE status != 0 ";
			$request = $this->select_all($sql);
				return $request;
 		}

 		public function selectCategoria( int $idcategoria)
		{
			$this->intIdcategoria = $idcategoria; 
			$sql = "SELECT idcategoria, nombre, descripcion, portada, DATE_FORMAT(datecreated, '%d-%m-%Y') as fechaRegistro, status FROM categoria 
			WHERE idcategoria = $this->intIdcategoria";
			//echo $sql;exit;
			$request = $this->select($sql);
			return $request;
		}

		public function updateCategoria(int $idcategoria, string $categoria, string $descripcion, string $portada, string $ruta, int $status)
		{
			$this->intIdcategoria = $idcategoria;
 			$this->strCategoria = $categoria;
 			$this->strDescripcion = $descripcion;
 			$this->strPortdada = $portada;
 			$this->strRuta = $ruta;
 			$this->intStatus = $status;
 			
 			//consulta si existe la categoria
			$sql = "SELECT * FROM categoria WHERE nombre = '{$this->strCategoria}' AND idcategoria != $this->intIdcategoria";
			//selecciona todos los filas(registros) donde este esa categoria
			$request = $this->select_all($sql);

			//si esta vacio es que no existe la categoria
			if (empty($request))
			{
				$sql = "UPDATE categoria SET nombre=?, descripcion=?, portada=?, ruta=?, status=?
					WHERE idcategoria = $this->intIdcategoria ";

				$arrData = array($this->strCategoria,
					 			$this->strDescripcion,
					 			$this->strPortdada,
					 			$this->strRuta,
					 			$this->intStatus);
				$request = $this->update($sql,$arrData);
			}
			else
			{
				$request = "exist";
			}
			return $request;
		}

		public function deleteCategoria(int $intIdcategoria)
		{
			$this->intIdcategoria = $intIdcategoria;
			$sql = "SELECT * FROM producto WHERE categoriaid = $this->intIdcategoria";
			$request = $this->select_all($sql);
			if (empty($request))
			{
				$sql = "UPDATE categoria SET status = ? WHERE idcategoria = $this->intIdcategoria";
				//se le coloca 0 al status por medio de un arreglo
				$arrData = array(0);
				$request = $this->update($sql,$arrData);
				if ($request)
				{
					$request = 'ok';
				}
				else
				{
					$request = 'error';
				}
			}
			else
			{
				$request = 'exist';
			}
			return $request;
		}
 	}
 ?>