<?php
/**
 * @var array $items
 * @var string $default_image
 * @see NC_Carousel_Widget::render()
 */
?>

<div class="carousel">
    <?php if ( !empty($items) ) : ?>
        <?php foreach ( $items as $item ) : ?>
            <?php if ( !empty($item['image']['id']) ) : ?>
                <div>
                    <div class="item">
                        <img src="<?= wp_get_attachment_image_src($item['image']['id'], 'nc_elementor_carousel')[0]; ?>" alt="<?php the_title(); ?>">
                        <?php if ( !empty($item['legende']) ) : ?>
                            <span><?= $item['legende']; ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php else : ?>
        <div>
            <div class="item">
                <img src="<?= $default_image; ?>" alt="<?php the_title(); ?>">
                <span>Lorem ipsum</span>
            </div>
        </div>
    <?php endif; ?>
</div>
