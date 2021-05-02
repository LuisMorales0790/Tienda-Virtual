<?php 
/**
 * //clase padre de CRUD
 */
class Mysql extends Conexion
{
	
	private $conexion;
	private $strquery;
	private $arrValues;

	function __construct()
	{   //creo el ojeto conexion de la clase Conexion
		$this->conexion = new Conexion();
		//obtenemos el metodo conect de la clase Conexion y lo guardamos en $this->conexion para luego trabajar con el
		$this->conexion = $this->conexion->conect();
	}

	//Insertar Registros
	public function insert(string $query, array $arrValues)
	{
		$this->strquery = $query;
		$this->arrValues = $arrValues;
		$insert = $this->conexion->prepare($this->strquery);
		$resInsert = $insert->execute($this->arrValues);

		if ($resInsert) 
		{
			$lastInsert = $this->conexion->lastInsertId();
		}
		else
		{
			$lastInsert = 0;
		}
		return $lastInsert;
	}

	//Devuelve un registro
	public function select(string $query)
	{
		$this->strquery = $query;
		$result = $this->conexion->prepare($this->strquery);
		$result->execute();
		//ESTO DEVUELVE UN ARREGLO CON UN REGISTRO
		$data = $result->fetch(PDO::FETCH_ASSOC);
		return $data;
	}

	//Devuelve todos los registros
	public function select_all(string $query)
	{
		$this->strquery = $query;
		$result = $this->conexion->prepare($this->strquery);
		$result->execute();
		//ESTO DEVUELVE MAS DE UN REGISTRO
		$data = $result->fetchall(PDO::FETCH_ASSOC);
		return $data;
	}

	//Actualiza registro
	public function update(string $query, array $arrValues)
	{
		$this->strquery = $query;
		$this->arrValues = $arrValues;

		$update = $this->conexion->prepare($this->strquery);
		$resExecute = $update->execute($this->arrValues);
		return $resExecute;
	}

	//Eliminar un registro
	public function delete(string $query)
	{
		$this->strquery = $query;
		$result = $this->conexion->prepare($this->strquery);
		$userDelete =	$result->execute();
		//devuelve un true = 1
		return $userDelete;
	}

}



 ?>