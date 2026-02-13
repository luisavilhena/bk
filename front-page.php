<?php
get_header();

while (have_posts()) : the_post();
?>

<div class="home-content">
<?php

function render_home_block( $home ) {
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

/* ============================
   COLETA OS 10 CAMPOS
============================ */

$fields = [
    'home',
    'home_2',
    'home_3',
    'home_4',
    'home_5',
    'home_6',
    'home_7',
    'home_8',
    'home_9',
    'home_10',
];

$blocks = [];

foreach ( $fields as $field ) {
    $data = get_field( $field );
    if ( ! empty( $data ) ) {
        $blocks[] = $data;
    }
}

/* ============================
   RANDOMIZA E PEGA 5
============================ */

shuffle( $blocks );
$blocks = array_slice( $blocks, 0, 5 );

/* ============================
   RENDERIZA
============================ */

foreach ( $blocks as $block ) {
    render_home_block( $block );
}

?>
</div>

<?php
endwhile;

get_footer();
?>


