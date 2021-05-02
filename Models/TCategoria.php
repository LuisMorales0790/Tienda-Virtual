<?php 
require_once("Libraries/Core/Mysql.php");
trait TCategoria
{
	private $con;
															// AND idcategoria IN ($categorias) recibe mas de una categoria por ejemplo(1,2,3)
	public function getCategoriasT(string $categorias)
	{
		$this->con = new Mysql();
		$sql = "SELECT idcategoria, nombre, descripcion, portada, ruta
		FROM categoria WHERE status != 0 AND idcategoria IN ($categorias)";
		$request = $this->con->select_all($sql);
		if (count($request) > 0)
		{
			for ($c=0; $c < count($request) ; $c++)
			{ 
				//al nombre de la imagen(portada) se le agrega la ruta de la imagen en el servidor
				$request[$c]['portada'] = BASE_URL.'/Assets/images/uploads/'.$request[$c]['portada'];
			}
		}
		return $request;
	}
}


 ?>