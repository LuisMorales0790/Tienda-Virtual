$(".js-select2").each(function(){
	$(this).select2({
		minimumResultsForSearch: 20,
		dropdownParent: $(this).next('.dropDownSelect2')
	});
})

////////////////////////////////////////////////////////////////

$('.parallax100').parallax100();

///////////////////////////////////////////////////////////////////
$('.gallery-lb').each(function() { // the containers for all your galleries
	$(this).magnificPopup({
        delegate: 'a', // the selector for gallery item
        type: 'image',
        gallery: {
        	enabled:true
        },
        mainClass: 'mfp-fade'
    });
});

/////////////////////////////////////////////////////////////////////

$('.js-addwish-b2').on('click', function(e){
	e.preventDefault();
});

$('.js-addwish-b2').each(function(){
	var nameProduct = $(this).parent().parent().find('.js-name-b2').html();
	$(this).on('click', function(){
		swal(nameProduct, "is added to wishlist !", "success");

		$(this).addClass('js-addedwish-b2');
		$(this).off('click');
	});
});

$('.js-addwish-detail').each(function(){
	var nameProduct = $(this).parent().parent().parent().find('.js-name-detail').html();

	$(this).on('click', function(){
		swal(nameProduct, "is added to wishlist !", "success");

		$(this).addClass('js-addedwish-detail');
		$(this).off('click');
	});
});

/*------------agregar al carrito---------------------------------*/

$('.js-addcart-detail').each(function(){
	var nameProduct = $(this).parent().parent().parent().parent().find('.js-name-detail').html();
	$(this).on('click', function(){
		let id = this.getAttribute('id');
		let cant = document.querySelector('#cant-product').value;
        //NaN si no es un numero o cant < 1
		if(isNaN(cant) || cant < 1)
		{
			swal("","La cantidad debe ser mayor o igual que 1", "error");
			return;
		}

		let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
		let ajaxUrl = base_url+'/Tienda/addCarrito';
		let formData = new FormData();
		formData.append('id',id);
		formData.append('cant',cant);

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
					document.querySelector("#productosCarrito").innerHTML = objData.htmlCarrito;
				    //document.querySelectorAll(".cantCarrito")[0].setAttribute("data-notify",objData.cantCarrito)
				    //document.querySelectorAll(".cantCarrito")[1].setAttribute("data-notify",objData.cantCarrito)
				    const cants = document.querySelectorAll(".cantCarrito");
				    cants.forEach(element => {
				    	element.setAttribute("data-notify",objData.cantCarrito)
				    });
					  swal(nameProduct, "!Se agrego al carrito!", "success");
				}
				else
				{
					swal("", objData.msg , "error");
				}
			}
			return false;
		}

		swal(nameProduct, "is added to cart !", "success");
	});
});

//////////////////////////////////////////////////////////
$('.js-pscroll').each(function(){
	$(this).css('position','relative');
	$(this).css('overflow','hidden');
	var ps = new PerfectScrollbar(this, {
		wheelSpeed: 1,
		scrollingThreshold: 1000,
		wheelPropagation: false,
	});

	$(window).on('resize', function(){
		ps.update();
	})
});

/*===================Agrega - desagrega productos al carrito===============
    [ +/- num product ]*/
    $('.btn-num-product-down').on('click', function(){
        let numProduct = Number($(this).next().val());
        let idpr = this.getAttribute('idpr');
        if(numProduct > 1) $(this).next().val(numProduct - 0);
        let cant = $(this).next().val();
        if(idpr != null)
        {
        	fntUpdateCant(idpr,cant);
        }
    });

    $('.btn-num-product-up').on('click', function(){
        let numProduct = Number($(this).prev().val());
        let idpr = this.getAttribute('idpr');
        $(this).prev().val(numProduct + 0);
        let cant = $(this).prev().val();
        if(idpr != null)
        {
        	fntUpdateCant(idpr,cant);
        }
    });

    //Actualizar producto sin botones + -
    if (document.querySelector(".num-product")){
     	let inputCant = document.querySelectorAll(".num-product");
     	inputCant.forEach(function(inputCant){
     		inputCant.addEventListener('keyup',function(){
     			let idpr = this.getAttribute('idpr');
     			let cant = this.value;
     			if(idpr != null)
		        {
		        	fntUpdateCant(idpr,cant);
		        }
     		});
     	});
     }

if(document.querySelector("#formRegister"))
{
	// crear  nuevo usuario o editar un usuarios
	let formRegister = document.querySelector("#formRegister");
	//al enviar en formulario ejecuta la suguiente funcion
	formRegister.onsubmit = function(e)
	{
			e.preventDefault();
			let strNombre = document.querySelector('#txtNombre').value;
			let strApellido = document.querySelector('#txtApellido').value;
			let strEmail = document.querySelector('#txtEmailCliente').value;
			let intTelefono = document.querySelector('#txtTelefono').value;
			//console.log(intTelefono)

			if (strNombre =='' || strApellido =='' || strEmail  =='' || intTelefono =='')
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

			//divLoading.style.displpay = "flex";
			let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
			let ajaxUrl = base_url +'/Tienda/registro';
			let formData = new FormData(formRegister);
			request.open("POST",ajaxUrl,true);
			request.send(formData);
			request.onreadystatechange = function()
		{
			if (request.readyState == 4 && request.status == 200)
			{
				let objData = JSON.parse(request.responseText);
				if (objData.status)
				{
					window.location.reload(false);
				}
				else
				{
					swal("Error", objData.msg, "error");	
				}
			}
			//div.loading.style.displpay = "none";
			return false;			
		}
	}
}

if (document.querySelector(".methodpago"))
 {
 	optmetodo = document.querySelectorAll(".methodpago");
 	optmetodo.forEach(function(optmetodo){
 		optmetodo.addEventListener('click', function(){
 			if (this.value == "Paypal"){
 				document.querySelector("#msgpaypal").classList.remove("notBlock");
 				document.querySelector("#divtipopago").classList.add("notBlock");
 			}
 			else
 			{
 				document.querySelector("#msgpaypal").classList.add("notBlock");
 				document.querySelector("#divtipopago").classList.remove("notBlock");
 			}
 		});
 	});
 }




function fntdelItem(element)
{
	//console.log(element);
	//Option 1 = Eliminar desde el Modal
	//Option 2 = Eliminar desde Vista Carrito
	let option = element.getAttribute("op");
	let idpr = element.getAttribute("idpr");

	if (option == 1 || option == 2) 
	{
		let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
		let ajaxUrl = base_url+'/Tienda/delCarrito';
		let formData = new FormData();
		formData.append('id',idpr);
		formData.append('option',option);

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
					if (option == 1)
					{
						//Para eliminar desde el modal
						document.querySelector("#productosCarrito").innerHTML = objData.htmlCarrito;


 						const cants = document.querySelectorAll(".cantCarrito");
				   		cants.forEach(element => {
				    		element.setAttribute("data-notify",objData.cantCarrito)
				   		});					
				    }
					else
					{
						//Para eliminar desde la vista carrito
						element.parentNode.parentNode.remove();
						//se agrega el nuevo subtotal luego de eliminar el elemnto
						document.querySelector("#subTotalCompra").innerHTML = objData.subTotal;
						document.querySelector("#totalCompra").innerHTML = objData.total;
						// 1 quiere decir que solo queda el encabezado y ya no hay productos
						if(document.querySelectorAll('#tblCarrito tr').length == 1)
						{
							window.location.href = base_url;
						}
					}
					
				}
				else
				{
					swal("", objData.msg , "error");
				} 
			}
			return false;
		}
	}
}

function fntUpdateCant(pro,cant)
{
		if(cant <= 0)
		{
			document.querySelector("#btmComprar").classList.add("notBlock");
		}
		else
		{
			document.querySelector("#btmComprar").classList.remove("notBlock");
			let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
			let ajaxUrl = base_url+'/Tienda/updCarrito';
			let formData = new FormData();
			formData.append('id',pro);
			formData.append('cantidad',cant);
			request.open("POST",ajaxUrl,true);
			request.send(formData);
			request.onreadystatechange = function()
			{
				if (request.readyState != 4) return;
				if (request.status == 200)
				{
					//console.log(request.responseText);
					let objData = JSON.parse(request.responseText);
					if (objData.status)
					{                                                           //se coloca [0] por ser una clase a la que se refiere
						let colSubtotal = document.getElementsByClassName(pro)[0];
						colSubtotal.cells[4].textContent = objData.totalProducto;
						document.querySelector("#subTotalCompra").innerHTML = objData.subTotal;
						document.querySelector("#totalCompra").innerHTML = objData.total;
					}
					else
					{
						swal("", objData.msg , "error");
					} 
				}
			} 
		}
    return false;
} 

