<?php 
/**Este es un controlador que se connecta con su clase padre Controller por medio del constructor y a su ves este se conecta con el metodo homeModel
 * 
 */
class Dashboard extends Controllers
{
	
	public function __construct()
	{
		 parent::__construct();

		 session_start();
		 //para que elimine los id anteriores de PHPSESSID de la sesion al recargar o cambiar de pagina y evitar vulnerabilidades
		 session_regenerate_id(true);

		 //si la variable es sesion esta vacia retorna al login
		 if(empty($_SESSION['login']))
		 {
		 	header('Location: '.base_url().'/login');
		 }
		 getPermisos(1);
	}

	public function dashboard()
	{
		//hacemos referencia a la vista home para enviarle informacion
		$data['page_id'] = 2;
		$data['page_tag'] = "Dashboard - Tienda Virtual";
		$data['page_title'] = "Dashboard - Tienda Virtual";
		$data['page_name'] = "dashboard";
		$data['page_functions_js'] = "functions_dashboard.js";
		$this->views->getView($this,"dashboard",$data);
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