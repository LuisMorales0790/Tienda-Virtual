<?php 
class Categorias extends Controllers
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
		 getPermisos(6);
	}

	public function Categorias()
	{
		//hacemos referencia a la vista home para enviarle informacion
		if (empty($_SESSION['permisosMod']['r'])){
			header("Location:".base_url().'/dashboard');
		}
		$data['page_tag'] = "Categorias";
		$data['page_title'] = "CATEGORIAS <small>Tienda Virtual</small>";
		$data['page_name'] = "categorias";
		$data['page_functions_js'] = "functions_categorias.js";
		$this->views->getView($this,"categorias",$data);
	}
	//$nombre_foto = $foto['name'];
	//$nombre_foto = $foto['name'];

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
				//strlower convierte las mayusculas en minusculas
			    //clear_cadena deja todas las letras sin tilde y sin ñ
			    $ruta = strtolower(clear_cadena($strCategoria));
			    //str_replace remplaza todos lo espacios en blanco con un -
			    $ruta = str_replace(" ","-",$ruta);


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
			        	$request_categoria = $this->model->insertCategoria($strCategoria, $strDescripcion, $imgPortada, $ruta, $intStatus);
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
				        $request_categoria = $this->model->updateCategoria($intIdcategoria, $strCategoria, $strDescripcion, $imgPortada, $ruta, $intStatus);
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

	public function getCategoria($idCategorias)
	{
		if ($_SESSION['permisosMod']['r']) 
		{
			$intIdcategoria = intval($idCategorias);
			if ($intIdcategoria > 0)
			{
				$arrData = $this->model->selectCategoria($intIdcategoria);
				//dep($arrData);exit;
				//si el id no se encuentra
				if (empty($arrData)) 
				{
					$arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
				}
				else
				{
					$arrData['url_portada'] = media().'/images/uploads/'.$arrData['portada'];
					$arrResponse = array('status' => true, 'data' => $arrData);
				}
				//dep($arrData);exit();
				echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
			}
		}	
				die();
	}

	public function delCategoria()
	{
		if($_POST)
		{ 
		  if ($_SESSION['permisosMod']['d']) 
			{
		      $intIdcategoria = intval($_POST['idCategoria']);
		      $requestDelete = $this->model->deleteCategoria($intIdcategoria);

		      if ($requestDelete == 'ok') 
		      {
		        $arrResponse = array('status' => true , 'msg' => 'Se ha eliminado la Categoria' );
		      }
		      else if($requestDelete == 'exist')
		      {
		      	$arrResponse = array('status' => true , 'msg' => 'No es posible eliminar una categoria con productos asociados.' );
		      }
		      else
		      {
		        $arrResponse = array('status' => false , 'msg' => 'Error al eliminar la Categoria.' );
		      }
		        echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
	       }
		}
		  die();
	}

	public function getSelectCategorias()
	{
		$htmlOptions = "";
		$arrData = $this->model->selectCategorias();
		if (count($arrData) > 0)
		{
			for ($i=0; $i < count($arrData) ; $i++) 
			{ 
				if ($arrData[$i]['status'] == 1)
				{
					$htmlOptions .= '<option value="'.$arrData[$i]['idcategoria'].'">'.$arrData[$i]['nombre'].'</option>';
				}
			}
		}
		echo $htmlOptions;
		die();
	}
}

?>