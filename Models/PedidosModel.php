<?php 
 	/**
 	 * 
 	 */
 	class PedidosModel extends mysql
 	{
 		private $intIdProducto;
 		private $strNombre;
 		private $strDescripcion;
 		private $intCodigo;
 		private $intCategoriaId;
 		private $intPrecio;
 		private $intStock;
 		private $strRuta;
 		private $intStatus;
 		public $strImagen;
 		
 		public function __construct()
 		{
 			parent::__construct();
 		}

 		 //$idpersona no se le pone el tipo de dato porque puede ser vacio o entero y puede mandar error
		public function selectPedidos($idpersona = null)
 		{
 			$where = "";
 			if ($idpersona != null) {
 				$where = " WHERE p.personaid = ".$idpersona;
 			}
 			$sql = "SELECT p.idpedido,
 						   p.referenciacobro,
 						   p.idtransaccionpaypal,
 						   DATE_FORMAT(p.fecha, '%d/%m/%Y') as fecha,
 						   p.monto,
 						   tp.tipopago,
 						   tp.idtipopago,
 						   p.status
 			       FROM pedido p 
 			       	
 			       INNER JOIN tipopago tp 
 			       ON p.tipopagoid = tp.idtipopago $where ";
			       $request = $this->select_all($sql);
			       return $request;
 		}
                                                     //$idpersona no se le pone el tipo de dato porque puede ser vacio o entero y puede mandar error
 		public function selectPedido(int $idpedido, $idpersona = null)
 		{
 			$busqueda = "";
 			if ($idpersona != NULL) {
 				$busqueda = " AND p.personaid =".$idpersona;
 			}
			$request = array();
			$sql = "SELECT p.idpedido,
							p.referenciacobro,
							p.idtransaccionpaypal,
							p.personaid,
							DATE_FORMAT(p.fecha, '%d/%m/%Y') as fecha,
							p.costo_envio,
							p.monto,
							p.tipopagoid,
							t.tipopago,
							p.direccion_envio,
							p.status
					FROM pedido as p
					INNER JOIN tipopago t
					ON p.tipopagoid = t.idtipopago
					WHERE p.idpedido = $idpedido".$busqueda;	
			$requestPedido = $this->select($sql);
			//return $requestPedido;

			if (!empty($requestPedido)) {
				$idpersona = $requestPedido['personaid'];
				$sql_cliente = "SELECT idpersona,
										nombres,
										apellidos,
										telefono,
										email_user,
										nit,
										nombrefiscal,
										direccionfiscal
				   				FROM persona WHERE idpersona = $idpersona";
				 $requestCliente = $this->select($sql_cliente);
				 $sql_detalle = "SELECT p.idproducto,
			                       p.nombre AS producto,
			                       d.precio,
			                       d.cantidad
			                FROM detalle_pedido d
			                INNER JOIN producto p
			                ON d.productoid = p.idproducto
			                WHERE d.pedidoid = $idpedido
			                ";
			    $requestProductos = $this->select_all($sql_detalle);
			    $request = array('cliente' => $requestCliente,
			    				  'orden' => $requestPedido,
			    				  'detalle' => $requestProductos
			    				 );
			}

			return $request;
 		}

 		public function selectTransPaypal(string $idtransaccion, $idpersona = NULL)
 		{
 			$busqueda = "";
 			if ($idpersona != NULL) {
 				$busqueda = " AND personaid =".$idpersona;
 			}
 			$objTransaccion = array();
 			$sql = "SELECT datospaypal FROM pedido WHERE idtransaccionpaypal = '{$idtransaccion}' ".$busqueda;
 			$requestData = $this->select($sql);
 			if (!empty($requestData)) {

 				//dep($requestData);

 				$objData = json_decode($requestData['datospaypal']);

 				
 				//dep($objData);exit();
 				$urlTransaccion = $objData->purchase_units[0]->payments->captures[0]->links[0]->href;
 				$urlOrden = $objData->purchase_units[0]->payments->captures[0]->links[2]->href;
 				$objTransaccion = CurlConnectionGet($urlOrden,"application/json",getTokenPaypal());
 			}
 			return $objTransaccion;
 		}

 		public function reembolsoPaypal(string $idtransaccion, string $observacion)
 		{
 			$response = false;
 			$sql = "SELECT idpedido,datospaypal FROM pedido WHERE idtransaccionpaypal = '{$idtransaccion}'";
 			$requestData = $this->select($sql);
 			if (!empty($requestData)) {
 				$objData = json_decode($requestData['datospaypal']);
 				$urlReembolso = $objData->purchase_units[0]->payments->captures[0]->links[1]->href;
 				$objTransaccion = CurlConnectionPost($urlReembolso,"application/json",getTokenPaypal());
 				if(isset($objTransaccion->status) and $objTransaccion->status == "COMPLETED")
 				{
 					$idpedido = $requestData['idpedido'];
 					$idtransaccion = $objTransaccion->id;
 					$status = $objTransaccion->status;
 					$jsonData = json_encode($objTransaccion);
 					$observacion = $observacion;
 					$query_insert = "INSERT INTO reembolso(pedidoid,
 															idtransaccion,
 															datosreembolso,
 															observacion,
 															status)
 									VALUES(?,?,?,?,?)";
 				    $arrData = array($idpedido,
 				    				 $idtransaccion,
 				    				 $jsonData,
 				    				 $observacion,
 				    				 $status
 				    				);
 				    $requestInsert = $this->insert($query_insert,$arrData);
 				    if ($requestInsert > 0)
 				    {
 				    	$updatePedido = "UPDATE pedido SET status = ? WHERE idpedido = $idpedido";
 				    	$arrPedido = array("Reembolsado");
 				    	$request = $this->update($updatePedido,$arrPedido);
 				    	$response = true;
 				    }
 				}
 				return $response;
 				//dep($objTransaccion);	
 			}
 		}

 	}
 ?>