<?php 

	/**
	 * 
	 */
	class RolesModel extends Mysql
	{
		public $intIdrol;
		public $strRol;
		public $strDescripcion;
		public $intStatus;
		
		public function __construct()
		{
			//esto es para que se ejecute el constructor de la clase padre Mysql
			parent::__construct();
		}

		public function selectRoles()
		{
			$whereAdmin = "";
			if($_SESSION ['idUser'] != 1)
			 {
			 	$whereAdmin = " and idrol != 1 ";
			 }
			//EXTRAER ROLES
			$sql = "SELECT * FROM rol WHERE status != 0".$whereAdmin;
			$request = $this->select_all($sql);
			return $request;
		}

		//BUSCAR ROLE
		public function selectRol(int $idrol)
		{
			//recibo el idrol y lo guardo en intIdrol
			$this->intIdrol = $idrol;
			//creo la consulta y la guardo en la variable $sql
			$sql = "SELECT * FROM rol WHERE idrol = $this->intIdrol";
			//hago con consulta por medio de la funcion select()
			$request = $this->select($sql);
			//retorno la respuesta para ser mostrada
			return $request;
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

		public function insertRol(string $rol, string $descripcion, int $status)
		{

			$return = "";
			$this->strRol = $rol;
			$this->strDescripcion = $descripcion;
			$this->intStatus = $status; 

			//consulta si existe el rol
			$sql = "SELECT * FROM rol WHERE nombrerol = '{$this->strRol}' ";
			//selecciona todos los filas(registros) donde este ese rol
			$request = $this->select_all($sql);

			//si esta vacio es que no existe el rol
			if (empty($request))
			{
				//entoces inserto los valores para crear el rol a la base de datos
				$query_insert = "INSERT INTO rol(nombrerol,descripcion,status) values(?,?,?)";
				// creo un arreglo con los valores
				$arraData = array($this->strRol, $this->strDescripcion, $this->intStatus);
				//envio al metodo insert las dos variables para insertar los datos a la BD
				$request_insert = $this->insert($query_insert,$arraData);
				//retorno el id del registro insertado
				$return = $request_insert;	
			}
			else
			{
				$return = "exist";
			}
			return $return;
		}

		public function updateRol(int $idrol, string $rol, string $descripcion, int $status)
		{
			$this->intIdrol = $idrol;
			$this->strRol = $rol;
			$this->strDescripcion = $descripcion;
			$this->intStatus = $status;

			//consulta donde se verifica si existe la fila que se quiere editar por medio de el idrol
			//si existe en la tabla rol el nombrerol pero no el idrol que se envio no existe en la BD, quiere decir que esta fila no existe por ende $request va a estar vacio en tal caso habria que crear ese registro o ese rol
			$sql = "SELECT * FROM rol WHERE nombrerol = '$this->strRol' AND idrol != $this->intIdrol";
			//tre todas las filas que tengas ese id
			$request = $this->select_all($sql); 
			// si $request esa vacio quiere decir que la fila (idrol) ya existe osea que se puede editar 
			if (empty($request)) 
			{
				// actualiza en la tabla rol en los campos seleccionados  
				$sql = "UPDATE rol SET nombrerol = ?, descripcion = ?, status = ? WHERE idrol = $this->intIdrol";
				// Actualiza los campos seleccionados con los siguientes datos
				$arraData = array($this->strRol, $this->strDescripcion, $this->intStatus);
				//envio los campos y los datos nuevos
				$request = $this->update($sql,$arraData);
			}
			else
			{
				//si $request no esta vacio quiere decir que no hay filas con ese idrol y por ende no se puede editar
				$request = "exist";
			}

			return $request;

		}

		public function deleteRol(int $idrol)
		{
			$this->intIdrol = $idrol;
			$sql = "SELECT * FROM persona WHERE rolid = $this->intIdrol";
			$request = $this->select_all($sql);
			if(empty($request))
			{
				$sql = "UPDATE rol SET status = ? WHERE idrol = $this->intIdrol ";
				$arraData = array(0);
				$request = $this->update($sql, $arraData);
				if ($request) 
				{
					$request = 'ok';
				}
				else
				{
					$request = 'error';
				}
			}
			else
			{
				$request = 'exist';
			}

			return $request;	
		}
	
	}


 ?>