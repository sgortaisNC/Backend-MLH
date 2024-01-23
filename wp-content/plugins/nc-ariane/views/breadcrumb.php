<div>
    <?php if ( !empty($breadcrumb['links']) ) : ?>
        <?php foreach ( $breadcrumb['links'] as $link ) : ?>
            <a href="<?= $link['url']; ?>" title="<?= $link['title']; ?>">
                <?= $link['title']; ?>
            </a>
        <?php endforeach; ?>
    <?php endif; ?>
    <?php if ( !empty($breadcrumb['current']) ) : ?>
        <?= $breadcrumb['current']; ?>
    <?php endif; ?>
</div>
