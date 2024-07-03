jQuery(function ($) {
  $(document).ready(function () {
    $(".flexslider").flexslider({
      animation: "slide",
      directionNav: false, // Oculta los botones de dirección (previo y siguiente)
      itemMargin: 5,
      itemWidth: 350,
    });
  });

  // Can also be used with $(document).ready()
  $(window).load(function () {
    // $('.flexslider-top').flexslider({
    // 	animation: "slide",
    //   	directionNav: false, // Oculta los botones de dirección (previo y siguiente)

    // });

    $(".js-abrir-plato").click(function () {
      // Verificar si contiene la clase js-pantalla-contenida

      if ($(this).hasClass("js-pantalla-contenida")) {
        cerrarVentanas();

        // delay de 1 segundo
        setTimeout(function () {
          mostrarPlato();
        }, 100);
      } else {
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
      var html = "";
      $.each(imagenesArray, function (index, value) {
        html += '<li class="slide"><img src="' + value + '" /></li>';
      });

      // Si solo es una imagen
      if (imagenesArray.length == 1) {
        html += '<li class="slide"><img src="' + imagenesArray[0] + '" /></li>';
      }

      $("#imagenes-platillo-individual").html(html);

      destroyFlexslider();

      $(".flexslider-top").flexslider({
        animation: "slide",
        directionNav: false, // Oculta los botones de dirección (previo y siguiente)
      });

      // Iterar sobre el data-recomendado que es un json
      var recomendado = $(this).data("recomendado");

      var htmlRecomendado = "";
      $.each(recomendado, function (index, value) {
        var descuentoHtml = "";
        if (value.descuento) {
          descuentoHtml = `<div class="descuento"> <span class="mini">ANTES</span> <span class="tachado">${value.precioDescuento}</span>    </div>`;
        }

        htmlRecomendado += `
            <div class="plato plato--active js-pantalla-contenida js-abrir-plato-sugerencia" data-id="${value.id}">
                <div class="imagen" style="background-image: url(${value.imagenes})">
                </div>
                <div class="descripcion">
                    <div class="superior">
                        <div class="nombre-platillo">
                            <span>${value.titulo}</span>
                        </div>
                        <div class="precio">
                            <div class="normal">
                              $${value.precio}
                            </div>
                            ${descuentoHtml}
                        </div>
                    </div>
                    <div class="tiempo">
                        <img src="//localhost:3000/work/wordpress/RestP/wp-content/themes/RestP/assets/images/reloj.svg" alt="Descripción del SVG">
                        <span>${value.tiempo} min aprox</span>
                    </div>
                    <div class="resumen">
                        <span>${value.descripcionSmall}</span>
                    </div>
                    
                </div>
            </div>
        `;
      });

      $('#carta-sugerencias').html(htmlRecomendado);

      // Agregar evento click a los platos sugeridos
      $(".js-abrir-plato-sugerencia").click(function () {
        console.log("hola");
        cerrarVentanas();
        // Abrir el plato con id data-id
        var idPlato = $(this).data("id");
        var plato = $(`.js-abrir-plato[data-id=${idPlato}]`);
        plato.click();
       });

       $(".cerrar-ventana").addClass("cerrar-ventana--animado");


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

      $("#texto-promo-individual").html(textoPromo);
      $("#imagen-promo-individual").find("img").attr("src", imagen);
      $(".cerrar-ventana").addClass("cerrar-ventana--animado");
    });

    $("#about-us").click(function () {
      cerrarVentanas();

      $(".pantalla-about-us").addClass("pantalla-card--active");
      $("body").addClass("quitar-overflow");
      $(".overlay").addClass("overlay--active");

      $(".flexslider-top--about").flexslider({
        animation: "slide",
        directionNav: false, // Oculta los botones de dirección (previo y siguiente)
      });

      $(".cerrar-ventana").addClass("cerrar-ventana--animado");

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
  document
    .getElementsByClassName("overlay")[0]
    .classList.add("overlay--active");
  document.getElementsByClassName("cerrar-ventana")[0].classList.add("cerrar-ventana--animado");

});

faq.addEventListener("click", function () {
  cerrarVentanas();
  document
    .getElementsByClassName("pantalla-preguntas-frecuentes")[0]
    .classList.add("pantalla-card--active");
  document.getElementsByTagName("body")[0].classList.add("quitar-overflow");
  document
    .getElementsByClassName("overlay")[0]
    .classList.add("overlay--active");

  document.getElementsByClassName("cerrar-ventana")[0].classList.add("cerrar-ventana--animado");
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
  // Agregar a overlay la clase overlay--active
  document
    .getElementsByClassName("overlay")[0]
    .classList.add("overlay--active");
  document
    .getElementsByClassName("overlay")[0]
    .classList.add("overlay--derecha");
}

function mostrarPromo() {
  document
    .getElementsByClassName("pantalla-promo-individual")[0]
    .classList.add("pantalla-card--active");
  document.getElementsByTagName("body")[0].classList.add("quitar-overflow");
  document
    .getElementsByClassName("overlay")[0]
    .classList.add("overlay--active");
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
    // Minusculas
    categoria = categoria.toLowerCase();

    // Iteramos sobre los hijos de .platos
    var platos = document.getElementsByClassName("js-plato-menu");

    for (i = 0; i < platos.length; i++) {
      platos[i].classList.remove("plato--active");
      var categoriasPlato = platos[i].dataset.cat;
      // minusculas
      categoriasPlato = categoriasPlato.toLowerCase();
      // Quitar espacios
      categoriasPlato = categoriasPlato.replace(/\s/g, "");
      categoriasPlato = categoriasPlato.split(",");

      // Si alguna categoria del plato es igual a la categoria seleccionada
      if (categoriasPlato.includes(categoria)) {
        platos[i].classList.add("plato--active");
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
  document
    .getElementsByClassName("overlay")[0]
    .classList.remove("overlay--active");
  document
    .getElementsByClassName("overlay")[0]
    .classList.remove("overlay--derecha");

  document.getElementsByClassName("cerrar-ventana")[0].classList.remove("cerrar-ventana--animado");

  // pantallaAboutUs[0].style.display = "none";
  // pantallaPromos[0].style.display = "none";
  // pantallaPreguntasFrecuentes[0].style.display = "none";
  // pantallaPlatilloIndividual[0].style.display = "none";
  // pantallaPromoIndividual[0].style.display = "none";
  // pantalla_principal[0].style.display = "block";
}

// Al cargar la pagina
function cargarPlatos() {
  // A todos los platos con data-categoria="Recomendado" les agregamos la clase plato--active
  var platos = document.getElementsByClassName("js-plato-menu");

  for (i = 0; i < platos.length; i++) {
    var categoriasPlato = platos[i].dataset.cat;
    // minusculas
    categoriasPlato = categoriasPlato.toLowerCase();
    // Quitar espacios
    categoriasPlato = categoriasPlato.replace(/\s/g, "");
    categoriasPlato = categoriasPlato.split(",");

    if (categoriasPlato.includes("recomendado")) {
      platos[i].classList.add("plato--active");
    }
  }
}

document.addEventListener("DOMContentLoaded", function () {
  cargarPlatos();
});
