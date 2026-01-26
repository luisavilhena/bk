<?php

use Carbon_Fields\Block;
use Carbon_Fields\Field;

add_action('carbon_fields_register_fields', 'bk_premios_block');

function bk_premios_block() {

    Block::make('Tabela de Prêmios')
        ->add_fields([

            Field::make('complex', 'anos', 'Anos')
                ->set_layout('tabbed-vertical')
                ->set_header_template('
                    <% if (ano) { %>
                        <strong><%= ano %></strong>
                    <% } else { %>
                        Novo ano
                    <% } %>
                ')
                ->add_fields([

                    Field::make('text', 'ano', 'Ano')
                        ->set_required(true),

                    Field::make('complex', 'itens', 'Prêmios / Projetos')
                        ->set_layout('tabbed-horizontal')
                        ->set_header_template('
                            <% if (projeto) { %>
                                <%= projeto %>
                            <% } else { %>
                                Novo item
                            <% } %>
                        ')
                        ->add_fields([

                            Field::make('textarea', 'premio', 'Prêmio / Concurso'),
                            Field::make('textarea', 'projeto', 'Projeto'),
                            Field::make('textarea', 'categoria', 'Categoria'),
                            Field::make('textarea', 'honraria', 'Honraria'),

                            Field::make('image', 'imagem', 'Imagem'),

                            Field::make('association', 'link_post', 'Link para projeto')
                                ->set_types([
                                    [
                                        'type'      => 'post',
                                        'post_type' => 'post',
                                    ],
                                ])
                                ->set_max(1),
                        ]),
                ]),
        ])
        ->set_render_callback(function ($block) {
            ?>
            <div class="premio-desktop">
                <div class="premios-grid">

                    <!-- IMAGEM FLUTUANTE -->
                    <div class="premio-hover-image">
                        <img src="" alt="">
                    </div>

                    <!-- HEADER -->
                    <div class="premios-header">
                        <div class="col-ano"></div>

                        <div class="col-right">
                            <div>Prêmio / Concurso</div>
                            <div>Projeto</div>
                            <div>Categoria</div>
                            <div>Honraria</div>
                        </div>
                    </div>


                    <?php foreach ($block['anos'] as $ano_group) : ?>
                        <?php
                            $ano   = $ano_group['ano'];
                            $items = $ano_group['itens'] ?? [];
                            $total = count($items);
                        ?>

                        <?php foreach ($items as $index => $item) : ?>
                            <?php
                                $is_last = ($index === $total - 1);

                                $link = '';
                                if (!empty($item['link_post'])) {
                                    $link = get_permalink($item['link_post'][0]['id']);
                                }

                                $img = '';
                                if (!empty($item['imagem'])) {
                                    $img = wp_get_attachment_image_url($item['imagem'], 'large');
                                }
                            ?>

                            <div
                                class="premios-row <?php echo $index === 0 ? 'year-start' : ''; ?> <?php echo $is_last ? 'year-end' : ''; ?>"
                                <?php if ($link) : ?> data-link="<?php echo esc_url($link); ?>" <?php endif; ?>
                                <?php if ($img)  : ?> data-image="<?php echo esc_url($img); ?>" <?php endif; ?>
                            >

                                <div class="col-ano">
                                    <?php if ($index === 0) echo nl2br(esc_html($ano)); ?>
                                </div>

                                <div class="col-right">
                                    <div class="col-premio"><?php echo nl2br(esc_html($item['premio'])); ?></div>

                                    <div class="col-projeto">
                                        <?php if ($link) : ?>
                                            <a href="<?php echo esc_url($link); ?>">
                                                <?php echo nl2br(esc_html($item['projeto'])); ?>
                                            </a>
                                        <?php else : ?>
                                            <?php echo nl2br(esc_html($item['projeto'])); ?>
                                        <?php endif; ?>
                                    </div>

                                    <div class="col-categoria"><?php echo nl2br(esc_html($item['categoria'])); ?></div>
                                    <div class="col-honraria"><?php echo nl2br(esc_html($item['honraria'])); ?></div>
                                </div>

                            </div>

                        <?php endforeach; ?>
                    <?php endforeach; ?>

                </div>
            </div>
            <div class="premios-mobile">

                <div class="premios-mobile-carousel">

                    <?php foreach ($block['anos'] as $ano_group) : ?>
                        <?php foreach ($ano_group['itens'] as $item) : ?>

                            <?php
                            $link = '';
                            if (!empty($item['link_post'])) {
                                $link = get_permalink($item['link_post'][0]['id']);
                            }

                            $img_url = '';
                            if (!empty($item['imagem'])) {
                                $img_url = wp_get_attachment_image_url($item['imagem'], 'large');
                            }
                            ?>

                            <div class="premio-slide">
                                <span class="ano"><?php echo esc_html($ano_group['ano']); ?></span>
                                <?php if ($link) : ?>
                                    <a href="<?php echo esc_url($link); ?>" class="premio-image">
                                        <img src="<?php echo esc_url($img_url); ?>" alt="">
                                    </a>
                                <?php else : ?>
                                    <div class="premio-image">
                                        <img src="<?php echo esc_url($img_url); ?>" alt="">
                                    </div>
                                <?php endif; ?>
                                <div class="premio-info">

                                <div class="premio-row">
                                    <div class="premio-label">Prêmio / Concurso</div>
                                    <div class="premio-value">
                                        <?php echo nl2br(esc_html($item['premio'])); ?>
                                    </div>
                                </div>

                                <div class="premio-row">
                                    <div class="premio-label">Projeto</div>
                                    <div class="premio-value">
                                        <?php echo nl2br(esc_html($item['projeto'])) ?>
                                    </div>
                                </div>

                                <div class="premio-row">
                                    <div class="premio-label">Categoria</div>
                                    <div class="premio-value">
                                        <?php echo nl2br(esc_html($item['categoria'])); ?>
                                    </div>
                                </div>

                                <div class="premio-row">
                                    <div class="premio-label">Honraria</div>
                                    <div class="premio-value">
                                        <?php echo nl2br(esc_html($item['honraria'])); ?>
                                    </div>
                                </div>

                                </div>


                            </div>

                        <?php endforeach; ?>
                    <?php endforeach; ?>

                </div>
            </div>


            <?php
        });
}
