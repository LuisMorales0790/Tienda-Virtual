<?php 
 	/**
 	 * 
 	 */
 	class ProductosModel extends mysql
 	{
 		private $intIdProducto;
 		private $strNombre;
 		private $strDescripcion;
 		private $intCodigo;
 		private $intCategoriaId;
 		private $intPrecio;
 		private $intStock;
 		private $strRuta;
 		private $intStatus;
 		public $strImagen;
 		
 		public function __construct()
 		{
 			parent::__construct();
 		}

 		public function insertProducto(string $nombre, string $descripcion, string $codigo, int $categoriaid, string $precio,int $stock, string $ruta, int $status)
 		{
 			$return = 0;
 			$this->strNombre = $nombre;
 			$this->strDescripcion = $descripcion;
 			$this->intCodigo = $codigo;
 			$this->intCategoriaId = $categoriaid;
 			$this->intPrecio = $precio;
 			$this->intStock = $stock;
 			$this->strRuta = $ruta;
 			$this->intStatus = $status;
 			
 			//consulta si existe la categoria
			$sql = "SELECT * FROM producto WHERE codigo = '{$this->intCodigo}' ";
			//selecciona todos los filas(registros) donde este esa categoria
			$request = $this->select_all($sql);

			//si esta vacio es que no existe la categoria
			if (empty($request))
			{
				//entoces inserto los valores para crear la categoria en la base de datos
				$query_insert = "INSERT INTO producto(categoriaid,codigo,nombre,descripcion,precio,stock,ruta,status) values(?,?,?,?,?,?,?,?)";
				// creo un arreglo con los valores
				$arraData = array($this->intCategoriaId,
								  $this->intCodigo,
								  $this->strNombre,
								  $this->strDescripcion,
								  $this->intPrecio,
								  $this->intStock,
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

 		public function selectProducto( int $idproducto)//
		{
			$this->intIdProducto = $idproducto; 
			$sql = "SELECT p.idproducto,
							p.codigo,
							p.nombre,
							p.descripcion,
							p.precio,
							p.stock,
							p.categoriaid,
							c.nombre as categoria,
							p.status
					FROM producto p
					INNER JOIN categoria c
					ON p.categoriaid = c.idcategoria
					WHERE idproducto = $this->intIdProducto";
			//echo $sql;exit;
			$request = $this->select($sql);
			return $request;
		}

		public function selectImages(int $idproducto)//
		{
			$this->intIdProducto = $idproducto;
			$sql = "SELECT productoid,img
					FROM imagen
					WHERE productoid =  $this->intIdProducto";
		    $request = $this->select_all($sql);
		   return $request;
		}

		public function deleteImage(int $idproducto, string $imagen)
		{
			$this->intIdProducto = $idproducto;
			$this->strImagen = $imagen;
			$query = "DELETE FROM imagen
						WHERE productoid = $this->intIdProducto
						AND img = '{$this->strImagen}'";
						$request_delete = $this->delete($query);
						return $request_delete;
		}

		public function updateProducto(int $idproducto, string $nombre, string $descripcion,string $codigo,string $categoriaid, string $precio, int $stock, string $ruta, int $status)
		{

            $this->intIdProducto = $idproducto;
            $this->strNombre = $nombre;
            $this->strDescripcion = $descripcion;
            $this->intCodigo = $codigo;
			$this->intCategoriaId = $categoriaid;
 			$this->intPrecio = $precio;
 			$this->intStock = $stock;
 			$this->strRuta = $ruta;
 			$this->intStatus = $status;
 			
 			//consulta si existe el prodcucto
			$sql = "SELECT * FROM producto WHERE codigo = '{$this->intCodigo}' AND idproducto != $this->intIdProducto";
			$request = $this->select_all($sql);

			//si esta vacio es que no existe el producto
			if (empty($request))
			{
				$sql = "UPDATE producto SET categoriaid=?, codigo=?, nombre=?, descripcion=?, precio=?, stock=?, ruta=?, status=?
					WHERE idproducto = $this->intIdProducto ";

				$arrData = array(
								$this->intCategoriaId,
								$this->intCodigo,
					            $this->strNombre,
					 			$this->strDescripcion,
					 			$this->intPrecio,
					 			$this->intStock,
					 			$this->strRuta,
					 			$this->intStatus);
				$request = $this->update($sql,$arrData);
				$return = $request;
			}
			else
			{
				$request = "exist";
			}
			return $request;
		}

		public function deleteProducto(int $idproducto)
		{
			$this->intIdproducto = $idproducto;
			$sql = "UPDATE producto SET status = ? WHERE idproducto = $this->intIdproducto";
			//se le coloca 0 al status por medio de un arreglo
			$arrData = array(0);
			$request = $this->update($sql,$arrData);
			return $request;
		}

		public function selectProductos()
 		{
 			$sql = "SELECT p.idproducto,
 						   p.codigo,
 						   p.nombre,
 						   p.descripcion,
 						   p.categoriaid,
 						   c.nombre as categoria,
 						   p.precio,
 						   p.stock,
 						   p.status
 			       FROM producto p 
 			       INNER JOIN categoria c 
 			       ON p.categoriaid = c.idcategoria
				   WHERE p.status != 0 ";
			       $request = $this->select_all($sql);
			return $request;
 		}

 		public function insertImage(int $idproducto, string $imagen)
 		{
 			$this->intIdProducto = $idproducto;
 			$this->strImagen = $imagen;
 			$query_insert = "INSERT INTO imagen(productoid,img) VALUES(?,?)";
 			$arrData = array($this->intIdProducto,
 								$this->strImagen);
 			$request_insert = $this->insert($query_insert,$arrData);
 			return $request_insert;

 		}
 	}
 ?>