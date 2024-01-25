<?php
/**
 * @see nc_post_list()
 * @var string $max_num_pages
 * @var array $posts
 *
 */
?>

<h1><?php the_title() ?></h1>

<?php if(!empty($posts)) : ?>
    <?php foreach ($posts as $post) : ?>
        <article>
            <h2><?= $post['titre'] ?></h2>
            <img src="<?= $post['image'] ?>" alt="">
            <p><?= $post['date'] ?></p>
            <a href="<?= $post['lien'] ?>">Lire la suite</a>
        </article>
    <?php endforeach; ?>
<?php endif; ?>

<?php if (!empty($max_num_pages > 1)) : ?>
    <section class="pagination">
        <?php nc_pagination($max_num_pages); ?>
    </section>
<?php endif; ?>


