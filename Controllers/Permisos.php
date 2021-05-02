<?php 
/**Este es un controlador que se connecta con su clase padre Controller por medio del constructor y a su ves este se conecta con el metodo homeModel
 * 
 */
class Permisos extends Controllers
{
	
	public function __construct()
	{
		 parent::__construct();
	}

	public function getPermisosRol(int $idrol)
	{
		$rolid = intval($idrol);
		if ($rolid > 0)
		{
			$arrModulos = $this->model->selectModulos();
			$arrPermisosRol = $this->model->slectPermisosRol($rolid);

			//dep($arrModulos);
			//dep($arrPermisosRol);

			$arrPermisos = array('r' => 0, 'w' => 0, 'u' => 0, 'd' => 0);
			$arrPermisoRol = array('idrol' => $rolid );

			if (empty($arrPermisosRol)) 
			{
				for ($i=0; $i < count($arrModulos); $i++)
				{ 
					$arrModulos[$i]['permisos'] = $arrPermisos;
				}
			}
			else
			{
				//en caso de que el id ya tenga permisos asignados a los modulos
				for ($i=0; $i < count($arrModulos); $i++)
				{ 
					$arrPermisos = array('r' => 0, 'w' => 0, 'u' => 0, 'd' => 0);
					if (isset($arrPermisosRol[$i]))
					{
						$arrPermisos = array('r' => $arrPermisosRol[$i]['r'],
										 'w' =>	$arrPermisosRol[$i]['w'],
										 'u' =>	$arrPermisosRol[$i]['u'],
										 'd' =>	$arrPermisosRol[$i]['d']
										);	# code...
					}
					$arrModulos[$i]['permisos'] = $arrPermisos;
				}
			}
			$arrPermisoRol['modulos'] = $arrModulos;
			$html = getModal("modalPermisos",$arrPermisoRol);
			//dep($arrPermisoRol);
		}
		die();
	}

	public function setPermisos()
	{
		if($_POST)
		{
			$intIdrol = intval($_POST['idrol']);
			$modulos = $_POST['modulos'];

			$this->model->deletePermisos($intIdrol);
			foreach ($modulos as $modulo) {
				$idmodulo = $modulo['idmodulo'];
				$r = empty($modulo['r']) ? 0 : 1;
				$w = empty($modulo['w']) ? 0 : 1;
				$u = empty($modulo['u']) ? 0 : 1;
				$d = empty($modulo['d']) ? 0 : 1;
				$requestPermiso = $this->model->insertPermisos($intIdrol, $idmodulo, $r, $w, $u, $d);
			}
			if ($requestPermiso > 0)
			{
				$arrResponse = array('status' => true, 'msg' => 'Permisos asignados correctamente');
			}
			else
			{
				$arrResponse = array('status' => false, 'msg' => 'No es posible asignar los permisos');
			}
			echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
		}
		die();
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