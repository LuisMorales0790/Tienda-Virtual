<?php 
/**Este es un controlador que se connecta con su clase padre Controller por medio del constructor y a su ves este se conecta con el metodo homeModel
 * 
 */
require_once("Models/TCategoria.php");
require_once("Models/TProducto.php");
require_once("Models/TCliente.php");
require_once("Models/LoginModel.php");
class Tienda extends Controllers
{
	//con traits puedo hacer herencias multiples 
	use TCategoria, TProducto, TCliente;
	public $login;
	public function __construct()
	{
		 parent::__construct();
		 session_start();
		 $this->login = new LoginModel();
	}

	public function tienda()
	{
		$data['page_tag'] = NOMBRE_EMPRESA;
		$data['page_title'] = NOMBRE_EMPRESA;
		$data['page_name'] = "tienda";
		$data['productos'] = $this->getProductosT();
		//dep($data);
		//exit();
		$this->views->getView($this,"tienda",$data);
	}

	public function categoria($params)
	{
		if (empty($params))
		{
			header("Location:".base_url());
		}
		else
		{
			//echo $params;
			//exit;
			//explode busca las comas dentro del parametro para convertirlo en un arreglo
			$arrParams = explode(",",$params);
			//dep($arrParams);
			//exit;
			$idcategoria = intval($arrParams[0]);
			$ruta = strClean($arrParams[1]);
			$infoCategoria = $this->getProductosCatergoriaT($idcategoria,$ruta);
			//dep($infoCategoria);
			//exit;
			$categoria = strClean($params);
			//dep($this->getProductosCatergoriaT($categoria));
			$data['page_tag'] = NOMBRE_EMPRESA." - ".$infoCategoria['categoria'];
			$data['page_title'] = $infoCategoria['categoria'];
			$data['page_name'] = "categoria";
			$data['productos'] = $infoCategoria['productos'];
			$this->views->getView($this,"categoria",$data);
		}
	}

	public function producto($params)
	{
		if (empty($params))
		{
			header("Location:".base_url());
		}
		else
		{
			$arrParams = explode(",",$params);
			$idproducto = intval($arrParams[0]);
			$ruta = strClean($arrParams[1]);
			$infoProducto = $this->getProductoT($idproducto,$ruta);
			//dep($infoProducto);
			//die();
			if (empty($infoProducto))
			{
				header("Location:".base_url());
			}
			$data['page_tag'] = NOMBRE_EMPRESA." - ".$infoProducto['nombre'];
			$data['page_title'] = $infoProducto['nombre'];     
			$data['page_name'] = "producto";
			$data['producto'] = $infoProducto;             //r = aleatoria ,a= ascendente, d= descendente
			$data['productos'] = $this->getProductosRandom($infoProducto['categoriaid'],8,"r");
			$this->views->getView($this,"producto",$data);
		}
	}

	public function addCarrito()
	{
		if ($_POST)
		{
			//elimina variable de session
			//unset($_SESSION['arrCarrito']);exit();
			$arrCarrito = array();
			$cantCarrito = 0;
			$idproducto = openssl_decrypt($_POST['id'], METHODENCRIPT, KEY);
			$cantidad = $_POST['cant'];
			if(is_numeric($idproducto) and is_numeric($cantidad))
			{
				$arrInfoProducto = $this->getProductoIDT($idproducto);
				//dep($arrInfoProducto);
				//die();
				if (!empty($arrInfoProducto))
				{
					$arrproducto = array('idproducto' => $idproducto,
										 'producto' => $arrInfoProducto['nombre'],
										 'cantidad' => $cantidad,
										 'precio' => $arrInfoProducto['precio'],
										 'imagen' => $arrInfoProducto['images'][0]['url_image']
										);
					if (isset($_SESSION['arrCarrito']))
					{
						//ya creada una variable de sesion esta se pasa a una variable con la info del rpoducto
					  	$on = true;
						$arrCarrito = $_SESSION['arrCarrito'];
						for ($pr=0; $pr < count($arrCarrito); $pr++) 
						{    //si el id del producto ya existe dentro del carrito se le suma la cantidad de ese producto
							if($arrCarrito[$pr]['idproducto'] == $idproducto)
							{
								$arrCarrito[$pr]['cantidad'] += $cantidad;
								$on = false;
							}
						}
						//esto ocurre en el caso de que ya exista una variable de sesion pero se quiere agregar un nuevo producto al carrito con un id diferente
						// si $on es verdadero vamos a agregar al carrito porque es un nuevo producto
						if ($on)
						{
							array_push($arrCarrito, $arrproducto);
						}
						//se agrega a la variable de sesion la variable $arrCarrito con los nuevos productos agregados 
						$_SESSION['arrCarrito'] = $arrCarrito;
					}
					else
					{
						//se agrega el primer producto al carrito y se crea una variable de sesion 
						array_push($arrCarrito, $arrproducto);
						$_SESSION['arrCarrito'] = $arrCarrito;
					}
					//sumar cantidad de productos que se mostraran en el carrito
					foreach ($_SESSION['arrCarrito'] as $pro)
					{
						$cantCarrito += $pro['cantidad'];
						//dep($pro);
					}
					//dep($_SESSION['arrCarrito']);
					//exit;
					$htmlCarrito = "";
					$htmlCarrito = getFile('Template/Modals/modalCarrito',$_SESSION['arrCarrito']);
					$arrResponse = array("status" => true,
										 "msg" => '!Se agrego al carrito',
										 "cantCarrito" => $cantCarrito,
										 "htmlCarrito" => $htmlCarrito);
				}
				else
				{
					$arrResponse = array('status' => false, "msg" => 'Producto no existente.');
				}
			}
			else
			{
				$arrResponse = array('status' => false, "msg" => 'Datos incorrectos.');
			}
			//echo $idproducto.' - '.$_POST['cant'];
			//dep($_POST);
			echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
		}
		die();
	}

	public function delCarrito()
	{
		if ($_POST)
		{
			//dep($_POST);
			//exit();
			$arrCarrito = array();
			$cantCarrito = 0;
			$subtotal = 0;
			$idproducto = openssl_decrypt($_POST['id'], METHODENCRIPT, KEY);
			$option = $_POST['option'];
			if (is_numeric($idproducto) and ($option == 1 or $option == 2))
			{
				$arrCarrito = $_SESSION['arrCarrito'];
				for ($pr=0; $pr < count($arrCarrito); $pr++)
				{ 
					if ($arrCarrito[$pr]['idproducto'] == $idproducto)
					{
						unset($arrCarrito[$pr]);
					}
				}
				//dep($arrCarrito);
				//sort ordena el array
				sort($arrCarrito);
				$_SESSION['arrCarrito'] = $arrCarrito;
				foreach ($_SESSION['arrCarrito'] as $pro)
				{
					//cantida del productos del carrito
					$cantCarrito += $pro['cantidad'];
					//guarda suma del resultado de la cantidad de cada producto dentro del carrito por el precio indicado
					$subtotal += $pro['cantidad'] * $pro['precio'];
				}
				$htmlCarrito = "";
				if ($option == 1)
				{
				   	$htmlCarrito = getFile('Template/Modals/modalCarrito',$_SESSION['arrCarrito']);
				}
				$arrResponse = array("status" => true,
										 "msg" => '!Producto eliminado!',
										 "cantCarrito" => $cantCarrito,
										 "htmlCarrito" => $htmlCarrito,
										 "subTotal" => SMONEY.formatMoney($subtotal),
										 "total" => SMONEY.formatMoney($subtotal + COSTOENVIO)
									);
			}
			else
			{
				$arrResponse = array("status" => false, "msg" => 'Datos incorrecto.');
			}
			echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
		}
		die();
	}

	public function updCarrito()
	{
		if ($_POST) {
			//dep($_POST);
			//die;

			$arrCarrito = array();
			$totalProducto = 0;
			$subtotal = 0;
			$total = 0;
			$idproducto = openssl_decrypt($_POST['id'], METHODENCRIPT, KEY);
			$cantidad = intval($_POST['cantidad']);
			//echo $idproducto;
			//echo $cantidad;
			//exit();
			if (is_numeric($idproducto) and $cantidad > 0)
			{
				$arrCarrito = $_SESSION['arrCarrito'];
				for($p=0; $p < count($arrCarrito) ; $p++)
				{ 
					if ($arrCarrito[$p]['idproducto'] == $idproducto)
					{
						$arrCarrito[$p]['cantidad'] = $cantidad;
						$totalProducto = $arrCarrito[$p]['precio'] * $cantidad;
						break;
					}
				}				
				$_SESSION['arrCarrito'] = $arrCarrito;
				//dep($arrCarrito);
				//dep($_SESSION['arrCarrito']);
				//exit();
				foreach ($_SESSION['arrCarrito'] as $pro)
				{
					$subtotal += $pro['cantidad'] * $pro['precio'];
				}
				//echo $totalProducto;
				//dep($arrCarrito);
				//echo $subtotal;
				//exit();
				$arrResponse = array("status" => true,
									 "msg" => '!Producto actualizado!',
									 "totalProducto" => SMONEY.formatMoney($totalProducto),
									 "subTotal" => SMONEY.formatMoney($subtotal),
									 "total" => SMONEY.formatMoney($subtotal + COSTOENVIO)
									);
			}
			else
			{
				$arrResponse = array('status' => false, "msg" => 'Dato incorrecto.');
			}
			echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
		}
		die();
	}

		public function registro()
	{
	
	 	error_reporting(0);
		if ($_POST) 
		{ 
			//dep($_POST);exit;

			if (empty($_POST['txtNombre']) || empty($_POST['txtApellido']) || empty($_POST['txtTelefono']) || empty($_POST['txtEmailCliente'])) 
			{
				$arrResponse = array("status" => false , "msg" => 'Datos incorrectos.' );
			}
			else
			{
				//ucwords convierte la primera letra de la palabra en mayuscula
				//strtolower convierte toda la palabra en minuscula
				$strNombre         = ucwords(strClean($_POST['txtNombre']));
				$strApellido       = ucwords(strClean($_POST['txtApellido']));
				$intTelefono       = intval(strClean($_POST['txtTelefono']));
				$strEmail          = strtolower(strClean($_POST['txtEmailCliente']));
				$intTipoId = 7;
				$request_user = "";

				//pasword sin encriptar para enviarcelo al cliente via web
					$strPassword = passGenerator();
					//password encriptado para enviarlo a la BD
					$strPasswordEncript = hash("SHA256",$strPassword);
					// $request_user devuelve el id del ultimo registro ingresado a la BD
					$request_user = $this->insertCliente($strNombre,
														$strApellido,
														$intTelefono,
														$strEmail,
														$strPasswordEncript,
														$intTipoId );

				// $request_user devuelve el id del ultimo registro ingresado a la BD
				if ($request_user > 0)
				{
					//Creando variables de sesion para el ingreso del cliente a la tienda
					$arrResponse = array("status" => true , "msg" => 'Datos guardados correctamente.');
						$nombreUsuario = $strNombre.' '.$strApellido;
						$dataUsuario = array('nombreUsuario' => $nombreUsuario,
												'email' => $strEmail,
												'password' => $strPassword,
												'asunto' => 'Bienvenido a tu tienda en linea');

						$_SESSION['idUser'] = $request_user;
						$_SESSION['login'] = true;
						$this->login->sessionLogin($request_user);
						//sendEmail($dataUsuario,'email_bienvenida');
				}
				else if ($request_user == 'exist') 
				{
					$arrResponse = array("status" => false , "msg" => '!Atencion! el email ya existe, ingrese otro.');
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

	public function procesarVenta()
	{
		//dep($_POST);
		//exit();
		if($_POST)
		{
			$idtransaccionpaypal = NULL;
			$datospaypal = NULL;
			$personaid = $_SESSION['idUser'];
			$monto = 0;
			$tipopagoid = intval($_POST['inttipopago']);
			$direccionenvio = strClean($_POST['direccion']).', '.strClean($_POST['ciudad']);
			$status = "Pendiente";
			$subtotal = 0;
			$costo_envio = COSTOENVIO;

			if(!empty($_SESSION['arrCarrito']))
			{
				foreach ($_SESSION['arrCarrito'] as $pro)
				{
					$subtotal += $pro['cantidad'] * $pro['precio'];
				}
				$monto = formatMoney($subtotal + COSTOENVIO);
				//si no se paga con paypal y se paga con contraentrega
				if(empty($_POST['datapay']))
				{
					//aqui se procesa el pedido cuando no se paga con paypal
					$request_pedido = $this->insertPedido($idtransaccionpaypal,
																  $datospaypal,
															      $personaid,
															      $costo_envio,
															      $monto,
															      $tipopagoid,
															      $direccionenvio,
															      $status);
					// pedidoid
					if ($request_pedido > 0) 
					{
						# aqui se ingresa a la bd los productos de esa venta
						foreach ($_SESSION['arrCarrito'] as $producto)
						{
							$productoid = $producto['idproducto'];
							$precio = $producto['precio'];
							$cantidad = $producto['cantidad'];
							$this->insertDetalle($request_pedido,$productoid,$precio,$cantidad);
						}

						//para enviar el mailing por correo
						/*$infoOrden = $this->getPedido($request_pedido);
						$dataEmailOrden = array('asunto' => "Se ha creado la orden No .".$request_pedido,
												'email' => $_SESSION['userData']['email_user'],
												'emailCopia' => EMAIL_PEDIDOS,
												'pedido' => $infoOrden);

						sendEmail($dataEmailOrden,"email_notificacion_orden"); */
						$orden = openssl_encrypt($request_pedido, METHODENCRIPT, KEY);
						$transaccion = openssl_encrypt($idtransaccionpaypal, METHODENCRIPT, KEY); 
						$arrResponse = array("status" => true,
											"orden" => $orden,
											"transaccion" => $transaccion,
										     "msg" => 'Pedido realizado con exito'
										  );
						$_SESSION['dataorden'] = $arrResponse;
						//destruimos la veriable de session que corresponde al carrito
						unset($_SESSION['arrCarrito']);
						//restablecemos el id de la sesion activa
						session_regenerate_id(true);
					}
				}
				else
				{
					//pago paypal
					$jsonPaypal = $_POST['datapay']; //formato json
					$objPaypal = json_decode($jsonPaypal); // tipo objeto
					$status = "Aprobado";

					if(is_object($objPaypal))
					{
						//esto se guardara en la BD en formato json
						$datospaypal = $jsonPaypal; 
						//con esto accedo al objeto y me dirijo a cada uno de los elementos señalados
						$idtransaccionpaypal = $objPaypal->purchase_units[0]->payments->captures[0]->id;
						if ($objPaypal->status == "COMPLETED")
						{
							$totalPaypal = formatMoney($objPaypal->purchase_units[0]->amount->value);
							if($monto == $totalPaypal) {
								$status = "completo";
							}

							//crear pedido
							//aqui se procesa el pedido cuando se paga con paypal
							$request_pedido = $this->insertPedido($idtransaccionpaypal,
																  $datospaypal,
															      $personaid,
															      $costo_envio,
															      $monto,
															      $tipopagoid,
															      $direccionenvio,
															      $status);
							// pedidoid
							if ($request_pedido > 0) 
							{
								# aqui se ingresa a la bd los productos de esa venta
								foreach ($_SESSION['arrCarrito'] as $producto)
								{
									$productoid = $producto['idproducto'];
									$precio = $producto['precio'];
									$cantidad = $producto['cantidad'];
									$this->insertDetalle($request_pedido,$productoid,$precio,$cantidad);
								}

								//para enviar el mailing por correo
								                      // id del pedido
							/*	$infoOrden = $this->getPedido($request_pedido);
								$dataEmailOrden = array('asunto' => "Se ha creado la orden No .".$request_pedido,
														'email' => $_SESSION['userData']['email_user'],
														'emailCopia' => EMAIL_PEDIDOS,
														'pedido' => $infoOrden);

								sendEmail($dataEmailOrden,"email_notificacion_orden"); */

								$orden = openssl_encrypt($request_pedido, METHODENCRIPT, KEY);
								$transaccion = openssl_encrypt($idtransaccionpaypal, METHODENCRIPT, KEY); 
								$arrResponse = array("status" => true,
													"orden" => $orden,
													"transaccion" => $transaccion,
												     "msg" => 'Pedido realizado con exito'
												  );
								$_SESSION['dataorden'] = $arrResponse;
								//destruimos la veriable de session que corresponde al carrito
								unset($_SESSION['arrCarrito']);
								//restablecemos el id de la sesion activa
								session_regenerate_id(true);
							}
							else
							{
								$arrResponse = array("status" => false , "msg" => 'No es posible procesar el pedido.');
							}
						}
						else
						{
							$arrResponse = array("status" => false , "msg" => 'No es posible completar el pago con PayPal.');
						}
					}
					else
					{
						$arrResponse = array("status" => false, "msg" => 'Hubo un error en la trasaccion.');
					}
				}
			}
			else
			{
				$arrResponse = array("status" => false, "msg" => 'No es posible procesar el pedido.');

			}
		}
		else
		{
			$arrResponse = array("status" => false, "msg" => 'No es posible procesar el pedido.');
		}
		echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
		die();
	}

	public function confirmarpedido()
	{
		if (empty($_SESSION['dataorden'] )) 
		{
			header("Location: ".base_url());
		}
		else
		{   
			$dataorden = $_SESSION['dataorden'];  
			$idpedido = openssl_decrypt($dataorden['orden'], METHODENCRIPT, KEY);      
			$transaccion = openssl_decrypt($dataorden['transaccion'], METHODENCRIPT, KEY); 
            $data['page_tag'] = "Confirmar Pedido";
            $data['page_title'] = "Confirmar Pedido";
            $data['page_name'] = "confirmarpedido";
            $data['orden'] = $idpedido;
            $data['transaccion'] = $transaccion;

            $this->views->getView($this,"confirmarpedido",$data);
		}
		//destruir variable de sesion
		unset($_SESSION['dataorden']);
	}

} 

?>