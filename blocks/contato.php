<?php

use Carbon_Fields\Block;
use Carbon_Fields\Field;

function contato() {
    Block::make('Contato BK')
        ->add_fields([
            Field::make('image', 'img', 'Imagem'),
            Field::make('rich_text', 'content1', 'Conteúdo 1'),
            Field::make('rich_text', 'content2', 'Conteúdo 2'),
            Field::make('rich_text', 'content3', 'Conteúdo 3'),
            Field::make('text', 'link', 'Link'),
        ])
        ->set_render_callback(function ($block) {
            ?>
            <div id="contato" class="">
                <div class="columns-container without-height">
                    <div class="column-left"></div>
                    <div class="column-right">
                        <div class="content">

                            <div class="texts">
                                <div class="text"><?php echo wp_kses_post($block['content1']); ?></div>
                                <div class="text"><?php echo wp_kses_post($block['content2']); ?></div>
                                <div class="text"><?php echo wp_kses_post($block['content3']); ?></div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="img">
                <?php if (!empty($block['img'])) :
                    $img = wp_get_attachment_image_src($block['img'], 'full'); ?>
                    <a href="<?php echo esc_url($block['link']); ?>">
                        <img src="<?php echo esc_url($img[0]); ?>" alt="Contato">
                    </a>
                <?php endif; ?>
            </div>  
            </div>
            <?php
        });
}

add_action('carbon_fields_register_fields', 'contato');
