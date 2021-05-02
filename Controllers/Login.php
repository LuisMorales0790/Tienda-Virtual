<?php 
class Login extends Controllers
{
	
	public function __construct()
	{
		session_start();
		//si existe la variable de session permite entrar al dashboard
		 if(isset($_SESSION['login']))
		 {
		 	header('Location: '.base_url().'/dashboard');
		 }
		 parent::__construct();
	}

	public function login()
	{
		//hacemos referencia a la vista home para enviarle informacion
		$data['page_tag'] = "Login - Tienda Virtual";
		$data['page_title'] = "Tienda Virtual";
		$data['page_name'] = "login";
		$data['page_functions_js'] = "functions_login.js";
		$this->views->getView($this,"login",$data);
	}

	//proceso para loguiarse y variables de sesion
	public function loginUser()
		{
			//dep($_POST);
			if($_POST)
			{
				if (empty($_POST['txtEmail'])  || empty($_POST['txtPassword'])) 
				{
					$arrResponse = array('status' => false, 'msg' => 'Error de datos');
				}
				else
				{
					//strlower convierte todo en minuscula y strclean limpia para tener una cadena pura
					$strUsuario = strtolower(strclean($_POST['txtEmail']));
					$strPassword = hash("SHA256", $_POST['txtPassword']);
					$requestUser = $this->model->loginUser($strUsuario,$strPassword);
					//dep($requestUser);
					if (empty($requestUser))
					{
						$arrResponse = array('status' => false, 'msg' => 'El usuario o la contraseña es incorrecto.');
					}
					else
					{
						$arrData = $requestUser;
						if($arrData['status'] == 1)
						{
							$_SESSION['idUser'] = $arrData['idpersona'];
							$_SESSION['login'] = true;
							
							$arrData = $this->model->sessionLogin($_SESSION['idUser']);
							//guarda y actualiza los datos del usuario automaticamente sin cerrar y abrir sesion
							sessionUser($_SESSION['idUser']);
							
							$arrResponse = array('status' => true, 'msg' => 'ok.');
						}
						else
						{
							$arrResponse = array('status' => false, 'msg' => 'Usuario inactivo.');
						}
					}
				}
				//sleep(5);
				echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
			}
			die();
		}

		public function resetPass()
		{
			if($_POST)
			{ // para que el error del email no salga en consola pero si en un modal
					error_reporting(0);

					if(empty($_POST['txtEmailReset']))
				{
					$arrResponse = array('status' => false , 'msg' => 'Error de datos');
				}
				else
				{
					$token = token();
					//strlower convierte todo en minuscula
					$strEmail = strtolower(strClean($_POST['txtEmailReset']));
					$arrData = $this->model->getUserEmail($strEmail);

					if (empty($arrData))
					{
						$arrResponse = array('status' => false, 'msg' => 'Usuario no existente.');
					}
					else
					{
						$idpersona = $arrData['idpersona'];
						$nombreUsuario = $arrData['nombres'].' '.$arrData['apellidos'];

						$url_recovery = base_url().'/login/confirmUser/'.$strEmail.'/'.$token; // = http://localhost/tienda/login/confirmUser/lemo@gmail.com/2489ce5b8a7498e968b3-28b27820f26ace6b2926-352b2b2b5199c4c98144-44c1b3b2b57cb721febd

						$requestUpdate = $this->model->setTokenUser($idpersona,$token);

						//

						$dataUsuario = array('nombreUsuario' => $nombreUsuario,
												'email' => $strEmail,
												'asunto' => 'Recuperar cuenta - '.NOMBRE_REMITENTE,
												'url_recovery' => $url_recovery );
						//$this->model->setTokenUser($idpersona,$token);

						

						if ($requestUpdate)
						{
							$sendEmail = sendEmail($dataUsuario,'email_cambioPassword');
							if ($sendEmail)
							{
								$arrResponse = array('status' => true , 'msg' => 'Se ha enviado un email a tu cuenta de correo para cambiar tu contraseña.');	
							}
							else
							{
								 $arrResponse = array('status' => false , 'msg' => 'No es posible realizar el proceso, intenta mas tarde.');
							}	
						}
						else
						{
						 $arrResponse = array('status' => false , 'msg' => 'No es posible realizar el proceso, intenta mas tarde.');
						}
					}
				}
				//sleep(3);
				echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);	
			}//
			die();
		}

		public function confirmUser(string $params)
		{
			if (empty($params)) {
				header('Location: '.base_url());
			}
			else
			{
				//La función explode de php se encarga convertir en arreglo y de dividir o separar una cadena con delimitador
				$arrParams = explode(',', $params);
				$strEmail = strClean($arrParams[0]);
				$strToken = strClean($arrParams[1]);
				//dep($arrParams);
				$arrResponse = $this->model->getUsuario($strEmail,$strToken);
				if (empty($arrResponse)){
					header("Location: ".base_url());
				}
				else
				{
					$data['page_tag'] = "Cambiar contraseña";
					$data['page_name'] = "cambiar_contraseña";
					$data['page_title'] = "Cambiar Contraseña";
					$data['email'] = $strEmail;
					$data['token'] = $strToken;
					$data['idpersona'] = $arrResponse['idpersona'];
					$data['page_functions_js'] = "functions_login.js";
					$this->views->getView($this,"cambiar_password",$data);
				}
			}
			die();	
		}

		public function setPassword(){
			//dep($_POST);
			if(empty($_POST['idUsuario']) || empty($_POST['txtEmail']) || empty($_POST['txtToken']) || empty($_POST['txtPassword']) || empty($_POST['txtPasswordConfirm'])){

				$arrResponse = array('status' => false,
									 'msg' => 'Error de datos');

			}else{
				$intIdpersona = intval($_POST['idUsuario']);
				$strPassword = $_POST['txtPassword'];		
				$strPasswordConfirm = $_POST['txtPasswordConfirm'];
				$strEmail = strClean($_POST['txtEmail']);
				$strToken = strClean($_POST['txtToken']);

				if($strPassword != $strPasswordConfirm){
					$arrResponse = array('status' => false,
										 'msg' => 'Las contraseñas no son iguales.');

				}else{
					$arrResponseUser = $this->model->getUsuario($strEmail,$strToken);
					if(empty($arrResponseUser)){
						$arrResponse = array('status' => false, 
											'msg' => 'Error de datos.');

					}else{
						$strPassword = hash("SHA256",$strPassword);
						$requestPass = $this->model->insertPassword($intIdpersona,$strPassword);
                       //
						if ($requestPass){
							$arrResponse = array('status' => true,
												 'msg' => 'Contraseña actualizada con exito.');

						}else{
							$arrResponse = array('status' => false,
												 'msg' => 'No es posible actualizar el proceso, intente mas tarde.' );
						}
					}
				}
			}
			//sleep(3);
			echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
			die();
		}	
	}
 ?>