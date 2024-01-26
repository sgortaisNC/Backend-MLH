
    let carte = document.getElementById('map');
    let events = document.getElementById('list-events');
    let btnEvents = document.getElementById('btn-list-events');
    let btnMap = document.getElementById('btn-map');
    let pagination = document.querySelector('.pagination');


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


        if (btnEvents && btnMap) {
            btnEvents.addEventListener('click', function(event) {
                event.preventDefault();

                setCookie('defaultView', 'events', 7);

                events.style.display = 'grid';
                carte.style.display = 'none';

                if(pagination){
                    pagination.style.display = 'block';
                }

                btnEvents.classList.add('btn--vert');
                btnEvents.classList.remove('btn--outline');
                btnMap.classList.remove('btn--vert');
                btnMap.classList.add('btn--outline');
            });

            btnMap.addEventListener('click', function(event) {
                event.preventDefault();

                setCookie('defaultView', 'map', 7);

                events.style.display = 'none';
                carte.style.display = 'block';

                if(pagination){
                    pagination.style.display = 'none';
                }

                btnMap.classList.add('btn--vert');
                btnMap.classList.remove('btn--outline');
                btnEvents.classList.remove('btn--vert');
                btnEvents.classList.add('btn--outline');

            });
        }
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
