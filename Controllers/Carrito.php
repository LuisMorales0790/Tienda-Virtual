<?php 
/**Este es un controlador que se connecta con su clase padre Controller por medio del constructor y a su ves este se conecta con el metodo homeModel
 * 
 */
require_once("Models/TCategoria.php");
require_once("Models/TProducto.php");
require_once("Models/TTipoPago.php");
require_once("Models/TCliente.php");
class Carrito extends Controllers
{
	//con traits puedo hacer herencias multiples 
	use TCategoria, TProducto, TTipoPago, TCliente;
	public function __construct()
	{
		 parent::__construct();
		 session_start();
	}

	public function carrito()
	{
		//hacemos referencia a la vista home para enviarle informacion
		//$data['page_id'] = 1;
		//dep($this->model->getCategorias());
       //dep($this->getCategoriasT(CAT_SLIDER));
      // exit();
		//dep($this->selectProductos());
		//exit();
		$data['page_tag'] = NOMBRE_EMPRESA.' - Carrito';
		$data['page_title'] ='Carrito de compras';
		$data['page_name'] = "carrito";
		$this->views->getView($this,"carrito",$data);
	}

	public function procesarpago()
	{
		if (empty($_SESSION['arrCarrito']))
		{
			header("Location: ".base_url());
			die();
		}
                           // id del pedido
		$infoOrden = $this->getPedido(3);
		$dataEmailOrden = array('pedido' => $infoOrden);
		$mail = getFile("Template/Email/confirmar_orden", $dataEmailOrden);
		dep($mail);

		$data['page_tag'] = NOMBRE_EMPRESA.' - Procesar Pago';
		$data['page_title'] ='Procesar Pago';
		$data['page_name'] = "procesarpago";
		$data['tiposPago'] = $this->getTiposPagoT();
		$this->views->getView($this,"procesarpago",$data);
	}

	//funcion que muestra del id de la sesion
/*	public function setDetalleTemp()
	{
		$sid = session_id();
		//echo $_SESSION['idUser'];
		//echo "<br>";
		//echo $sid;
		//dep($_SESSION['arrCarrito']);

		$arrPedido = array('idcliente' => $_SESSION['idUser'],
						    'idtransaccion' => $sid ,
						    'productos' => $_SESSION['arrCarrito']                              
						  );
		//dep($arrPedido);
		$this->insertDetalleTemp($arrPedido);  *
	} */


	public function insertar()
	{
		$data = $this->model->setUser("Zehibell", 26);
		print_r($data);
	}

	public function verusuario($id)
	{
		$data = $this->model->getUser($id);
		print_r($data);
	}

	public function actualizar()
	{
		$data = $this->model->updateUser(1,"Eduardo",29);
		print_r($data);
	}

	public function verusuarios()
	{
		$data = $this->model->getUsers();
		print_r("<pre>");
		print_r($data);
		print_r("</pre>");
	}

	public function eliminar($id)
	{
		$data = $this->model->deleteUser($id);
		print_r($data);
	}

} 

?>