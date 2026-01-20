<?php
get_header();

while (have_posts()) : the_post();
?>

<div class="home-content">
<?php
    function render_home_block( $field_name ) {
    $home = get_field( $field_name );
    if ( empty( $home ) ) return;

    $link = $home['link'] ?? null;
    $img  = $home['imagem'] ?? null;

    if ( ! $link || ! $img ) return;
    ?>
    <a href="<?php echo esc_url( $link['url'] ); ?>"
        target="<?php echo esc_attr( $link['target'] ?: '_self' ); ?>">
        <figure>
        <?php echo wp_get_attachment_image( $img, 'full' ); ?>
        <figcaption>
            <span><?php echo esc_html( $home['texto_1'] ?? '' ); ?></span>
            <span><?php echo esc_html( $home['texto_2'] ?? '' ); ?></span>
        </figcaption>
        </figure>
    </a>
    <?php
    }
    render_home_block('home');
    render_home_block('home_2');
    render_home_block('home_3');

?>

</div>
<?php
endwhile;

get_footer();
?>


