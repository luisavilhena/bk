<?php get_header(); ?>

<main class="archive-publicacoes structure-container structure-container-bigger">

<?php
$terms = get_terms([
    'taxonomy'   => 'publicacao',
    'hide_empty' => true,
  
    'meta_key'   => 'term_order',
    'orderby'    => 'meta_value_num',
    'order'      => 'DESC',
  ]);
  

foreach ($terms as $term) :
?>

    <section class="publicacao-group columns-container">
        <span class="line"></span>
        <div class="more" aria-label="Ver mais"><span>+</span></div>



        <!-- TÍTULO DA CATEGORIA -->
         <div class="column-left">
            <h2 class="publicacao-category">
                <?php echo esc_html($term->name); ?>
            </h2>
         </div>


        <?php
        $query = new WP_Query([
            'post_type'      => 'publicacoes',
            'posts_per_page' => -1,
            'tax_query'      => [
                [
                    'taxonomy' => 'publicacao',
                    'field'    => 'term_id',
                    'terms'    => $term->term_id,
                ]
            ],
        ]);
        ?>
        <div class="column-right">
            <?php if ($query->have_posts()) : ?>
                <div class="publicacoes-list">

                    <?php while ($query->have_posts()) : $query->the_post(); ?>

                        <article class="publicacao-item">

                            <!-- IMAGEM -->
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="publicacao-thumb">
                                <div class="normal">
                                <a
                                    href="<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'full')); ?>"
                                    class="publicacao-lightbox-trigger"
                                    data-group="<?php echo esc_attr($term->term_id); ?>"
                                >
                                    <?php the_post_thumbnail('medium_large'); ?>
                                </a>
                                </div>
                                    <div class="hover">
                                        <?php the_content(); ?>
                                    </div>
                                </div>
                                <div class="text">
                                    <h2><?php the_title(); ?></h2>
                                    <p><?php the_excerpt(); ?></p>
                                </div>
                            <?php endif; ?>

                        </article>

                    <?php endwhile; ?>

                </div>
            <?php endif; ?>
        </div>

        <?php wp_reset_postdata(); ?>

    </section>

<?php endforeach; ?>

</main>

<?php get_footer(); ?>
