@extends('layouts.master')

@section('title', 'Airports')
@section('page-title', 'Papua New Guinea Airports')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.fullscreen/Control.FullScreen.css" />

<style>
    #map {
        height: 700px;
    }
    .filter-container {
        margin-bottom: 20px;
        background-color: #f8f9fa;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,.1);
    }
    .form-check-scrollable {
        max-height: 150px;
        overflow-y: auto;
        border: 1px solid #ddd;
        border-radius: 5px;
        padding: 10px;
    }
    .total-airports {
        background: white;
        padding: 8px 12px;
        border-radius: 8px;
        box-shadow: 0 0 6px rgba(0,0,0,0.2);
        font-weight: bold;
    }

    .select2-container .select2-selection--single {
        height: 45px;
        padding: 6px 12px;
        border: 1px solid #ced4da;
        border-radius: 10px;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 30px;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 45px;
        right: 10px;
    }

    .p-modal{
        text-align:justify;
    }

     .btn-danger{
            background-color:#395272;
            border-color: transparent;
        }

        .btn-danger:hover{
            background-color:#5686c3;
            border-color: transparent;
        }

        .btn.active {
            background-color: #5686c3 !important;
            border-color: transparent !important;
            color: #fff !important;
        }

        .p-3{
            padding: 10px !important;
            margin: 0 3px;
        }

        .btn-outline-danger{
            color: #FFFFFF;
            background-color:#395272;
            border-color: transparent;
        }

        .btn-outline-danger:hover{
            background-color:#5686c3;
            border-color: transparent;
        }

        .fa,
        .fab,
        .fad,
        .fal,
        .far,
        .fas {
            color: #346abb;
        }

        .card-header{
            padding: 0.25rem 1.25rem;
            color: #3c66b5;
            font-weight: bold;
        }

        .mb-4{
            margin-bottom: 0.5rem !important;
        }
</style>
@endpush

@section('conten')

<div class="card">

    <div class="d-flex justify-content-end p-3" style="background-color: #dfeaf1;">

        <div class="d-flex gap-2 mt-2">

            <a href="{{ url('airports') }}" class="btn btn-danger d-flex flex-column align-items-center p-3 {{ request()->is('airports') ? 'active' : '' }}">
                <i class="bi bi-airplane fs-3"></i>
                <small>Airports</small>
            </a>

            <a href="{{ url('hospital') }}" class="btn btn-danger d-flex flex-column align-items-center p-3 {{ request()->is('hospital') ? 'active' : '' }}">
             <img src="{{ asset('images/icon-medical.png') }}" style="width: 24px; height: 24px;">
                <small>Medical</small>
            </a>

            <a href="{{ url('aircharter') }}" class="btn btn-danger d-flex flex-column align-items-center p-3 {{ request()->is('aircharter') ? 'active' : '' }}">
                  <img src="{{ asset('images/icon-air-charter.png') }}" style="width: 48px; height: 24px;">
                <small>Air Charter</small>
            </a>

            <a href="{{ url('embassiees') }}" class="btn btn-danger d-flex flex-column align-items-center p-3 {{ request()->is('embassiees') ? 'active' : '' }}">
            <img src="{{ asset('images/icon-embassy.png') }}" style="width: 24px; height: 24px;">
                <small>Embassies</small>
            </a>

        </div>
    </div>

    <div class="filter-container p-3">
        <form id="filterForm">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="name" class="form-label">Airport Name</label>
                    <select id="name" class="form-select select2-search" name="name">
                        <option value="">🔍 All Airports</option>
                        @foreach($airportNames as $name)
                            <option value="{{ $name }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="category" class="form-label">Category</label>
                    <select id="category" class="form-select select2-search" name="category">
                        <option value="">🔍 All Categories</option>
                        @foreach($airportCategories as $category)
                            <option value="{{ $category }}">{{ $category }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="location" class="form-label">Location</label>
                    <select id="location" class="form-select select2-search" name="location">
                        <option value="">🔍 All Locations</option>
                        @foreach($airportLocations as $location)
                            <option value="{{ $location }}">{{ $location }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="radiusRange" class="form-label">Search in radius <span id="radiusValue">0</span> kilometers</label>
                    <input type="range" id="radiusRange" name="radius" class="form-control" min="0" max="400" value="0">
                </div>

                 <div class="col-md-10 mt-2">
                    <label class="form-label d-flex align-items-center" style="cursor: pointer;" data-bs-toggle="collapse" data-bs-target="#provinceCollapse" aria-expanded="false" aria-controls="provinceCollapse">
                        <span class="me-1">Province</span>
                        <i class="bi bi-chevron-down" id="provinceToggleIcon"></i>
                    </label>

                    <div class="collapse" id="provinceCollapse">
                        <div class="form-check-scrollable" style="max-height: 200px; overflow-y: auto; border: 1px solid #ccc; padding: 10px; border-radius: 5px;">
                            @foreach ($provinces as $province)
                                <div class="form-check">
                                    <input class="form-check-input province-checkbox" type="checkbox" name="provinces[]" value="{{ $province->id }}" id="province_{{ $province->id }}">
                                    <label class="form-check-label" for="province_{{ $province->id }}">
                                        {{ $province->provinces_region }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="col-md-2 mt-2">
                    <button type="submit" class="btn btn-primary">Apply Filter</button>
                    <button type="button" id="resetFilter" class="btn btn-secondary">Reset Filter</button>
                </div>
            </div>
        </form>
    </div>

    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center gap-3 my-2">

            <div class="d-flex align-items-center gap-2">
                <button class="btn btn-link p-0 fw-bold text-decoration-underline text-dark" data-bs-toggle="modal" data-bs-target="#disclaimerModal">
                    <i class="bi bi-info-circle text-primary fs-5"></i>
                    Disclaimer
                </button>
            </div>

            <div class="d-flex align-items-center gap-3">
                <span class="fw-bold me-2">Map Legend:</span>

                <button class="btn p-1" data-bs-toggle="modal" data-bs-target="#level6Modal">
                    <img src="https://pg.concordreview.com/wp-content/uploads/2024/10/International-Airport.png" style="width:30px; height:30px;">
                    <small>International</small>
                </button>

                <button class="btn p-1" data-bs-toggle="modal" data-bs-target="#level5Modal">
                    <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/regional-airport.png" style="width:30px; height:30px;">
                    <small>Domestic</small>
                </button>

                <button class="btn p-1" data-bs-toggle="modal" data-bs-target="#level4Modal">
                    <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/regional-domestic-airport.png" style="width:30px; height:30px;">
                    <small>Regional Domestic</small>
                </button>

                <button class="btn p-1" data-bs-toggle="modal" data-bs-target="#level3Modal">
                    <img src="https://pg.concordreview.com/wp-content/uploads/2024/10/military-airport-red.png" style="width:30px; height:30px;">
                    <small>Military</small>
                </button>

                <button class="btn p-1" data-bs-toggle="modal" data-bs-target="#level2Modal">
                    <img src="https://pg.concordreview.com/wp-content/uploads/2024/10/civil-military-airport.png" style="width:30px; height:30px;">
                    <small>Combined (Civil - Military)</small>
                </button>

                <button class="btn p-1" data-bs-toggle="modal" data-bs-target="#level1Modal">
                    <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/private-airport.png" style="width:30px; height:30px;">
                    <small>Private</small>
                </button>
            </div>

        </div>
    </div>
</div>


<div class="modal fade" id="disclaimerModal" tabindex="-1" aria-labelledby="disclaimerLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="disclaimerLabel">Disclaimer</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
       <p class="p-modal">Every attempt has been made to ensure the completeness and accuracy of the most updated information and data available. Clients are advised, however, that provided information, and data is subject to change.</p>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="level1Modal" tabindex="-1" aria-labelledby="disclaimerLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <div class="d-flex align-items-center">
             <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/private-airport.png" style="width:30px; height:30px;">
            <h5 class="modal-title" id="disclaimerLabel">Private Airfield</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="p-modal">Also known as private airfields or airstrips are primarily used for general and private aviation are owned by private individuals, groups, corporations, or organizations operated for their exclusive use that may include limited access for authorized personnel by the owner or manager. Owners are responsible to ensure safe operation, maintenance, repair, and control of who can use the facilities. Typically, they are not open to the public or provide scheduled commercial airline services and cater to private pilots, business aviation, and sometimes small charter operations. Services may be provided if authorized by the appropriate regulatory authority.</p>

        <p class="p-modal">A large majority of private airports are grass or dirt strip fields without services or facilities, they may feature amenities such as hangars, fueling facilities, maintenance services, and ground transportation options tailored to the needs of their owners or users. Private airports are not subject to the same level of regulatory oversight as public airports, but must still comply with applicable aviation regulations, safety standards, and environmental requirements. In the event of an emergency, landing at a private airport is authorized without any prior approval and should be done if landing anywhere else compromises the safety of the aircraft, crew, passengers, or cargo.</p>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="level2Modal" tabindex="-1" aria-labelledby="disclaimerLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <div class="d-flex align-items-center">
            <img src="https://pg.concordreview.com/wp-content/uploads/2024/10/civil-military-airport.png" style="width:30px; height:30px;">
            <h5 class="modal-title" id="disclaimerLabel">Combined Airfield</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="p-modal">Also called "joint-use airport," are used by both civilian and military aircraft, where a formal agreement exists between the military and a local government agency allowing shared access to infrastructure and facilities, typically with separate passenger terminals and designated operating areas, airspace allocation, and aircraft scheduling. Features can include aircraft maintenance, air traffic control, communications, emergency response, and fuel storage.</p>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="level3Modal" tabindex="-1" aria-labelledby="disclaimerLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <div class="d-flex align-items-center">
             <img src="https://pg.concordreview.com/wp-content/uploads/2024/10/military-airport-red.png" style="width:30px; height:30px;">
            <h5 class="modal-title" id="disclaimerLabel">Military Airfield</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="p-modal">Facilities where military aircraft operate, also known as a military airport, airbase, or air station. Features include aircraft maintenance, air traffic control, communications, emergency response, fuel and weapon storage, defensive systems, aircraft shelters, and personnel facilities.</p>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="level4Modal" tabindex="-1" aria-labelledby="disclaimerLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <div class="d-flex align-items-center">
            <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/regional-domestic-airport.png" style="width:30px; height:30px;">
            <h5 class="modal-title" id="disclaimerLabel">Regional Domestic Airfield</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="p-modal">A small or remote regional domestic airfield usually located in a geographically isolated area, far from major population centers, often with difficult terrain or vast distances from other airports with limited passenger traffic. May have shorter runways, basic facilities, and limited amenities, and basic infrastructure, serving primarily local communities providing access to essential services like medical transport or regional travel, rather than large-scale commercial flights.</p>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="level5Modal" tabindex="-1" aria-labelledby="disclaimerLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <div class="d-flex align-items-center">
            <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/regional-airport.png" style="width:30px; height:30px;">
            <h5 class="modal-title" id="disclaimerLabel">Domestic Airfield</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="p-modal">Exclusively manages flights that originate and end within the same country, does not have international customs or border control facilities. Airport often has smaller and shorter runways, suitable for smaller regional aircraft used on domestic routes, and cannot support larger haul aircraft having less developed support services. Features can include aircraft maintenance, air traffic control, communications, emergency response, and fuel storage.</p>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="level6Modal" tabindex="-1" aria-labelledby="disclaimerLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <div class="d-flex align-items-center">
            <img src="https://pg.concordreview.com/wp-content/uploads/2024/10/International-Airport.png" style="width:30px; height:30px;">
            <h5 class="modal-title" id="disclaimerLabel">International Airfield</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="p-modal">Meet standards set by the International Air Transport Association (IATA) and the International Civil Aviation Organization (ICAO), facilitate transnational travel managing flights between countries, have customs and border control facilities to manage passengers and cargo, and may have dedicated terminals for domestic and international flights. International airports have longer runways to accommodate larger, heavier aircraft, are often a main hub for air traffic, and can serve as a base for larger airlines. Features can include aircraft maintenance, air traffic control, communications, emergency response, and fuel storage</p>
      </div>
    </div>
  </div>
</div>


    <div id="map"></div>
</div>

@endsection

@push('service')
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://unpkg.com/leaflet.fullscreen/Control.FullScreen.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    const provinceCollapse = document.getElementById('provinceCollapse');
    const icon = document.getElementById('provinceToggleIcon');

    provinceCollapse.addEventListener('show.bs.collapse', () => {
        icon.classList.remove('bi-chevron-down');
        icon.classList.add('bi-chevron-up');
    });

    provinceCollapse.addEventListener('hide.bs.collapse', () => {
        icon.classList.remove('bi-chevron-up');
        icon.classList.add('bi-chevron-down');
    });
</script>

<script>

    const map = L.map('map', {
        fullscreenControl: true
    }).setView([-6.80188562253168, 144.0733101155011], 16);

    // --- Define Tile Layers ---
    // 1. OpenStreetMap (Default Street Map)
    const osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        maxZoom: 19,
    });

    // 2. Esri World Imagery (Satellite Map) - Recommended, no API key needed
    const satelliteLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community',
        maxZoom: 19,
    });

    // Add the default street map (OpenStreetMap) to the map initially
    osmLayer.addTo(map);

    // --- Layer Control for switching map types ---
    // Define the base layers that the user can switch between
    const baseLayers = {
        "Street Map": osmLayer,
        "Satelit Map": satelliteLayer
    };

    // Add the layer control to the map. This will appear in the top-right corner.
    L.control.layers(baseLayers).addTo(map);


    let airportMarkers = L.featureGroup().addTo(map);
    let centerMarker = null;
    let lastClickedAirport = null;
    let destinationMarker = null;
    let destinationCoordinates = null;
    let drawnPolygonGeoJSON = null;

    const drawnItems = new L.FeatureGroup().addTo(map);

    const drawControl = new L.Control.Draw({
        draw: {
            polygon: true,
            polyline: false,
            rectangle: false,
            circle: false,
            marker: false,
            circlemarker: false
        },
        edit: {
            featureGroup: drawnItems,
            remove: true
        }
    });
    map.addControl(drawControl);

    map.on(L.Draw.Event.CREATED, function (event) {
        const layer = event.layer;
        drawnItems.clearLayers();
        drawnItems.addLayer(layer);
        drawnPolygonGeoJSON = layer.toGeoJSON();
        applyFilters();
    });

    map.on(L.Draw.Event.EDITED, function (event) {
        const layers = event.layers;
        layers.eachLayer(function (layer) {
            drawnPolygonGeoJSON = layer.toGeoJSON();
        });
        applyFilters();
    });

    map.on(L.Draw.Event.DELETED, function (event) {
        drawnItems.clearLayers();
        drawnPolygonGeoJSON = null;
        applyFilters();
    });

    const totalControl = L.control({ position: 'topright' });
    totalControl.onAdd = function (map) {
        const div = L.DomUtil.create('div', 'total-airports');
        div.innerHTML = 'Loading airports count...';
        return div;
    };
    totalControl.addTo(map);

    const destinationIcon = L.icon({
        iconUrl: 'https://cdn-icons-png.flaticon.com/512/684/684908.png',
        iconSize: [32, 32],
        iconAnchor: [16, 32],
        popupAnchor: [0, -32]
    });

    function setDestination(lat, lng) {
        if (destinationMarker) {
            map.removeLayer(destinationMarker);
        }
        destinationCoordinates = [lat, lng];
        destinationMarker = L.marker(destinationCoordinates, { icon: destinationIcon }).addTo(map);
        destinationMarker.bindPopup("<b>Destination</b>").openPopup();

        if (airportMarkers.getLayers().length > 0) {
            const bounds = airportMarkers.getBounds().extend(destinationCoordinates);
            map.fitBounds(bounds, { padding: [50, 50] });
        } else {
            map.setView(destinationCoordinates, 10);
        }
    }

    function updateRadiusCircle() {
        const radius = parseInt(document.getElementById('radiusRange').value);
        const center = lastClickedAirport ?? map.getCenter();

        if (centerMarker) {
            map.removeLayer(centerMarker);
            centerMarker = null;
        }

        if (radius > 0) {
            centerMarker = L.circle(center, {
                color: 'red',
                fillColor: '#f03',
                fillOpacity: 0.3,
                radius: radius * 1000
            }).addTo(map);
        }
    }

    document.getElementById('radiusRange').addEventListener('input', function() {
        document.getElementById('radiusValue').textContent = this.value;
        updateRadiusCircle();
    });

    map.on('click', function(e) {
        lastClickedAirport = { lat: e.latlng.lat, lng: e.latlng.lng };
        updateRadiusCircle();
    });

    async function fetchAndDisplayAirports(filters = {}) {
        airportMarkers.clearLayers();

        const params = new URLSearchParams();
        Object.keys(filters).forEach(key => {
            if (Array.isArray(filters[key])) {
                filters[key].forEach(value => {
                    params.append(`${key}[]`, value);
                });
            } else {
                params.append(key, filters[key]);
            }
        });

        if (drawnPolygonGeoJSON) {
            params.append('polygon', JSON.stringify(drawnPolygonGeoJSON));
        }

         // --- Simpan parameter filter ke localStorage untuk persistensi ---
        localStorage.setItem('airportFilterParams', params.toString());
        if (drawnPolygonGeoJSON) {
            localStorage.setItem('airportDrawnPolygon', JSON.stringify(drawnPolygonGeoJSON));
        } else {
            localStorage.removeItem('airportDrawnPolygon');
        }
        if (lastClickedAirport) {
            localStorage.setItem('airportLastClickedCenter', JSON.stringify(lastClickedAirport));
        } else {
            localStorage.removeItem('airportLastClickedCenter');
        }

        try {
            const response = await fetch(`/api/airports?${params.toString()}`);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const airports = await response.json();

        document.querySelector('.total-airports').innerText = `Total Airports: ${airports.length}`;

        if (airports.length === 0) {
            airportMarkers.clearLayers();
            return;
        }

        airports.forEach(airport => {
            const airportIcon = L.icon({
                iconUrl: airport.icon || 'https://unpkg.com/leaflet/dist/images/marker-icon.png',
                iconSize: [24, 24],
                iconAnchor: [12, 24],
                popupAnchor: [0, -20]
            });

            const marker = L.marker([airport.latitude, airport.longitude], { icon: airportIcon }).addTo(airportMarkers);

            marker.on('click', () => {
                lastClickedAirport = {
                    lat: airport.latitude,
                    lng: airport.longitude
                };
                updateRadiusCircle();
            });

            marker.bindPopup(`
                <h5 style="border-bottom:1px solid #cccccc;">${airport.airport_name || 'N/A'}</h5>
                <strong>Address:</strong> ${airport.address || 'N/A'}<br>
                <strong>Telephone:</strong> ${airport.telephone || 'N/A'}<br>
                ${airport.website ? `<strong>Website:</strong><a href='${airport.website}' target='__blank'> ${airport.website} </a><br>` : ''}
                ${airport.id ? `<a href="/airports/${airport.id}/detail" class="btn btn-primary btn-sm mt-2" style="color:white;">Read More</a>` : ''}
            `);
        });

        if (airportMarkers.getLayers().length > 0) {
            let bounds = airportMarkers.getBounds();
            if (destinationCoordinates) {
                bounds.extend(destinationCoordinates);
            }
            map.fitBounds(bounds, { padding: [50, 50] });
        } else if (destinationCoordinates) {
            map.setView(destinationCoordinates, 10);
        }
    } catch (error) {
            console.error('Error fetching airport data:', error);
            document.querySelector('.total-airports').innerText = 'Error loading airports.';
        }
    }

    function applyFilters() {
        const name = document.getElementById('name').value;
        const category = document.getElementById('category').value;
        const location = document.getElementById('location').value;
        const radius = parseInt(document.getElementById('radiusRange').value);

        const selectedProvinces = Array.from(document.querySelectorAll('.province-checkbox:checked'))
            .map(checkbox => checkbox.value);

        let filters = {
            name: name,
            category: category,
            location: location,
            provinces: selectedProvinces
        };

        if (radius > 0) {
            const center = lastClickedAirport ?? map.getCenter();
            filters.radius = radius;
            filters.center_lat = center.lat;
            filters.center_lng = center.lng;
        }

        fetchAndDisplayAirports(filters);
    }

     // Fungsi untuk memuat filter dari localStorage dan menerapkannya
    function loadFiltersAndApply() {
        const savedParamsString = localStorage.getItem('airportFilterParams');
        const savedPolygonString = localStorage.getItem('airportDrawnPolygon');
        const savedCenterString = localStorage.getItem('airportLastClickedCenter');

        // Pastikan Select2 sudah diinisialisasi sebelum mencoba mengatur nilainya
        $('.select2-search').select2({
            placeholder: "🔍 Search...",
            allowClear: true,
            width: '100%',
        });

        if (savedParamsString) {
            const params = new URLSearchParams(savedParamsString);

            // Isi kembali form fields
            document.getElementById('name').value = params.get('name') || '';
            document.getElementById('category').value = params.get('category') || '';
            document.getElementById('location').value = params.get('location') || '';

            // Tangani radius
            const savedRadius = parseInt(params.get('radius')) || 0;
            document.getElementById('radiusRange').value = savedRadius;
            document.getElementById('radiusValue').textContent = savedRadius;

            // Tangani checkboxes provinsi
            const savedProvinces = params.getAll('provinces[]');
            document.querySelectorAll('.province-checkbox').forEach(checkbox => {
                checkbox.checked = savedProvinces.includes(checkbox.value);
            });

            // Pulihkan pilihan Select2
            $('#name').val(params.get('name')).trigger('change');
            $('#category').val(params.get('category')).trigger('change');
            $('#location').val(params.get('location')).trigger('change');

            // Pulihkan lastClickedAirport untuk lingkaran radius jika tersedia
            if (savedCenterString) {
                lastClickedAirport = JSON.parse(savedCenterString);
            }

            // Pulihkan poligon yang digambar
            if (savedPolygonString) {
                drawnPolygonGeoJSON = JSON.parse(savedPolygonString);
                // Penting: pastikan GeoJSON adalah tipe yang valid sebelum ditambahkan
                if (drawnPolygonGeoJSON && drawnPolygonGeoJSON.geometry && drawnPolygonGeoJSON.geometry.coordinates) {
                    const layer = L.geoJSON(drawnPolygonGeoJSON);
                    drawnItems.clearLayers();
                    drawnItems.addLayer(layer);

                    // Sesuaikan peta ke poligon yang digambar
                    map.fitBounds(layer.getBounds(), { padding: [50, 50] });
                }
            }

            // Terapkan filter untuk mengambil data
            applyFilters();
            updateRadiusCircle(); // Pastikan lingkaran radius diperbarui setelah semua data dimuat
        } else {
            // Jika tidak ada filter yang disimpan, ambil data awal (tanpa filter)
            fetchAndDisplayAirports();
        }
    }

     document.getElementById('filterForm').addEventListener('submit', function(e) {
        e.preventDefault();
        applyFilters();
    });


    document.getElementById('resetFilter').addEventListener('click', function() {
        document.getElementById('filterForm').reset();
        document.getElementById('radiusValue').textContent = '0';
        document.querySelectorAll('.province-checkbox').forEach(checkbox => {
            checkbox.checked = false;
        });

         // Reset Select2
        $('.select2-search').val(null).trigger('change');

        if (centerMarker) {
            map.removeLayer(centerMarker);
            centerMarker = null;
        }
        if (destinationMarker) {
            map.removeLayer(destinationMarker);
            destinationMarker = null;
            destinationCoordinates = null;
        }

        lastClickedAirport = null;

        drawnItems.clearLayers();
        drawnPolygonGeoJSON = null;

        // Hapus filter yang disimpan dari localStorage
        localStorage.removeItem('airportFilterParams');
        localStorage.removeItem('airportDrawnPolygon');
        localStorage.removeItem('airportLastClickedCenter');

        map.setView([-6.80188562253168, 144.0733101155011], 6);
        fetchAndDisplayAirports();
        updateRadiusCircle();
    });

    document.addEventListener('DOMContentLoaded', () => {
         loadFiltersAndApply();
    });
</script>
@endpush
