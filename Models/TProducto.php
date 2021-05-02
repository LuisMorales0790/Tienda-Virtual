<?php 	
require_once("Libraries/Core/Mysql.php");
trait TProducto
{
	private $con;
	private $strCategoria;
	private $intIdcategoria;
	private $strProducto;
	private $cant;
	private $option;
	private $strRuta;
	private $intIdproducto;
	// AND idcategoria IN ($categorias) recibe mas de una categoria por ejemplo(1,2,3)
	public function getProductosT()
	{
		$this->con = new Mysql();
		$sql = "SELECT p.idproducto,
					   p.codigo,
					   p.nombre,
					   p.descripcion,
					   p.categoriaid,
					   c.nombre as categoria,
					   p.precio,
					   p.ruta,
					   p.stock
		        FROM producto p 
		        INNER JOIN categoria c 
		        ON p.categoriaid = c.idcategoria
		   		WHERE p.status != 0 ";
	      		$request = $this->con->select_all($sql);
	      		if (count($request) > 0)
	      		{
	      			for ($c=0; $c < count($request) ; $c++)
	      			{ 
	      				$this->intIdProducto = $request[$c]['idproducto'];
	      				$sqlimg = "SELECT img
	      					FROM imagen
	      					WHERE productoid = $this->intIdProducto";
	      			    $arrImg = $this->con->select_all($sqlimg);
	      			    if (count($arrImg) > 0)
	      			    {
	      			    	for ($i=0; $i < count($arrImg); $i++) { 
	      			    		$arrImg[$i]['url_image'] = media().'/images/uploads/'.$arrImg[$i]['img'];
	      			    	}
	      			    }
	      			    $request[$c]['images'] = $arrImg;
	      			}	
	      		}
				return $request;
	}

	public function getProductoT(int $idproducto, string $ruta)
	{
		$this->con = new Mysql();
		$this->intIdproducto = $idproducto;
		$this->strRuta = $ruta;
		$sql = "SELECT p.idproducto,
					   p.codigo,
					   p.nombre,
					   p.descripcion,
					   p.categoriaid,
					   c.nombre as categoria,
					   c.ruta as ruta_categoria,
					   p.precio,
					   p.ruta,
					   p.stock
		        FROM producto p 
		        INNER JOIN categoria c 
		        ON p.categoriaid = c.idcategoria
		   		WHERE p.status != 0 AND p.idproducto = '{$this->intIdproducto}' AND p.ruta = '{$this->strRuta}'";
	      		$request = $this->con->select($sql);
	      		//dep($request);
	      		if (!empty($request))
	      		{
	      				$this->intIdProducto = $request['idproducto'];
	      				$sqlimg = "SELECT img
	      					FROM imagen
	      					WHERE productoid = $this->intIdProducto";
	      			    $arrImg = $this->con->select_all($sqlimg);
	      			    if (count($arrImg) > 0)
	      			    {
	      			    	for ($i=0; $i < count($arrImg); $i++) { 
	      			    		$arrImg[$i]['url_image'] = media().'/images/uploads/'.$arrImg[$i]['img'];
	      			    	}
	      			    }
	      			    else
	      			    {
	      			    	$arrImg[0]['url_image'] = media().'/images/uploads/portada_categoria.png';
	      			    }
	      			    $request['images'] = $arrImg;
	      				
	      		} 
				return $request;
	}

	public function getProductosCatergoriaT(int $idcategoria,string $ruta)
	{
		$this->intIdcategoria = $idcategoria;
		$this->strRuta = $ruta;
		$this->con = new Mysql();
		$sql_cat = "SELECT idcategoria,nombre FROM categoria WHERE idcategoria = '{$this->intIdcategoria}'";
		$request = $this->con->select($sql_cat);

		if(!empty($request)) 
		{
			$this->strCategoria = $request['nombre'];
			$sql = "SELECT p.idproducto,
						   p.codigo,
						   p.nombre,
						   p.descripcion,
						   p.categoriaid,
						   c.nombre as categoria,
						   p.precio,
						   p.ruta,
						   p.stock
			        FROM producto p 
			        INNER JOIN categoria c 
			        ON p.categoriaid = c.idcategoria
			   		WHERE p.status != 0 AND p.categoriaid = $this->intIdcategoria AND c.ruta = '{$this->strRuta}'";
		      		$request = $this->con->select_all($sql);
		      		if (count($request) > 0)
		      		{
		      			for ($c=0; $c < count($request) ; $c++)
		      			{ 
		      				$this->intIdProducto = $request[$c]['idproducto'];
		      				$sqlimg = "SELECT img
		      					FROM imagen
		      					WHERE productoid = $this->intIdProducto";
		      			    $arrImg = $this->con->select_all($sqlimg);
		      			    if (count($arrImg) > 0)
		      			    {
		      			    	for ($i=0; $i < count($arrImg); $i++)
		      			    	{ 
		      			    		$arrImg[$i]['url_image'] = media().'/images/uploads/'.$arrImg[$i]['img'];
		      			    	}
		      			    }
		      			    $request[$c]['images'] = $arrImg;
		      			}	
		      		}
		    $request = array('idcategoria' => $this->intIdcategoria,
		    	             'categoria' => $this->strCategoria,
		    	             'productos' => $request
		    	            );
		}
		return $request;			
	}


	public function getProductosRandom(int $idcategoria, int $cant, string $option)
	{
		$this->intIdcategoria = $idcategoria;
		$this->cant= $cant;
		$this->option = $option;

		if($option == "r")
		{
			$this->option = " RAND() ";
		}
		else if($option == "a")
		{
			$this->option = " idproducto ASC ";
		}
		else
		{
			$this->option = " idproducto DESC ";
		}

		$this->con = new Mysql();
			$sql = "SELECT p.idproducto,
						   p.codigo,
						   p.nombre,
						   p.descripcion,
						   p.categoriaid,
						   c.nombre as categoria,
						   p.precio,
						   p.ruta,
						   p.stock
			        FROM producto p 
			        INNER JOIN categoria c 
			        ON p.categoriaid = c.idcategoria
			   		WHERE p.status != 0 AND p.categoriaid = $this->intIdcategoria ORDER BY $this->option LIMIT $this->cant ";
			   		//echo $sql; exit();

		      		$request = $this->con->select_all($sql);
		      		if (count($request) > 0)
		      		{
		      			for ($c=0; $c < count($request) ; $c++)
		      			{ 
		      				$this->intIdProducto = $request[$c]['idproducto'];
		      				$sqlimg = "SELECT img
		      					FROM imagen
		      					WHERE productoid = $this->intIdProducto";
		      			    $arrImg = $this->con->select_all($sqlimg);
		      			    if (count($arrImg) > 0)
		      			    {
		      			    	for ($i=0; $i < count($arrImg); $i++)
		      			    	{ 
		      			    		$arrImg[$i]['url_image'] = media().'/images/uploads/'.$arrImg[$i]['img'];
		      			    	}
		      			    }
		      			    $request[$c]['images'] = $arrImg;
		      			}	
		      		}
		return $request;
	} 

	
	public function getProductoIDT(int $idproducto)
	{
		$this->con = new Mysql();
		$this->intIdproducto = $idproducto;
		$sql = "SELECT p.idproducto,
					   p.codigo,
					   p.nombre,
					   p.descripcion,
					   p.categoriaid,
					   c.nombre as categoria,
					   p.precio,
					   p.ruta,
					   p.stock
		        FROM producto p 
		        INNER JOIN categoria c 
		        ON p.categoriaid = c.idcategoria
		   		WHERE p.status != 0 AND p.idproducto = '{$this->intIdproducto}'";
	      		$request = $this->con->select($sql);
	      		//dep($request);
	      		if (!empty($request))
	      		{
	      				$this->intIdProducto = $request['idproducto'];
	      				$sqlimg = "SELECT img
	      					FROM imagen
	      					WHERE productoid = $this->intIdProducto";
	      			    $arrImg = $this->con->select_all($sqlimg);
	      			    if (count($arrImg) > 0)
	      			    {
	      			    	for ($i=0; $i < count($arrImg); $i++) { 
	      			    		$arrImg[$i]['url_image'] = media().'/images/uploads/'.$arrImg[$i]['img'];
	      			    	}
	      			    }
	      			    else
	      			    {
	      			    	$arrImg[0]['url_image'] = media().'/images/uploads/portada_categoria.png';
	      			    }
	      			    $request['images'] = $arrImg;
	      				
	      		} 
				return $request;
	}
}


 ?>




