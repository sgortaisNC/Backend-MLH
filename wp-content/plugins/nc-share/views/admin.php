<?php
$type = [
    'ncshare_fb' => [
        'titre' => 'Facebook',
        'value' => get_option('ncshare_fb'),
    ],
    'ncshare_twitter' => [
        'titre' => 'Twitter',
        'value' => get_option('ncshare_twitter'),
    ],
    'ncshare_linkedin' => [
        'titre' => 'LinkedIn',
        'value' => get_option('ncshare_linkedin'),
    ],
    'ncshare_pinterest' => [
        'titre' => 'Pinterest',
        'value' => get_option('ncshare_pinterest'),
    ],
    'ncshare_mail' => [
        'titre' => 'Mail',
        'value' => get_option('ncshare_mail'),
    ],
    'ncshare_print' => [
        'titre' => 'Impression',
        'value' => get_option('ncshare_print'),
    ],
];
?>

<style>
    #ncshareForm label span{
        display: inline-block;
        width: 150px;
        margin-bottom: 20px;
        font-size: 20px;
    }
</style>

<h1><?= get_admin_page_title() ?></h1>
<h2>Sélectionnez vos plateforme de partages : </h2>
<div id="ncshareForm">
<?php foreach ($type as $key => $data) : ?>
    <label>
        <span><?= $data['titre'] ?></span>
        <input type="checkbox" class="ncshare_checkbox" id="<?= $key ?>" <?= '1' === $data['value'] ? 'checked="checked"' : null ?>>
    </label>
    <br>
<?php endforeach; ?>
</div>

<div style="display: none" class="nc_reroute updated"><p>Configuration sauvegardée</p></div>
<div style="display: none" class="nc_reroute error"><p>Erreur, il y a baleineau sous gravillons</p></div>

<script>
    const $ = jQuery;
    const NCShareChechoxesDOM = $(".ncshare_checkbox");
    const updatedDOM = $('.nc_reroute.updated');
    const errorDOM = $('.nc_reroute.error');

    NCShareChechoxesDOM.change(function(e){
        e.preventDefault();
        let that = $(this);
        console.log($(this));
        let val = 0;
        if (that[0].checked){
            val = 1;
        }
        $.post(ajaxurl, {
            'action': 'saveShareOptions',
            'field': that.attr('id'),
            'val': val
        }).done(function (response) {
            console.log(response)
            if (response.success) {
                updatedDOM.fadeIn();
                setTimeout(function () {
                    updatedDOM.fadeOut();
                }, 2500);
            } else {
                errorDOM.fadeIn();
                setTimeout(function () {
                    errorDOM.fadeOut();
                }, 2500);
            }
        });
        return false;
    });

</script>

