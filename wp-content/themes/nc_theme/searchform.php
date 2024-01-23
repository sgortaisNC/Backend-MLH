<form id="search" method="get" action="<?= get_home_url(); ?>">
    <label for="s">Rechercher :</label>
    <input type="text" name="s" id="s" value="<?= get_search_query(); ?>">
    <div class="d-none">
        <input type="checkbox" name="s_remember" id="s_remember" value="1">
        <label for="s_remember">Se souvenir de ma recherche</label>
    </div>
    <div class="d-none">
        <input type="checkbox" name="s_favorites" id="s_favorites" value="1" checked>
        <label for="s_favorites">Ajouter aux favoris</label>
    </div>
    <input type="submit" value="Rechercher">
</form>
