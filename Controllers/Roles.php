<?php 
/**Este es un controlador que se connecta con su clase padre Controller por medio del constructor y a su ves este se conecta con el metodo homeModel
 * 
 */
class Roles extends Controllers
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
      getPermisos(2);
  }

  public function Roles()
  {
    //hacemos referencia a la vista home para enviarle informacion
    if (empty($_SESSION['permisosMod']['r'])){
      header("Location:".base_url().'/dashboard');
    }
    $data['page_id'] = 3;
    $data['page_tag'] = "Roles Usuarios";
    $data['page_name'] = "rol_usuario";
    $data['page_title'] = "Roles Usuario <small> Tienda Virtual</small>";
    $data['page_functions_js'] = "functions_roles.js";
    $this->views->getView($this,"roles",$data);
  }

  public function getRoles()
  {
      if ($_SESSION['permisosMod']['r']) 
    {
        $btnView = '';
        $btnEdit = '';
        $btnDelete = '';

      $arrData = $this->model->selectRoles();

      for($i=0; $i < count($arrData); $i++){

        if($arrData[$i]['status'] == 1)
        {
          $arrData[$i]['status'] = '<span class="badge badge-success">Activo</span>';
        }
        else
        {
          $arrData[$i]['status'] = '<span class="badge badge-danger">Inactivo</span>';
        }

            if ($_SESSION['permisosMod']['u']){
              $btnView = '<button class="btn btn-secondary btn-sm btnPermisosRol" onClick=" fntPermisos('.$arrData[$i]['idrol'].')" title="Permisos"><i class="fas fa-key"></i></button>';

              $btnEdit = '<button class="btn btn-primary btn-sm btnEditRol" onClick="fntEditRol('.$arrData[$i]['idrol'].')" title="Editar"><i class="fas fa-pencil-alt"></i></button>';
           }

           if ($_SESSION['permisosMod']['d']){
              $btnDelete = '<button class="btn btn-danger btn-sm btnDelRol" onClick="fntDelRol('.$arrData[$i]['idrol'].')" title="Eliminar"><i class="far fa-trash-alt"></i></button>';
           }

          $arrData[$i]['options'] = '<div class="text-center">'.$btnView.' '.$btnEdit.' '.$btnDelete.'</div>'; 
      }
      //dep es una funcion que esta en helpers
     // dep($arrData);

     // <span class="badge badge-success">Success</span>

      echo json_encode($arrData,JSON_UNESCAPED_UNICODE);
    }
    //die finaliza el proceso
    die();
    
  }

public function getRol(int $idrol)
{
  if ($_SESSION['permisosMod']['r']) 
  {
    //convierte el valor a entero para confirmar y strclean limpia la variable para evitar que venga con una inyeccion sql
    $intIdrol = intval(strClean($idrol));
     //se verifica si el idrol es mayor a cero para que sea valido
    if ($intIdrol > 0) 
    {
      //envio el idrol al la funcion selectrol del modelo
      $arrData = $this->model->selectRol($intIdrol);
      //verifico si la variable esta vacia
      if (empty($arrData)) 
      {
        //si esta vacia creo este arreglo para luego mostrar con estatus y mensaje
        $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.' );
      }
      else
      {
        //si no esta vacia creo este arreglo para luego mostrar con estatus y data (la informacion de ese archivo)
        $arrResponse = array('status' => true , 'data' => $arrData );
      }
      //convierto cualquiera de las respuestas a JSON para ser reenviadas al javascript
      echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
    }
  }
      //detengo la funcion hasta aqui
      die();
}

   public function getSelectRoles()
  {
    $htmlOptions = "";
    $arrData = $this->model->selectRoles();
    if(count($arrData) > 0)
    {
      for ($i=0; $i < count($arrData); $i++)
      { 
        if ($arrData[$i]['status'] == 1)
        {
          $htmlOptions .= '<option value="'.$arrData[$i]['idrol'].'">'.$arrData[$i]['nombrerol'].'</option>';
        }   
      }
    }
    echo $htmlOptions;
    die();
  }

  public function setRol()
  {
    if ($_SESSION['permisosMod']['w']) 
    {
      //dep($_POST);
      $intIdrol = intval($_POST['idRol']);
      $strRol = strClean($_POST['txtNombre']);
      $strDescripcion = strClean($_POST['txtDescripcion']);
      $intStatus = intval($_POST['listStatus']);
      
      if ($intIdrol == 0)
      {
        //CREAR
        $request_rol = $this->model->insertRol($strRol, $strDescripcion, $intStatus);
        $option = 1;
      }
      else
      {
        //ACTUALIZAR
        $request_rol = $this->model->updateRol($intIdrol, $strRol, $strDescripcion, $intStatus);
        $option = 2;
      }


      if ($request_rol > 0)
      {
        if ($option == 1)
        {
            $arrResponse = array('status' => true, 'msg' => 'Datos guardados correctamente.' );
        }
        else
        {
            $arrResponse = array('status' => true, 'msg' => 'Datos actualizados correctamente.' );
        }
      }
      else if ($request_rol == 'exist')
        {
            $arrResponse = array('status' => false, 'msg' => '!Atencion el rol ya existe.' );
        }
        else
        {
          $arrResponse = array('status' => false, 'msg' => 'No es posible almacenar datos.' );
        }
      //retornamos el array en formato jason con el satus y el msg y el segundo parametro es para enviar caracteres especiales
      //sleep(3);
      echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
      //con esto detenemos el peoceso del metodo
    }
      die(); 
  }

  public function delRol()
  { 
    
    if($_POST)
    { 
        if ($_SESSION['permisosMod']['d']) 
      {
        $intIdrol = intval($_POST['idrol']);
        $requestDelete = $this->model->deleteRol($intIdrol);
        if ($requestDelete == 'ok') 
        {
          $arrResponse = array('status' => true , 'msg' => 'Se ha eliminado el Rol' );
        }
        else if($requestDelete == 'exist')
        {
          $arrResponse = array('status' => false , 'msg' => 'No es posible eliminar un Rol asociado a usuarios.' );
        }
        else
        {
          $arrResponse = array('status' => false , 'msg' => 'Error al eliminar Rol.' );
        }
          echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
      }
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