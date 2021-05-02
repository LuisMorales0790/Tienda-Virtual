<?php 
/** este controlador es usado en el template header_admin para cerrar una sesion.
 * 
 */
class Logout 
{
	
	public function __construct()
	{
		//inicialisamos session
		session_start();
		//limpiamos las variables de sesion
		session_unset();
		//destruimos las sesion
		session_destroy();
		//redirigir al formulario de sesion
		header('location: '.base_url().'/login');
	}
}

 ?>