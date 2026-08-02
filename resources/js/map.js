import L from 'leaflet';

/*
|--------------------------------------------------------------------------
| Uzbekistan bounds
|--------------------------------------------------------------------------
*/

const uzbekistanBounds = [
    [37.17, 55.99],
    [45.59, 73.15],
];

/*
|--------------------------------------------------------------------------
| Dashboard map
|--------------------------------------------------------------------------
*/

function initDashboardMap() {
    const element = document.getElementById('map');

    if (!element || element._leaflet_id) {
        return;
    }

    const map = L.map(element, {
        minZoom: 5,
        maxZoom: 18,
        zoomSnap: 0.25,
        zoomDelta: 0.25,
        scrollWheelZoom: false,
        maxBounds: uzbekistanBounds,
        maxBoundsViscosity: 1.0,
    });

    L.tileLayer(
        'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
        {
            attribution: '&copy; OpenStreetMap contributors',
        }
    ).addTo(map);

    let objects = [];

    try {
        objects = JSON.parse(
            element.dataset.objects ?? '[]'
        );
    } catch (error) {
        console.error(
            'Ошибка загрузки объектов Dashboard:',
            error
        );
    }

    objects.forEach((object) => {
        const latitude = Number(object.latitude);
        const longitude = Number(object.longitude);

        if (
            !Number.isFinite(latitude) ||
            !Number.isFinite(longitude)
        ) {
            return;
        }

        L.marker([
            latitude,
            longitude,
        ])
            .bindPopup(createPopup(object))
            .addTo(map);
    });

    /*
    |--------------------------------------------------------------------------
    | Dashboard показывает весь Узбекистан
    |--------------------------------------------------------------------------
    */

    map.fitBounds(uzbekistanBounds, {
        padding: [30, 30],
    });

    /*
    |--------------------------------------------------------------------------
    | Делаем обзор немного шире
    |--------------------------------------------------------------------------
    */

    map.setZoom(map.getZoom() - 0.25);

    /*
    |--------------------------------------------------------------------------
    | После отрисовки корректируем размер Leaflet
    |--------------------------------------------------------------------------
    */

    setTimeout(() => {
        map.invalidateSize();
    }, 100);
}

/*
|--------------------------------------------------------------------------
| Objects map
|--------------------------------------------------------------------------
*/

let objectsMap = null;
let objectsMarkers = null;

function escapeHtml(value) {
    if (value === null || value === undefined) {
        return '';
    }

    return String(value)
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');
}

function createPopup(object) {
    return `
        <div class="map-popup">

            <div class="fw-semibold mb-2">
                ${escapeHtml(object.name)}
            </div>

            <div class="small mb-1">
                <strong>Контрагент:</strong>
                ${escapeHtml(object.counterparty)}
            </div>

            <div class="small mb-1">
                <strong>Регион:</strong>
                ${escapeHtml(object.region)}
            </div>

            <div class="small mb-1">
                <strong>Город:</strong>
                ${escapeHtml(object.city)}
            </div>

            <div class="small mb-1">
                <strong>Тип:</strong>
                ${escapeHtml(object.type)}
            </div>

            <div class="small mb-3">
                <strong>Статус:</strong>
                ${escapeHtml(object.status)}
            </div>

            <a
                href="${escapeHtml(object.url)}"
                class="btn btn-sm btn-primary"
            >
                Открыть объект
            </a>

        </div>
    `;
}

function renderObjects(objects) {
    if (!objectsMap || !objectsMarkers) {
        return;
    }

    /*
    |--------------------------------------------------------------------------
    | Удаляем ВСЕ старые маркеры
    |--------------------------------------------------------------------------
    */

    objectsMarkers.clearLayers();

    /*
    |--------------------------------------------------------------------------
    | Добавляем новые
    |--------------------------------------------------------------------------
    */

    objects.forEach((object) => {
        const latitude = Number(object.latitude);
        const longitude = Number(object.longitude);

        if (
            !Number.isFinite(latitude) ||
            !Number.isFinite(longitude)
        ) {
            return;
        }

        L.marker([
            latitude,
            longitude,
        ])
            .bindPopup(createPopup(object))
            .addTo(objectsMarkers);
    });

    /*
    |--------------------------------------------------------------------------
    | Подстраиваем карту под найденные объекты
    |--------------------------------------------------------------------------
    */

    const layers = objectsMarkers.getLayers();

    if (layers.length > 0) {
        const bounds = L.featureGroup(layers).getBounds();

        objectsMap.fitBounds(bounds, {
            padding: [40, 40],
            maxZoom: 13,
        });
    } else {
        objectsMap.fitBounds(uzbekistanBounds);
    }
}

function initObjectsMap() {
    const element = document.getElementById('objects-map');

    if (!element) {
        return;
    }

    /*
    |--------------------------------------------------------------------------
    | Карта уже существует
    |--------------------------------------------------------------------------
    */

    if (objectsMap) {
        return;
    }

    /*
    |--------------------------------------------------------------------------
    | Создаем карту
    |--------------------------------------------------------------------------
    */

    objectsMap = L.map(element, {
        minZoom: 6,
        maxZoom: 18,
        scrollWheelZoom: true,
        maxBounds: uzbekistanBounds,
        maxBoundsViscosity: 1.0,
    });

    L.tileLayer(
        'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
        {
            attribution: '&copy; OpenStreetMap contributors',
        }
    ).addTo(objectsMap);

    /*
    |--------------------------------------------------------------------------
    | Один постоянный LayerGroup для всех маркеров
    |--------------------------------------------------------------------------
    */

    objectsMarkers = L.layerGroup();

    objectsMarkers.addTo(objectsMap);

    /*
    |--------------------------------------------------------------------------
    | Первоначальные объекты
    |--------------------------------------------------------------------------
    */

    let objects = [];

    try {
        objects = JSON.parse(
            element.dataset.objects ?? '[]'
        );
    } catch (error) {
        console.error(
            'Ошибка загрузки объектов карты:',
            error
        );
    }

    renderObjects(objects);
}

/*
|--------------------------------------------------------------------------
| Initialization
|--------------------------------------------------------------------------
*/

function initMaps() {
    initDashboardMap();
    initObjectsMap();
}

document.addEventListener(
    'DOMContentLoaded',
    initMaps
);

/*
|--------------------------------------------------------------------------
| Livewire
|--------------------------------------------------------------------------
*/

document.addEventListener('livewire:init', () => {

    Livewire.on(
        'map-objects-updated',
        (event) => {

            const objects = event.objects ?? [];

            console.log(
                'Обновление карты:',
                objects.length,
                'объектов'
            );

            renderObjects(objects);
        }
    );

});

/*
|--------------------------------------------------------------------------
| Livewire navigation
|--------------------------------------------------------------------------
*/

document.addEventListener(
    'livewire:navigated',
    () => {

        /*
         * При wire:navigate DOM страницы может быть заменен.
         */

        const element =
            document.getElementById('objects-map');

        if (
            element &&
            !element._leaflet_id
        ) {
            objectsMap = null;
            objectsMarkers = null;
        }

        initMaps();
    }
);