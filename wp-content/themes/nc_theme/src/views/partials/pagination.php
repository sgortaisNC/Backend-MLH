<?php
/**
 * @var string $url
 * @var array $links
 * @var int $paged
 * @var int $max_num_pages
 * @see nc_pagination()
 */
?>

<div class="pagination">
    <ul>
        <?php if($paged != 1): ?>
            <li class="icon">
                <a href="<?= $url ?>">
                  <<#
                </a>
            </li>

            <li class="icon">
                <a href="<?= $url . 'pg='. ($paged - 1) ?>">
                    <#
                </a>
            </li>
        <?php endif; ?>

        <?php if ( ! in_array( 1, $links ) ) : ?>
            <li class="<?= 1 == $paged ? ' active' : '' ?>">
                <a href="<?= $url . 'pg=1' ?>">
                    1
                </a>
            </li>

            <?php if( ! in_array( 2, $links ) ) : ?>
                <li>…</li>
            <?php endif; ?>
        <?php endif; ?>

        <?php sort( $links );
        foreach ( $links as $link ) : ?>
            <li class="<?= $paged == $link ? ' active' : '' ?>">
                <a href="<?= $url . 'pg='. $link ?>">
                    <?= $link ?>
                </a>
            </li>
        <?php endforeach; ?>

        <?php if ( ! in_array( $max_num_pages, $links ) ): ?>
            <?php if ( ! in_array( $max_num_pages - 1, $links ) ) : ?>
                <li>…</li>
            <?php endif; ?>

            <li class="<?= $paged == $max_num_pages ? ' active' : '' ?>">
                <a href="<?= $url . 'pg='. $max_num_pages ?>">
                    <?= $max_num_pages ?>
                </a>
            </li>
        <?php endif; ?>

        <?php if ( $paged != $max_num_pages ): ?>
            <li class="icon">
                <a href="<?= $url . 'pg='. ($paged + 1) ?>">#></a>
            </li>

            <li class="icon">
                <a href="<?= $url . 'pg='. $max_num_pages ?>">
                    #>>
                </a>
            </li>
        <?php endif ?>
    </ul>
</div>
