jQuery(function ($) {
  //beginning

  // Can also be used with $(document).ready()
$(window).load(function() {
	$('.flexslider').flexslider({
	  	animation: "slide",
    	directionNav: false, // Oculta los botones de dirección (previo y siguiente)
		itemMargin: 5,
		itemWidth: 350,
	});

	
	// $('.flexslider-top').flexslider({
	// 	animation: "slide",
	//   	directionNav: false, // Oculta los botones de dirección (previo y siguiente)

  	// });


	$('.js-abrir-plato').click(function(){

		 mostrarPlato();


		 var nombrePlatillo = $(this).data('titulo');
		 var precioPlatillo = $(this).data('precio');

		 $('#titulo-platillo-individual').find('span').html(nombrePlatillo);
		 $('#precio-platillo-individual').find('span').html( "$"+precioPlatillo);
		 $('#descripcion-platillo-individual').find('p').html( $(this).data('descripcion'));
		 $('#tiempo-platillo-individual').find('span').html( $(this).data('tiempo') + " min aprox.");
		 $('#calorias-platillo-individual').find('span').html( $(this).data('calorias') + " cal");
		 $('#gramos-platillo-individual').find('span').html( $(this).data('gramos') + " gr");
		 $('#ingredientes-platillo-individual').find('p').html( $(this).data('ingredientes'));
		 $('#imagen-platillo-individual').attr('src', $(this).data('imagen'));
		 

		$('.flexslider-top').flexslider({
			animation: "slide",
			directionNav: false, // Oculta los botones de dirección (previo y siguiente)
		});



	});

	$('#about-us').click(function(){
		$('.pantalla-about-us').show();
		$('.pantalla-principal').hide();
		
		$('.flexslider-top--about').flexslider({
			animation: "slide",
			directionNav: false, // Oculta los botones de dirección (previo y siguiente)
		});
	});
  });

  //ending
});

var coll = document.getElementsByClassName("card");
var i;

for (i = 0; i < coll.length; i++) {
  coll[i].addEventListener("click", function () {
    // Obtenemos el hijo de la card de clase titulo
    var content = this.children[0].children[0];
	// Toggle de la clase active
	content.classList.toggle("titulo--active");		

	// Obtenemos el siguiente hijo de la card
    var respuesta = this.children[1];
	// Toggle de la clase show
	if (respuesta.style.display === "block") {
      respuesta.style.display = "none";
    } else {
      respuesta.style.display = "block";
    }						

  });
}

// Cerrar ventana
var coll = document.getElementsByClassName("cerrar-ventana");
var pantalla_principal = document.getElementsByClassName("pantalla-principal");

for (i = 0; i < coll.length; i++) {				
	// Obtenemos el padre de la ventana
	// Poner display none al padre
	coll[i].addEventListener("click", function () {
		parent = this.parentElement;
		parent.style.display = "none";
		pantalla_principal[0].style.display = "block";
	});

  
}

var promos = document.getElementById("promos");
var faq = document.getElementById("faq");


promos.addEventListener("click", function () {
	document.getElementsByClassName("pantalla-promos")[0].style.display = "block";
  	pantalla_principal[0].style.display = "none";
});

faq.addEventListener("click", function () {
	document.getElementsByClassName("pantalla-preguntas-frecuentes")[0].style.display = "block";
  	pantalla_principal[0].style.display = "none";
});

// var platos = document.getElementsByClassName("plato");

// for (i = 0; i < platos.length; i++) {
// 	platos[i].addEventListener("click", function () {
// 		mostrarPlato();
// 	});
// }


function mostrarPlato(){
	document.getElementsByClassName("pantalla-platillo-individual")[0].style.display = "block";
	pantalla_principal[0].style.display = "none";
}


var promos = document.getElementsByClassName("promo");
for (i = 0; i < promos.length; i++) {
	promos[i].addEventListener("click", function () {
		mostrarPromo();
	});
}

function mostrarPromo(){
	document.getElementsByClassName("pantalla-promo-individual")[0].style.display = "block";
	pantalla_principal[0].style.display = "none";
}

var regresar = document.getElementsByClassName("contenedor-regresar");

for (i = 0; i < regresar.length; i++) {
	regresar[i].addEventListener("click", function () {
		parent = this.parentElement;
		parent.style.display = "none";
		pantalla_principal[0].style.display = "block";
	});
}

