<?php
use Carbon_Fields\Block;
use Carbon_Fields\Field;

add_action('carbon_fields_register_fields', 'bk_ex_bks_block');

function bk_ex_bks_block() {

    Block::make('Ex BKs')
        ->add_fields([

            // COLUNA ESQUERDA
            Field::make('text', 'title', 'Título da coluna esquerda')
                ->set_default_value('EX BK’s'),

            // COLUNA DIREITA (4 colunas)
            Field::make('textarea', 'col_1', 'Coluna 1'),
            Field::make('textarea', 'col_2', 'Coluna 2'),
            Field::make('textarea', 'col_3', 'Coluna 3'),
            Field::make('textarea', 'col_4', 'Coluna 4'),

        ])
        ->set_render_callback(function ($block) {
            ?>
            <section class="ex-bks">

                <!-- COLUNA ESQUERDA -->
                <div class="col-left">
                    <h3><?php echo esc_html($block['title']); ?></h3>
                </div>

                <!-- COLUNA DIREITA -->
                <div class="col-right">
                    <div class="columns">

                        <div class="column">
                            <?php echo nl2br(esc_html($block['col_1'])); ?>
                        </div>

                        <div class="column">
                            <?php echo nl2br(esc_html($block['col_2'])); ?>
                        </div>

                        <div class="column">
                            <?php echo nl2br(esc_html($block['col_3'])); ?>
                        </div>

                        <div class="column">
                            <?php echo nl2br(esc_html($block['col_4'])); ?>
                        </div>

                    </div>
                </div>
                <div class="col-mobile">
                    <div class="columns">

                        <div class="column">
                            <p><?php echo nl2br($block['col_1']); ?></p>
                            <p><?php echo nl2br($block['col_2']); ?></p>
                        </div>

                        <div class="column">
                            <p><?php echo nl2br($block['col_3']); ?></p>
                            <p><?php echo nl2br($block['col_4']); ?></p>
                        </div>
                    </div>
                </div>

            </section>
            <?php
        });
}
