jQuery(document).ready(function ($) {
    //Popup - Default config
    const popupCenter = function (url, title, width, height) {
        const popupWidth = width || 640;
        const popupHeight = height || 320;
        const windowLeft = window.screenLeft || window.screenX;
        const windowTop = window.screenTop || window.screenY;
        const windowWidth = window.innerWidth || document.documentElement.clientWidth;
        const windowHeight = window.innerHeight || document.documentElement.clientHeight;
        const popupLeft = windowLeft + windowWidth / 2 - popupWidth / 2;
        const popupTop = windowTop + windowHeight / 2 - popupHeight / 2;
        const popup = window.open(url, title, 'scrollbars=yes, width=' + popupWidth + ', height=' + popupHeight + ', top=' + popupTop + ', left=' + popupLeft);
        popup.focus();
        return true;
    };

    //Récupération des informations de la page en cours de consultation
    const url = window.location.href;
    const title = document.title;

    //Bouton Twitter
    $('#share-twitter').on('click', function (e) {
        e.preventDefault();
        let shareUrl = "https://twitter.com/intent/tweet?text=" + encodeURIComponent(title) +
            "&url=" + encodeURIComponent(url);
        popupCenter(shareUrl, "Partager sur Twitter");
    });

    //Bouton Facebook
    $('#share-facebook').on('click', function (e) {
        e.preventDefault();
        let shareUrl = "https://www.facebook.com/sharer/sharer.php?u=" + encodeURIComponent(url);
        popupCenter(shareUrl, "Partager sur Facebook");
    });

    //Bouton LinkedIn
    $('#share-linkedin').on('click', function (e) {
        e.preventDefault();
        let shareUrl = "https://www.linkedin.com/shareArticle?url=" + encodeURIComponent(url);
        popupCenter(shareUrl, "Partager sur Linkedin");
    });

    //Bouton Pinterest
    $('#share-pinterest').on('click', function (e) {
        e.preventDefault();
        let shareUrl = "http://www.pinterest.com/pin/create/button/?url=" + encodeURIComponent(url) +
            "&description=" + encodeURIComponent(document.title) +
            "&media=" + encodeURIComponent(url);
        popupCenter(shareUrl, "Partager sur Pinterest");
    });

    //Bouton Email

    $('#share-email-send').on('click', function (e) {
        e.preventDefault();
        $('#share-email-alerte .alert').hide();
        let titre = title.split('|');
        $.post(ajax.url,{
            'action': 'sendmailShare',
            'name': $('#share-email-body #name').val(),
            'email': $('#share-email-body #email').val(),
            'link': '<a href="' + url + '" title="' + titre[0] + '">' + titre[0] + '</a>',
        }).done(function (response) {
            $('#share-email-body #name').val('');
            $('#share-email-body #email').val('');
            if (response.success === true) {
                $('#share-email-alerte .alert-success').show();
            } else {
                $('#share-email-alerte .alert-warning').show();
            }
        });
    });

    //Bouton Print
    $('#share-print').on('click', function (e) {
        e.preventDefault();
        window.print();
    });

});


