<?php
use Carbon_Fields\Container;
use Carbon_Fields\Field;

add_action('carbon_fields_register_fields', 'studio_events_meta');

function studio_events_meta() {

    Container::make('post_meta', 'Eventos')
        ->where('post_template', '=', 'page-noticias.php') // opcional
        ->add_fields([

            Field::make('complex', 'event_dates', 'Eventos')
                ->add_fields([
                    Field::make('date', 'date', 'Data'),
                    Field::make('text', 'text', 'Texto'),
                    Field::make('text', 'link', 'Link (opcional)')
                ])
                ->set_layout('tabbed-vertical')
                ->set_header_template( '
                  <% if (text) { %>
                      <%- text %>
                  <% } %>
              ' ),

        ]);
}
