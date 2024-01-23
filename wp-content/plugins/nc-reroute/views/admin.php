<?php
/**
 * @TODO Externaliser CSS & JS
 */
?>
<style>
    #emailField {
        width: 80%;
    }
</style>

<h1>
    <?= get_admin_page_title() ?>
</h1>
<form action="" id="saveMail">


    <label>
        Mail(s) de redirection :
        <input type="text" name="email" id="emailField" value="<?= get_option('nc_reroute') ?>">
    </label>
    <button type="submit" class="button button-primary">Sauvegarder</button>
    <br>
    <small>255 caractères max. ( <span id="resteCaracteres"></span> / 255)</small>
</form>
<div style="display: none" class="nc_reroute updated"><p>Configuration sauvegardée</p></div>
<div style="display: none" class="nc_reroute error"><p>Erreur, il y a baleineau sous gravillons</p></div>

<script>
    const $ = jQuery;
    const restDOM = $('#resteCaracteres');
    const emailDOM = $('#emailField');
    const updatedDOM = $('.nc_reroute.updated');
    const errorDOM = $('.nc_reroute.error');
    const formDOM = $("#saveMail");

    function calcRest() {
        restDOM.text(255 - emailDOM.val().length)
    }

    emailDOM.keyup(function () {
        calcRest();
    });

    calcRest();

    formDOM.submit(function (e) {
        e.preventDefault();
        $.post(ajaxurl, {
            'action': 'saveRerouteMail',
            "str": emailDOM.val()
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
