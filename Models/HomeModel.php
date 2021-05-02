<?php 

	/**
	 * 
	 */
	//require_once("CategoriasModel.php");
	class HomeModel extends Mysql
	{
	
		private $objCategoria;	
		public function __construct()
		{
			//esto es para que se ejecute el constructor de la clase padre Mysql
			parent::__construct();
			//$this->objCategoria = new CategoriasModel();
		}

		public function setUser(string $nombre, int $edad)
		{
			$query_insert = "INSERT INTO usuario(nombre,edad) VALUES(?,?)";
			 $arraData = array($nombre, $edad);
			 $request_insert = $this->insert($query_insert,$arraData);
			 return $request_insert;
		}

		public function getUser($id)
		{
			$sql = "SELECT * FROM usuario WHERE id = $id ";
			$request = $this->select($sql);
			return $request;
		}

		public function updateUser(int $id, string $nombre, int $edad)
		{
			$sql = "UPDATE usuario SET nombre=?, edad=? WHERE id=$id";
			$arraData = array($nombre, $edad); 
			$request = $this->update($sql,$arraData);
			return $request;
		}

		public function getUsers()
		{
			$sql = "SELECT * FROM usuario";
			$request = $this->select_all($sql);
			return $request;
		}

		public function deleteUser($id)
		{
			$sql = "DELETE FROM usuario WHERE id = $id ";
			$request = $this->delete($sql);
			return $request;
		}

		public function getCategorias()
		{
			//return $this->objCategoria->selectCategorias();
		}

	
	}


 ?>