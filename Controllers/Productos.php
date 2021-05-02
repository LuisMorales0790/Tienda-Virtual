<?php 
class Productos extends Controllers
{
	
	public function __construct()
	{
		 parent::__construct();
		session_start();
		
		 //para que elimine los id anteriores de PHPSESSID de la sesion al recargar o cambiar de pagina y se genere uno nuevo y evitar vulnerabilidades
		 session_regenerate_id(true);
		 //si la variable es sesion esta vacia retorna al login
		 if(empty($_SESSION['login']))
		 {
		 	header('Location: '.base_url().'/login');
		 }
		
		 // 6 representa el id del modulo categorias
		 getPermisos(4);
	}

	public function Productos()
	{
		//hacemos referencia a la vista home para enviarle informacion
		if (empty($_SESSION['permisosMod']['r'])){
			header("Location:".base_url().'/dashboard');
		}
		$data['page_tag'] = "Productos";
		$data['page_title'] = "PRODUCTOS <small>Tienda Virtual</small>";
		$data['page_name'] = "categorias";
		$data['page_functions_js'] = "functions_productos.js";
		$this->views->getView($this,"productos",$data);
	}
	//$nombre_foto = $foto['name'];
	//$nombre_foto = $foto['name'];

	public function setProductos()//
  	{
    	if ($_POST) 
	    {
	    	//dep($_POST); //esto aparece en network
	    	//die();
	    	if (empty($_POST['txtNombre']) || empty($_POST['txtCodigo']) || empty($_POST['listCategoria']) || empty($_POST['txtPrecio']) || empty($_POST['listStatus']))
			{
				$arrResponse = array("status" => false, "msg" => 'Datos incorrectos.' );
			}
			else
			{
				//dep($_POST);
		      $idProducto = intval($_POST['idProducto']);
		      $strNombre = strClean($_POST['txtNombre']);
		      $strDescripcion = strClean($_POST['txtDescripcion']);
		      $strCodigo = strClean($_POST['txtCodigo']);
		      $IntCategoriaId = intval($_POST['listCategoria']);
		      $strPecio = strClean($_POST['txtPrecio']);
		      $intStock = intval($_POST['txtStock']);
		      $intStatus = intval($_POST['listStatus']);
		      $request_producto = "";
		      //strlower convierte las mayusculas en minusculas
		      //clear_cadena deja todas las letras sin tilde y sin ñ
		      $ruta = strtolower(clear_cadena($strNombre));
		      //str_replace remplaza todos lo espacios en blanco con un -
		      $ruta = str_replace(" ","-",$ruta);

		      if( $idProducto == 0)
		      {
			     $option = 1;
			     if ($_SESSION['permisosMod']['w'])
			    {
			      	$request_producto = $this->model->insertProducto($strNombre,
			      													 $strDescripcion,
			      													 $strCodigo,
			      													 $IntCategoriaId,
			      													 $strPecio,
			      													 $intStock,
			      													 $ruta,
			      													 $intStatus);
			    }
		      }
		      else
		      {
			    $option = 2;
			    if ($_SESSION['permisosMod']['u'])
		      	{

			      	$request_producto = $this->model->updateProducto($idProducto,
			      													 $strNombre,
			      													 $strDescripcion,
			      													 $strCodigo,
			      													 $IntCategoriaId,
			      													 $strPecio,
			      													 $intStock,
			      													 $ruta,
			      													 $intStatus); 
			    }
		      }

		      if ($request_producto > 0)
		      {
			      	if ($option == 1)
			      	{
			      		$arrResponse = array('status' => true, 'idproducto' => $request_producto, 'msg' => 'Datos guardados correctamente.');
			      	}
			      	else
			      	{
			      		$arrResponse = array('status' => true, 'idproducto' => $idProducto, 'msg' => 'Datos actualizados correctamente.' );
			      	}
		      }
		      else if($request_producto == 'exist')
		      {
		      	$arrResponse = array('status' => false , 'msg' => '!Atencion! ya existe un producto con el codigo ingresado.');
		      }
		      else
		      {
		      	$arrResponse = array('status' => false, 'msg' => 'No es posible almacenar los datos.' );

		      }
			}
			//retornamos el array en formato jason con el satus y el msg y el segundo parametro es para enviar caracteres especiales
	      //sleep(3);
	      echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
	      //con esto detenemos el peoceso del metodo 
	    } 
    	die();  
    }
       
  	public function setImage() //
  	{
  		//dep($_POST);
	  	//dep($_FILES);
	  	//die();
	  	if ($_POST) 
	  	{
	  		if (empty($_POST['idproducto']))
	  		{
	  		  $arrResponse = array('status' => false , 'msg' => 'Error de dato.');
	  		}
	  		else
	  		{
		  		$idProducto = intval($_POST['idproducto']);
		  		//echo $idProducto;
		  		//$idProducto = 1;
		  		$foto = $_FILES['foto'];
		  		//le creamos un nombre a la foto para guardad en la bd
		  		$imgNombre = 'pro_'.md5(date('d-m-Y H:m:s')).'.jpg';
		  		//enviamos los datos al modelo pa insertar en BD
		  		$request_image = $this->model->insertImage($idProducto,$imgNombre);
		  		if ($request_image)
		  		{
		  			//si el envio fue exitoso se envia la imagen en el servidor
		  			$uploadImage = uploadImage($foto,$imgNombre);
		  			$arrResponse = array('status' => true , 'imgname' => $imgNombre, 'msg' => 'Archivo cargado.' );
		  		}
		  		else
		  		{
		  			$arrResponse = array('status' => false , 'msg' => 'Error de carga.');
		  		}		
	  		}
	  		echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
	  	}
	  		//sleep(3);
	  		die();
  	}

  	public function delFile()//
  	{
  		if ($_POST) {
  			if (empty($_POST['idproducto']) || empty($_POST['file']))
  			{
  				$arrResponse = array("status" => false, "msg" => 'Datos incorrectos.' );
  			}
  			else
  			{
  				//ELIMINAR DE LA BD
  				$idProducto = intval($_POST['idproducto']);
  				$imgNombre = strClean($_POST['file']);
  				$request_image = $this->model->deleteImage($idProducto,$imgNombre);

  				if ($request_image)
  				{
  					$deleteFile = deleteFile($imgNombre);
  					$arrResponse = array('status' => true, 'msg' => 'Archivo eliminado');
  				}
  				else
  				{
  					$arrResponse = array('status' => false, 'msg' => 'Archivo eliminado');
  				}
  			}
  			echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
  		}
  		die();                                                          
  	}

	public function setCategoria()
   {
		if ($_POST)
		{
		    //dep($_POST);
	  		//dep($_FILES);
	  		//exit();

			if (empty($_POST['txtNombre']) || empty($_POST['txtDescripcion']) || empty($_POST['listStatus']))
			{
				$arrResponse = array("status" => false, "msg" => 'Datos incorrectos.' );
			}
			else
			{
				$intIdcategoria = intval($_POST['idCategoria']);
				$strCategoria = strClean($_POST['txtNombre']);
				$strDescripcion = strClean($_POST['txtDescripcion']);
				$intStatus = intval($_POST['listStatus']);

				$foto        = $_FILES['foto'];
				$nombre_foto = $foto['name'];
				$type        = $foto['type'];
				$url_temp    = $foto['tmp_name'];
				$imgPortada  = 'portada_categoria.png';
				$request_categoria = "";
				if ($nombre_foto != '')
				{
					$imgPortada = 'img_'.md5(date('d-m-Y H:m:s')).'.jpg';
				}

				if ($intIdcategoria == 0)
			    {
			        //CREAR
			        //permiso para crear
			        if ($_SESSION['permisosMod']['w']) 
			        {
			        	$request_categoria = $this->model->insertCategoria($strCategoria, $strDescripcion, $imgPortada, $intStatus);
			        	$option = 1;
			        }
			        
			    }
			    else
			    {
			        //ACTUALIZAR
			        //permiso para actualizar
			        if ($_SESSION['permisosMod']['u'])
			        {
				        if ($nombre_foto == '')
				        {
				        	//en el caso de que se cambie la foto
				        	if ($_POST['foto_actual'] != 'portada_categoria.png' && $_POST['foto_remove'] == 0)
				        	{
				        		$imgPortada = $_POST['foto_actual'];
				        	}
				        }
				        $request_categoria = $this->model->updateCategoria($intIdcategoria, $strCategoria, $strDescripcion, $imgPortada, $intStatus);
				        $option = 2;
			    	}
			    }

			    if ($request_categoria > 0)
			    {
			        if ($option == 1)
			        {
			            $arrResponse = array('status' => true, 'msg' => 'Datos guardados correctamente.' );
			            if ($nombre_foto != ''){ uploadImage($foto,$imgPortada); }
			        }
			        else
			        {
			            $arrResponse = array('status' => true, 'msg' => 'Datos actualizados correctamente.' );
			            if ($nombre_foto != ''){ uploadImage($foto,$imgPortada); }
			            //en el caso de que se quite la foto actual y se deje la foto de portada O se quite la foto actual y se agregue una nueva foto.
			            if (($nombre_foto == '' && $_POST['foto_remove'] == 1 && $_POST['foto_actual'] != 'portada_categoria.png') || ($nombre_foto != '' && $_POST['foto_actual'] != 'portada_categoria.png')) 
			            {
			            	deleteFile($_POST['foto_actual']);
			            }
			        }
				}
			    else if ($request_categoria == 'exist')
		        {
		            $arrResponse = array('status' => false, 'msg' => '!Atencion la categoria ya existe.' );
		        }
			    else
		        {
		          $arrResponse = array('status' => false, 'msg' => 'No es posible almacenar datos.' );
		        }
			}
			//retornamos el array en formato jason con el satus y el msg y el segundo parametro es para enviar caracteres especiales
	      //sleep(3);
	      echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
		}
	    die(); 
   }

   Public function getCategorias()
	{
		if ($_SESSION['permisosMod']['r']) 
		{
			# code...
			$arrData = $this->model->selectCategorias();
			//dep($arrData);
			//exit();
			for($i=0; $i < count($arrData); $i++)
			{
				$btnView = '';
				$btnEdit = '';
				$btnDelete = '';

				if($arrData[$i]['status'] == 1)
				  {
				  		$arrData[$i]['status'] = '<span class="badge badge-success">Activo</span>';
				  }
				else
				  {
				  		$arrData[$i]['status'] = '<span class="badge badge-danger">Inactivo</span>';
				  }

		       if ($_SESSION['permisosMod']['r'])
			       { 
			       		$btnView = ' <button class="btn btn-info btn-sm" onclick="fntViewInfo('.$arrData[$i]['idcategoria'].')" title="Ver categoria"><i class="far fa-eye"></i></button>';
			       }

		        if ($_SESSION['permisosMod']['u'])
		        	{
		        		//el boton se va a enviar a traves de this para poder hacer uso de ese elemento en el js
		        	    $btnEdit = '<button class="btn btn-primary btn-sm" onclick="fntEditInfo(this,'.$arrData[$i]['idcategoria'].')" title="Editar categoria"><i class="fas fa-pencil-alt"></i></button>';
		            }

		        if ($_SESSION['permisosMod']['d'])
			       {
		       			$btnDelete = '<button class="btn btn-danger btn-sm" onclick="fntDelInfo('.$arrData[$i]['idcategoria'].')" title="Eliminar categoria"><i class="far fa-trash-alt"></i></button>';	
			    	}
		    	$arrData[$i]['options'] = '<div class="text-center">'.$btnView.' '.$btnEdit.' '.$btnDelete.'</div>'; 
			}
				 echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
		}
				die();
	}

	public function getProducto($idproducto)//
	{
		if ($_SESSION['permisosMod']['r']) 
		{
			$idproducto = intval($idproducto);
			if ($idproducto > 0)
			{
				$arrData = $this->model->selectProducto($idproducto);
				//dep($arrData);
				//die();
				//si el id no se encuentra
				if (empty($arrData)) 
				{
					$arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
				}
				else
				{
					$arrImg = $this->model->selectImages($idproducto);
					//dep($arrImg);
					//dep($arrData);
					//die();
					if (count($arrImg) > 0)
					{ 
						//se recorre el arreglo con las imagenes
						for ($i=0; $i < count($arrImg); $i++) 
						{
							// y se busca la ruta donde se encuentra cada una de la imagenes en el servidor y se guardan en la variable $arrImg['url_image']
							$arrImg[$i]['url_image'] = media().'/images/uploads/'.$arrImg[$i]['img'];						
					    } 
					}
					//al arreglo arrData que contiene la info del producto le agregamos un nuevo elemento llamado images que contendra el arreglo con todas las imagenes asociadas a ese producto por medio del id con su respectiva ruta dentro del servidor.
					$arrData['images'] = $arrImg;	
					$arrResponse = array('status' => true, 'data' => $arrData);
				}
				//dep($arrData);
				//dep($arrResponse);
				echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
				die();		
			}
	    }	
	}		
	
	public function delProducto()//
	{
		if($_POST)
		{ 
		  if ($_SESSION['permisosMod']['d']) 
			{
		      $intIdproducto = intval($_POST['idProducto']);
		      $requestDelete = $this->model->deleteProducto($intIdproducto);

		      if ($requestDelete) 
		      {
		        $arrResponse = array('status' => true , 'msg' => 'Se ha eliminado el Producto' );
		      }
		      else 
		      {
		      	$arrResponse = array('status' => false , 'msg' => 'Error al eliminar El producto.' );
		      }
		        echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
	       }
		}
		  die();
	}

	Public function getProductos() //
	{
		if ($_SESSION['permisosMod']['r']) 
		{
			# code...
			$arrData = $this->model->selectProductos();
			//dep($arrData);
			//exit();
			for($i=0; $i < count($arrData); $i++)
			{
				$btnView = '';
				$btnEdit = '';
				$btnDelete = '';

				if($arrData[$i]['status'] == 1)
				  {
				  		$arrData[$i]['status'] = '<span class="badge badge-success">Activo</span>';
				  }
				else
				  {
				  		$arrData[$i]['status'] = '<span class="badge badge-danger">Inactivo</span>';
				  }

				  $arrData[$i]['precio'] = SMONEY.' '.formatMoney($arrData[$i]['precio']);

		       if ($_SESSION['permisosMod']['r'])
			       { 
			       		$btnView = ' <button class="btn btn-info btn-sm" onclick="fntViewInfo('.$arrData[$i]['idproducto'].')" title="Ver producto"><i class="far fa-eye"></i></button>';
			       }

		        if ($_SESSION['permisosMod']['u'])
		        	{
		        		//el boton se va a enviar a traves de this para poder hacer uso de ese elemento en el js
		        	    $btnEdit = '<button class="btn btn-primary btn-sm" onclick="fntEditInfo(this,'.$arrData[$i]['idproducto'].')" title="Editar producto"><i class="fas fa-pencil-alt"></i></button>';
		            }

		        if ($_SESSION['permisosMod']['d'])
			       {
		       			$btnDelete = '<button class="btn btn-danger btn-sm" onclick="fntDelInfo('.$arrData[$i]['idproducto'].')" title="Eliminar producto"><i class="far fa-trash-alt"></i></button>';	
			    	}
		    	$arrData[$i]['options'] = '<div class="text-center">'.$btnView.' '.$btnEdit.' '.$btnDelete.'</div>'; 
			}
				 echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
		}
				die();
	}


}

?>