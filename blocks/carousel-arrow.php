<?php
 
use Carbon_Fields\Block;
use Carbon_Fields\Field;
 
add_action( 'after_setup_theme', 'bk-theme' );
 
function carousel_description() {
	Block::make( 'Carrossel com setas' )
		->add_fields( array(
			Field::make('complex', 'carousel', 'Carousel')
			  ->add_fields(array(
			    Field::make('image', 'img', 'Imagem'),
			    Field::make('text', 'subtitle', 'Legenda'),
			  ))
			    ->set_layout('tabbed-vertical')
		) )
		->set_render_callback( function ( $block ) {
 
			// ob_start();
			?>
			<div id="carousel-arrow" class="carousel-main">
				<div id="carousel-arrow-item">
					<?php foreach ($block['carousel'] as $carousel) : ?>
						<div class="carousel-arrow-item__item">
						<?php if ($carousel['img']) : ?>
							<img class="carousel-arrow-item__img" data-featherlight="<?php echo wp_get_attachment_image_src($carousel['img'],'ap_image_desktop_full_no_crop')[0]; ?>" src="<?php echo wp_get_attachment_image_src($carousel['img'],'image_desktop_full_no_crop')[0]; ?>">
						<?php endif; ?>
						</div>
					<?php endforeach;  ?>					
				</div>
			</div>
			<?php
 
			// return ob_get_flush();
		} );
}
add_action( 'carbon_fields_register_fields', 'carousel_description' );