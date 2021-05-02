///////////////////////////////////////// MOSTRAR TABLA DE ROLES DE USUARIOS//////////////////////////////////////////
var tableRoles;
var divLoading = document.querySelector("#divLoading");
document.addEventListener('DOMContentLoaded', function()
{
   tableRoles = $('#tableRoles').dataTable( {
	   	"aProcessing":true,
	   	"aServerSide":true,
	   	"language": {
	   			"url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
	   	},
        "ajax":{
        	"url": " "+base_url+"/Roles/getRoles",
        	"dataSrc":""
        },
        "columns": [
            { "data": "idrol" },
            { "data": "nombrerol" },
            { "data": "descripcion" },
            { "data": "status" },
            { "data": "options" }
        ],
        "responsieve":"true",
        "bDestroy": true,
        "iDisplayLength": 10,
        "order":[[0,"desc"]]
    });

   //NUEVO ROL
   var formRol = document.querySelector("#formRol");
   formRol.onsubmit = function(e)
   {
        //previene que se recargue el formulario o la pagina
         e.preventDefault();

         var intIdRol = document.querySelector('#idRol').value;
         var strNombre = document.querySelector('#txtNombre').value;
         var strDescripcion = document.querySelector('#txtDescripcion').value;
         var intStatus = document.querySelector('#listStatus').value;
         if(strNombre == '' || strDescripcion == '' || intStatus == '')
         {
            swal("Atencion", "Todos los campos son obligatorios." , "error");
            return false;
         }
         // enviar datos por ajax
         //        si es navegador chrome o farefox crea un nuevp objeto, pero si es vegador de microsoft como edge
         divLoading.style.display = "flex";
         var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
         // enviamos al controlador con su ruta
         var ajaxUrl = base_url+'/Roles/setRol';
         //var formElement = document.querySelector("#formRol");
         //creamos un objetos con los datos del formulario
         var formData = new FormData(formRol);
         //enviamos los datos por ajax
         request.open("POST",ajaxUrl,true);
         request.send(formData);
         request.onreadystatechange = function()
         {
            if(request.readyState == 4 && request.status == 200)
            //.log(request.responseText);
           {
                //obtengo en formato json para convertirlo en un objeto javascript
                var objData = JSON.parse(request.responseText);

                if (objData.status)
                {
                    // aqui cierro el modal
                    $('#modalFormRol').modal("hide");
                    //reseteo el formulario del modal
                    formRol.reset();
                    swal("Roles de usuario", objData.msg ,"success");
                    //refreso del data table de rol para mostrar el nuevo rol
                    tableRoles.api().ajax.reload(function(){
                        //funcion para darle el evento click a los botones
                       //fntEditRol(); 
                       //fntDelRol();
                       //fntPermisos();
                    });
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

});

////////////////////////////////////////////////////////////////////
$('#tableRoles').DataTable();

function openModal(){
    //limpiamos el idrol para que no quede con el id de actualizar ya que vamos a crear un nuevo rol por ende debe ser un id distinto
    document.querySelector('#idRol').value = "";
    //remplazamos la clase de la cabacera del modal para cambiar de color
    document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");
    //remplazamos el color del boton de el modal verde por uno azul
    document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
    //remplazamos el texto de el boton de actualizar por Guardar
    document.querySelector('#btnText').innerHTML = "Guardar";
    //rempazamos del titulo de el moda de actualizar rol a Nuevo rol
    document.querySelector('#titleModal').innerHTML = "Nuevo Rol";
    //reseteamos el formulario para que tome los cambios que se le asignaron y limpia los campos
    document.querySelector('#formRol').reset();
    //se muestral el modal con los cambios efectuados
	$('#modalFormRol').modal('show');
}
// se agrega el evento load cuando se cargue todo el documento ejecuta la funcion
window.addEventListener('load', function() {
    //que seria el llamado de la funcion para darle el evento click a los botones
    //fntEditRol();
    //fntDelRol();
   // fntPermisos();
}, false);

function fntEditRol(idusario){
    // creo una variable donde guardo todos los elementos que tengan la clas .btnEditRol (botones)
   // var btnEditRol = document.querySelectorAll(".btnEditRol");
    //recorremos los botones con foreach Y le asignamos a cada uno como parametro de funcion btnEditRol con las clase
   // btnEditRol.forEach(function(btnEditRol){
        // agrego el evento click al boton para que al hacerlo
       // btnEditRol.addEventListener('click', function(){

            //cambiamos el titulo de el modal y remplazamos nuevo rol por actualizar rol
            document.querySelector('#titleModal').innerHTML ="Actualizar Rol";
            //cammbiamos el color de la cabezera del modal remplazando headerRegister por headerUpdate
            document.querySelector('.modal-header').classList.replace("headerRegister", "headerUpdate");
            //cambiamos el  color del boton del modal de btnprymari a btninfo (azul-verde)
            document.querySelector('#btnActionForm').classList.replace("btn-primary", "btn-info");
            //cambiamos el texto del el boton de Guardar por Actualizar
            document.querySelector('#btnText').innerHTML = "Actualizar"


            //script para ejecutar el ajax
            //aqui obtengo de id de la solicitud por medio de rl que es un atributo del boton editar
            //var idrol = this.getAttribute("rl");
            var idrol = idusario;
            //crea un objeto para chrome o firefox depende el navegador que se utilice
            var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            //creamos la ruta para obtener el archivo o la fila por medio de la ruta raiz + el controlador y la funcion + el idrol obetenido del boton
            var ajaxUrl = base_url + '/Roles/GetRol/'+idrol;
            //abrimos la coneccion y enviamos el metodo GET para obtener la info mas la ruta y true
            request.open("GET", ajaxUrl, true);
            //enviamos la peticion 
            request.send();

            //verificamos la respuesta de la peticion
            request.onreadystatechange = function(){
                //esto indica que fue exitos
                if (request.readyState == 4 && request.status == 200){

                    //convertimos en un objeto javascript el JSON
                    var objData = JSON.parse(request.responseText);
                    //si el estatus del objeto es true
                    if (objData.status)
                    { 
                        //paso el idrol al texbox id=#idrol que esta oculto
                        document.querySelector("#idRol").value = objData.data.idrol;
                        //paso el nombrerol al texbox id=#txtNombre del modal actualizar rol
                        document.querySelector('#txtNombre').value = objData.data.nombrerol;
                        //paso la descripcion al texbox id=#txtDescripcion del modal actualizar rol
                        document.querySelector("#txtDescripcion").value = objData.data.descripcion;
                        //si el estado de el registro es (activo=1,inactivo=0)
                        if (objData.data.status == 1)
                         {
                            //creo un combobox de html y lo asigno como activo
                            var optionSelect = '<option value="1" slected class="notBlock">Activo</option>'
                         }
                         else
                         {
                            //el mismo combobox de html y lo asigno como inacivo
                            var optionSelect = '<option value="2" slected class="notBlock">Inactivo</option>';
                         }

                         //creo el combobox con la opcion seleccionada anteriormente pero con la opcion de escoger la otra opcion
                         var htmlSelect = `${optionSelect}
                         <option value="1">Activo</option>
                         <option value="2">Inactivo</option>
                         `;
                         //tomo el id del cmbox original y le agrego la opcion seleccionada
                         document.querySelector('#listStatus').innerHTML = htmlSelect;
                         //muestra el modal con los datos traidos de la base de datos
                         $('#modalFormRol').modal('show');
                    }
                    else
                    {
                        //en caso de que es satus sea false se muestro un modal con mensaje de que el rol no existe
                        swal("Error", objData.msg , "error");
                    }

                }
            }

            //me muestre el modal actualizado 
           // $('#modalFormRol').modal('show');


     ///   });
   // });

}

//funcion para eliminar un rol
    function fntDelRol(idusario)
{
        //selecciona todos los elementos(botones) que tengan la clase .btnRol
       // var btnDelRol = document.querySelectorAll(".btnDelRol");
        //recorre los elementos(botones) 
        //btnDelRol.forEach(function(btnDelRol)
    //{
        //agrega eel evento click a los botones
      //  btnDelRol.addEventListener('click', function()
        
            //y que al dar click tome el atributo rl(id) del boton
             //var idrol = this.getAttribute("rl");
             var idrol = idusario;
             swal({
                    title: "Eliminar Rol",
                    text: "Realmente quiere eliminar el Rol?",
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
                    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
                    var ajaxUrl = base_url+'/Roles/delRol/';
                    var strData = "idrol="+idrol;
                    request.open("POST",ajaxUrl,true);
                    //forma como se van a enviar los datos
                    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                    request.send(strData);
                    //se recibe la respuesta
                    request.onreadystatechange = function()
                    {
                        if(request.readyState == 4 && request.status == 200)
                        {
                            var objData = JSON.parse(request.responseText);
                            if (objData.status) 
                            {
                                swal("Eliminar!", objData.msg , "success");
                                tableRoles.api().ajax.reload();
                                    //fntEditRol();
                                    //fntDelRol();
                                    //fntPermisos();
                            }
                            else
                            {
                                swal("Atencion!", objData.msg , "error");
                            }
                        }
                    }
                }
            });
       // });
    //});  
}

function fntPermisos(idusario){
  //  var btnPermisosRol = document.querySelectorAll(".btnPermisosRol");
   // btnPermisosRol.forEach(function(btnPermisosRol){
     //  btnPermisosRol.addEventListener('click', function(){

           // var idrol = this.getAttribute("rl");
            var idrol = idusario;
            var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            var ajaxUrl = base_url+'/Permisos/getPermisosRol/'+idrol;
            request.open("GET",ajaxUrl,true);
            request.send();

            request.onreadystatechange = function(){
                if (request.readyState == 4 && request.status == 200){
                   // console.log(request.responseText);
                    document.querySelector('#contentAjax').innerHTML = request.responseText;
                    $('.modalPermisos').modal('show');
                    document.querySelector('#formPermisos').addEventListener('submit',fntSavePermisos, false);                }
            }
            
      //  });
    //});
}

function fntSavePermisos(event){
    event.preventDefault();
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    var ajaxUrl = base_url+'/Permisos/setPermisos';
    var formElement = document.querySelector('#formPermisos');
    var formData = new FormData(formElement);
    request.open("POST",ajaxUrl,true);
    request.send(formData);

     request.onreadystatechange = function(){
        if (request.readyState == 4 && request.status == 200){
            var objData = JSON.parse(request.responseText);
            if(objData.status)
            {
                swal("Permiso de usuario", objData.msg , "success");
            }
            else
            {
                swal("Error", objData.msg , "error");
            }
        }
    }
}