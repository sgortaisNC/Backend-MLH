<?php
/**
 * @see nc_post_list()
 * @var string $max_num_pages
 * @var array $posts
 *
 */
?>

<h1><?php the_title() ?></h1>

<?php foreach ($posts as $post) : ?>
    <article>
        <h2><?= $post['titre'] ?></h2>
        <p><?= $post['date'] ?></p>
        <a href="<?= $post['lien'] ?>">Lire la suite</a>
    </article>
<?php endforeach; ?>

<?= nc_pagination($max_num_pages) ?>


