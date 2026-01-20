<?php
 
use Carbon_Fields\Block;
use Carbon_Fields\Field;
 
add_action( 'after_setup_theme', 'bk' );
 
function carousel_team() {
	Block::make( 'Carrossel de equipe' )
		->add_fields( array(
			Field::make('text', 'text', 'Texto 1'),
			Field::make('text', 'text_2', 'Texto 2'),
			Field::make('complex', 'carousel', 'Carousel')
			  ->add_fields(array(
			    Field::make('image', 'img', 'Image'),
			    Field::make('text', 'title', 'Title'),
			    Field::make('rich_text', 'description', 'Description'),
			  ))
			  ->set_layout('tabbed-vertical')
			  ->set_header_template( '
			  <% if (title) { %>
				  <%- title %>
			  <% } %>
		  ' )
		) )
		->set_render_callback( function ( $block ) {
 
			// ob_start();
			?>
 
			<div class="carousel">
				<div class="carousel__imgs">
					<?php foreach ($block['carousel'] as $carousel) : ?>
						<?php if ($carousel['img']) : ?>
							<div class="carousel__imgs__item">
								<img alt="<?php echo $carousel['title'] ?>" src="<?php echo wp_get_attachment_image_src($carousel['img'],'vertical')[0]; ?>">
								<div class="menu__item__title">
									<h2><?php echo $carousel['title'] ?></h2>
								</div>
							</div>
						<?php endif; ?>
					<?php endforeach;  ?>
				</div>
				<div class="description">
					<p><?php echo $block['text'] ?></p>					
					<p><?php echo $block['text_2'] ?></p>					
				</div>
			</div>
			<?php
 
			// return ob_get_flush();
		} );
}
add_action( 'carbon_fields_register_fields', 'carousel_team' );