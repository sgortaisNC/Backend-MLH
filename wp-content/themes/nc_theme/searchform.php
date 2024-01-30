<form class="global-search" action="<?= get_home_url(); ?>">
    <input title="Rechercher dans le site"
           name="s"
           type="text"
           placeholder="Rechercher dans le site"
           value="<?= get_search_query(); ?>">
    <input type="submit">
        <i class="fal fa-search"></i>
    </input>
    <i class="fal fa-circle-xmark" data-form></i>

    <div class="noField">
        <input type="checkbox" name="sr" value="1" />
        <label for="sr">Se souvenir de ma recherche</label>
    </div>
    <div class="noField">
        <input type="checkbox" name="sf" value="1" checked />
        <label for="sf">Ajouter aux favoris</label>
    </div>
</form>
