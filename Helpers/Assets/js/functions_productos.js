var tableProductos;
var divLoading = document.querySelector("#divLoading");
let rowTable = "";
document.addEventListener('DOMContentLoaded', function()
{
   tableProductos = $('#tableProductos').dataTable( {
        "aProcessing":true,
        "aServerSide":true,
        "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
        },
        "ajax":{
            "url": " "+base_url+"/Productos/getProductos",
            "dataSrc":""
        },
        "columns": [
            { "data": "idproducto" },
            { "data": "codigo" },
            { "data": "nombre" },
            { "data": "stock" },
            { "data": "precio" },
            { "data": "status" },
            { "data": "options" }
        ],
        //colocando clase a cada celda
        "columnDefs": [
                        { 'className': "textcenter", "targets": [3] },
                        { 'className': "textright", "targets": [4] },
                        { 'className': "textcenter", "targets": [5] }
                      ],

         //botones de exportacion
         'dom': 'lBfrtip',
        'buttons': [
            {
                "extend": "copyHtml5",
                "text": "<i class= 'far fa-copy'></i> Copiar",
                "titleAttr":"Copiar",
                "className": "btn btn-secondary",
                "exportOptions": {
                    "columns": [0, 1, 2, 3, 4, 5]
                }
            },{
                "extend": "excelHtml5",
                "text": "<i class= 'fas fa-file-excel'></i> Excel",
                "titleAttr":"Esportar a Excel",
                "className": "btn btn-success",
                "exportOptions": {
                    "columns": [0, 1, 2, 3, 4, 5]
                }
            },{
                "extend": "pdfHtml5",
                "text": "<i class= 'fas fa-file-pdf'></i> PDF",
                "titleAttr":"Esportar a PDF",
                "className": "btn btn-danger",
                "exportOptions": {
                    "columns": [0, 1, 2, 3, 4, 5]
                }
            },{
                "extend": "csvHtml5",
                "text": "<i class= 'fas fa-file-csv'></i> CSV",
                "titleAttr":"Esportar a CSV",
                "className": "btn btn-info",
                "exportOptions": {
                    "columns": [0, 1, 2, 3, 4, 5]
                }
            }
        ],
       
        "responsieve":"true",
        "bDestroy": true,
        "iDisplayLength": 2,
        "order":[[0,"desc"]]
    });

//Proceso de creacion de html donde se montara las imagebes
  if (document.querySelector(".btnAddImage")){
      let btnAddImage = document.querySelector(".btnAddImage");
      btnAddImage.onclick = function(e){
        let key = Date.now(); // retorna fecha y hora, min , seg
        //creamos un nuevo div con javascript 
        let newElement = document.createElement("div");
        //A ese nuevo div le agregamos un id con key
        newElement.id = "div"+key;
        // al nuevo div le agregamos un nodo para cargar una imagen
        newElement.innerHTML = `
            <div class="prevImage">
            </div>
            <input type="file" name="foto" id="img${key}" class="inputUploadfile">
            <label for="img${key}" class="btnUploadfile"><i class="fas fa-upload "></i></label>
            <button class="btnDeleteImage notBlock" type="button" onclick="fntDelItem('#div${key}')"><i class="fas fa-trash-alt"></i></button>`;
        //al contenedor ya existente en el modal le agregamos el nuevo div que creamos 
        document.querySelector("#containerImages").appendChild(newElement);
        //le agregamos la opcion click al boton verde de cargar una nueva imagen
        document.querySelector("#div"+key+" .btnUploadfile").click();
        fntInputFile();
      }
  }

   fntCategorias(); 
   fntInputFile();
   //fntEditInfo();
}, false);

// se crea aqui y no en el footer porque solo se va a utilizar en esta ruta y evita gastar mas recurso de lo necesarios
document.write(`<script src="${base_url}/Assets/js/plugins/JsBarcode.all.min.js"></script>`);

$(document).on('focusin', function(e) {
    if ($(e.target).closest(".tox-dialog").length) {
        e.stopImmediatePropagation();
    }
});

// configuracion del div donde se muestra el codigo de barra
if (document.querySelector("#txtCodigo")){
    let inputCodigo = document.querySelector("#txtCodigo");
    // a la variable se le asigna el evento onkeyup el cual ejecuta la siguiente function()
    inputCodigo.onkeyup = function() {
        if(inputCodigo.value.length >= 5)
        {
            document.querySelector('#divBarCode').classList.remove("notBlock");
            fntBarcode();  
        }
        else
        {
            document.querySelector('#divBarCode').classList.add("notBlock");
        }
    };
}



//codigo para agregar al txtarea
tinymce.init({
    selector: '#txtDescripcion',
    width: "100%",
    height: 400,    
    statubar: true,
    plugins: [
        "advlist autolink link image lists charmap print preview hr anchor pagebreak",
        "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
        "save table contextmenu directionality emoticons template paste textcolor"
    ],
    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons",
});

if(document.querySelector("#formProductos"))
    {
        // crear  nuevo usuario o editar un usuarios
        let formProductos = document.querySelector("#formProductos");
        //al enviar en formulario ejecuta la suguiente funcion
        formProductos.onsubmit = function(e)
        {
                e.preventDefault(); //pare que no se recargue la pagina ya que el boton es tipo submit
                let strNombre = document.querySelector('#txtNombre').value;
                //let strDescripcion = document.querySelector('#txtDescripcion').value;
                let intCodigo = document.querySelector('#txtCodigo').value;
                let strPrecio = document.querySelector('#txtPrecio').value;
                let intStock = document.querySelector('#txtStock').value;
                //let intCategoria = document.querySelector('#listCategoria').value;
                let intStatus = document.querySelector('#listStatus').value;
                

                if (strNombre == '' || intCodigo =='' || strPrecio  =='' || intStock =='')
                {
                    swal("Atencion", "Todos los campos son obligatorios." , "error");
                    return false;
                }

                if (intCodigo.length < 5)
                {
                    swal("Atencion", "El codigo debe ser mayor que 5 digitos." , "error");
                    return false;
                }

                 divLoading.style.display = "flex";
                 tinyMCE.triggerSave(); //pasa todo lo que esta en el editor al textarea
                let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
                let ajaxUrl = base_url +'/Productos/setProductos';
                let formData = new FormData(formProductos);
                request.open("POST",ajaxUrl,true);
                request.send(formData);
                request.onreadystatechange = function()
            {
                if (request.readyState == 4 && request.status == 200)
                {
                    //console.log(request.responseText); //esto aparece en la consola
                
                    let objData = JSON.parse(request.responseText);
                    if (objData.status)
                    {
                        swal("", objData.msg , "success");
                        document.querySelector("#idProducto").value = objData.idproducto;
                        document.querySelector("#containerGallery").classList.remove("notBlock");

                       if (rowTable == '')
                        {
                            //para refrescar los datos y que aparescan en el data table
                            tableProductos.api().ajax.reload();
                        }
                        else
                        {
                            htmlStatus = intStatus == 1 ?
                            '<span class="badge badge-success">Activo</span>' :
                            '<span class="badge badge-danger">Inactivo</span>';
                            rowTable.cells[1].textContent = intCodigo;
                            rowTable.cells[2].textContent = strNombre;
                            rowTable.cells[3].textContent = intStock;
                            rowTable.cells[4].textContent = smony+strPrecio;
                            rowTable.cells[5].innerHTML = htmlStatus;
                            rowTable = "";
                        }
                        //para ocultar el modal
                      // $('#modalFormProductos').modal("hide");
                        // para resetiar todos los campos
                       // formProductos.reset();
                       // swal("Productos", objData.msg, "success"); 
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
//funcion para cargar imagen
function fntInputFile()
{
    let inputUploadfile = document.querySelectorAll(".inputUploadfile");
    //recorre todos los elementos que tenga la clase inputUploadfile
    inputUploadfile.forEach(function(inputUploadfile){
        inputUploadfile.addEventListener('change', function(){
            let idProducto = document.querySelector("#idProducto").value;
            //se obtiene el id del elemento padre del elemento al que le estamos dando click (boton verde)
            let parentId = this.parentNode.getAttribute("id");
            //se obtiene el id del input tipo file que esta arriba del elemento que le estamos dando click
            let idFile = this.getAttribute("id");
            //se obtiene la foto del input que contiene el id concatenado con la almoadilla (la imagen)
            let uploadFoto = document.querySelector("#"+idFile).value;
            //se obtiene la informacion del input (info de la foto) que contiene el id concatenado con la almoadilla (el archivo o informacion de la imagen)
            let fileimg = document.querySelector("#"+idFile).files;
            //nos dirigimos al id del elemento padre para luego ir al div con la clase prevImage
            let prevImg = document.querySelector("#"+parentId+" .prevImage");
            //para el tipo de navegador en que nos encontremos  
            let nav = window.URL || window.webkitURL;
            if(uploadFoto !='')
            {
                let type = fileimg[0].type;
                let name = fileimg[0].name;
                if (type != 'image/jpeg' && type != 'image/jpg' && type != 'image/png'){
                    //si no se encuentra ninguno de los tipos  en el div de prevImage se coloca arch...
                    prevImg.innerHTML = "archivo no valido";
                    // donde va la image se coloca vacio
                    uploadFoto.value = "";
                    return false;
                }
                else
                {
                    //hace referencia de ese input y toma los valores de ese archivo
                    let objeto_url = nav.createObjectURL(this.files[0]);
                    //le agregamor el loading al input
                    prevImg.innerHTML = `<img class="loading" src="${base_url}/Assets/images/loading.svg" >`;
                    //donde se envia la imagen con ajax
                    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
                    let ajaxUrl = base_url+'/Productos/setImage';
                    //creamos una variable tipo formulario
                    let formData = new FormData();
                    // a ese formulario le agregamos el id del producto
                    formData.append('idproducto' ,idProducto);
                    //a ese formulario le agregamos la informacion de la foto
                    formData.append("foto", this.files[0]);
                    request.open("POST", ajaxUrl, true);
                    request.send(formData);
                    request.onreadystatechange = function(){
                        if (request.readyState !=4) return;
                        if (request.status == 200){
                            let objData = JSON.parse(request.responseText);
                            if (objData.status){
                               //guardamos la ruta de la imagen
                                prevImg.innerHTML = `<img src="${objeto_url}">`;
                                //al boton con la clase btnDeleteImage se le agrega el atributo imgname que es el nombre de la foto
                                document.querySelector("#"+parentId+" .btnDeleteImage").setAttribute("imgname",objData.imgname)
                                // al elemento con la clase btnUmploadFile se le agrega la clase notBlock para que se oculte
                                document.querySelector("#"+parentId+" .btnUploadfile").classList.add("notBlock");
                                // al elemento con la clase btnDeleteImage se le quita la clase notBlock para que se muestre
                                document.querySelector("#"+parentId+" .btnDeleteImage").classList.remove("notBlock"); 
                            }
                            else
                            {
                                swal("Error", objData.msg, "error");
                            }
                        }
    
                    }

                }

            }

        });
    });
}

function fntDelItem(element)
{
    let nameImg = document.querySelector(element+' .btnDeleteImage').getAttribute("imgname");
    let idProducto = document.querySelector("#idProducto").value;
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url+'/Productos/delFile';

    let formData = new FormData();
    formData.append('idproducto',idProducto);
    formData.append("file",nameImg);
    request.open("POST",ajaxUrl,true);
    request.send(formData);
    request.onreadystatechange = function()
    {
        if (request.readyState != 4) return;
        if (request.status == 200)
        {
            let objData = JSON.parse(request.responseText);
            if (objData.status)
            {
                let itemRemove = document.querySelector(element);
                itemRemove.parentNode.removeChild(itemRemove);
            }
            else
            {
                swal("", objData.msg , "error");
            }
        }
    }
}

function fntCategorias()
{
    if(document.querySelector('#listCategoria'))
    {
        let ajaxUrl = base_url+'/Categorias/getSelectCategorias';
        var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
        request.open("GET",ajaxUrl,true);
        request.send();
        request.onreadystatechange = function(){
            if (request.readyState == 4 && request.status == 200){
                document.querySelector('#listCategoria').innerHTML = request.responseText;
                $('#listCategoria').selectpicker('render');
            }
        }
    }
}

// funcion para generar codigo de barra
function fntBarcode()
{
    let codigo = document.querySelector("#txtCodigo").value;
    JsBarcode("#barcode", codigo);
}

function fntPrintBarcode(area){
    let elementArea = document.querySelector(area);
    //abre una nueva ventana con el alto y ancho determinado
    let vprint = window.open(' ', 'popimpr', 'height=400,width=600');
    //lo que tenemos en elementArea lo copiamos en su html
    vprint.document.write(elementArea.innerHTML);
    vprint.document.close();
    vprint.print();
    vprint.close();
}

function fntViewInfo(idproducto)
{
    
    //let no permite repetir el mismo nombre de letiable
    //let idpersona = idpersona;
    //$('#modalViewProducto').modal('show');
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url+'/Productos/getProducto/'+idproducto;
    request.open("GET",ajaxUrl,true);
    request.send();
    request.onreadystatechange = function()
    {
        if(request.readyState == 4 && request.status == 200)
        {
            let objData = JSON.parse(request.responseText);
            if (objData.status) 
            {
                //console.log(objData);
                let objProducto = objData.data;
                //console.log(objProducto.images);
                let htmlImage = "";
                let estadoProducto = objData.data.status ==  1 ?
                '<span class="badge badge-success">Activo</span>':
                '<span class="badge badge-danger">Inactivo</span>';

                document.querySelector("#celCodigo").innerHTML = objData.data.codigo;
                document.querySelector("#celNombre").innerHTML = objData.data.nombre;
                document.querySelector("#celPrecio").innerHTML = objData.data.precio;
                document.querySelector("#celStock").innerHTML = objData.data.stock;
                document.querySelector("#celCategoria").innerHTML = objData.data.categoria;
                document.querySelector("#celStatus").innerHTML = estadoProducto;
                document.querySelector("#celDescripcion").innerHTML = objData.data.descripcion;
                //si el objetoProducto en la posicion images tiene imagenes
                if(objProducto.images.length > 0)
                {
                    //se crea una variable para guardar todas las images
                    let objProductos = objProducto.images;
                    //se recorre la variable o arreglo con todas las fotos
                    for (let p = 0; p < objProductos.length; p++) {
                        //cada imagen que se encuentre en el arreglo se guarda su ruta en la src(ruta) de una etiqueta img 
                        // y se concatenan a la variable htmlImage como un acumulador
                        // de este modo la variable htmlImage va a contener todas las imagenes asociadas al producto seleccionado
                        htmlImage +=`<img src="${objProductos[p].url_image}"></img>`;
                    }
                }
                //se agrega a variable htmlimage con las imagenes a la tabla con el campo #celFotos
                document.querySelector("#celFotos").innerHTML = htmlImage;
                //se abre el modal con toda la informacion e imagenes
                $('#modalViewProducto').modal('show'); 
            }
            else
            {
                swal("Error", objData.msg , "error" );
            } 
        }
    }                                                                           
} 

function fntEditInfo(element,idproducto)
{   
    rowTable = element.parentNode.parentNode.parentNode;
    //rowTable.cells[1].textContent = "goku";
    //console.log(rowTable);   
    document.querySelector('#titleModal').innerHTML = "Actualizar Producto";
    document.querySelector('.modal-header').classList.replace("headerRegister", "headerUpdate");
    document.querySelector('#btnActionForm').classList.replace("btn-primary", "btn-info");
    document.querySelector('#btnText').innerHTML = "Actualizar";


    //let no permite repetir el mismo nombre de letiable
    //let idpersona = idpersona;
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url+'/Productos/getProducto/'+idproducto;
    request.open("GET",ajaxUrl,true);
    request.send();
    request.onreadystatechange = function()
    {
        if (request.readyState == 4 && request.status == 200)
         {
            let objData = JSON.parse(request.responseText);
            if (objData.status)
             {

                let htmlImage = "";
                let objProducto = objData.data
                document.querySelector("#idProducto").value = objProducto.idproducto;
                document.querySelector("#txtNombre").value = objProducto.nombre;
                document.querySelector("#txtDescripcion").value = objProducto.descripcion;
                document.querySelector("#txtCodigo").value = objProducto.codigo;
                document.querySelector("#txtPrecio").value = objProducto.precio;
                document.querySelector("#txtStock").value = objProducto.stock;
                document.querySelector("#listCategoria").value = objProducto.categoriaid;

                tinymce.activeEditor.setContent(objProducto.descripcion);
                $('#listCategoria').selectpicker('render');


                if (objProducto.status == 1)
                 {
                    document.querySelector("#listStatus").value = 1;
                 }
                 else
                 {
                    document.querySelector("#listStatus").value = 2;
                 }
                 //para que se seleccione el valor que se ha designado
                 $('#listStatus').selectpicker('render');
                 //mostrar codigo de barra
                 fntBarcode();

                 if (objProducto.images.length > 0) 
                 {
                    let objProductos = objProducto.images;
                    for (let p = 0; p < objProductos.length; p++) {
                        let key = Date.now()+p;
                        htmlImage +=`<div id="div${key}">
                            <div class="prevImage">
                            <img src="${objProductos[p].url_image}"></img>
                            </div>
                            <button class="btnDeleteImage" type="button" onclick="fntDelItem('#div${key}')" imgname="${objProductos[p].img}">
                            <i class="fas fa-trash-alt"></i></button></div>`;
                    }
                 }

                 document.querySelector("#containerImages").innerHTML = htmlImage;
                 document.querySelector("#divBarCode").classList.remove("notBlock"); 
                 document.querySelector("#containerGallery").classList.remove("notBlock");
                 $('#modalFormProductos').modal('show');
             }
             else
             {
                swal("Error", objData.msg , "error");
             }
         }
    }
}

function fntDelInfo(idproducto)
{
    //let no permite repetir el mismo nombre de letiable
    // let idUsuario = idpersona;
     
     swal({
            title: "Eliminar Producto",
            text: "Realmente quiere eliminar el producto?",
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
            let ajaxUrl = base_url+'/Productos/delProducto/';
            let strData = "idProducto="+idproducto;
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
                         tableProductos.api().ajax.reload();//{
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

function openModal()
{
    rowTable = "";
    //removePhoto();
    document.querySelector('#idProducto').value = "";
    document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");
    document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
    document.querySelector('#btnText').innerHTML = "Guardar";
    document.querySelector('#titleModal').innerHTML = "Nuevo Producto";
    document.querySelector('#formProductos').reset();
    document.querySelector("#divBarCode").classList.add("notBlock");
    document.querySelector("#containerGallery").classList.add("notBlock");
    document.querySelector("#containerImages").innerHTML = "";
    $('#modalFormProductos').modal('show');
}