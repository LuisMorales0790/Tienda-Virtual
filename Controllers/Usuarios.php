<?php 
/**Este es un controlador que se connecta con su clase padre Controller por medio del constructor y a su ves este se conecta con el metodo homeModel
 * 
 */
class Usuarios extends Controllers
{
	
	public function __construct()
	{
		 parent::__construct();
		session_start();
		//si la variable es sesion esta vacia retorna al login
		 //para que elimine los id anteriores de PHPSESSID de la sesion al recargar o cambiar de pagina y evitar vulnerabilidades
		 session_regenerate_id(true);
		 if(empty($_SESSION['login']))
		 {
		 	header('Location: '.base_url().'/login');
		 }
		
		 // 2 representa el id del modulo usuario
		 getPermisos(2);
	}

	public function Usuarios()
	{
		//hacemos referencia a la vista home para enviarle informacion
		if (empty($_SESSION['permisosMod']['r'])){
			header("Location:".base_url().'/dashboard');
		}
		$data['page_tag'] = "Usuarios";
		$data['page_title'] = "USUARIOS <small>Tienda Virtual</small>";
		$data['page_name'] = "usuarios";
		$data['page_functions_js'] = "functions_usuarios.js";
		$this->views->getView($this,"usuarios",$data);
	}

	public function setUsuario()
	{
		if ($_POST) 
		{ 

			if (empty($_POST['txtIdentificacion']) || empty($_POST['txtNombre']) || empty($_POST['txtApellido']) || empty($_POST['txtTelefono']) || empty($_POST['txtEmail']) || empty($_POST['listRolid']) || empty($_POST['listStatus'])) 
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
				$intTipoId         = intval(strClean($_POST['listRolid']));
				$intStatus         = intval(strClean($_POST['listStatus']));

				$request_user = "";
				if ($idUsuario == 0)
				{
					$option = 1;
					$strPassword = empty($_POST['txtPassword']) ? hash("SHA256",passGenerator()) : hash("SHA256",$_POST['txtPassword']);
					if ($_SESSION['permisosMod']['w']) 
					{
						$request_user = $this->model->insertUsuario($strIdentificacion,
																	$strNombre,
																	$strApellido,
																	$intTelefono,
																	$strEmail,
																	$strPassword,
																	$intTipoId,
																	$intStatus );
					}
				}
				else
				{
					$option = 2;
					$strPassword = empty($_POST['txtPassword']) ? "" : hash("SHA256",$_POST['txtPassword']);
					if ($_SESSION['permisosMod']['u']) 
					{
						$request_user = $this->model->updateUsuario($idUsuario,
																	$strIdentificacion,
																	$strNombre,
																	$strApellido,
																	$intTelefono,
																	$strEmail,
																	$strPassword,
																	$intTipoId,
																	$intStatus );
					}
				
				}

				if ($request_user > 0)
				{
					if ($option == 1) 
					{
						$arrResponse = array("status" => true , "msg" => 'Datos guardados correctamente.');
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
			//sleep(3);
			echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
		}
		die();
	}

	Public function getUsuarios()
	{
		if ($_SESSION['permisosMod']['r']) 
		{
			# code...
		
			$arrData = $this->model->selectUsuarios();
			//dep($arrData);

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

		       if ($_SESSION['permisosMod']['r']){
		       		$btnView = ' <button class="btn btn-info btn-sm btnViewUsuario" onclick="fntViewUsuario('.$arrData[$i]['idpersona'].')" title="Ver usuario"><i class="far fa-eye"></i></button>';
		       }

		        if ($_SESSION['permisosMod']['u'])
		        {
		        	if (($_SESSION['idUser'] == 1 and $_SESSION['userData']['idrol'] == 1) ||
		        		($_SESSION['userData']['idrol'] == 1 and $arrData[$i]['idrol'] != 1))
		        	 	{
		        	 		//this toma todo el contenido del boton o del elemento osea la fila del usuario a editar
		        			$btnEdit = '<button class="btn btn-primary btn-sm btnEditUsuario" onclick="fntEditUsuario(this,'.$arrData[$i]['idpersona'].')" title="Editar usuario"><i class="fas fa-pencil-alt"></i></button>';
		        		}
		        	else      
			        	{
			        		$btnEdit = '<button class="btn btn-secondary btn-sm disabled"><i class="fas fa-pencil-alt"></i></button>';
			        	}	
		        }

		       if ($_SESSION['permisosMod']['d'])
		       {
			       	if (($_SESSION['idUser'] == 1 and $_SESSION['userData']['idrol'] == 1) ||
			        		($_SESSION['userData']['idrol'] == 1 and $arrData[$i]['idrol'] != 1) and ($_SESSION['userData']['idpersona'] != $arrData[$i]['idpersona']))
			        	{

		       				$btnDelete = '<button class="btn btn-danger btn-sm btnDelUsuario" onclick="fntDelUsuario('.$arrData[$i]['idpersona'].')" title="Eliminar Usuario"><i class="far fa-trash-alt"></i></button>';
		       			}
		       		else
			       		{
			       			$btnDelete = '<button class="btn btn-secondary btn-sm disabled" title="Eliminar Usuario"><i class="far fa-trash-alt"></i></button>';
			       		}
		    	}
		    	$arrData[$i]['options'] = '<div class="text-center">'.$btnView.' '.$btnEdit.' '.$btnDelete.'</div>'; 
			}
				 echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
		}
				die();
	}

	public function getUsuario($idpersona)
	{
		if ($_SESSION['permisosMod']['r']) 
		{
			$idusuario = intval($idpersona);
			if ($idusuario > 0)
			{
				$arrData = $this->model->selectUsuario($idusuario);
				//dep($arrData);
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

	public function delUsuario()
	{
		if($_POST)
		{ 
		  if ($_SESSION['permisosMod']['d']) 
			{
		      $intIdpersona = intval($_POST['idUsuario']);
		      $requestDelete = $this->model->deleteUsuario($intIdpersona);
		      if ($requestDelete) 
		      {
		        $arrResponse = array('status' => true , 'msg' => 'Se ha eliminado el usuario' );
		      }
		      else 
		      {
		        $arrResponse = array('status' => false , 'msg' => 'Error al eliminar el usuario.' );
		      }
		        echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
	       }
		}
		  die();
	}

	public function perfil()
	{
		$data['page_tag'] = "Perfil";
		$data['page_title'] = "Perfil de usuario";
		$data['page_name'] = "perfil";
		$data['page_functions_js'] = "functions_usuarios.js";
		$this->views->getView($this,"perfil",$data);
	}

	public function putPerfil()
	{ 

		//dep($_POST);
		//die();
	
		if($_POST){
			if(empty($_POST['txtIdentificacion']) || empty($_POST['txtNombre']) || empty($_POST['txtApellido']) || empty($_POST['txtTelefono']))
			{
				$arrResponse = array('status' => false, "msg" => 'Datos incorrectos.' );
			}
			else
			{
				//strclean para eliminar inyecciones sql
				$idUsuario = $_SESSION['idUser'];
				$strIdentificacion = strClean($_POST['txtIdentificacion']);
				$strNombre = strClean($_POST['txtNombre']);
				$strApellido = strClean($_POST['txtApellido']);
				$intTelefono = intval(strClean($_POST['txtTelefono']));
				$strPassword = "";
				if (!empty($_POST['txtPassword'])){
					$strPassword = hash("SHA256", $_POST['txtPassword']);
				}

				$request_user = $this->model->updatePerfil($idUsuario,
															$strIdentificacion,
															$strNombre,
															$strApellido,
															$intTelefono,
															$strPassword);
				if ($request_user)
				{
					//sessionUser se encuentra en Helpers y sirve para obtener todos los datos del usuario
					sessionUser($_SESSION['idUser']);
					$arrResponse = array('status' => true, 'msg' => 'Datos actualizados correctamente.');
				}
				else
				{
					$arrResponse = array('status' => false, 'msg' => 'No es posible actualizar los datos.');
				}
			}
			sleep(3);
			echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
		}
		die();
	}

	public function putDFiscal()
	{
		if($_POST){
			if (empty($_POST['txtNit']) || empty($_POST['txtNombreFiscal']) || empty($_POST['txtDirFiscal']))
			{
				$arrResponse = array("status" => false, "msg" => 'Datos incorrectos.' );
			}
			else
			{
				$idUsuario = $_SESSION['idUser'];
				$strNit = strClean($_POST['txtNit']);
				$strNomFiscal = strClean($_POST['txtNombreFiscal']);
				$strDirFiscal = strClean($_POST['txtDirFiscal']);
				$request_datafiscal = $this->model->updateDataFiscal($idUsuario,
																	  $strNit,
																	  $strNomFiscal,
																	  $strDirFiscal);
				if ($request_datafiscal)
				{
					//sessionUser se encuentra en Helpers y sirve para obtener todos los datos del usuario
					sessionUser($_SESSION['idUser']);
					$arrResponse = array('status' => true, 'msg' => 'Datos actualizados correctamente.');
				}
				else
				{
					$arrResponse = array('status' => false, 'msg' => 'No es posible actualizar los datos.');
				}
			}
			//sleep(3);
			echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
		}
		die();
	}

} 

?>