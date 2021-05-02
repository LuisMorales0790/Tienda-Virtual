<?php 
class Pedidos extends Controllers
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
		 getPermisos(MPEDIDOS);
	}

	public function Pedidos()
	{
		//hacemos referencia a la vista home para enviarle informacion
		if (empty($_SESSION['permisosMod']['r'])){
			header("Location:".base_url().'/dashboard');
		}
		$data['page_tag'] = "Pedidos";
		$data['page_title'] = "PEDIDOS <small>Tienda Virtual</small>";
		$data['page_name'] = "pedidos";
		$data['page_functions_js'] = "functions_pedidos.js";
		$this->views->getView($this,"pedidos",$data);
	}
	//$nombre_foto = $foto['name'];
	//$nombre_foto = $foto['name'];

	Public function getPedidos() //
	{
		if ($_SESSION['permisosMod']['r']) 
		{
			$idpersona = "";
			if ($_SESSION['userData']['idrol'] == RCLIENTES) {
				$idpersona = $_SESSION['userData']['idpersona'];
			}
			$arrData = $this->model->selectPedidos($idpersona);
			//dep($arrData);
			for($i=0; $i < count($arrData); $i++)
			{
				$btnView = '';
				$btnEdit = '';
				$btnDelete = '';


				  $arrData[$i]['transaccion'] = $arrData[$i]['referenciacobro'];
				  if ($arrData[$i]['idtransaccionpaypal'] != "") 
				  {
				  	$arrData[$i]['transaccion'] = $arrData[$i]['idtransaccionpaypal'];
				  }

				  $arrData[$i]['monto'] = SMONEY.formatMoney($arrData[$i]['monto']);

		       if ($_SESSION['permisosMod']['r'])
			       { 
			       		$btnView .= ' <a title="ver Detalle" href="'.base_url().'/pedidos/orden/'.$arrData[$i]['idpedido'].'" target="_blanck" class="btn btn-info btn-sm" title="Ver Detalle"> <i class="far fa-eye"></i> </a>

			       		 	<button class="btn btn-danger btn-sm" onclick="fntViewDPF('.$arrData[$i]['idpedido'].')" title="Generar PDF"><i class="fas fa-file-pdf"></i></button> ';

			       		 	if ($arrData[$i]['idtipopago'] == 1){
			       		 		$btnView .= ' <a title="ver Transacción" href="'.base_url().'/pedidos/transaccion/'.$arrData[$i]['idtransaccionpaypal'].'" target="_blanck" class="btn btn-info btn-sm" title="Ver Detalle"> <i class="fa fa-paypal" aria-hidden="true"></i> </a> ';
			       		 	}
			       		 	else
			       		 	{
			       		 		$btnView .= '<button class="btn btn-secondary btn-sm" disabled=""><i class="fa fa-paypal" aria-hidden="true"></i></button>';
			       		 	}
			       }

		        if ($_SESSION['permisosMod']['u'])
		        	{
		        		//el boton se va a enviar a traves de this para poder hacer uso de ese elemento en el js
		        	    $btnEdit = '<button class="btn btn-primary btn-sm" onclick="fntEditInfo(this,'.$arrData[$i]['idpedido'].')" title="Editar pedido"><i class="fas fa-pencil-alt"></i></button>';
		            }

		        if ($_SESSION['permisosMod']['d'])
			       {
		       			$btnDelete = '<button class="btn btn-danger btn-sm" onclick="fntDelInfo('.$arrData[$i]['idpedido'].')" title="Eliminar pedido"><i class="far fa-trash-alt"></i></button>';	
			    	}
		    	$arrData[$i]['options'] = '<div class="text-center">'.$btnView.' '.$btnEdit.' '.$btnDelete.'</div>'; 
			}
				 echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
		}
	    die();

	}

	public function orden($idpedido)
	{
		if (!is_numeric($idpedido)) {
			header("Location:".base_url().'/pedidos');
		}
		if (empty($_SESSION['permisosMod']['r'])){
			header("Location:".base_url().'/dashboard');
		}
		$idpersona = "";
		if($_SESSION['userData']['idrol'] == RCLIENTES){
			$idpersona = $_SESSION['userData']['idpersona'];
		}

		$pedido = $this->model->selectPedido($idpedido,$idpersona);
		//dep($pedido);

		$data['page_tag'] = "Pedidos - Tienda Virtual";
		$data['page_title'] = "PEDIDO <small>Tienda Virtual</small>";
		$data['page_name'] = "pedido";
		$data['arrPedido'] = $pedido;
		$this->views->getView($this,"orden",$data);
	}

	public function transaccion($transaccion)
	{
		if (empty($_SESSION['permisosMod']['r'])){
		header("Location:".base_url().'/dashboard');
		}
		$idpersona = "";
		if($_SESSION['userData']['idrol'] == RCLIENTES){
			$idpersona = $_SESSION['userData']['idpersona'];
		}

		$requestTransaccion = $this->model->selectTransPaypal($transaccion, $idpersona);
		//dep($requestTransaccion);exit;

		//dep($pedido);

		$data['page_tag'] = "Detalles de la Transacción - Tienda Virtual";
		$data['page_title'] = "<small>Detalles de la transacción</small>";
		$data['page_name'] = "detalle_transaccion";
		$data['page_functions_js'] = "functions_pedidos.js";
		$data['objTransaccion'] = $requestTransaccion;
		$this->views->getView($this,"transaccion",$data);
	}

	public function getTransaccion(string $transaccion)
	{
		if ($_SESSION['permisosMod']['r'] and $_SESSION['userData']['idrol'] != RCLIENTES) {
			if ($transaccion == "") {
				$arrResponse = array("status" => false, "msg" => 'Datos incorrectos.');
			}else{
				$transaccion = strClean($transaccion);
				$requestTransaccion = $this->model->selectTransPaypal($transaccion);
				//dep($requestTransaccion);
				if (empty($requestTransaccion)) {
					$arrResponse = array("status" => false, "msg" => "Datos no disponibles." );
				}else{
					$htmlModal = getFile("Template/Modals/modalReembolso",$requestTransaccion);
					$arrResponse = array("status" => true, "html" => $htmlModal);
				}
			}

			echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
		}
		die();
	}
	
	public function setReembolso()
	{
		if ($_POST) 
		{
			if($_SESSION['permisosMod']['u'] and $_SESSION['userData']['idrol'] != RCLIENTES){
				//dep($_POST);
				$transaccion = strClean($_POST['idtransaccion']);
				$observacion = strClean($_POST['observacion']);
				$requestTransaccion = $this->model->reembolsoPaypal($transaccion,$observacion);
				//dep($requestTransaccion);
				if ($requestTransaccion) {
					$arrResponse = array("status" => true, "msg" => "El reembolso se ha procesado con exito.");
				}
				else
				{
					$arrResponse = array("status" => false, "msg" => "No es posible procesar el reembolso.");
				}
		 	}
		 	else
		 	{
		 		$arrResponse = array("status" => false, "msg" => "No es posible realizar el proceso, consulte al administrador.");
		 	}

		 	echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
		}
		die();
	}

}

?>