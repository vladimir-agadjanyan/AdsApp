import L from 'leaflet';

const mapElement = document.getElementById('map');

if (mapElement) {
    // Границы Узбекистана
    const uzbekistanBounds = [
        [37.17, 55.99], // Юго-запад
        [45.59, 73.15], // Северо-восток
    ];

    const map = L.map(mapElement, {
        minZoom: 6,
        maxZoom: 18,
        scrollWheelZoom: false,
        maxBounds: uzbekistanBounds,
        maxBoundsViscosity: 1.0,
    });

    map.fitBounds(uzbekistanBounds);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors',
    }).addTo(map);

    // Тестовый маркер
    L.marker([41.3111, 69.2797])
        .addTo(map)
        .bindPopup('Ташкент');
}