
    let carte = document.getElementById('map');

    if (carte) {

        const currentUrl = new URL(window.location.href);
        currentUrl.searchParams.set('map', 'true');
        const url = '/ajax/map_locations.php' + currentUrl.search;

        let map = L.map('map', {
            dragging: !L.Browser.mobile,
            scrollWheelZoom: true
        }).setView([48.85, 2.29], 7);

        map.scrollWheelZoom.disable();

        L.tileLayer('http://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright" target="_blank">Contributeurs de OpenStreetMap</a>',
            subdomains: 'abc',
        }).addTo(map);

        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur');
                }
                return response.json();
            })
            .then(data => {

                handleData(data, map);
            })
            .catch(error => {
                console.error(error);
            });

    }


function handleData(data, map) {
    let markers = L.markerClusterGroup();
    data.forEach(function(data) {
        if (data.latitude !== undefined && data.longitude !== undefined) {
            let marker = L.marker(
                [parseFloat(data.latitude), parseFloat(data.longitude)],
                {
                    icon: new L.icon({
                        iconUrl: '/wp-content/themes/nc_theme/assets/js/vendor/leaflet/images/marker-icon.png',
                        iconSize: [29, 39],
                        iconAnchor: [15, 39],
                        popupAnchor: [0, -30],
                    }),
                }
            );

            let popupContent = data.popup;
            marker.bindPopup(popupContent);
            markers.addLayer(marker);
        }
    });

    map.addLayer(markers);

    if (markers.getLayers().length > 0) {
        map.fitBounds(markers.getBounds());
    }
}
