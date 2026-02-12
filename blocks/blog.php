<?php
use Carbon_Fields\Container;
use Carbon_Fields\Field;

add_action('carbon_fields_register_fields', 'studio_column_right_fields');

function studio_column_right_fields() {

    Container::make('post_meta', 'Conteúdo Lateral')
        ->where('post_template', '=', 'page-noticias.php') // opcional
        ->add_fields([

            Field::make('complex', 'right_blocks', 'Blocos')
                ->add_fields([

                    Field::make('text', 'title', 'Título'),
                    Field::make('text', 'subtitle', 'Subtítulo'),
                    Field::make('image', 'image', 'Imagem'),
                    Field::make('text', 'link', 'Link'),
                    Field::make('rich_text', 'description', 'Descrição'),

                ])
                ->set_layout('tabbed-vertical')
                ->set_header_template('
                    <% if (title) { %>
                        <%- title %>
                    <% } else { %>
                        Novo Bloco
                    <% } %>
                ')

        ]);
}
