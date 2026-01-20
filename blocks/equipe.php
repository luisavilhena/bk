<?php
 
use Carbon_Fields\Block;
use Carbon_Fields\Field;
 
add_action( 'after_setup_theme', 'bk' );
 
function equipe_block() {
	Block::make( 'Equipe' )
		->add_fields( array(
			Field::make( 'text', 'title', 'Título' ),
			Field::make( 'checkbox', 'columns', '2 Colunas' )
			  ->set_option_value( 'yes' ),
			Field::make('complex', 'equipe', 'Equipe')
			  ->add_fields(array(
			    Field::make('image', 'img', 'Image'),
			    Field::make('text', 'title', 'Título'),
			    Field::make('rich_text', 'text', 'Texto'),
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
 			<div class="structure-block">
 				<div id="equipe" class="structure-block__center structure-container__side">
 					<h2><?php echo $block['title'] ?></h2>
 					<div class="equipe-items">
 						<?php foreach ($block['equipe'] as $equipe) : ?>
 						<div class="equipe-item <?php if($block['columns']){echo('equipe-item__2columns');}?>">
 							<img class="equipe-item__img" src="<?php echo wp_get_attachment_image_src($equipe['img'],'quarter')[0]; ?>">
 							<div>
 								<h4>
 									<?php echo $equipe['title'] ?>
 								</h4>
 								<div><?php echo $equipe['text'] ?></div>
 							</div>
 						</div>
 						<?php endforeach;  ?>
 					</div>					
 				</div>
 			</div>

			<?php
 
			// return ob_get_flush();
		} );
}
add_action( 'carbon_fields_register_fields', 'equipe_block' );