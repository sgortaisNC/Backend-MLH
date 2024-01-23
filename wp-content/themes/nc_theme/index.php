<?= get_header(); ?>
    <main>
        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
            <?= do_action('nc_content'); ?>
        <?php endwhile; endif; ?>
    </main>
<?= get_footer(); ?>
