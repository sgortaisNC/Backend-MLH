<?php
// Params
nc_sanitize_url($_GET);
$search = stripslashes($_GET["s"]);

$go_out = true;
if (empty($_GET['sr']) && !empty($_GET['sf']) && ($_GET['sf'] == 1)) {
    $go_out = false;
}
if ($go_out !== false) {
    echo 'robot';
    exit;
}

// Query
$args = [
    'posts_per_page' => -1,
    'post_status' => ['publish'],
];

$types = "";
if (!empty($_GET['type'])) {
    $args['post_type'] = [$_GET['type']];
    $types = $_GET['type'];
}

$s = "";
if (!empty($search)) {
    $args['s'] = $search;
    $s = $search;
    $swp_query = new SWP_Query($args);
} else {
    $args['s'] = '';

    if (empty($_GET['type'])) {
        $args['post_type'] = [
            'page',
            'offre_emploi',
            'bien_louer',
            'post',
        ];
    }

    $swp_query = new WP_Query($args);
}

//Pagination
$nb_per_page = 15;
$nb_delta = 2;

$nb_results = $swp_query->found_posts;
$page_en_cours = empty($_GET['page']) ? 1 : $_GET['page'];
$nb_pages = ceil($nb_results / $nb_per_page);

if ($page_en_cours < 1)
    $page_en_cours = 1;

if ($page_en_cours > $nb_pages)
    $page_en_cours = $nb_pages;

$nb_start = max(1, $page_en_cours - $nb_delta);
$nb_end = min($nb_pages, $page_en_cours + $nb_delta);

$results = [];
if (!empty($swp_query->posts)) {
    $results = array_slice($swp_query->posts, ($page_en_cours - 1) * $nb_per_page, $nb_per_page);
}
?>

<?= get_header(); ?>

<div class="container inner no-sidebar">
    <main>
        <h1>Votre recherche</h1>

        <div class="filtres">
            <div class="filtres__heading">
                <span><?= $nb_results ?></span> résultat<?= ($nb_results > 1) ? 's' : null ?>
                <?= (!empty($search)) ? ' pour "' . $search . '"' : null ?>
            </div>

            <form class="searchForm" method="get" action="<?= get_home_url(); ?>">
                <div>
                    <label for="s">Mots-clés</label>
                    <input type="text" name="s" value="<?= !empty($search) ? $search : null; ?>">
                </div>

                <div class="noField">
                    <input type="checkbox" name="sr" value="1"/>
                    <label for="sr">Se souvenir de ma recherche</label>
                </div>

                <div class="noField">
                    <input type="checkbox" name="sf" value="1" checked/>
                    <label for="sf">Ajouter aux favoris</label>
                </div>

                <div class="actions">
                    <button type="submit" class="btn">Rechercher</button>

                    <a href="<?= get_home_url(); ?>?s=&sf=1" class="btn btn--outline">
                        Réinitialiser
                    </a>
                </div>
            </form>
        </div>

        <div class="liste-recherche">
            <?php if (!empty($results)): ?>
                <?php foreach ($results as $id => $post) : setup_postdata($post); ?>
                    <article class="global-teaser">
                        <div class="global-teaser__title"><?= get_the_title() ?></div>
                        <p class="global-teaser__chapo">
                            <?php if (get_the_excerpt()) : ?>
                                <?= str_ireplace($search, '<mark>' . $search . '</mark>', nc_substr(get_the_excerpt(), 300)); ?>
                            <?php else : ?>
                                <?= str_ireplace($search, '<mark>' . $search . '</mark>', nc_substr(get_the_content(), 300)); ?>
                            <?php endif; ?>
                        </p>
                        <a href="<?= get_permalink() ?>" class="readmore">Lire la suite <i
                                    class="fas fa-arrow-right"></i></a>
                    </article>
                <?php endforeach; ?>
            <?php else : ?>
                <div class="wysiwyg mt-5">
                    <h4>
                        Aucun contenu ne correspond à votre recherche.
                    </h4>

                    <a href="<?= get_home_url(); ?>?s=&sf=1">
                        Réinitialiser votre recherche
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <?php if (!empty($swp_query->posts)): ?>
            <?php if ($nb_pages > 1): ?>
                <div class="pagination">
                    <ul>
                        <?php if ($page_en_cours > 1) : ?>
                            <li class="icon">
                                <a href="?s=<?= $search ?>&page=1&sf=1">
                                    <i class="fas fa-angles-left"></i>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if ($page_en_cours > 1) : ?>
                            <li class="icon">
                                <a href="?s=<?= $search ?>&page=<?= $page_en_cours - 1 ?>&sf=1">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php for ($i = $nb_start; $i <= $nb_end; $i++): ?>
                            <li <?= ($i == $page_en_cours) ? 'class="active"' : null ?>>
                                <?php if ($i == $page_en_cours) : ?>
                                    <a href="#"><?= $i ?></a>
                                <?php else : ?>
                                    <a href="?s=<?= $search ?>&page=<?= $i ?>&sf=1"><?= $i ?></a>
                                <?php endif; ?>
                            </li>
                        <?php endfor; ?>

                        <?php if ($page_en_cours < $nb_pages) : ?>
                            <li class="icon">
                                <a href="?s=<?= $search ?>&page=<?= $page_en_cours + 1 ?>&sf=1">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if ($page_en_cours != $nb_pages) : ?>
                            <li class="icon">
                                <a href="?s=<?= $search ?>&page=<?= $nb_pages ?>&sf=1">
                                    <i class="fas fa-angles-right"></i>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </main>
</div>

<?= get_footer(); ?>
