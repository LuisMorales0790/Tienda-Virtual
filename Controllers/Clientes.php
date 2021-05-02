<?php 

class Clientes extends Controllers
{
	public function __construct()
	{
		parent::__construct();
		session_start();
		//resetea el id de la session que se ha generado
		session_regenerate_id(true);
		//si la variable es sesion esta vacia retorna al login
		 if(empty($_SESSION['login']))
		 {
		 	header('Location: '.base_url().'/login');
		 }
		 getPermisos(MCLIENTES);
	}

	public function Clientes()
	{
		if (empty($_SESSION['permisosMod']['r']))
		{
			header("Location:".base_url().'/dashboard');
		}
		$data['page_tag'] = "Clientes";
		$data['page_title'] = "CLIENTES <small>Tienda Virtual</small>";
		$data['page_name'] = "clientes";
		$data['page_functions_js'] = "functions_clientes.js";
		$this->views->getView($this,"clientes",$data);
	}

	public function setCliente()
	{
		error_reporting(0);
		if ($_POST) 
		{ 
			//dep($_POST);exit;

			if (empty($_POST['txtIdentificacion']) || empty($_POST['txtNombre']) || empty($_POST['txtApellido']) || empty($_POST['txtTelefono']) || empty($_POST['txtEmail']) || empty($_POST['txtNit']) || empty($_POST['txtNombreFiscal']) || empty($_POST['txtDirFiscal'])) 
			{
				$arrResponse = array("status" => false , "msg" => 'Datos incorrectos.' );
			}
			else
			{
				//ucwords convierte la primera letra de la palabra en mayuscula
				//strtolower convierte toda la palabra en minuscula
				$idUsuario 		   = intval($_POST['idUsuario']);
				$strIdentificacion = strClean($_POST['txtIdentificacion']);
				$strNombre         = ucwords(strClean($_POST['txtNombre']));
				$strApellido       = ucwords(strClean($_POST['txtApellido']));
				$intTelefono       = intval(strClean($_POST['txtTelefono']));
				$strEmail          = strtolower(strClean($_POST['txtEmail']));
				$strNit         = strClean($_POST['txtNit']);
				$strNomFiscal         = strClean($_POST['txtNombreFiscal']);
				$strDirFiscal     = strClean($_POST['txtDirFiscal']);
				$intTipoId		= 7;
				$request_user = "";
				if ($idUsuario == 0)
				{
					$option = 1;
					//pasword sin encriptar para enviarcelo al cliente via web
					$strPassword = empty($_POST['txtPassword']) ? passGenerator() : $_POST['txtPassword'];
					//password encriptado para enviarlo a la BD
					$strPasswordEncript = hash("SHA256",$strPassword);
					if ($_SESSION['permisosMod']['w'])
					{ 
						$request_user = $this->model->insertCliente($strIdentificacion,
																	$strNombre,
																	$strApellido,
																	$intTelefono,
																	$strEmail,
																	$strPasswordEncript,
																	$intTipoId,
																	$strNit,
																	$strNomFiscal,
																	$strDirFiscal);
					}
				}
				else
				{
					$option = 2;
					$strPassword = empty($_POST['txtPassword']) ? "" : hash("SHA256",$_POST['txtPassword']);
					if ($_SESSION['permisosMod']['u'])
					{
						$request_user = $this->model->updateCliente($idUsuario,
																	$strIdentificacion,
																	$strNombre,
																	$strApellido,
																	$intTelefono,
																	$strEmail,
																	$strPassword,
																	$strNit,
																	$strNomFiscal,
																	$strDirFiscal ); 
					}
				}

				if ($request_user > 0)
				{
					if ($option == 1) 
					{
						$arrResponse = array("status" => true , "msg" => 'Datos guardados correctamente.');
						$nombreUsuario = $strNombre.' '.$strApellido;
						$dataUsuario = array('nombreUsuario' => $nombreUsuario,
												'email' => $strEmail,
												'password' => $strPassword,
												'asunto' => 'Bienvenido a tu tienda en linea');
						sendEmail($dataUsuario,'email_bienvenida');
					}
					else
					{
						$arrResponse = array("status" => true , "msg" => 'Datos actualizados correctamente.');
					}
					
				}
				else if ($request_user == 'exist') 
				{
					$arrResponse = array("status" => false , "msg" => '!Atencion! el email o la identificacion ya existe, ingrese otro.');
				}
				else
				{
					$arrResponse = array("status" => false , "msg" => 'No es posible almacenar los datos');
				}
			}

			echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
		}
		die();
	}

/*	Public function getClientes()
	{
		$arrData = $this->model->selectClientes();
		//dep($arrData);
		//exit;

		for($i=0; $i < count($arrData); $i++)
		{
			$btnView = '';
			$btnEdit = '';
			$btnDelete = '';

	      $arrData[$i]['options'] = '<div class="text-center">
	      <button class="btn btn-info btn-sm btnViewUsuario" onclick="fntViewUsuario('.$arrData[$i]['idpersona'].')" title="Ver usuario"><i class="far fa-eye"></i></button>

	      <button class="btn btn-primary btn-sm btnEditUsuario" onclick="fntEditUsuario('.$arrData[$i]['idpersona'].')" title="Editar usuario"><i class="fas fa-pencil-alt"></i></button>

	      <button class="btn btn-danger btn-sm btnDelUsuario" onclick="fntDelUsuario('.$arrData[$i]['idpersona'].')" title="Eliminar Usuario"><i class="far fa-trash-alt"></i></button>
	      
	      </div>'; 
	    }
	    echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
	      die();
	} */

	Public function getClientes()
	{
		if ($_SESSION['permisosMod']['r']) 
		{
			# code...
			$arrData = $this->model->selectClientes();
			//dep($arrData);

			for($i=0; $i < count($arrData); $i++)
			{
				$btnView = '';
				$btnEdit = '';
				$btnDelete = '';

		       if ($_SESSION['permisosMod']['r'])
			       { 
			       		$btnView = ' <button class="btn btn-info btn-sm" onclick="fntViewInfo('.$arrData[$i]['idpersona'].')" title="Ver Cliente"><i class="far fa-eye"></i></button>';
			       }

		        if ($_SESSION['permisosMod']['u'])
		        	{
		        	    $btnEdit = '<button class="btn btn-primary btn-sm" onclick="fntEditInfo(this,'.$arrData[$i]['idpersona'].')" title="Editar Cliente"><i class="fas fa-pencil-alt"></i></button>';
		            }

		        if ($_SESSION['permisosMod']['d'])
			       {
		       			$btnDelete = '<button class="btn btn-danger btn-sm" onclick="fntDelInfo('.$arrData[$i]['idpersona'].')" title="Eliminar Cliente"><i class="far fa-trash-alt"></i></button>';	
			    	}
		    	$arrData[$i]['options'] = '<div class="text-center">'.$btnView.' '.$btnEdit.' '.$btnDelete.'</div>'; 
			}
				 echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
		}
				die();
	}

	public function getCliente($idpersona)
	{
		if ($_SESSION['permisosMod']['r']) 
		{
			$idusuario = intval($idpersona);
			if ($idusuario > 0)
			{
				$arrData = $this->model->selectCliente($idusuario);
				//dep($arrData);exit;s
				//si el id no se encuentra
				if (empty($arrData)) 
				{
					$arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
				}
				else
				{
					$arrResponse = array('status' => true, 'data' => $arrData);
				}

				echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
			}
		}	
				die();
	}

	public function delCliente()
	{
		if($_POST)
		{ 
		  if ($_SESSION['permisosMod']['d']) 
			{
		      $intIdpersona = intval($_POST['idUsuario']);
		      $requestDelete = $this->model->deleteCliente($intIdpersona);
		      if ($requestDelete) 
		      {
		        $arrResponse = array('status' => true , 'msg' => 'Se ha eliminado el Cliente' );
		      }
		      else 
		      {
		        $arrResponse = array('status' => false , 'msg' => 'Error al eliminar el Cliente.' );
		      }
		        echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
	       }
		}
		  die();
	}

}

 ?>