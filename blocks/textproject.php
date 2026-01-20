<?php
 
use Carbon_Fields\Block;
use Carbon_Fields\Field;
 
add_action( 'after_setup_theme', 'bk-theme' );
 
function textproject() {
	Block::make( 'Texto projeto' )
		->add_fields( array(
			Field::make('rich_text', 'description', 'Descrição'),
		) )
		->set_render_callback( function ( $block ) {
 
			// ob_start();
			?>
			<div class="text-project">
				 <?php echo $block['description'] ?>
			</div>
			<?php
 
			// return ob_get_flush();
		} );
}
add_action( 'carbon_fields_register_fields', 'textproject' );