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
                                    <a href="#" class="open-galeria">
                                        <?php the_post_thumbnail('medium_large'); ?>
                                    </a>
                                    </div>
                                    <div class="hover">
                                        <?php the_content(); ?>
                                    </div>
                                    <?php
                                    $galeria = carbon_get_the_post_meta('publicacao_galeria');

                                    if (!empty($galeria)) :
                                    ?>
                                        <div class="publicacao-extra-galeria">
                                            <?php foreach ($galeria as $item) :

                                                $img_id = $item['imagem'];
                                                $full   = wp_get_attachment_image_url($img_id, 'full');
                                                $thumb  = wp_get_attachment_image($img_id, 'medium_large');
                                                $caption = esc_attr($item['legenda']);
                                            ?>

                                                <a 
                                                    href="<?php echo esc_url($full); ?>"
                                                    data-fancybox="post-<?php the_ID(); ?>"
                                                    data-caption="<?php echo $caption; ?>"
                                                >
                                                    <?php echo $thumb; ?>
                                                </a>

                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
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
