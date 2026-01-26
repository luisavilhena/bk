<?php
 
use Carbon_Fields\Block;
use Carbon_Fields\Field;
 
add_action( 'after_setup_theme', 'bk_theme' );
 
function midia() {
	Block::make( 'Mídia' )
		->add_fields( array(
            Field::make('complex', 'topics', 'Tópicos')
            ->add_fields(array(
                Field::make('text', 'title', 'Título'),
                Field::make('rich_text', 'description', 'Description'),
                Field::make('image', 'img', 'Imagem'),
        
                // 🔗 Link externo
                Field::make('text', 'link_url', 'Link externo')
                    ->set_help_text('Ex: https://exemplo.com'),
        
                // 🔗 Link interno (página/post do site)
                Field::make('text', 'btn', 'Texto do botão'),
                Field::make('association', 'link_internal', 'Link interno')
                    ->set_types(array(
                        array(
                            'type' => 'post',
                            'post_type' => 'page',
                        ),
                        array(
                            'type' => 'post',
                            'post_type' => 'post',
                        ),
                    ))
                    ->set_max(1)
            ))
            ->set_layout('tabbed-vertical')
            ->set_header_template('
                <% if (title) { %>
                    <%- title %>
                <% } else { %>
                    Novo tópico
                <% } %>
            ')
        
		) )
		->set_render_callback( function ( $block ) {
 
			// ob_start();
			?>
            <div class="midia-container">
               <?php foreach ($block['topics'] as $topics) : 
                $img_url = '';
                if (!empty($topics['img'])) {
                    $img_url = wp_get_attachment_image_url($topics['img'], 'large');
                }
                ?>
                <div class="midia-bloco">

                    <div class="topic-content">
                        <div>
                            <h2><?php echo esc_html($topics['title']); ?></h2>

                            <?php echo $topics['description']; ?>
                        </div>
                        <div class="btn-container">
                            <a href="<?php echo esc_url(get_permalink($topics['link_internal'][0]['id'])); ?>" class="btn">
                                <?php echo esc_html($topics['btn']); ?>
                            </a>
                        </div>  
                    </div>

                    <div class="topic-image">
                        <a href="<?php echo esc_url($topics['link_url']); ?>" target="_blank" rel="noopener noreferrer">
                            <img src="<?php echo esc_url($img_url); ?>" alt="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="51" height="65" viewBox="0 0 51 65" fill="none">
                                <path d="M0 64.1667V0L50.4167 32.0833L0 64.1667Z" fill="white"/>
                            </svg>
                        </a>
                    </div>

                </div>
                <?php endforeach; ?>
            </div>

			<?php
 
			// return ob_get_flush();
		} );
}
add_action( 'carbon_fields_register_fields', 'midia' );