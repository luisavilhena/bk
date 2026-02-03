<?php
/**
 * Template Name: Linha do Tempo
 */

get_header();

/**
 * 1. Busca TODOS os projetos (posts)
 */
$args = [
    'post_type'      => 'post',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'orderby'        => 'date',
    'order'          => 'DESC',
];

$query = new WP_Query($args);

/**
 * 2. Agrupa os posts por ANO
 */
$projects_by_year = [];

if ($query->have_posts()) {
    while ($query->have_posts()) {
        $query->the_post();

        $tags = get_the_terms(get_the_ID(), 'post_tag');
        if (!$tags || is_wp_error($tags)) {
            continue;
        }

        $year = null;

        // procura uma tag que seja um ano (ex: 2023)
        foreach ($tags as $tag) {
            if (preg_match('/^\d{4}$/', $tag->name)) {
                $year = $tag->name;
                break;
            }
        }

        // se não achou ano, ignora o post
        if (!$year) {
            continue;
        }

        if (!isset($projects_by_year[$year])) {
            $projects_by_year[$year] = [];
        }

        $projects_by_year[$year][] = [
            'id'    => get_the_ID(),
            'title' => get_the_title(),
            'link'  => get_permalink(),
            'thumb' => get_the_post_thumbnail_url(get_the_ID(), 'large'),
        ];
    }

    wp_reset_postdata();
    $svg_path = file_get_contents(get_template_directory() . '/resources/icons/arrow-thin.svg');

}

/**
 * Ordena do MAIOR ANO para o MENOR
 * ex: 2025 → 2024 → 2023 → 1990
 */
krsort($projects_by_year, SORT_NUMERIC);
?>
<div class="filter">
    <div class="space"></div>
    <div id="filtro-categorias" method="GET">
        <div class="blocos">
            <div class="bloco">
                <div class="selections">
                    <div>
                        <span class="category">
                            Linha do tempo
                            <?php echo $svg_path; ?>
                        </span>

                        <ul class="sibling">
                            <li
                                class="category-list-item is-reset">
                                Linha do tempo

                            </li>
                            <li
                                class="category-list-item is-reset">
                                <a href="<?php echo esc_url( get_permalink( get_page_by_path('projetos') ) ); ?>">Galeria de fotos </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <form role="search" method="get" class="search-form" id="search-form" action="<?php echo esc_url(home_url('/projetos')); ?>">
            <label>
                <span class="screen-reader-text"><?php echo _x('Search for:', 'label'); ?></span>
                <input type="text" placeholder="Buscar" class="search-field" value="<?php echo get_search_query(); ?>" name="s" />
            </label>
            <button type="submit" class="search-submit">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="13" viewBox="0 0 14 13" fill="none">
                <circle cx="5.5" cy="5.5" r="5" stroke="white"/>
                <line x1="9.35355" y1="8.64645" x2="13.3536" y2="12.6464" stroke="white"/>
                </svg>
            </button>
        </form> 
    </div>
</div>
<div id="timeline-page"class="structure-container">
	<div class="columns-container without-height">
		<div class="column-left">
            <div class="header-timeline">
                <h1>LINHA DO TEMPO</h1>
                <span></span>

            </div>
		</div>
		<div id="column-right" class="column-right">
            <!-- ===== CARROSSEL PRINCIPAL ===== -->
            <div class="projects-carousel">
                <div class="projects-track" id="timeline">

                    <?php foreach ($projects_by_year as $year => $projects): ?>

                        <!-- SLIDE DE TÍTULO DO ANO -->
                        <div class="timeline-year-slide" data-year="<?php echo esc_attr($year); ?>">
                            <h2><?php echo esc_html($year); ?></h2>
                        </div>

                        <!-- SLIDES DE PROJETOS DO ANO -->
                        <?php foreach ($projects as $project): ?>

                            <?php
                                // ID do post atual do projeto
                                $post_id = $project['id'];

                                // pega o termo pai "tipologia"
                                $tipologia_parent = get_term_by('slug', 'tipologia', 'category');

                                $tipologia_child_name = '';

                                if ($tipologia_parent && !is_wp_error($tipologia_parent)) {

                                    // categorias do post
                                    $terms = get_the_terms($post_id, 'category');

                                    if ($terms && !is_wp_error($terms)) {
                                        foreach ($terms as $term) {
                                            if ((int) $term->parent === (int) $tipologia_parent->term_id) {
                                                $tipologia_child_name = $term->name;
                                                break; // só uma
                                            }
                                        }
                                    }
                                }
                                ?>

                            <article
                                class="project-card"
                                data-year="<?php echo esc_attr($year); ?>">
                                <div class="text">
                                    <h2><?php echo esc_attr($project['title']); ?></h2>
                                    <?php $city = get_field('city', $project['id']); ?>

                                    <?php if ($city): ?>
                                        <p><?php echo esc_html($city); ?></p>
                                    <?php endif; ?>
                                    <p>  <?php echo esc_html($tipologia_child_name); ?></p>
                                    <div class="ver-mais"> <a href="<?php echo esc_url($project['link']); ?>">Ver mais</a></div>
                                </div>
                                <a href="<?php echo esc_url($project['link']); ?>">
                                    <?php if ($project['thumb']): ?>
                                        <img
                                            src="<?php echo esc_url($project['thumb']); ?>"
                                            alt="<?php echo esc_attr($project['title']); ?>"
                                            loading="lazy"
                                        >
                                    <?php endif; ?>
                                </a>
                            </article>
                        <?php endforeach; ?>

                    <?php endforeach; ?>

                </div>
            </div>

		</div>
	</div>
    <section class="timeline-control">

        <!-- ===== TIMELINE INFERIOR ===== -->
        <?php $slide_index = 0; ?>

        <div class="timeline-wrapper">
        <!-- SCRUBBER -->
        <div class="timeline-scrubber">
            <span class="timeline-handle"></span>
            <span class="line"></span>
        </div>

        <!-- ANOS -->
        <div class="timeline-track">
            <?php foreach ($projects_by_year as $year => $projects): ?>

                <?php
                    // se for divisível por 5 → ano completo
                    if ($year % 5 === 0) {
                        $label = '<span class="year-major">' . $year . '</span>';
                    } else {
                        // senão → últimos 2 dígitos
                        $label = substr($year, -2);
                    }
                ?>

                <div
                    class="timeline-year"
                    data-year="<?php echo esc_attr($year); ?>"
                    data-slide="<?php echo esc_attr($slide_index); ?>"
                >
                    <?php echo $label; ?>
                </div>

                <?php $slide_index += count($projects) + 1; ?>


            <?php endforeach; ?>
        </div>
    </section>
</div>

<?php get_footer(); ?>
