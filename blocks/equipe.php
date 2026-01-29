<?php
use Carbon_Fields\Block;
use Carbon_Fields\Field;

add_action('carbon_fields_register_fields', 'bk_equipe_block');

function bk_equipe_block() {

    Block::make('Equipe')
        ->add_fields([

            /* =====================
               LINHA 1 – HERO
            ===================== */
            Field::make('text', 'hero_title', 'Título da equipe'),
            Field::make('text', 'hero_subtitle', 'Subtítulo'),
            Field::make('image', 'hero_image', 'Imagem da equipe'),

            /* =====================
               LINHA 2 – GRID 3
            ===================== */
            Field::make('complex', 'team_members', 'Equipe')
                ->add_fields([
                    Field::make('image', 'image', 'Foto'),
                    Field::make('text', 'title', 'Nome'),
                    Field::make('rich_text', 'subtitle', 'Cargo'),
                    Field::make('rich_text', 'bio', 'Descrição'),
                ])
                ->set_layout('tabbed-vertical')
                ->set_header_template( '
                  <% if (title) { %>
                      <%- title %>
                  <% } %>
              ' ),

            /* =====================
               LINHA 3 – GRID 2
            ===================== */
            Field::make('text', 'highlight_title', 'Título padrão (linha 3)'),

            Field::make('complex', 'team_highlights', 'Destaques')
                ->set_layout('tabbed-vertical')
                ->set_header_template('<%= title ? title : "Novo destaque" %>')
                ->add_fields([
                    Field::make('image', 'image', 'Foto'),
                    Field::make('text', 'title', 'Nome'),
                    Field::make('rich_text', 'subtitle', 'Cargo'),
                    Field::make('rich_text', 'bio', 'Descrição'),
                ]),
        ])
        ->set_render_callback(function ($block) {
        ?>

<section class="equipe">

    <!-- ===== LINHA 1 – HERO ===== -->
    <div class="equipe-hero">
        <div class="col-left">
            <h2><?= esc_html($block['hero_title']); ?></h2>
            <p><?= esc_html($block['hero_subtitle']); ?></p>
        </div>

        <div class="col-right">
            <?= wp_get_attachment_image($block['hero_image'], 'full'); ?>
        </div>
    </div>

    <!-- ===== LINHA 2 – GRID 3 ===== -->
    <div class="equipe-grid grid-3">

        <div class="col-left equipe-info">
            <div class="info-top">
                <h3 class="info-title"></h3>
                <p class="info-subtitle"></p>
            </div>
            <div class="info-bottom info-bio"></div>
        </div>

        <div class="col-right">
            <div class="grid">
                <?php foreach ($block['team_members'] as $member): ?>
                    <div
                        class="member"
                        data-title="<?= esc_attr($member['title']); ?>"
                        data-subtitle="<?= esc_attr($member['subtitle']); ?>"
                        data-bio="<?= esc_attr($member['bio']); ?>"
                    >
                        <?= wp_get_attachment_image($member['image'], 'full'); ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

    </div>

    <!-- ===== LINHA 3 – GRID 2 ===== -->
    <div class="equipe-grid grid-2">

        <div class="col-left equipe-info default">

            <div class="info-default">
                <h3 class="info-title-default">
                    <?= esc_html($block['highlight_title']); ?>
                </h3>
            </div>

            <div class="info-hover">
                <div class="info-top">
                    <h3 class="info-title"></h3>
                    <p class="info-subtitle"></p>
                </div>
                <div class="info-bottom info-bio"></div>
            </div>

         </div>


        <div class="col-right">
            <div class="grid">
                <?php foreach ($block['team_highlights'] as $member): ?>
                    <div
                        class="member"
                        data-title="<?= esc_attr($member['title']); ?>"
                        data-subtitle="<?= esc_attr($member['subtitle']); ?>"
                        data-bio="<?= esc_attr($member['bio']); ?>"
                    >
                        <?= wp_get_attachment_image($member['image'], 'medium'); ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

    </div>

</section>
<!-- ================= MOBILE ================= -->
<section class="equipe-mobile">

    <!-- ===== LINHA 1 – HERO (SÓ IMAGEM) ===== -->
    <div class="equipe-mobile-hero">
        <?= wp_get_attachment_image($block['hero_image'], 'full'); ?>
    </div>

        <!-- ===== LINHA 3 – GRID 2 ===== -->
        <div class="equipe-mobile-grid-2">

<?php foreach ($block['team_highlights'] as $member): ?>

    <div class="mobile-highlight">

        <?= wp_get_attachment_image($member['image'], 'full'); ?>

        <div class="highlight-text">
            <h3><?= esc_html($member['title']); ?></h3>
            <p class="cargo"><?= esc_html($member['subtitle']); ?></p>

            <div class="bio">
                <?= wpautop($member['bio']); ?>
            </div>
        </div>

    </div>

<?php endforeach; ?>

</div>

    <!-- ===== LINHA 2 – GRID 3 → LISTA ===== -->
    <div class="equipe-mobile-list">

        <?php foreach ($block['team_members'] as $index => $member): ?>

            <?php
                $bio_raw = trim(wp_strip_all_tags($member['bio']));

                $short = $bio_raw;
                $full  = $bio_raw;

                $pos = mb_strpos($bio_raw, '.');

                if ($pos !== false) {
                    $short = mb_substr($bio_raw, 0, $pos + 1);
                    $full  = mb_substr($bio_raw, $pos + 1);
                }

            ?>

            <div class="mobile-member">

                <div class="mobile-member-image">
                    <?= wp_get_attachment_image($member['image'], 'full'); ?>
                </div>

                <div class="mobile-member-text">
                    <div>
                        <h3><?= esc_html($member['title']); ?></h3>
                        <p class="cargo"><?= esc_html($member['subtitle']); ?></p>
                    </div>
                    <div>

                        <div class="bio-short">
                            <?= esc_html($short); ?>
                        </div>

                        <?php if (!empty(trim($full))) : ?>
                            <div class="bio-full">
                                <?= esc_html($full); ?>
                            </div>
                            <button class="read-more" type="button">
                                Leia mais
                            </button>

                        <?php endif; ?>
                    </div>
                    </div>


            </div>

        <?php endforeach; ?>

    </div>


</section>



<?php
        });
}
