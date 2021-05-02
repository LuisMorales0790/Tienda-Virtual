//cuando cargue todo el html de la vista va a agregar los seiguientes eventos
let tableUsuarios;
let rowTable = "";
let divLoading = document.querySelector("#divLoading");
document.addEventListener('DOMContentLoaded', function()
{
	tableUsuarios = $('#tableUsuarios').dataTable( {
	   	"aProcessing":true,
	   	"aServerSide":true,
	   	"language": {
	   			"url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
	   	},
        "ajax":{
        	"url": " "+base_url+"/Usuarios/getUsuarios",
        	"dataSrc":""
        },
        "columns": [                                  
            { "data": "idpersona" },
            { "data": "nombres" },
            { "data": "apellidos" },
            { "data": "email_user" },
            { "data": "telefono" },
            { "data": "nombrerol" },
            { "data": "status" },
            { "data": "options" }
        ],
        //botones de exportacion
         'dom': 'lBfrtip',
        'buttons': [
            {
            	"extend": "copyHtml5",
            	"text": "<i class= 'far fa-copy'></i> Copiar",
            	"titleAttr":"Copiar",
            	"className": "btn btn-secondary"
            },{
            	"extend": "excelHtml5",
            	"text": "<i class= 'fas fa-file-excel'></i> Excel",
            	"titleAttr":"Esportar a Excel",
            	"className": "btn btn-success"
            },{
            	"extend": "pdfHtml5",
            	"text": "<i class= 'fas fa-file-pdf'></i> PDF",
            	"titleAttr":"Esportar a PDF",
            	"className": "btn btn-danger"
            },{
            	"extend": "csvHtml5",
            	"text": "<i class= 'fas fa-file-csv'></i> CSV",
            	"titleAttr":"Esportar a CSV",
            	"className": "btn btn-info"
            }
        ],
        "responsieve":"true",
        "bDestroy": true,
        "iDisplayLength": 10,
        "order":[[0,"desc"]]
    });

    if(document.querySelector("#formUsuario"))
    {
		// crear  nuevo usuario o editar un usuarios
		let formUsuario = document.querySelector("#formUsuario");
		//al enviar en formulario ejecuta la suguiente funcion
		formUsuario.onsubmit = function(e)
		{
				e.preventDefault();
				let strIdentificacion = document.querySelector('#txtIdentificacion').value;
				let strNombre = document.querySelector('#txtNombre').value;
				let strApellido = document.querySelector('#txtApellido').value;
				let intTelefono = document.querySelector('#txtTelefono').value;
				let strEmail = document.querySelector('#txtEmail').value;
				let intTipousuario = document.querySelector('#listRolid').value;
				let strPassword = document.querySelector('#txtPassword').value;
				let intStatus = document.querySelector('#listStatus').value;

				if (strIdentificacion == '' || strNombre =='' || strApellido =='' || intTelefono  =='' || strEmail =='' || intTipousuario =='')
				{
					swal("Atencion", "Todos los campos son obligatorios." , "error");
					return false;
				}

				let elementsValid = document.getElementsByClassName("valid");
				for (let i = 0; i < elementsValid.length; i++)
				{
					if(elementsValid[i].classList.contains('is-invalid'))
					{
						swal("Atención", "Por favor verifique los campos en rojo." , "error");
						return false;
					}
				}

				divLoading.style.display = "flex";
				let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
				let ajaxUrl = base_url +'/Usuarios/setUsuario';
				let formData = new FormData(formUsuario);
				request.open("POST",ajaxUrl,true);
				request.send(formData);
				request.onreadystatechange = function()
			{
				if (request.readyState == 4 && request.status == 200)
				{
					let objData = JSON.parse(request.responseText);
					if (objData.status)
					{
						//Para crear un usuario
						if (rowTable == "")
						{
							tableUsuarios.api().ajax.reload();
						}
						else
						{
							htmlStatus = intStatus == 1 ?
							'<span class="badge badge-success">Activo</span>':
							'<span class="badge badge-danger">Inactivo</span>';
							//Para editar un usuario
							rowTable.cells[1].textContent = strNombre;
							rowTable.cells[2].textContent = strApellido;
							rowTable.cells[3].textContent = strEmail;
							rowTable.cells[4].textContent = intTelefono;
							rowTable.cells[5].textContent = document.querySelector("#listRolid").selectedOptions[0].text;
							rowTable.cells[6].innerHTML = htmlStatus;
						}
						//para ocultar el modal
						$('#modalFormUsuario').modal("hide");
						// para resetiar todos los campos
						formUsuario.reset();
						swal("Usuarios", objData.msg, "success");
						//para que los datos aparescan en el data table
					}
					else
					{
						swal("Error", objData.msg, "error");	
					}
				}
				divLoading.style.display = "none";
				return false;			
			}
		}
	}
///////////////////////////////////////////////////////////////////////ACTUALIZAR PERFIL//////////////////////////////////////////////////////////////////
	if(document.querySelector("#formPerfil"))
    {
		// crear  nuevo usuario o editar un usuarios
		let formPerfil = document.querySelector("#formPerfil");
		//al enviar en formulario ejecuta la suguiente funcion
		formPerfil.onsubmit = function(e)
		{
				e.preventDefault();
				let strIdentificacion = document.querySelector('#txtIdentificacion').value; 
				let strNombre = document.querySelector('#txtNombre').value;
				let strApellido = document.querySelector('#txtApellido').value;
				let intTelefono = document.querySelector('#txtTelefono').value;
				//let strEmail = document.querySelector('#txtEmail').value;
				let strPassword = document.querySelector('#txtPassword').value;
				let strPasswordConfirm = document.querySelector('#txtPasswordConfirm').value;

				if (strIdentificacion == '' || strNombre == '' || strApellido == '' || intTelefono  == '')
				{
					swal("Atención", "Todos los campos son obligatorios." , "error");
					return false;
				}

				if(strPassword != '' || strPasswordConfirm != "")
				{
					if (strPassword !=  strPasswordConfirm){
						swal("Atención", "Las contraseñas no son iguales." , "info");
						return false;
					}
					if (strPassword.length < 5){
						swal("Atención", "La contraseña debe tener un minimo de 5 caracteres." , "info");
						return false;
					}
				}

				let elementsValid = document.getElementsByClassName("valid");
				for (let i = 0; i < elementsValid.length; i++) {
					if (elementsValid[i].classList.contains("is-invalid")) {
						swal("Atencion", "Por favor verifique los campos en rojo." , "error");
						return false;
					}
				}
				divLoading.style.display = "flex";
				let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
				let ajaxUrl = base_url +'/Usuarios/putPerfil';
				let formData = new FormData(formPerfil);
				request.open("POST",ajaxUrl,true);
				request.send(formData);
				request.onreadystatechange = function(){
				if (request.readyState != 4) return;	
				if (request.status == 200){
					let objData = JSON.parse(request.responseText);
					if (objData.status)
					{
						$('#modalFormPerfil').modal("hide");
						swal({
							title: "",
							text: objData.msg,
							type: "success",
							confirmButtonText: "Aceptar",
							closeOnConfirm: false,
						}, function(isConfirm){
							if(isConfirm){
								location.reload();
							}

						});
					}
					else
					{
						swal("Error", objData.msg, "error");	
					}
				}
				divLoading.style.display = "none";
				return false;			
			}
		}
	}

	///////////////////////////////////////////////////////////////////////ACTUALIZAR DATOS FISCALES//////////////////////////////////////////////////////////////////
	if(document.querySelector("#FormDataFiscal"))
    {
		// crear  nuevo usuario o editar un usuarios
		let FormDataFiscal = document.querySelector("#FormDataFiscal");
		//al enviar en formulario ejecuta la suguiente funcion
		FormDataFiscal.onsubmit = function(e)
		{
				e.preventDefault();
				let strNit = document.querySelector('#txtNit').value; 
				let strNombreFiscal = document.querySelector('#txtNombreFiscal').value;
				let strDirFiscal = document.querySelector('#txtDirFiscal').value;
				//let intTelefono = document.querySelector('#txtTelefono').value;
				//let strEmail = document.querySelector('#txtEmail').value;
				//let strPassword = document.querySelector('#txtPassword').value;
				//let strPasswordConfirm = document.querySelector('#txtPasswordConfirm').value;

				if (strNit == '' || strNombreFiscal == '' || strDirFiscal == '')
				{
					swal("Atención", "Todos los campos son obligatorios." , "error");
					return false;
				}
				divLoading.style.display = "flex";
				let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
				let ajaxUrl = base_url +'/Usuarios/putDFiscal';
				let formData = new FormData(FormDataFiscal);
				request.open("POST",ajaxUrl,true);
				request.send(formData);
				request.onreadystatechange = function(){
				if (request.readyState != 4) return;	
				if (request.status == 200){
					let objData = JSON.parse(request.responseText);
					if (objData.status)
					{
						$('#modalFormPerfil').modal("hide");
						swal({
							title: "",
							text: objData.msg,
							type: "success",
							confirmButtonText: "Aceptar",
							closeOnConfirm: false,
						}, function(isConfirm){
							if(isConfirm){
								location.reload();
							}

						});
					}
					else
					{
						swal("Error", objData.msg, "error");	
					}
				}
				divLoading.style.display = "none";
				return false;			
			}
		}
	}
}, false);


window.addEventListener('load' , function(){
	fntRolesUsuario();
	//fntViewUsuario();
	//fntEditUsuario();
	//fntDelUsuario();
}, false);

function fntRolesUsuario(){
	if(document.querySelector("#listRolid"))
	{
		//ruta a donde se va a hacer la peticion
		let ajaxUrl = base_url+'/Roles/getSelectRoles';
		let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
		//hacemos la peticion
		request.open("GET",ajaxUrl,true);
		//hacemos la peticiona
		request.send();

		//obtener resultados de el ajax

		request.onreadystatechange = function()
		{
			if (request.readyState == 4 && request.status == 200)
			{
				document.querySelector('#listRolid').innerHTML = request.responseText;
				//seleccionamos el primero option en el select
				//document.querySelector('#listRolid').value = 1;
				$('#listRolid').selectpicker('render');
			}
		}
	}	
}

function fntViewUsuario(idpersona)
{
	//let no permite repetir el mismo nombre de variable
	//let idpersona = idpersona;
	let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
	let ajaxUrl = base_url+'/Usuarios/getUsuario/'+idpersona;
	request.open("GET",ajaxUrl,true);
	request.send();
	request.onreadystatechange = function()
	{
		if(request.readyState == 4 && request.status == 200)
		{
			let objData = JSON.parse(request.responseText);
			//alert(objData.data.telefono);
			if (objData.status)
			{
				let estadoUsuario = objData.data.status ==  1 ?
				'<span class="badge badge-success">Activo</span>':
				'<span class="badge badge-danger">Inactivo</span>';

				document.querySelector("#celIdentificacion").innerHTML = objData.data.identificacion;
				document.querySelector("#celNombre").innerHTML = objData.data.nombres;
				document.querySelector("#celApellidos").innerHTML = objData.data.apellidos;
				document.querySelector("#celTelefono").innerHTML = objData.data.telefono;
				document.querySelector("#celEmail").innerHTML = objData.data.email_user;
				document.querySelector("#celTipoUsuario").innerHTML = objData.data.nombrerol;
				document.querySelector("#celEstado").innerHTML = estadoUsuario;
				document.querySelector("#celFechaRegistro").innerHTML = objData.data.fechaRegistro;
				$('#modalViewUser').modal('show');
			}
			else
			{
				swal("Error", objData.msg , "error" );
			}
		}
	}
}

function openModal()
{
	rowTable = "";
	document.querySelector('#idUsuario').value = "";
	document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");
	document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
	document.querySelector('#btnText').innerHTML = "Guardar";
	document.querySelector('#titleModal').innerHTML = "Nuevo Usuario";
	document.querySelector('#formUsuario').reset();
	$('#modalFormUsuario').modal('show');
}

function fntEditUsuario(element,idpersona)
{	
	rowTable = element.parentNode.parentNode.parentNode;
	//rowTable.cells[1].textContent = "Julio";
	//console.log(rowTable);   
	document.querySelector('#titleModal').innerHTML = "Actualizar Usuario";
	document.querySelector('.modal-header').classList.replace("headerRegister", "headerUpdate");
	document.querySelector('#btnActionForm').classList.replace("btn-primary", "btn-info");
	document.querySelector('#btnText').innerHTML = "Actualizar";


	//let no permite repetir el mismo nombre de variable
	//let idpersona = idpersona;
	let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
	let ajaxUrl = base_url+'/Usuarios/getUsuario/'+idpersona;
	request.open("GET",ajaxUrl,true);
	request.send();
	request.onreadystatechange = function()
	{
		if (request.readyState == 4 && request.status == 200)
		 {
		 	let objData = JSON.parse(request.responseText);
		 	if (objData.status)
		 	 {
		 	 	document.querySelector("#idUsuario").value = objData.data.idpersona;
		 	 	document.querySelector("#txtIdentificacion").value = objData.data.identificacion;
		 	 	document.querySelector("#txtNombre").value = objData.data.nombres;
		 	 	document.querySelector("#txtApellido").value = objData.data.apellidos;
		 	 	document.querySelector("#txtTelefono").value = objData.data.telefono;
		 	 	document.querySelector("#txtEmail").value = objData.data.email_user;
		 	 	document.querySelector("#listRolid").value = objData.data.idrol;
		 	 	$('#listRolid').selectpicker('render');

		 	 	if (objData.data.status == 1)
		 	 	 {
		 	 	 	document.querySelector("#listStatus").value = 1;
		 	 	 }
		 	 	 else
		 	 	 {
		 	 	 	document.querySelector("#listStatus").value = 2;
		 	 	 }
		 	 	 $('#listStatus').selectpicker('render');
		 	 }
		 }
		$('#modalFormUsuario').modal('show');
	}
}

  function fntDelUsuario(idpersona)
{
	//let no permite repetir el mismo nombre de variable
	// let idUsuario = idpersona;
	 
	 swal({
	        title: "Eliminar Usuario",
	        text: "Realmente quiere eliminar el Usuario?",
	       	type: "warning",
	        showCancelButton: true,
	        confirmButtonText: "Si, eliminar!",
	        cancelButtonText: "No, cancelar",
	        closeOnConfirm: false,
	        closeOnCancel: true
	    }, function(isConfirm)

	{
	    if (isConfirm) 
	    {
	         
	        let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
	        let ajaxUrl = base_url+'/Usuarios/delUsuario/';
	        let strData = "idUsuario="+idpersona;
	        request.open("POST",ajaxUrl,true);
	        //forma como se van a enviar los datos
	        request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	        request.send(strData);
	        //se recibe la respuesta
	        request.onreadystatechange = function()
	        {
	            if(request.readyState == 4 && request.status == 200)
	            {
	                let objData = JSON.parse(request.responseText);
	                if (objData.status) 
	                {
	                    swal("Eliminar!", objData.msg , "success");
	                    //para que los datos aparescan en el data table
						tableUsuarios.api().ajax.reload(function(){
							fntRolesUsuario();
							//fntViewUsuario();
							//fntEditUsuario();
							//fntDelUsuario();
	                    });
	                }
	                else
	                {
	                    swal("Atencion!", objData.msg , "error");
	                }
	            }
	        }
	    }
	});
}

function openModalPerfil()
{
	$('#modalFormPerfil').modal('show');
}





