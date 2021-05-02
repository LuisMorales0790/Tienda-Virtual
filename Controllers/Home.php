<?php 
/**Este es un controlador que se connecta con su clase padre Controller por medio del constructor y a su ves este se conecta con el metodo homeModel
 * 
 */
require_once("Models/TCategoria.php");
require_once("Models/TProducto.php");
class Home extends Controllers
{
	//con traits puedo hacer herencias multiples 
	use TCategoria, TProducto;
	public function __construct()
	{
		 parent::__construct();
		 session_start();
	}

	public function home()
	{
		//hacemos referencia a la vista home para enviarle informacion
		//$data['page_id'] = 1;
		dep($this->model->getCategorias());
       //dep($this->getCategoriasT(CAT_SLIDER));
      // exit();
		//dep($this->selectProductos());
		//exit();
		$data['page_tag'] = NOMBRE_EMPRESA;
		$data['page_title'] = NOMBRE_EMPRESA;
		$data['page_name'] = "tienda_virtual";
		$data['slider'] = $this->getCategoriasT(CAT_SLIDER);
		$data['banner'] = $this->getCategoriasT(CAT_BANNER);
		$data['productos'] = $this->getProductosT();
		//dep($data);
		//exit();
		$this->views->getView($this,"home",$data);
	}

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