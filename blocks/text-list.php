<?php
 
use Carbon_Fields\Block;
use Carbon_Fields\Field;
 
add_action( 'after_setup_theme', 'bk-theme' );
 
function textlist() {
	Block::make( 'Lista projeto' )
		->add_fields( array(
			Field::make('rich_text', 'description', 'lista'),
		) )
		->set_render_callback( function ( $block ) {
 
			// ob_start();
			?>
			<div class="text-list">
				 <?php echo $block['description'] ?>
			</div>
			<?php
 
			// return ob_get_flush();
		} );
}
add_action( 'carbon_fields_register_fields', 'textlist' );