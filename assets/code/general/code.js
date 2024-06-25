jQuery(function ($) {
  //beginning

  // Can also be used with $(document).ready()
  $(window).load(function () {
    $(".flexslider").flexslider({
      animation: "slide",
      directionNav: false, // Oculta los botones de dirección (previo y siguiente)
      itemMargin: 5,
      itemWidth: 350,
    });

    // $('.flexslider-top').flexslider({
    // 	animation: "slide",
    //   	directionNav: false, // Oculta los botones de dirección (previo y siguiente)

    // });

    $(".js-abrir-plato").click(function () {
      // Verificar si contiene la clase js-pantalla-contenida
      
      if($(this).hasClass("js-pantalla-contenida")) {
        cerrarVentanas();

        // delay de 1 segundo
        setTimeout(function() {
          mostrarPlato();
        } , 100);
      }
      else{
        cerrarVentanas();
        mostrarPlato();
      }

      $("body").addClass("quitar-overflow");

      var nombrePlatillo = $(this).data("titulo");
      var precioPlatillo = $(this).data("precio");

      $("#titulo-platillo-individual").find("span").html(nombrePlatillo);
      $("#precio-platillo-individual")
        .find("span")
        .html("$" + precioPlatillo);
      $("#descripcion-platillo-individual")
        .find("p")
        .html($(this).data("descripcion"));
      $("#tiempo-platillo-individual")
        .find("span")
        .html($(this).data("tiempo") + " min aprox.");
      $("#calorias-platillo-individual")
        .find("span")
        .html($(this).data("calorias") + " cal");
      $("#gramos-platillo-individual")
        .find("span")
        .html($(this).data("gramos") + " gr");
      $("#ingredientes-platillo-individual")
        .find("p")
        .html($(this).data("ingredientes"));

      //  Obtener el data de imagenes
      var imagenes = $(this).data("imagenes");
      var imagenesArray = imagenes.split(",");

      // Obtener la ruta actual
      var ruta = window.location.href;

      var html = "";
      $.each(imagenesArray, function (index, value) {
        html +=
          '<li class="slide"><img src="' +
          ruta +
          "wp-content/themes/RestP/assets/images/" +
          value +
          '" /></li>';
      });

      $("#imagenes-platillo-individual").html(html);

      destroyFlexslider();

      $(".flexslider-top").flexslider({
        animation: "slide",
        directionNav: false, // Oculta los botones de dirección (previo y siguiente)
      });

      function destroyFlexslider() {
        var $slider = $(".flexslider-top");
        $slider.find(".flex-viewport").children().unwrap();
        $slider.find(".clone").remove();
        $slider.removeData("flexslider");
        $slider.removeClass("flexslider flexslider-loading");
        $slider.find(".slides").removeAttr("style");
        $slider.find(".slides > li").removeAttr("style");
        $slider.find(".flex-control-nav").remove();
      }
    });

    $(".js-abrir-promo").click(function () {
      // Verificar si contiene la clase js-pantalla-contenida

      cerrarVentanas();
      mostrarPromo();
      $("body").addClass("quitar-overflow");
      var textoPromo = $(this).data("texto");
      var imagen = $(this).data("imagen");

      // Obtener la ruta actual
      var ruta = window.location.href;

      $("#texto-promo-individual").find("p").html(textoPromo);
      $("#imagen-promo-individual")
        .find("img")
        .attr("src", ruta + "wp-content/themes/RestP/assets/images/" + imagen);
    });

    $("#about-us").click(function () {
      cerrarVentanas();

      $(".pantalla-about-us").addClass("pantalla-card--active");
      $("body").addClass("quitar-overflow");

      $(".flexslider-top--about").flexslider({
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
    var content = this.children[0];
    // Toggle de la clase active
    content.classList.toggle("titulo--active");

    // Obtenemos el siguiente hijo de la card
    var respuesta = this.children[1];
    // Toggle de la clase active
    respuesta.classList.toggle("respuesta--active");
  });
}

// Cerrar ventana
var coll = document.getElementsByClassName("cerrar-ventana");
var pantalla_principal = document.getElementsByClassName("pantalla-principal");

for (i = 0; i < coll.length; i++) {
  // Obtenemos el padre de la ventana
  // Poner display none al padre
  coll[i].addEventListener("click", function () {
    cerrarVentanas();
  });
}

var promos = document.getElementById("promos");
var faq = document.getElementById("faq");

promos.addEventListener("click", function () {
  cerrarVentanas();
  document
    .getElementsByClassName("pantalla-promos")[0]
    .classList.add("pantalla-card--active");
  document.getElementsByTagName("body")[0].classList.add("quitar-overflow");
});

faq.addEventListener("click", function () {
  cerrarVentanas();
  document
    .getElementsByClassName("pantalla-preguntas-frecuentes")[0]
    .classList.add("pantalla-card--active");
  document.getElementsByTagName("body")[0].classList.add("quitar-overflow");
});

// var platos = document.getElementsByClassName("plato");

// for (i = 0; i < platos.length; i++) {
// 	platos[i].addEventListener("click", function () {
// 		mostrarPlato();
// 	});
// }

function mostrarPlato() {
  document
    .getElementsByClassName("pantalla-platillo-individual")[0]
    .classList.add("pantalla-card--active");
  document.getElementsByTagName("body")[0].classList.add("quitar-overflow");
}

function mostrarPromo() {
  document
    .getElementsByClassName("pantalla-promo-individual")[0]
    .classList.add("pantalla-card--active");
    document.getElementsByTagName("body")[0].classList.add("quitar-overflow");
}

var regresar = document.getElementsByClassName("contenedor-regresar");

for (i = 0; i < regresar.length; i++) {
  regresar[i].addEventListener("click", function () {
    cerrarVentanas();
  });
}

// Obtener .categorias
var categorias = document.getElementsByClassName("categoria");

for (i = 0; i < categorias.length; i++) {
  categorias[i].addEventListener("click", function () {
    // Remover categoria--selected a todas las categorias
    removerCategoriaSelected();
    // Agregar categoria--selected a la categoria seleccionada
    this.classList.add("categoria--selected");

    // Obtener el data de la categoria seleccionada
    var categoria = this.dataset.categoria;

    // Iteramos sobre los hijos de .platos
    var platos = document.getElementsByClassName("js-plato-menu");

    for (i = 0; i < platos.length; i++) {
      platos[i].classList.remove("plato--active");

      // Si la categoria del plato es igual a la categoria seleccionada
      if (platos[i].dataset.cat == categoria) {
        // Mostrar el plato
        platos[i].classList.toggle("plato--active");
      }
    }
  });
}

// Remover categoria--selected a todas las categorias
function removerCategoriaSelected() {
  for (i = 0; i < categorias.length; i++) {
    categorias[i].classList.remove("categoria--selected");
  }
}

function cerrarVentanas() {
  var pantallaAboutUs = document.getElementsByClassName("pantalla-about-us");
  var pantallaPromos = document.getElementsByClassName("pantalla-promos");
  var pantallaPreguntasFrecuentes = document.getElementsByClassName(
    "pantalla-preguntas-frecuentes"
  );
  var pantallaPlatilloIndividual = document.getElementsByClassName(
    "pantalla-platillo-individual"
  );
  var pantallaPromoIndividual = document.getElementsByClassName(
    "pantalla-promo-individual"
  );

  var body = document.getElementsByTagName("body");

  var pantalla_principal =
    document.getElementsByClassName("pantalla-principal");

  pantallaAboutUs[0].classList.remove("pantalla-card--active");
  pantallaPromos[0].classList.remove("pantalla-card--active");
  pantallaPreguntasFrecuentes[0].classList.remove("pantalla-card--active");
  pantallaPlatilloIndividual[0].classList.remove("pantalla-card--active");
  pantallaPromoIndividual[0].classList.remove("pantalla-card--active");
  pantalla_principal[0].classList.remove("pantalla-card--active");
  body[0].classList.remove("quitar-overflow");



  // pantallaAboutUs[0].style.display = "none";
  // pantallaPromos[0].style.display = "none";
  // pantallaPreguntasFrecuentes[0].style.display = "none";
  // pantallaPlatilloIndividual[0].style.display = "none";
  // pantallaPromoIndividual[0].style.display = "none";
  // pantalla_principal[0].style.display = "block";
}
