<?php
/**
 * Template Name: Notícias
 */
?>

<?php get_header();?>

<div id="noticias" class="structure-container">
    <div class="title-fixed">
        <div class="columns-container without-height">
            <div class="column-left">
                 <h1>Agenda BK</h1>
            </div>
            <div class="column-right">
                <h2>Blog</h2>
            </div>
        </div>
    </div>
    <div class="columns-container">
        <div class="column-left">
            <div class="agenda">

                <?php
                $events = carbon_get_the_post_meta('event_dates');

                if ($events) :

                    $today = strtotime(date('Y-m-d'));

                    $upcoming = [];
                    $past     = [];

                    foreach ($events as $event) {
                        $timestamp = strtotime($event['date']);

                        if ($timestamp >= $today) {
                            $upcoming[] = $event;
                        } else {
                            $past[] = $event;
                        }
                    }

                    // Ordena próximos do mais próximo para o mais distante
                    usort($upcoming, function($a, $b) {
                        return strtotime($a['date']) - strtotime($b['date']);
                    });

                    // Ordena antigos do mais recente para o mais antigo
                    usort($past, function($a, $b) {
                        return strtotime($b['date']) - strtotime($a['date']);
                    });
                ?>

                    <?php if (!empty($upcoming)) : ?>
                        <h2 class="events-title">Próximos Eventos</h2>

                        <div class="events-list upcoming">
                            <?php foreach ($upcoming as $event) :
                                $formatted_date = studio_format_date_pt($event['date']);
                            ?>
                                    <div class="event-date">
                                        <?php echo esc_html($formatted_date); ?>
                                    </div>

                                    <div class="event-text">
                                        <?php if (!empty($event['link'])) : ?>
                                            <a href="<?php echo esc_url($event['link']); ?>">
                                                <?php echo esc_html($event['text']); ?>
                                            </a>
                                        <?php else : ?>
                                            <?php echo esc_html($event['text']); ?>
                                        <?php endif; ?>
                                    </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>


                    <?php if (!empty($past)) : ?>
                        <h2 class="events-title past">Eventos Antigos</h2>

                        <div class="events-list past">
                            <?php foreach ($past as $event) :
                                $formatted_date = studio_format_date_pt($event['date']);
                            ?>
                                    <div class="event-date">
                                        <?php echo esc_html($formatted_date); ?>
                                    </div>

                                    <div class="event-text">
                                        <?php if (!empty($event['link'])) : ?>
                                            <a href="<?php echo esc_url($event['link']); ?>">
                                                <?php echo esc_html($event['text']); ?>
                                            </a>
                                        <?php else : ?>
                                            <?php echo esc_html($event['text']); ?>
                                        <?php endif; ?>
                                    </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                <?php endif; ?>
            </div>
        </div>
        <div class="column-right">

            <?php
            $blocks = carbon_get_the_post_meta('right_blocks');

            if ($blocks) :
            ?>

                <div class="right-blocks">

                    <?php foreach ($blocks as $block) : ?>

                        <div class="right-block-item">
                            <div class="item-left">
                                <div class="item-1">
                                    <?php if (!empty($block['title'])) : ?>
                                        <h3 class="title">
                                            <?php echo esc_html($block['title']); ?>
                                        </h3>
                                    <?php endif; ?>

                                    <?php if (!empty($block['subtitle'])) : ?>
                                        <div class="subtitle">
                                            <?php echo esc_html($block['subtitle']); ?>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (!empty($block['description'])) : ?>
                                        <div class="description">
                                            <?php echo apply_filters('the_content', $block['description']); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                

                                <?php if (!empty($block['link'])) : ?>
                                    <div class="link">
                                        <a href="<?php echo esc_url($block['link']); ?>">
                                         Ler mais →
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="item-right">
                                <?php if (!empty($block['image'])) : ?>
                                    <div class="image">
                                        <?php echo wp_get_attachment_image($block['image'], 'medium'); ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                        </div>

                    <?php endforeach; ?>

                </div>

            <?php endif; ?>

            </div>

    </div>
</div>

<?php get_footer(); ?>
