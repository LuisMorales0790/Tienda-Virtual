<?php 
	
	//define("BASE_URL", "http://localhost/tienda_virtual/");
	const BASE_URL = "http://localhost/tienda";

	//Zona horaria
	date_default_timezone_set('America/Guatemala');

	//Datos de conexión a Base de Datos
	const DB_HOST = "localhost";
	const DB_NAME = "db_tiendavirtual";
	const DB_USER = "root";
	const DB_PASSWORD = "";
	const DB_CHARSET = "utf8";

	//Deliminadores decimal y millar Ej. 24,1989.00
	const SPD = ".";
	const SPM = ",";

	//Simbolo de moneda
	const SMONEY = "$";
	const CURRENCY = "USD";
	//apy Paypal  
	// IDCLIENTE es el id mio dentro de paypal
	// sanbox paypal (pruebas)
	const IDCLIENTE = "AWM3047o1YZlV7gaW3JjYmD7zNz8rOBQjenXheYwEOdSfjX71cV_bjYqUyClOZXOZFsClFO0GzVy1ODK";
	//url prueba
	const URLPAYPAL = "https://api-m.sandbox.paypal.com";
	const SECRET = "EFMJZLDpU8V1fkd0HUYvpP-sViTGQxo7Tc03qSvd0FcCkF5GqVvBX6Z6PE3Y10WmP8rS3u8viMMCBe0o";
	//url de produccion
	//const URLPAYPAL = "https://api-m.paypal.com";
	//live paypal (produccion)
	//const IDCLIENTE = "AQk_r0CS0uax6mzDcUkvukEx98QBSHHO5RAFSN-5F-gbZkvDmpZSJzD68GqnET7zD5C1rmIRu2V8Akyr";
	//secret produccion
	//const SECRET = "EN-tx9KIHki8gRaqooKMeK_gvbt3oVIxRTL7z-g6UNO_65DdQZpl0M0Hq6hfdoCqgSuuQ85Qm8trq4bp";


	//Datos envio de correo
	const NOMBRE_REMITENTE = "Tienda Virtual";
	const EMAIL_REMITENTE = "no-reply@abelosh.com";
	const NOMBRE_EMPRESA = "Tienda Virtual";
	const WEB_EMPRESA = "www.abelosh.com";

	//Datos Empresa
	const DIRECCION = "Avenida las Americas Zona 13, Guatemala";
	const TELEMPRESA = "+(502)78787845";
	const EMAIL_EMPRESA = "info@abelosh.com";
	const EMAIL_PEDIDOS = "lemo_0790@hotmail.com";

	const CAT_SLIDER = "1,2,3";
	const CAT_BANNER = "4,5,6";

	//Datos para Encriptar / Desencriptar
	const KEY = 'abelosh';
	const METHODENCRIPT = "AES-128-ECB";

	//Envío
	const COSTOENVIO = 20;

	//Modulos
    const MCLIENTES = 3;
	const MPEDIDOS = 5;

	//Roles 
	const RCLIENTES = 7;
	const RADMINISTRADOR = 1;
	



	


 ?>