<?php 


/**
 * 
 */
class Conexion
{
    private $conect;

    public function __construct(){
        //nombre de conexion, de la bd, y codificacion de la conexion
        $connectionString = "mysql:host=".DB_HOST.";dbname=".DB_NAME.";.DB_CHARSET.";
        try {
            //se hace la conexion con el nombre del host, el nombre de usuario y contrasena
            $this->conect = new PDO($connectionString, DB_USER, DB_PASSWORD);
            //con esta linea detecto los herrores
            $this->conect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
           // echo "Conexion Exitosa"."<br>";
            //aqui capturo si hay un error
        } catch (Exception $e) {
            //le doy a la variable conect el mensaje de error
            $this->conect ='Error de conexion';
            //muestro de que se trata el error
            echo "ERROR: ". $e->getMessage();
        }
    }

    
    //al ser conect una variable privada se debe crear una funcion para poder ser utilizada en otro archivo donde sea llamdao
    public function conect()
    {
        return $this->conect;
    }
    
}


 ?>