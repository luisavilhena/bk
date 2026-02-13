<?php

get_header(); ?>
<?php
    $next_project = get_previous_post();

    if ( ! $next_project ) {
    $latest = new WP_Query([
        'post_type'      => get_post_type(),
        'posts_per_page' => 1,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ]);

    if ( $latest->have_posts() ) {
        $latest->the_post();
        $next_link  = get_permalink();
        $next_title = get_the_title();
        wp_reset_postdata();
    }
} else {
    $next_link  = get_permalink( $next_project );
    $next_title = get_the_title( $next_project );
    }
?>


<div id="single-projetos"class="single-projetos structure-container structure-container-bigger">
	<div class="columns-container">
		<div class="column-left">
			<h2 class="title"><?php the_title(); ?></h2>
            <div class="excerpt">
                <?php
                $tags = get_the_tags();

                if ($tags) {
                    foreach ($tags as $tag) {
                    echo esc_html($tag->name);
                    }
                }
                ?>
                </div>
			<?php
				$descricao = get_field('descricao_do_projeto');
				$especificidade = get_field('especificidade_projeto');
				?>
				<div class="descricao-do-projeto">
				<?php echo wp_kses_post( $descricao ); ?>
                <?php
                    preg_match_all('/<p\b[^>]*>/i', $descricao, $matches);
                    $paragraph_count = count($matches[0]);

                    if ($paragraph_count > 1) :
                    ?>
                        <div class="toggle-descricao">Ler mais</div>
                    <?php endif; ?>
				</div>
				<div id="especificidade-projeto" class="especificidade-projeto">
					<?php echo wp_kses_post( $especificidade ); ?>
				</div>
				<?php if ( isset( $next_link ) ) : ?>
					<a href="<?php echo esc_url( $next_link ); ?>" id="next-project"class="next-project">
						Próximo projeto →
					</a>
				<?php endif; ?>
		</div>
		<div id="column-right" class="column-right">
				<?php the_content(); ?> 
			</div>
		</div>
        <div class="mobile-footer">
            <a
                id="footer-logo-anchor"
                class="animationbacktotop projeto"
                href="">
                Voltar ao topo
                <img alt="voltar ao topo" src="<?php echo get_template_directory_uri() ?>/resources/icons/vector.png">

            </a>
            <?php if ( isset( $next_link ) ) : ?>
                <a href="<?php echo esc_url( $next_link ); ?>" id="next-project"class="">
                    Próximo projeto →
                </a>
            <?php endif; ?>
        </div>
	</div>
</div>
<?php
get_footer();
?>