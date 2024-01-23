<?php global $REDUX;
$config = $REDUX; ?>

Bonjour, <br>

<?= $nom ?> souhaite vous partager un lien depuis le site <?= get_option('blogname') ?> : <?= stripslashes($lien) ?>
