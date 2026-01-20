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
$posts_per_page = 4;
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

<div id="category" class="structure-container">
    <div class="structure-container__content structure-container__side">
        <div class="filter">
            <div class="filter-form">
                <form id="filtro-categorias" method="GET">
                <div id="loading" class="loading">
                <div class="spinner"></div>
            </div>
                    <div class="blocos">
                        <div class="blocos-1">
                            <!-- Tipologia -->
                            <div class="bloco">
                                <?php
                                $tipo = 'tipologia';
                                $tipo_term = get_term_by('slug', $tipo, 'category');
                                $terms = get_terms(array(
                                    'taxonomy' => 'category',
                                    'hide_empty' => true,
                                    'parent' => $tipo_term->term_id,
                                ));
                                ?>
                                <div class="selections">
                                    <div  data-selected="tipologia" class="filter-selected"></div>
                                    <div name="tipologia" id="select-tipologia">
                                        <span class="category"><?php echo $tipo_term->name; ?>
                                            <?php
                                                echo $svg_path;

                                            ?>
                                        </span>
                                        <ul class="sibling">
                                        <?php
                                        foreach ($terms as $term) {
                                            $class = isset($filter_status[$term->term_id]) ? 'class="' . $filter_status[$term->term_id] . '"' : '';
                                            echo '<li class="category-list-item" data-term-id="' . $term->term_id . '" data-filter="tipologia" ' . $class . '>' . $term->name . '</li>';
                                        }
                                        ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Local -->
                            <div class="bloco">
                                <?php
                                $tipo = 'local';
                                $tipo_term = get_term_by('slug', $tipo, 'category');
                                $terms = get_terms(array(
                                    'taxonomy' => 'category',
                                    'hide_empty' => true,
                                    'parent' => $tipo_term->term_id,
                                ));
                                ?>
                                <div class="selections">
                                    <div data-selected="local" class="filter-selected"></div>
                                    <div name="local" id="select-local">
                                        <span class="category"><?php echo $tipo_term->name; ?>
                                            <?php
                                                echo $svg_path;

                                            ?>
                                        </span>
                                        <ul class="sibling">
                                        <?php
                                        foreach ($terms as $term) {
                                            $class = isset($filter_status[$term->term_id]) ? 'class="' . $filter_status[$term->term_id] . '"' : '';
                                            echo '<li class="category-list-item" data-term-id="' . $term->term_id . '" data-filter="local" ' . $class . '>' . $term->name . '</li>';
                                        }
                                        ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Fase -->
                            <div class="bloco">
                                <?php
                                $tipo = 'fase';
                                $tipo_term = get_term_by('slug', $tipo, 'category');
                                $terms = get_terms(array(
                                    'taxonomy' => 'category',
                                    'hide_empty' => true,
                                    'parent' => $tipo_term->term_id,
                                ));
                                ?>
                                <div class="selections">
                                    <div data-selected="fase" class="filter-selected"></div>
                                    <div name="fase" id="select-fase">
                                        <span class="category"><?php echo $tipo_term->name; ?>
                                            <?php
                                                echo $svg_path; 
                                            ?>
                                        </span>
                                        <ul class="sibling">
                                        <?php
                                        foreach ($terms as $term) {
                                            $class = isset($filter_status[$term->term_id]) ? 'class="' . $filter_status[$term->term_id] . '"' : '';
                                            echo '<li class="category-list-item" data-term-id="' . $term->term_id . '" data-filter="fase" ' . $class . '>' . $term->name . '</li>';
                                        }
                                        ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Década -->
                            <div class="bloco">
                                <?php
                                $tipo = 'decada';
                                $tipo_term = get_term_by('slug', $tipo, 'category');
                                $terms = get_terms(array(
                                    'taxonomy' => 'category',
                                    'hide_empty' => true,
                                    'parent' => $tipo_term->term_id,
                                ));
                                ?>
                                <div class="selections">
                                    <div data-selected="decada" class="filter-selected"></div>
                                    <div name="decada" id="select-decada">
                                        <span class="category"><?php echo $tipo_term->name; ?>
                                            <?php
                                                    echo $svg_path;

                                            ?>
                                        </span>
                                        <ul class="sibling">
                                        <?php
                                        foreach ($terms as $term) {
                                            $class = isset($filter_status[$term->term_id]) ? 'class="' . $filter_status[$term->term_id] . '"' : '';
                                            echo '<li class="category-list-item" data-term-id="' . $term->term_id . '" data-filter="decada" ' . $class . '>' . $term->name . '</li>';
                                        }
                                        ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Escala -->
                            <div class="bloco">
                                <?php
                                $tipo = 'escala';
                                $tipo_term = get_term_by('slug', $tipo, 'category');
                                $terms = get_terms(array(
                                    'taxonomy' => 'category',
                                    'hide_empty' => true,
                                    'parent' => $tipo_term->term_id,
                                ));
                                ?>
                                <div class="selections">
                                    <div data-selected="escala" class="filter-selected"></div>
                                    <div name="escala" id="select-escala">
                                        <span class="category"><?php echo $tipo_term->name; ?>
                                            <?php
                                                echo $svg_path;

                                            ?>
                                        </span>
                                        <ul class="sibling">
                                        <?php
                                        foreach ($terms as $term) {
                                            $class = isset($filter_status[$term->term_id]) ? 'class="' . $filter_status[$term->term_id] . '"' : '';
                                            echo '<li class="category-list-item" data-term-id="' . $term->term_id . '" data-filter="escala" ' . $class . '>' . $term->name . '</li>';
                                        }
                                        ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Premiado -->
                            <div class="bloco">
                                <?php
                                    $tipo = 'premiado';
                                    $tipo_term = get_term_by('slug', $tipo, 'category');
                                    if ( ! $tipo_term || is_wp_error($tipo_term) ) {
                                        return;
                                    }

                                    $is_active = isset($_GET['premiado']) && intval($_GET['premiado']) === $tipo_term->term_id;
                                ?>

                                <div class="selections">
                                    <div name="premiado" id="select-premiado">
                                        <ul class="sibling single">
                                            <li
                                                class="category-list-item <?php echo $is_active ? 'bold' : ''; ?>"
                                                data-filter="premiado"
                                                data-term-id="<?php echo esc_attr($tipo_term->term_id); ?>">
                                                <?php echo esc_html($tipo_term->name); ?>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>



                        </div>
                    </div>
                </form>
            </div>
        </div>
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
        Load more
    </button>
<?php endif; ?>



        <?php endif; ?>

    </div>
</div>

<?php get_footer(); ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Adiciona evento de clique aos itens <li>
    document.querySelectorAll('.selections .category-list-item').forEach(function(item) {
        item.addEventListener('click', function() {
            if (this.classList.contains('no-projects')) {
                return; // Não faz nada se o item tiver a classe 'no-projects'
            }

            const filterType = this.getAttribute('data-filter');
            const termId = this.getAttribute('data-term-id');
            const termText = this.textContent;

            // Atualiza a URL com o filtro selecionado
            const urlParams = new URLSearchParams(window.location.search);
            urlParams.set(filterType, termId);

            // Remove o parâmetro de busca 's'
            urlParams.delete('s');
            window.location.search = urlParams.toString();  // Recarrega a página com novos parâmetros de filtro

            // Armazena todos os filtros aplicados no armazenamento local
            const selectedFilters = JSON.parse(localStorage.getItem('selectedFilters')) || {};
            selectedFilters[filterType] = termText;
            localStorage.setItem('selectedFilters', JSON.stringify(selectedFilters));
        });
    });

    // Restaura o estado dos filtros ao carregar a página
    window.addEventListener('load', function() {
        updateFilterDisplay();
    });

    // Atualiza a exibição dos filtros selecionados
    function updateFilterDisplay() {
        const selectedFilters = JSON.parse(localStorage.getItem('selectedFilters')) || {};

        // Oculta todos os elementos `data-selected`
        document.querySelectorAll('[data-selected]').forEach(function(div) {
            div.style.display = "none";
        });

        // Exibe todos os elementos `.category`
        document.querySelectorAll('.category').forEach(function(span) {
            span.classList.remove('bold'); // Remove a classe 'bold' de todos os elementos
            span.style.display = "block";
        });

        for (const [filterType, termText] of Object.entries(selectedFilters)) {
            const filterSelectedDiv = document.querySelector(`[data-selected="${filterType}"]`);
            if (filterSelectedDiv) {
                filterSelectedDiv.style.display = "block";
                filterSelectedDiv.textContent = 'x ' + termText;
            }

            const spanCategory = document.querySelector(`[name="${filterType}"] .category`);
            if (spanCategory) {
                spanCategory.style.display = "none";
            }
        }
    }

    // Ocultar o filtro selecionado quando clicado
    document.addEventListener('click', function(event) {
        if (event.target.hasAttribute('data-selected')) {
            const selectedValue = event.target.getAttribute('data-selected');

            // Oculta o filtro selecionado
            event.target.style.display = 'none';

            // Mostra o menu de seleção correspondente
            const targetElement = document.querySelector(`[name="${selectedValue}"] .category`);
            if (targetElement) {
                targetElement.style.display = 'block';
            }

            // Remove o filtro selecionado do armazenamento local
            const selectedFilters = JSON.parse(localStorage.getItem('selectedFilters')) || {};
            delete selectedFilters[selectedValue];
            localStorage.setItem('selectedFilters', JSON.stringify(selectedFilters));

            // Atualiza a URL para remover o filtro
            const urlParams = new URLSearchParams(window.location.search);
            urlParams.delete(selectedValue);
            window.location.search = urlParams.toString();  // Recarrega a página com filtros removidos
        }
    });

    // Mostrar/ocultar as listas de categorias
    const blockFilter = document.getElementById("filtro-categorias");
    document.querySelectorAll('.category').forEach(function(category) {
        category.addEventListener('click', function() {
            // Encontra a lista irmã
            var sibling = this.nextElementSibling;
            // Verifica se a lista irmã existe e tem a classe 'sibling'
            if (sibling && sibling.classList.contains('sibling')) {
                // Verifica se a lista irmã já tem a classe 'active'
                if (sibling.classList.contains('active')) {
                    // Remove a classe 'active' se já estiver ativa
                    sibling.classList.remove('active');
                    blockFilter.classList.remove('open');
                } else {
                    // Oculta todas as listas irmãs
                    document.querySelectorAll('.sibling').forEach(function(siblingList) {
                        siblingList.classList.remove('active');
                        blockFilter.classList.remove('open');
                    });

                    // Adiciona a classe 'active' à lista irmã correspondente
                    sibling.classList.add('active');
                    blockFilter.classList.add('open');
                }
            }

            // Remove a classe 'bold' de todos os outros elementos .category
            document.querySelectorAll('.category').forEach(function(span) {
                span.classList.remove('bold');
            });

            // Adiciona a classe 'bold' ao elemento clicado
            this.classList.add('bold');
        });
    });
});

</script>
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

            // 🔥 incrementa offset UMA ÚNICA VEZ
            btn.dataset.offset = offset + perPage;
        });
    });

});
</script>

