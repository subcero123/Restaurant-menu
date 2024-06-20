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

	$('.flexslider-top').flexslider({
		animation: "slide",
	  	directionNav: false, // Oculta los botones de dirección (previo y siguiente)
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

var about = document.getElementById("about-us");
var promos = document.getElementById("promos");
var faq = document.getElementById("faq");

// Agregar evento click a todos
about.addEventListener("click", function () {
  	document.getElementsByClassName("pantalla-about-us")[0].style.display = "block";
  	pantalla_principal[0].style.display = "none";
});

promos.addEventListener("click", function () {
	document.getElementsByClassName("pantalla-promos")[0].style.display = "block";
  	pantalla_principal[0].style.display = "none";
});

faq.addEventListener("click", function () {
	document.getElementsByClassName("pantalla-preguntas-frecuentes")[0].style.display = "block";
  	pantalla_principal[0].style.display = "none";
});

var platos = document.getElementsByClassName("plato");

for (i = 0; i < platos.length; i++) {
	platos[i].addEventListener("click", function () {
		mostrarPlato();
	});
}


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

