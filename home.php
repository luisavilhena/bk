<?php
get_header();

// filtros via GET
$filters = [
    'tipologia' => isset($_GET['tipologia']) ? intval($_GET['tipologia']) : 0,
    'local'     => isset($_GET['local']) ? intval($_GET['local']) : 0,
    'fase'      => isset($_GET['fase']) ? intval($_GET['fase']) : 0,
    'decada'    => isset($_GET['decada']) ? intval($_GET['decada']) : 0,
    'escala'    => isset($_GET['escala']) ? intval($_GET['escala']) : 0,
    'premiado'  => isset($_GET['premiado']) ? intval($_GET['premiado']) : 0,
];

$search_query   = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';
$posts_per_page = 12;
$paged          = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;

// base da query
$args = [
    'post_type'           => 'post',
    'post_status'         => 'publish',
    'posts_per_page'      => $posts_per_page,
    'paged'               => $paged,
    'orderby'             => 'date',
    'order'               => 'DESC',
    'ignore_sticky_posts' => true,
    's'                   => $search_query,
    'orderby' => [
        'date' => 'DESC',
        'ID'   => 'DESC',
    ]
];

// monta tax_query corretamente
$tax_query = [];

foreach ($filters as $value) {
    if ($value) {
        $tax_query[] = [
            'taxonomy'         => 'category',
            'field'            => 'term_id',
            'terms'            => $value,
            'include_children' => false,
        ];
    }
}

if (!empty($tax_query)) {
    $args['tax_query'] = array_merge(
        ['relation' => 'AND'],
        $tax_query
    );
}

// query final
$the_query = new WP_Query($args);


// Obtenha todos os termos da taxonomia 'category' para análise, incluindo termos filhos
$all_terms = get_terms(array(
    'taxonomy' => 'category',
    'hide_empty' => false, // Certifique-se de obter todos os termos, mesmo os que não têm posts
));

// Filtra os termos que têm posts com os filtros aplicados
$filter_status = array();

foreach ($all_terms as $term) {
    $term_args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        's' => $search_query,
        'tax_query' => array('relation' => 'AND'),
    );

    if ($tipologia) {
        $term_args['tax_query'][] = array(
            'taxonomy' => 'category',
            'field'    => 'term_id',
            'terms'    => $tipologia,
        );
    }

    if ($local) {
        $term_args['tax_query'][] = array(
            'taxonomy' => 'category',
            'field'    => 'term_id',
            'terms'    => $local,
        );
    }

    if ($fase) {
        $term_args['tax_query'][] = array(
            'taxonomy' => 'category',
            'field'    => 'term_id',
            'terms'    => $fase,
        );
    }

    if ($decada) {
        $term_args['tax_query'][] = array(
            'taxonomy' => 'category',
            'field'    => 'term_id',
            'terms'    => $decada,
        );
    }

    if ($escala) {
        $term_args['tax_query'][] = array(
            'taxonomy' => 'category',
            'field'    => 'term_id',
            'terms'    => $escala,
        );
    }

    if ($premiado) {
        $term_args['tax_query'][] = array(
            'taxonomy' => 'category',
            'field'    => 'term_id',
            'terms'    => $premiado,
            'include_children' => false,
        );
    }

    // 👉 Aqui SIM entra o termo sendo avaliado
    $term_args['tax_query'][] = array(
        'taxonomy' => 'category',
        'field'    => 'term_id',
        'terms'    => $term->term_id,
        'include_children' => false,
    );

    $term_query = new WP_Query($term_args);

    if (!$term_query->have_posts()) {
        $filter_status[$term->term_id] = 'no-projects';
    }

    wp_reset_postdata();
}

$svg_path = file_get_contents(get_template_directory() . '/resources/icons/arrow-thin.svg');



?>

<?php
function render_filter_block($args) {
    $slug          = $args['slug'];
    $filter_status = $args['filter_status'];
    $svg_path      = $args['svg_path'];

    $parent = get_term_by('slug', $slug, 'category');
    if (!$parent || is_wp_error($parent)) return;

    $terms = get_terms([
        'taxonomy'   => 'category',
        'hide_empty' => true,
        'parent'     => $parent->term_id,
    ]);

    // label padrão
    $label = $parent->name;

    // label selecionado via GET
    if (!empty($_GET[$slug])) {
        $selected = get_term((int) $_GET[$slug], 'category');
        if ($selected && !is_wp_error($selected)) {
            $label = $selected->name;
        }
    }
    ?>
        <div class="selections">
            <div name="<?php echo esc_attr($slug); ?>" id="select-<?php echo esc_attr($slug); ?>">
                
                <div class="category <?php echo !empty($_GET[$slug]) ? 'bold' : ''; ?>">
                    <?php echo esc_html($label); ?>
                    <?php echo $svg_path; ?>
                </div>

                <ul class="sibling">
                    <!-- reset -->
                    <li
                        class="category-list-item is-reset"
                        data-text="Nenhuma seleção"
                        data-filter="<?php echo esc_attr($slug); ?>"
                        data-term-id="">
                        Nenhuma seleção
                    </li>

                    <?php foreach ($terms as $term) :
                        $class = isset($filter_status[$term->term_id])
                            ? 'no-projects'
                            : '';
                    ?>
                        <li
                            class="category-list-item <?php echo esc_attr($class); ?>"
                            data-text="<?php echo esc_html($term->name); ?>"
                            data-filter="<?php echo esc_attr($slug); ?>"
                            data-term-id="<?php echo esc_attr($term->term_id); ?>">
                            <?php echo esc_html($term->name); ?>
                        </li>
                    <?php endforeach; ?>
                </ul>

            </div>
        </div>
    <?php
}
?>

<?php
function render_single_filter_block($args) {
    $slug = $args['slug'];

    $term = get_term_by('slug', $slug, 'category');
    if (!$term || is_wp_error($term)) return;

    $is_active = isset($_GET[$slug]) && intval($_GET[$slug]) === $term->term_id;
    ?>
        <div class="selections">
            <div name="<?php echo esc_attr($slug); ?>" id="select-<?php echo esc_attr($slug); ?>">
                <ul class="sibling single">
                    <li
                        class="category-list-item <?php echo $is_active ? 'bold' : ''; ?>"
                        data-text="<?php echo esc_html($term->name); ?>"
                        data-filter="<?php echo esc_attr($slug); ?>"
                        data-term-id="<?php echo esc_attr($term->term_id); ?>">
                        <?php echo esc_html($term->name); ?>
                    </li>
                </ul>
            </div>
        </div>
    <?php
}
?>
<div class="filter">
    <div class="space"> Filtros  <?php echo $svg_path; ?></div>
    <div id="filtro-categorias" method="GET">
        <div class="blocos">
            <div class="bloco">
                <div class="selections">
                    <div>
                        <span class="category">
                            Galeria de fotos
                            <?php echo $svg_path; ?>
                        </span>

                        <ul class="sibling">
                            <!-- reset -->
                            <li
                                class="category-list-item is-reset">
                                Linha do Tempo
                            </li>
                            <li
                                class="category-list-item is-reset">
                                <a href="">Galeria de fotos</a>
                            </li>
                        </ul>

                    </div>
                </div>
                <?php
                render_filter_block([
                    'slug'          => 'tipologia',
                    'filter_status' => $filter_status,
                    'svg_path'      => $svg_path,
                ]);

                render_filter_block([
                    'slug'          => 'local',
                    'filter_status' => $filter_status,
                    'svg_path'      => $svg_path,
                ]);

                render_filter_block([
                    'slug'          => 'fase',
                    'filter_status' => $filter_status,
                    'svg_path'      => $svg_path,
                ]);

                render_filter_block([
                    'slug'          => 'decada',
                    'filter_status' => $filter_status,
                    'svg_path'      => $svg_path,
                ]);

                render_filter_block([
                    'slug'          => 'escala',
                    'filter_status' => $filter_status,
                    'svg_path'      => $svg_path,
                ]);
                ?>

                <?php
                render_single_filter_block([
                    'slug' => 'premiado'
                ]);
                ?>
                </div>
            </div>
            <div>
        </div>
        <form role="search" method="get" class="search-form" id="search-form" action="<?php echo esc_url(home_url('/projetos')); ?>">
            <label>
                <span class="screen-reader-text"><?php echo _x('Search for:', 'label'); ?></span>
                <input type="text" placeholder="Buscar" class="search-field" value="<?php echo get_search_query(); ?>" name="s" />
            </label>
            <button type="submit" class="search-submit">
                <img alt="search" src="<?php echo get_template_directory_uri(); ?>/resources/icons/search.png">
            </button>
        </form> 
    </div>

</div>

<div id="category" class="structure-container structure-container-bigger ">
    <div class="structure-container__content structure-container__side">
        <div id="resultado-posts" class="project-list">
            <?php
            if ($the_query->have_posts()) {
                while ($the_query->have_posts()) : $the_query->the_post();

                    $thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'horizontal');
                    $post_url = get_permalink();
                    $excerpt = get_the_excerpt();
                    $categories = get_the_terms(get_the_ID(), 'category');

                    $category_names = array();

                    foreach ($categories as $category) {
                        $category_names[] = $category->name;
                    }

                    $categories_list = implode(', ', $category_names);
                    echo '<p class="categoria" style="display:none">' . $categories_list . '</p>';

                    ?>
                    <a href="<?php echo esc_url($post_url); ?>" class="project-list__item">
                        <?php if ($thumbnail_url) : ?>
                            <div class="post-thumbnail">
                                <img src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php the_title_attribute(); ?>">
                                <span></span>
                        </div>
                        <?php endif; ?>
                        <div class="project-list__item-description">
                            <h2 class="post-title">
                                <?php the_title(); ?></h2>
                            <p><?php echo $excerpt; ?></p>
                        </div>
                        </a>
                    <?php

                endwhile;
                // Restaure os dados do post
                wp_reset_postdata();
            }
            ?>
        </div>
        <?php if ($the_query->max_num_pages > 1) : ?>
            <?php if ($the_query->have_posts()) : ?>
    <button
        id="load-more"
        data-offset="<?php echo esc_attr($the_query->post_count); ?>"
        data-per-page="<?php echo esc_attr($the_query->query_vars['posts_per_page']); ?>">
        Ver mais projetos <span>+</span>
    </button>
<?php endif; ?>



        <?php endif; ?>

    </div>
</div>

<?php get_footer(); ?>

<script>

document.addEventListener('DOMContentLoaded', function () {

    const btn = document.getElementById('load-more');
    if (!btn) return;

    btn.addEventListener('click', function () {

        let offset  = parseInt(btn.dataset.offset, 10);
        let perPage = parseInt(btn.dataset.perPage, 10);

        if (isNaN(offset) || isNaN(perPage)) {
            console.error('Offset inválido', btn.dataset);
            return;
        }

        const data = new URLSearchParams();
        data.append('action', 'load_more_posts');
        data.append('offset', offset);

        const allowedFilters = [
            'tipologia','local','fase','decada','escala','premiado'
        ];

        const params = new URLSearchParams(window.location.search);
        allowedFilters.forEach(key => {
            if (params.get(key)) {
                data.append(key, params.get(key));
            }
        });

        fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
            },
            body: data.toString()
        })
        .then(res => res.text())
        .then(html => {

            if (!html.trim()) {
                btn.remove();
                return;
            }

            document
                .querySelector('#resultado-posts')
                .insertAdjacentHTML('beforeend', html);

            btn.dataset.offset = offset + perPage;
        });
    });

});

document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.category-list-item').forEach(item => {
        item.addEventListener('click', function (e) {
            e.preventDefault();

            if (this.classList.contains('no-projects')) return;

            const filter   = this.dataset.filter;
            const termId   = this.dataset.termId; // pode ser ""
            const isSingle = this.closest('.sibling')?.classList.contains('single');
            const isActive = this.classList.contains('bold');

            const params = new URLSearchParams(window.location.search);

            // 🔁 SINGLE toggle (premiado)
            if (isSingle && isActive) {
                params.delete(filter);
            } 
            // reset (li vazio)
            else if (!termId) {
                params.delete(filter);
            } 
            // normal
            else {
                params.set(filter, termId);
            }

            params.delete('s');
            window.location.search = params.toString();
        });
    });

});

</script>
