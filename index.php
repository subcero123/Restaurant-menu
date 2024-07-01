<?php

// Obtener los colores desde los campos personalizados de ACF
$color_fondo = get_field('color-fondo', 19);  // Reemplaza 'primary_color' con el nombre real del campo
$color_titulo = get_field('color-titulo', 19);  // Reemplaza 'secondary_color' con el nombre real del campo
$color_texto = get_field('color-texto', 19);  // Reemplaza 'tertiary_color' con el nombre real del campo
$color_acento = get_field('color-acento', 19);  // Reemplaza 'accent_color' con el nombre real del campo
?>

<style>
        :root {
            --color-fondo: <?php echo esc_attr($color_fondo); ?>;
            --color-titulo: <?php echo esc_attr($titulo); ?>;
			--ccolor-texto: <?php echo esc_attr($color_texto); ?>;
			--color-acento: <?php echo esc_attr($color_acento); ?>;
        }
</style>

<?php get_header() ?>


	<div class="page-wrapper">

		<div class="overlay">
			<div class="cerrar-ventana">
				<span>&times;</span>
			</div>
		</div>

		<div class="pantalla-principal">
				<div class="logo">
				<?php
					// Obtener el logo desde el campo personalizado de ACF
					$logo = get_field('logo', 'options');  // Reemplaza 'logo_field_name' con el nombre del campo ACF para el logo

					if ($logo) {
						// Obtener la URL del logo
						$logo_url = $logo['url'];  // Si el campo es de tipo "Imagen" y devuelve un array
						$logo_alt = $logo['alt'];  // Obtener el texto alternativo, si está disponible
					} else {
						// Si no se encuentra el logo, usar un logo por defecto
						$logo_url = get_stylesheet_directory_uri() . '/assets/images/logo.webp';
						$logo_alt = 'Logo por defecto';
					}
				?>
				<img src="<?php echo esc_url($logo_url); ?>" alt="<?php echo esc_attr($logo_alt); ?>">
			</div>
			<div class="contenedor-slider">
					<div class="flexslider">
						<div class="slides">
						<?php
							// Inicia el loop personalizado para el post type 'promocion'
							$args = array(
								'post_type' => 'promocion',
								'posts_per_page' => -1, // Cambiar este valor si necesitas un número específico de posts
							);

							// Inicia WP_Query
							$the_query = new WP_Query($args);

							// Comprueba si hay platillos encontrados
							if ($the_query->have_posts()) :
								// Itera sobre cada platillo encontrado
								while ($the_query->have_posts()) :
									$the_query->the_post();
									// Obtener los campos personalizados de ACF
									$imagen_promocion = get_field('imagen_promocion');
									$imagen_promocion = $imagen_promocion['url'];
									$descripcion_promocion = get_field('descripcion_promocion');
									?>
									<li class="slide promo js-abrir-promo" data-imagen="<?php echo esc_url($imagen_promocion); ?>" data-texto="<?php echo esc_attr($descripcion_promocion); ?>">
										<img src="<?php echo esc_url($imagen_promocion); ?>" alt="">
									</li>
									<?php
								endwhile;
								wp_reset_postdata();
							else :
								echo '<p>No se encontraron promociones.</p>';
							endif;
							?>

						</div>
					</div>
			</div>
			<div class="contenedor-principal">
				<div class="contenedor-carta-menu">
					<div class="titulo-menu">
						<span>Nuestro Menú</span>
					</div>
					<div class="contenedor-categoria">
						<div class="difuminado">
						</div>
						<div class="categorias">
							<div class="categoria categoria--selected" data-categoria="recomendado">
								<span>Recomendado</span>
							</div>
							<div class="categoria" data-categoria="hamburguesas">
								<span>Hamburguesas</span>
							</div>
							<div class="categoria">
								<span>Burritos</span>
							</div>
							<div class="categoria" data-categoria="pasta">
								<span>Pasta</span>
							</div>
							<div class="categoria">
								<span>Sandwich</span>
							</div>
						</div>
					</div>
					<div class="carta-menu">
					<?php
						// Define los argumentos para WP_Query
						$args = array(
							'post_type' => 'platillo',
							'posts_per_page' => -1,
						);

						// Inicia WP_Query
						$the_query = new WP_Query($args);

						// Comprueba si hay platillos encontrados
						if ($the_query->have_posts()) :
							// Itera sobre cada platillo encontrado
							while ($the_query->have_posts()) :
								$the_query->the_post();

								// Obtener el titulo del post
								$id = get_the_ID();
								$titulo = get_the_title();
								$categoria = get_the_category();
								// Concatenar las categorías en un string separado por comas
								$categoria = implode(', ', wp_list_pluck($categoria, 'name'));

								$tiempo = get_field('tiempo_preparacion');
								$calorias = get_field('numero_calorias');
								$gramos = get_field('gramos');
								$descripcion = get_field('descripcion');
								// remover los tags html
								$descripcion = strip_tags($descripcion);
								$descripcionSmall = substr($descripcion, 0, 50) . '...';
								$ingredientes = get_field('ingredientes');
								// remover los tags html
								$ingredientes = strip_tags($ingredientes);
								$imagenes = get_field('carrusel'); // Repeater de imágenes
								// El precio es un group
								$precio = get_field('precio');
								$precioUnitario = $precio['precio-unitario'];
								$precioDescuento = $precio['precio-descuento'];
								$descuento = $precio['descuento_bol'];
								$recomendado = get_field('recomendaciones');

								// Recomendado es un array de post objects necesitamos obtener los títulos, categorías y precios ...
								$recomendados = array();
								foreach ($recomendado as $recomendado) {
									// obtener los url de las imagenes
									$imagenes = get_field('carrusel', $recomendado->ID);
									$imagenes_array = array();
									foreach ($imagenes as $imagen) {
										$imagenes_array[] = $imagen['imagen']['url'];
										// Solo la primera imagen
										break;
									}
									$recomendados[] = array(
										// id del post
										'id' => $recomendado->ID,
										'titulo' => $recomendado->post_title,
										'tiempo' => get_field('tiempo_preparacion', $recomendado->ID),
										'descripcionSmall' => substr(get_field('descripcion', $recomendado->ID), 0, 50) . '...',
										'imagenes' => $imagenes_array,
										'precio' => get_field('precio', $recomendado->ID)['precio-unitario'],
										'precioDescuento' => get_field('precio', $recomendado->ID)['precio-descuento'],
										'descuento' => get_field('precio', $recomendado->ID)['descuento_bol'],		
									);
								}

								// Recomendados a string
								$recomendados = json_encode($recomendados);

								// Comprobar si hay imágenes en el repeater
								if ($imagenes) {
									
									$imagenes_array = array();
									foreach ($imagenes as $imagen) {
										// Obtener la URL de cada imagen en el repeater
										$imagenes_array[] = $imagen['imagen']['url'];
									}
									// Convertir el array de URLs en una cadena separada por comas para data-imagenes
									$data_imagenes = implode(',', $imagenes_array);
								} else {
									$data_imagenes = ''; // En caso de que no haya imágenes
								}
								
								
						?>

						<!-- Estructura HTML para cada platillo -->
						<div class="plato js-abrir-plato js-plato-menu js-pantalla-contenida"
							data-id = "<?php echo esc_attr($id); ?>"
							data-cat="<?php echo esc_attr($categoria); ?>"
							data-titulo="<?php echo esc_attr($titulo); ?>"
							data-precio="<?php echo esc_attr($precioUnitario); ?>"
							data-tiempo="<?php echo esc_attr($tiempo); ?>"
							data-calorias="<?php echo esc_attr($calorias); ?>"
							data-gramos="<?php echo esc_attr($gramos); ?>"
							data-descripcion="<?php echo esc_attr($descripcion); ?>"
							data-ingredientes="<?php echo esc_attr($ingredientes); ?>"
							data-imagenes="<?php echo esc_attr($data_imagenes); ?>"
							data-recomendado="<?php echo esc_attr($recomendados); ?>">
							<div class="imagen" style="background-image: url('<?php echo esc_url($imagenes_array[0]); ?>');"></div>
							<div class="descripcion">
								<div class="superior">
									<div class="nombre-platillo">
										<span><?php echo esc_html($titulo); ?></span>
									</div>
									<div class="precio">
										<div class="normal">
											<?php echo '$' . number_format($precioUnitario, 2); ?> <!-- Formato de precio -->
										</div>
										<?php if ($descuento) : ?>
											<div class="descuento">
												<span class="mini">ANTES</span> <span class="tachado"><?php echo '$' . number_format($precioDescuento, 2); ?></span>
											</div>
										<?php endif; ?>
									</div>
								</div>
								<div class="tiempo">
									<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/reloj.svg" alt="Descripción del SVG">
									<span><?php echo esc_html($tiempo); ?> min aprox</span>
								</div>
								<div class="resumen">
									<span><?php echo esc_html($descripcionSmall); ?></span>
								</div>
							</div>
						</div>

						<?php
							endwhile;
							wp_reset_postdata(); // Restablece los datos del post
						else :
							// No se encontraron platillos
							echo 'No hay platillos disponibles.';
						endif;
					?>
					</div>
				</div>
			</div>


		</div>

		<div class="pantalla-about-us pantalla-card">
			<div class="flexslider-top--about">
					<div class="slides">
						<?php
							// Obtener el grupo de campos 'imagenes_carousel' de la página con el ID 15
							$imagenes_carousel = get_field('imagenes-carousel', 'option');
							// Si 'imagenes_carousel' no está vacío, imprimir los valores
							if ($imagenes_carousel) {
								foreach ($imagenes_carousel as $imagen) {
									if ($imagen['url'] != '') {
										echo '<li class="slide"><img src="' . esc_url($imagen['url']) . '" alt="' . esc_attr($imagen['alt']) . '"></li>';
									}
								}
							} else {
								echo 'No hay imágenes disponibles.';
							}
						?>
					</div>
			</div>
		    	<div class="redes-sociales">
					<?php while( have_rows('redes-sociales', 'option') ): the_row(); 
						$red = get_sub_field('red');
						$link = get_sub_field('link');
						?>
						<div class="icono">
							<?php if( $red == 0 ): // Facebook ?>
								<a href="<?php echo esc_url($link); ?>" target="_blank" rel="noopener noreferrer">
									<svg fill="#797979" height="200px" width="200px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="-143 145 512 512" xml:space="preserve" stroke="#797979"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M113,145c-141.4,0-256,114.6-256,256s114.6,256,256,256s256-114.6,256-256S254.4,145,113,145z M169.5,357.6l-2.9,38.3h-39.3 v133H77.7v-133H51.2v-38.3h26.5v-25.7c0-11.3,0.3-28.8,8.5-39.7c8.7-11.5,20.6-19.3,41.1-19.3c33.4,0,47.4,4.8,47.4,4.8l-6.6,39.2 c0,0-11-3.2-21.3-3.2c-10.3,0-19.5,3.7-19.5,14v29.9H169.5z"></path> </g></svg>
								</a>
							<?php elseif( $red == 1 ): // LinkedIn ?>
								<a href="<?php echo esc_url($link); ?>" target="_blank" rel="noopener noreferrer">
									<svg class="small" viewBox="0 0 24.00 24.00" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#797979" stroke-width="0.00024000000000000003"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M18.72 3.99997H5.37C5.19793 3.99191 5.02595 4.01786 4.86392 4.07635C4.70189 4.13484 4.55299 4.22471 4.42573 4.34081C4.29848 4.45692 4.19537 4.59699 4.12232 4.75299C4.04927 4.909 4.0077 5.07788 4 5.24997V18.63C4.01008 18.9901 4.15766 19.3328 4.41243 19.5875C4.6672 19.8423 5.00984 19.9899 5.37 20H18.72C19.0701 19.9844 19.4002 19.8322 19.6395 19.5761C19.8788 19.32 20.0082 18.9804 20 18.63V5.24997C20.0029 5.08247 19.9715 4.91616 19.9078 4.76122C19.8441 4.60629 19.7494 4.466 19.6295 4.34895C19.5097 4.23191 19.3672 4.14059 19.2108 4.08058C19.0544 4.02057 18.8874 3.99314 18.72 3.99997ZM9 17.34H6.67V10.21H9V17.34ZM7.89 9.12997C7.72741 9.13564 7.5654 9.10762 7.41416 9.04768C7.26291 8.98774 7.12569 8.89717 7.01113 8.78166C6.89656 8.66615 6.80711 8.5282 6.74841 8.37647C6.6897 8.22474 6.66301 8.06251 6.67 7.89997C6.66281 7.73567 6.69004 7.57169 6.74995 7.41854C6.80986 7.26538 6.90112 7.12644 7.01787 7.01063C7.13463 6.89481 7.2743 6.80468 7.42793 6.74602C7.58157 6.68735 7.74577 6.66145 7.91 6.66997C8.07259 6.66431 8.2346 6.69232 8.38584 6.75226C8.53709 6.8122 8.67431 6.90277 8.78887 7.01828C8.90344 7.13379 8.99289 7.27174 9.05159 7.42347C9.1103 7.5752 9.13699 7.73743 9.13 7.89997C9.13719 8.06427 9.10996 8.22825 9.05005 8.3814C8.99014 8.53456 8.89888 8.6735 8.78213 8.78931C8.66537 8.90513 8.5257 8.99526 8.37207 9.05392C8.21843 9.11259 8.05423 9.13849 7.89 9.12997ZM17.34 17.34H15V13.44C15 12.51 14.67 11.87 13.84 11.87C13.5822 11.8722 13.3313 11.9541 13.1219 12.1045C12.9124 12.2549 12.7546 12.4664 12.67 12.71C12.605 12.8926 12.5778 13.0865 12.59 13.28V17.34H10.29V10.21H12.59V11.21C12.7945 10.8343 13.0988 10.5225 13.4694 10.3089C13.84 10.0954 14.2624 9.98848 14.69 9.99997C16.2 9.99997 17.34 11 17.34 13.13V17.34Z" fill="#797979"></path> </g></svg>
								</a>
							<?php elseif( $red == 2 ): // YouTube ?>
								<a href="<?php echo esc_url($link); ?>" target="_blank" rel="noopener noreferrer">
									<svg class="small" fill="#797979" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg" stroke="#797979"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path d="M24.325 8.309s-2.655-.334-8.357-.334c-5.517 0-8.294.334-8.294.334A2.675 2.675 0 0 0 5 10.984v10.034a2.675 2.675 0 0 0 2.674 2.676s2.582.332 8.294.332c5.709 0 8.357-.332 8.357-.332A2.673 2.673 0 0 0 27 21.018V10.982a2.673 2.673 0 0 0-2.675-2.673zM13.061 19.975V12.03L20.195 16l-7.134 3.975z"></path></g></svg>
								</a>
							<?php elseif( $red == 3 ): // Instagram ?>
								<a href="<?php echo esc_url($link); ?>" target="_blank" rel="noopener noreferrer">
									<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M12 18C15.3137 18 18 15.3137 18 12C18 8.68629 15.3137 6 12 6C8.68629 6 6 8.68629 6 12C6 15.3137 8.68629 18 12 18ZM12 16C14.2091 16 16 14.2091 16 12C16 9.79086 14.2091 8 12 8C9.79086 8 8 9.79086 8 12C8 14.2091 9.79086 16 12 16Z" fill="#797979"></path> <path d="M18 5C17.4477 5 17 5.44772 17 6C17 6.55228 17.4477 7 18 7C18.5523 7 19 6.55228 19 6C19 5.44772 18.5523 5 18 5Z" fill="#797979"></path> <path fill-rule="evenodd" clip-rule="evenodd" d="M1.65396 4.27606C1 5.55953 1 7.23969 1 10.6V13.4C1 16.7603 1 18.4405 1.65396 19.7239C2.2292 20.8529 3.14708 21.7708 4.27606 22.346C5.55953 23 7.23969 23 10.6 23H13.4C16.7603 23 18.4405 23 19.7239 22.346C20.8529 21.7708 21.7708 20.8529 22.346 19.7239C23 18.4405 23 16.7603 23 13.4V10.6C23 7.23969 23 5.55953 22.346 4.27606C21.7708 3.14708 20.8529 2.2292 19.7239 1.65396C18.4405 1 16.7603 1 13.4 1H10.6C7.23969 1 5.55953 1 4.27606 1.65396C3.14708 2.2292 2.2292 3.14708 1.65396 4.27606ZM13.4 3H10.6C8.88684 3 7.72225 3.00156 6.82208 3.0751C5.94524 3.14674 5.49684 3.27659 5.18404 3.43597C4.43139 3.81947 3.81947 4.43139 3.43597 5.18404C3.27659 5.49684 3.14674 5.94524 3.0751 6.82208C3.00156 7.72225 3 8.88684 3 10.6V13.4C3 15.1132 3.00156 16.2777 3.0751 17.1779C3.14674 18.0548 3.27659 18.5032 3.43597 18.816C3.81947 19.5686 4.43139 20.1805 5.18404 20.564C5.49684 20.7234 5.94524 20.8533 6.82208 20.9249C7.72225 20.9984 8.88684 21 10.6 21H13.4C15.1132 21 16.2777 20.9984 17.1779 20.9249C18.0548 20.8533 18.5032 20.7234 18.816 20.564C19.5686 20.1805 20.1805 19.5686 20.564 18.816C20.7234 18.5032 20.8533 18.0548 20.9249 17.1779C20.9984 16.2777 21 15.1132 21 13.4V10.6C21 8.88684 20.9984 7.72225 20.9249 6.82208C20.8533 5.94524 20.7234 5.49684 20.564 5.18404C20.1805 4.43139 19.5686 3.81947 18.816 3.43597C18.5032 3.27659 18.0548 3.14674 17.1779 3.0751C16.2777 3.00156 15.1132 3 13.4 3Z" fill="#797979"></path> </g></svg>
								</a>
							<?php endif; ?>
						</div>
					<?php endwhile; ?>
				</div>
			<div class="contenedor-about">
				<div class="titulo">
					<b>Acerca de nosotros</b>
				</div>
				<div class="parrafo">
					<?php
						$texto_about = get_field('descripcion', 'option');
					?>
					<p><?php echo wp_kses_post($texto_about); ?></p>
				</div>
			</div>

			<div class="contenedor-regresar">
				<span><span class="back"><</span> Regresar al menú</span>		
			</div>


		</div>

		<div class="pantalla-promos pantalla-card">

			<div class="contenedor-pantalla">
				<div class="titulo">
					<span>Promos y descuentos</span>
				</div>

				<div class="contenedor-promos">
				<?php
							// Inicia el loop personalizado para el post type 'promocion'
							$args = array(
								'post_type' => 'promocion',
								'posts_per_page' => -1, // Cambiar este valor si necesitas un número específico de posts
							);

							// Inicia WP_Query
							$the_query = new WP_Query($args);

							// Comprueba si hay platillos encontrados
							if ($the_query->have_posts()) :
								// Itera sobre cada platillo encontrado
								while ($the_query->have_posts()) :
									$the_query->the_post();
									// Obtener los campos personalizados de ACF
									$imagen_promocion = get_field('imagen_promocion');
									$imagen_promocion = $imagen_promocion['url'];
									$descripcion_promocion = get_field('descripcion_promocion');
									?>
									<div class="promo js-abrir-promo js-pantalla-contenida" data-imagen="<?php echo esc_url($imagen_promocion); ?>" data-texto="<?php echo esc_attr($descripcion_promocion); ?>">
										<img src="<?php echo esc_url($imagen_promocion); ?>" alt="">
									</div>
									<?php
								endwhile;
								wp_reset_postdata();
							else :
								echo '<p>No se encontraron promociones.</p>';
							endif;
							?>
				</div>

				<div class="carta-menu">
				<?php
						// Define los argumentos para WP_Query
						$args = array(
							'post_type' => 'platillo',
							'posts_per_page' => -1,
							'tax_query' => array(
								array(
									'taxonomy' => 'category', // Asegúrate de que la taxonomía sea correcta, en este caso es 'category'
									'field' => 'slug',
									'terms' => 'Promocion', // El slug de la categoría que deseas filtrar
								),
							),
						);

						// Inicia WP_Query
						$the_query = new WP_Query($args);

						// Comprueba si hay platillos encontrados
						if ($the_query->have_posts()) :
							// Itera sobre cada platillo encontrado
							while ($the_query->have_posts()) :
								$the_query->the_post();

								// Obtener el titulo del post
								$titulo = get_the_title();
								$categoria = get_the_category();
								// Concatenar las categorías en un string separado por comas
								$categoria = implode(',', wp_list_pluck($categoria, 'name'));
								$tiempo = get_field('tiempo_preparacion');
								$calorias = get_field('numero_calorias');
								$gramos = get_field('gramos');
								$descripcion = get_field('descripcion');
								// remover los tags html
								$descripcion = strip_tags($descripcion);
								$descripcionSmall = substr($descripcion, 0, 50) . '...';
								$ingredientes = get_field('ingredientes');
								// remover los tags html
								$ingredientes = strip_tags($ingredientes);
								$imagenes = get_field('carrusel'); // Repeater de imágenes
								// El precio es un group
								$precio = get_field('precio');
								$precioUnitario = $precio['precio-unitario'];
								$precioDescuento = $precio['precio-descuento'];
								$descuento = $precio['descuento_bol'];
								$recomendado = get_field('recomendaciones');
								$recomendados = array();
								// Comprobar si hay imágenes en el repeater
								if ($imagenes) {
									
									$imagenes_array = array();
									foreach ($imagenes as $imagen) {
										// Obtener la URL de cada imagen en el repeater
										$imagenes_array[] = $imagen['imagen']['url'];
									}
									// Convertir el array de URLs en una cadena separada por comas para data-imagenes
									$data_imagenes = implode(',', $imagenes_array);
								} else {
									$data_imagenes = ''; // En caso de que no haya imágenes
								}
								
								
						?>

						<!-- Estructura HTML para cada platillo -->
						<div class="plato js-abrir-plato js-pantalla-contenida"
							data-cat="<?php echo esc_attr($categoria); ?>"
							data-titulo="<?php echo esc_attr($titulo); ?>"
							data-precio="<?php echo esc_attr($precioUnitario); ?>"
							data-tiempo="<?php echo esc_attr($tiempo); ?>"
							data-calorias="<?php echo esc_attr($calorias); ?>"
							data-gramos="<?php echo esc_attr($gramos); ?>"
							data-descripcion="<?php echo esc_attr($descripcion); ?>"
							data-ingredientes="<?php echo esc_attr($ingredientes); ?>"
							data-imagenes="<?php echo esc_attr($data_imagenes); ?>"
							data-recomendado="<?php echo esc_attr($recomendados); ?>">
							<div class="imagen" style="background-image: url('<?php echo esc_url($imagenes_array[0]); ?>');"></div>
							<div class="descripcion">
								<div class="superior">
									<div class="nombre-platillo">
										<span><?php echo esc_html($titulo); ?></span>
									</div>
									<div class="precio">
										<div class="normal">
											<?php echo '$' . number_format($precioUnitario, 2); ?> <!-- Formato de precio -->
										</div>
										<?php if ($descuento) : ?>
											<div class="descuento">
												<span class="mini">ANTES</span> <span class="tachado"><?php echo '$' . number_format($precioDescuento, 2); ?></span>
											</div>
										<?php endif; ?>
									</div>
								</div>
								<div class="tiempo">
									<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/reloj.svg" alt="Descripción del SVG">
									<span><?php echo esc_html($tiempo); ?> min aprox</span>
								</div>
								<div class="resumen">
									<span><?php echo esc_html($descripcionSmall); ?></span>
								</div>
							</div>
						</div>

						<?php
							endwhile;
							wp_reset_postdata(); // Restablece los datos del post
						else :
							// No se encontraron platillos
							echo 'No hay platillos disponibles.';
						endif;
					?>
				</div>

				<div class="contenedor-regresar">
					<span><span class="back"><</span> Regresar al menú</span>					
				</div>
			</div>
		</div>

		<div class="pantalla-promo-individual pantalla-card">
				<div class="contenedor-imagen-top" id="imagen-promo-individual">
					<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/promo1.webp" alt="">
				</div>

				<div class="contenedor-terminos">
					<div class="parrafo" id="texto-promo-individual">
						<p>Aprovecha esta promocion y disfruta de un 10% de descuento en tu cuenta total al presentar este cupón</p>
					</div>
				</div>

				<div class="contenedor-regresar">
					<span><span class="back"><</span> Regresar al menú</span>
					
				</div>
		</div>

		<div class="pantalla-platillo-individual pantalla-card pantalla-card--derecha">
				<div class="flexslider-top">
					<ul class="slides" id="imagenes-platillo-individual">
						<li class="slide">
							<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/platillo1.webp" alt="">
						</li>
						<li class="slide">
							<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/platillo1.webp" alt="">
						</li>
						<li class="slide">
							<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/platillo1.webp" alt="">
						</li>
					</ulv>
				</div>

				<div class="descripcion-breve-platillo">
					<div class="superior">
						<div class="titulo" id="titulo-platillo-individual">
							<span>Corte New York</span>
						</div>
						<div class="precio" id="precio-platillo-individual">
							<span>$250</span>
						</div>
					</div>
					<div class="datos">
						<div class="tiempo" id="tiempo-platillo-individual">
							<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/reloj.svg" alt="Descripción del SVG">
							<span>25 min aprox</span>
						</div>
						<div class="calorias" id="calorias-platillo-individual">
							<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/llama.svg" alt="Descripción del SVG">
							<span>450 cal</span>
						</div>
						<div class="gramos" id="gramos-platillo-individual">
							<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/peso.svg" alt="Descripción del SVG">
							<span>300 gr</span>
						</div>
					</div>
				</div>

				<div class="descripcion-platillo">
					<div class="titulo">
						<span>Descripción</span>
					</div>
					<div class="parrafo" id="descripcion-platillo-individual">
						<p>Corte de lomo angosto americano al grill de carbón, acompañado de papas a la francesa y ensalada de la casa</p>
					</div>
				</div>

				<div class="descripcion-platillo">
					<div class="titulo">
						<span>Ingredientes</span>
					</div>
					<div class="parrafo" id="ingredientes-platillo-individual">
						<p>Carne de res, papas, lechuga, jitomate, cebolla, aderezo de la casa</p>
					</div>
				</div>

				<div class="sugerencias">
					<div class="encabezado">
						<div class="circulo"></div>
						<span>Te recomendamos acompañar este platillo con</span>
					</div>
					<div class="carta-menu" id="carta-sugerencias">
					<div class="plato js-abrir-plato plato--active js-pantalla-contenida" data-cat="recomendado" data-titulo="Corte New York" data-precio="250" data-tiempo="25" data-calorias="450" data-gramos="300" data-descripcion="Corte de lomo angosto americano al grill de carbón, acompañado de papas a la francesa y ensalada de la casa" data-ingredientes="Carne de res, papas, lechuga, jitomate, cebolla, aderezo de la casa" data-imagenes="platillo1.webp,platillo2.webp">
						<div class="imagen">
							<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/platillo1.webp" alt="">
						</div>
						<div class="descripcion">
							<div class="superior">
								<div class="nombre-platillo">
									<span>Corte New York</span>
								</div>
								<div class="precio">
									$250
								</div>
							</div>
							<div class="tiempo">
															<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/reloj.svg" alt="Descripción del SVG">
								<span>25 min aprox</span>
							</div>
							<div class="resumen">
								<span>Corte de lomo angosto americano al grill de ...</span>
							</div>
						</div>
					</div>
					<div class="plato js-abrir-plato plato--active js-pantalla-contenida" data-cat="recomendado" data-titulo="Fajitas & Chilly" data-precio="299" data-tiempo="30" data-calorias="350" data-gramos="350" data-descripcion="Tiras de carne asada con pimientos y cebollas, servidas con tortillas." data-ingredientes="Fajitas cocinadas al gusto, acompañado de Chilly" data-imagenes="platillo2.webp,platillo1.webp">
						<div class="imagen">
							<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/platillo2.webp" alt="">
						</div>
						<div class="descripcion">
							<div class="superior">
								<div class="nombre-platillo">
									<span>Fajitas & Chilly</span>
								</div>
								<div class="precio">
									<div class="normal">
										$299
									</div>
									<div class="descuento">
										<span class="mini">ANTES</span> <span class="tachado">$350</span>
									</div>
								</div>
							</div>
							<div class="tiempo">
														<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/reloj.svg" alt="Descripción del SVG">
								<span>30 min aprox</span>
							</div>
							<div class="resumen">
								<span>Deliciosas fajitas de Pollo o Res aderezadas...</span>
							</div>
						</div>
					</div>
					</div>
				</div>
				<div class="contenedor-regresar">
					<span><span class="back"><</span> Regresar al menú</span>					
				</div>
		</div>

		<div class="pantalla-preguntas-frecuentes pantalla-card">
			<div class="titulo-principal">
				<span>Preguntas Frecuentes</span>
			</div>

			<?php
				// Suponiendo que tienes acceso a los datos de ACF y estás en un entorno donde puedes utilizar PHP

				// Obtener el campo de preguntas frecuentes (FAQ) de la página con ID 19
				$faq = get_field('faq', 'options');

				// Verificar si hay preguntas frecuentes disponibles
				if ($faq) {
					// Iterar sobre cada elemento FAQ
					foreach ($faq as $item) {
						// Extraer los valores del ACF
						$pregunta = $item['faq-item']['pregunta'];
						$respuesta = $item['faq-item']['respuesta'];

						// Imprimir la estructura HTML de la tarjeta
						// Imprimir la estructura HTML de la tarjeta
						echo '<div class="card">';
						echo '<div class="titulo">';
						echo '<span>' . esc_html($pregunta) . '</span>';
						echo '</div>';
						echo '<div class="respuesta">';
						echo '<p>' . esc_html($respuesta) . '</p>';
						echo '</div>';
						echo '</div>';
					}
				}
			?>
			<div class="contenedor-regresar">
					<span><span class="back"><</span> Regresar al menú</span>
					
			</div>

		</div>

		<div class="contenedor-footer">
					<div class="footer">
						<div id="about-us" class="seccion">
						<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <circle cx="12" cy="12" r="10" stroke="#ffffff" stroke-width="1.5"></circle> <path d="M12 17V11" stroke="#ffffff" stroke-width="1.5" stroke-linecap="round"></path> <circle cx="1" cy="1" r="1" transform="matrix(1 0 0 -1 11 9)" fill="#ffffff"></circle> </g></svg>
							<span>Nosotros</span>
						</div>
						<div id="promos" class="seccion">
						<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M9 9H9.01M15 15H15.01M16 8L8 16M9.5 9C9.5 9.27614 9.27614 9.5 9 9.5C8.72386 9.5 8.5 9.27614 8.5 9C8.5 8.72386 8.72386 8.5 9 8.5C9.27614 8.5 9.5 8.72386 9.5 9ZM15.5 15C15.5 15.2761 15.2761 15.5 15 15.5C14.7239 15.5 14.5 15.2761 14.5 15C14.5 14.7239 14.7239 14.5 15 14.5C15.2761 14.5 15.5 14.7239 15.5 15ZM22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12Z" stroke="#db0000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
							<span>Promos y Desc.</span>
						</div>
						<div id="faq" class="seccion">
							<svg fill="#ffffff" viewBox="0 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg" stroke="#ffffff"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M16 0c-8.836 0-16 7.163-16 16s7.163 16 16 16c8.837 0 16.001-7.163 16.001-16s-7.163-16-16.001-16zM16 30.032c-7.72 0-14-6.312-14-14.032s6.28-14 14-14 14.001 6.28 14.001 14-6.281 14.032-14.001 14.032zM14.53 25.015h2.516v-2.539h-2.516zM15.97 6.985c-1.465 0-2.672 0.395-3.62 1.184s-1.409 2.37-1.386 3.68l0.037 0.073h2.295c0-0.781 0.261-1.904 0.781-2.308s1.152-0.604 1.893-0.604c0.854 0 1.511 0.232 1.971 0.696s0.689 1.127 0.689 1.989c0 0.725-0.17 1.343-0.512 1.855-0.343 0.512-0.916 1.245-1.721 2.198-0.831 0.749-1.344 1.351-1.538 1.806s-0.297 1.274-0.305 2.454h2.405c0-0.74 0.047-1.285 0.14-1.636s0.36-0.744 0.799-1.184c0.945-0.911 1.703-1.802 2.277-2.674 0.573-0.87 0.86-1.831 0.86-2.881 0-1.465-0.443-2.607-1.331-3.424s-2.134-1.226-3.736-1.226z"></path> </g></svg>
							<span>FAQ's</span>
						</div>
					</div>
			</div>


	</div>


<?php get_footer() ?>