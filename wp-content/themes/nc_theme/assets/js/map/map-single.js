let carte = document.getElementById('map-single');

if (carte) {

    let map = L.map('map-single', {
        //dragging: !L.Browser.mobile,
        scrollWheelZoom: true
    }).setView([48.20, 3.28], 11);

    map.setView([48.20, 3.28], 11);
    map.scrollWheelZoom.disable();

    L.tileLayer('https://{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright" target="_blank">Contributeurs de OpenStreetMap</a>',
        subdomains: 'abc',
        //maxZoom: 16
    }).addTo(map);

    let e = JSON.parse(carte.dataset.carte);

    if (parseFloat(e.latitude) < 100 && parseFloat(e.longitude) < 100) {
        let marker = L.marker(
            [e.latitude, e.longitude],
            {
                icon: new L.icon({
                    iconUrl: '/wp-content/themes/nc_theme/assets/img/leaflet/marker-icon.png',
                    iconSize: [29, 39],
                    iconAnchor: [15, 39],
                    popupAnchor: [0, 0],
                }),
            }
        ).addTo(map);

        map.setView([e.latitude, e.longitude], 11);
    }
}
