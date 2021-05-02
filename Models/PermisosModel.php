<?php 

	/**
	 * 
	 */
	class PermisosModel extends Mysql
	{
		public $intIdpermisos;
		public $intRolid;
		public $intModuloid;
		public $r;
		public $w;
		public $u;
		public $d;
		
		public function __construct()
		{
			//esto es para que se ejecute el constructor de la clase padre Mysql
			parent::__construct();
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

		public function selectModulos()
		{
			$sql = "SELECT * FROM modulo WHERE status != 0";
			$request = $this->select_all($sql);
			return $request;
		}

		public function slectPermisosRol(int $idrol)
		{
			$this->intRolid = $idrol;
			$sql = "SELECT * FROM permisos WHERE rolid = $this->intRolid";
			$request = $this->select_all($sql);
			return $request;
		}

		public function deletePermisos(int $idrol)
		{
			$this->intRolid = $idrol;
			$sql = "DELETE FROM permisos WHERE rolid = $this->intRolid";
			$request = $this->delete($sql);
			return $request;
		}

		public function insertPermisos(int $idrol, int $idmodulo, int $r, int $w, int $u, int $d)
		{
			$return = "";
			$this->intRolid = $idrol;
			$this->intModuloid = $idmodulo;
			$this->r = $r;
			$this->w = $w;
			$this->u = $u;
			$this->d = $d;
			$query_insert = "INSERT INTO permisos(rolid,moduloid,r,w,u,d) VALUES(?,?,?,?,?,?)";
			 $arraData = array($this->intRolid, $this->intModuloid, $this->r, $this->w, $this->u, $this->d);
			 $request_insert = $this->insert($query_insert,$arraData);
			 return $request_insert;
		}

		public function permisosModulo(int $idrol)
		{
			$this->intRolid = $idrol;
			$sql = "SELECT p.rolid,
							p.moduloid,
							m.titulo as modulo,
							p.r,
							p.w,
							p.u,
							p.d
					FROM permisos p
					INNER JOIN modulo m
					ON p.moduloid = m.idmodulo
					WHERE p.rolid = $this->intRolid";
			$request = $this->select_all($sql);
			//dep($request);
			$arrPermisos = array();
			for ($i=0; $i < count($request); $i++) { 
				$arrPermisos[$request[$i]['moduloid']] = $request[$i];
			}
			return $arrPermisos;
		}
	}


 ?>