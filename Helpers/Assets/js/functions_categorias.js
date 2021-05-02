let tableCategorias;
// toma el valor de toda la fila donde vamos a actualizar los datos al momento de dar click al boton actualizar
let rowTable = '';
let divLoading = document.querySelector("#divLoading");
document.addEventListener('DOMContentLoaded', function(){

	tableCategorias = $('#tableCategorias').dataTable( {
	   	"aProcessing":true,
	   	"aServerSide":true,
	   	"language": {
	   			"url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
	   	},
        "ajax":{
        	"url": " "+base_url+"/Categorias/getCategorias",
        	"dataSrc":""
        },
        "columns": [       //se colocan los mismos nombres que vienen del json o la BD
        	{ "data": "idcategoria" },                           
            { "data": "nombre" },
            { "data": "descripcion" },
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
        "iDisplayLength": 3,
        "order":[[0,"desc"]]
    });


	if(document.querySelector("#foto")){
    let foto = document.querySelector("#foto");
    foto.onchange = function(e) {
        let uploadFoto = document.querySelector("#foto").value;
        let fileimg = document.querySelector("#foto").files;
        let nav = window.URL || window.webkitURL;
        let contactAlert = document.querySelector('#form_alert');
        if(uploadFoto !=''){
            let type = fileimg[0].type;
            let name = fileimg[0].name;
            if(type != 'image/jpeg' && type != 'image/jpg' && type != 'image/png'){
                contactAlert.innerHTML = '<p class="errorArchivo">El archivo no es válido.</p>';
                if(document.querySelector('#img')){
                    document.querySelector('#img').remove();
                }
                document.querySelector('.delPhoto').classList.add("notBlock");
                foto.value="";
                return false;
            }else{  
                    contactAlert.innerHTML='';
                    if(document.querySelector('#img')){
                        document.querySelector('#img').remove();
                    }
                    document.querySelector('.delPhoto').classList.remove("notBlock");
                    let objeto_url = nav.createObjectURL(this.files[0]);
                    document.querySelector('.prevPhoto div').innerHTML = "<img id='img' src="+objeto_url+">";
                }
        }else{
            alert("No selecciono foto");
            if(document.querySelector('#img')){
                document.querySelector('#img').remove();
            }
        }
    }
}

if(document.querySelector(".delPhoto")){
    let delPhoto = document.querySelector(".delPhoto");
    delPhoto.onclick = function(e) {
    	document.querySelector("#foto_remove").value = 1;
        removePhoto();
    }
}

//NUEVA CATEGORIA
   let formCategoria = document.querySelector("#formCategoria");
   formCategoria.onsubmit = function(e)
   {
        //previene que se recargue el formulario o la pagina
         e.preventDefault();

         //let intIdcategoria = document.querySelector('#idCategoria').value;
         let strNombre = document.querySelector('#txtNombre').value;
         let strDescripcion = document.querySelector('#txtDescripcion').value;
         let intStatus = document.querySelector('#listStatus').value;
         if(strNombre == '' || strDescripcion == '' || intStatus == '')
         {
            swal("Atencion", "Todos los campos son obligatorios." , "error");
            return false;
         }
         // enviar datos por ajax
         //        si es navegador chrome o farefox crea un nuevp objeto, pero si es vegador de microsoft como edge
         divLoading.style.display = "flex";
         let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
         // enviamos al controlador con su ruta
         let ajaxUrl = base_url+'/Categorias/setCategoria';
         //let formElement = document.querySelector("#formRol");
         //creamos un objetos con los datos del formulario
         let formData = new FormData(formCategoria);
         //enviamos los datos por ajax
         request.open("POST",ajaxUrl,true);
         request.send(formData);
         request.onreadystatechange = function()
         {
            if(request.readyState == 4 && request.status == 200)
            //.log(request.responseText);
           {
                //obtengo en formato json para convertirlo en un objeto javascript
                let objData = JSON.parse(request.responseText);

                if (objData.status)
                {
                	if (rowTable == "") //si rowTable esta vacio es porque se creo una nueva categoria
                	{
                		 //refreso del data table de categorias para mostrar la nueva categoria
                		  tableCategorias.api().ajax.reload();
                	}
                	else // si no es porque se actualizo una ya existente
                	{
                		htmlStatus = intStatus == 1 ?
                			'<span class="badge badge-success">Activo</span>':
                			'<span class="badge badge-danger">Inactivo</span>';

                		rowTable.cells[1].textContent = strNombre;
                		rowTable.cells[2].textContent = strDescripcion;
                		rowTable.cells[3].innerHTML = htmlStatus;
                		rowTable = "";
                	}
                    // aqui cierro el modal
                    $('#modalFormCategorias').modal("hide");
                    //reseteo el formulario del modal
                    formCategoria.reset();
                    swal("Categoria", objData.msg ,"success");
                    removePhoto();
                   
                    //tableRoles.api().ajax.reload(function(){
                        //funcion para darle el evento click a los botones
                       //fntEditRol(); 
                       //fntDelRol();
                       //fntPermisos();
                    //});
                }
                else
                {
                    swal("Error", objData.msg , "error");
                }
            }

            divLoading.style.display = "none";
            return false;  
         } 
   }


}, false);


function fntViewInfo(idcategoria)
{
	
  	//let no permite repetir el mismo nombre de letiable
	//let idpersona = idpersona;
	let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
	let ajaxUrl = base_url+'/Categorias/getCategoria/'+idcategoria;
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

				let estadoCategoria = objData.data.status ==  1 ?
				'<span class="badge badge-success">Activo</span>':
				'<span class="badge badge-danger">Inactivo</span>';

				document.querySelector("#celId").innerHTML = objData.data.idcategoria;
				document.querySelector("#celNombre").innerHTML = objData.data.nombre;
				document.querySelector("#celDescripcion").innerHTML = objData.data.descripcion;
				document.querySelector("#celFechaRegistro").innerHTML = objData.data.fechaRegistro;
				document.querySelector("#celStatus").innerHTML = estadoCategoria;
				document.querySelector("#imgCategoria").innerHTML = '<img src="'+objData.data.url_portada+'"></img>';
				$('#modalViewCategoria').modal('show');
			}
			else
			{
				swal("Error", objData.msg , "error" );
			}
		}
	} 																			
} 

function fntEditInfo(element,idcategoria)
{	
	rowTable = element.parentNode.parentNode.parentNode;
	//rowTable.cells[1].textContent = "goku";
	//console.log(rowTable);   
	document.querySelector('#titleModal').innerHTML = "Actualizar Categoria";
	document.querySelector('.modal-header').classList.replace("headerRegister", "headerUpdate");
	document.querySelector('#btnActionForm').classList.replace("btn-primary", "btn-info");
	document.querySelector('#btnText').innerHTML = "Actualizar";


	//let no permite repetir el mismo nombre de letiable
	//let idpersona = idpersona;
	let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
	let ajaxUrl = base_url+'/Categorias/getCategoria/'+idcategoria;
	request.open("GET",ajaxUrl,true);
	request.send();
	request.onreadystatechange = function()
	{
		if (request.readyState == 4 && request.status == 200)
		 {
		 	let objData = JSON.parse(request.responseText);
		 	if (objData.status)
		 	 {
		 	 	document.querySelector("#idCategoria").value = objData.data.idcategoria;
		 	 	document.querySelector("#txtNombre").value = objData.data.nombre;
		 	 	document.querySelector("#txtDescripcion").value = objData.data.descripcion;
		 	 	document.querySelector("#foto_actual").value = objData.data.portada;
		 	 	document.querySelector("#foto_remove").value = 0;

		 	 	if (objData.data.status == 1)
		 	 	 {
		 	 	 	document.querySelector("#listStatus").value = 1;
		 	 	 }
		 	 	 else
		 	 	 {
		 	 	 	document.querySelector("#listStatus").value = 2;
		 	 	 }
		 	 	 //para que se seleccione el valor que se ha designado
		 	 	 $('#listStatus').selectpicker('render');
		 	 	 //para mostrar imagen en el modal
		 	 	 if (document.querySelector('#img'))
		 	 	 {
		 	 	 	document.querySelector('#img').src = objData.data.url_portada;
		 	 	 }
		 	 	 else
		 	 	 {
		 	 	 	document.querySelector('.prevPhoto div').innerHTML = "<img id='img' src="+objData.data.url_portada+">";
		 	 	 }

		 	 	 if (objData.data.portada == 'portada_categoria.png')
		 	 	 {
		 	 	 	document.querySelector('.delPhoto').classList.add("notBlock");
		 	 	 }
		 	 	 else
		 	 	 {
		 	 	 	document.querySelector('.delPhoto').classList.remove("notBlock");
		 	 	 }

		 	 	 $('#modalFormCategorias').modal('show');
		 	 }
		 	 else
		 	 {
		 	 	swal("Error", objData.msg , "error");
		 	 }
		 }
	}
}

 function fntDelInfo(idcategoria)
{
	//let no permite repetir el mismo nombre de letiable
	// let idUsuario = idpersona;
	 
	 swal({
	        title: "Eliminar Categoria",
	        text: "Realmente quiere eliminar la Categoria?",
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
	        let ajaxUrl = base_url+'/Categorias/delCategoria/';
	        let strData = "idCategoria="+idcategoria;
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
						//refreso del data table de categorias para mostrar las categorias restantes
                         tableCategorias.api().ajax.reload();//{
							//fntRolesUsuario();
							//fntViewUsuario();
							//fntEditUsuario();
							//fntDelUsuario();
	                   // });
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

  function removePhoto()
{
    document.querySelector('#foto').value ="";
    document.querySelector('.delPhoto').classList.add("notBlock");
    if (document.querySelector('#img'))
    {
    	document.querySelector('#img').remove();
    }
}

function openModal()
{
	rowTable = "";
	removePhoto();
	document.querySelector('#idCategoria').value = "";
	document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");
	document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
	document.querySelector('#btnText').innerHTML = "Guardar";
	document.querySelector('#titleModal').innerHTML = "Nueva Categoria";
	document.querySelector('#formCategoria').reset();
	$('#modalFormCategorias').modal('show');
}