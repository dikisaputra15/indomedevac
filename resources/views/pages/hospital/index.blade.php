@extends('layouts.master')

@section('title','Hospitals')
@section('page-title', 'Papua New Guinea Medical Facility')

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
    .total-hospital {
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

        /* Classification */
        .advanced{
            border-bottom: 3px solid #397fff;
        }

        .intermediete{
            border-bottom: 3px solid #48d12c;
        }

        .basic{
            border-bottom: 3px solid #b4a911ff;
        }

        /* Boder */
        .bl{
            border-left: 2px solid #DDDDDD;
        }

        .br{
            border-right: 2px solid #DDDDDD;
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

    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center gap-3 my-2">

        <div class="d-flex align-items-center gap-2">
            <button class="btn btn-link p-0 fw-bold text-decoration-underline text-dark" data-bs-toggle="modal" data-bs-target="#disclaimerModal">
                <i class="bi bi-info-circle text-primary fs-5"></i>
                Disclaimer
            </button>
        </div>

        <div class="d-flex align-items-end gap-3">
            <div style="margin-right:20px;">
                <span class="fw-bold pb-2 d-inline-block">Classification:</span>
            </div>
            <!-- Classification -->
            <div class="text-end" style="min-width: 700px;">
                <div class="row">
                    <div class="col-3 text-center fw-bold advanced br">Advanced</div>
                    <div class="col-4 text-center fw-bold intermediete br">Intermediate</div>
                    <div class="col-5 text-center fw-bold basic">Basic</div>
                </div>

                <div class="row text-center">
                <!-- Advanced -->
                    <div class="col-3 text-danger br">
                        <button class="btn p-1" data-bs-toggle="modal" data-bs-target="#level6Modal">
                            <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/hospital-pin-red.png" style="width:30px; height:30px;">
                            <small>Class A</small>
                        </button>
                    </div>

                    <!-- Intermediete -->
                     <div class="col-2 text-primary">
                        <button class="btn p-1" data-bs-toggle="modal" data-bs-target="#level5Modal">
                            <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/hospital_pin-blue.png" style="width:30px; height:30px;">
                            <small>Class B</small>
                        </button>
                    </div>
                    <div class="col-2 text-purple br">
                        <button class="btn p-1" data-bs-toggle="modal" data-bs-target="#level4Modal">
                            <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/hospital_pin-purple.png" style="width:30px; height:30px;">
                            <small>Class C</small>
                        </button>
                    </div>

                    <!-- Basic -->
                    <!-- <div class="col-2 text-warning">
                        <button class="btn p-1" data-bs-toggle="modal" data-bs-target="#level2Modal">
                            <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/hospital_pin-orange.png" style="width:30px; height:30px;">
                            <small>Class </small>
                        </button>
                    </div> -->
                    <div class="col-2 text-success">
                        <button class="btn p-1" data-bs-toggle="modal" data-bs-target="#level3Modal">
                            <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/hospital_pin-green.png" style="width:30px; height:30px;">
                            <small>Class D</small>
                        </button>
                    </div>

                    <div class="col-3 text-info">
                        <button class="btn p-1" data-bs-toggle="modal" data-bs-target="#level1Modal">
                            <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/hospital_pin-tosca.png" style="width:30px; height:30px;">
                            <small>PUSKESMAS</small>
                        </button>
                    </div>
                </div>
            </div>
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
            <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/hospital_pin-tosca.png" style="width:30px; height:30px;">
            <h5 class="modal-title" id="disclaimerLabel">Public Health Center (PUSKESMAS)</h5>
         </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="p-modal">A basic healthcare facility focusing on preventive, promotive, and basic curative services. Located at the sub-district and village level, offers maternal and child health, immunization, and community health programs.</p>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="level2Modal" tabindex="-1" aria-labelledby="disclaimerLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
         <div class="d-flex align-items-center">
            <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/hospital_pin-orange.png" style="width:30px; height:30px;">
            <h5 class="modal-title" id="disclaimerLabel">Class 2</h5>
         </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p><b>Community Health Post - Health Sub Center (CHP)</b></p>
        <p class="p-modal">Primary health, ambulatory care, and short stay inpatient and maternity care at the local rural / remote community level, with a minimum of six (6) health workers to ensure safe 24-hour care and treatment.</p>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="level3Modal" tabindex="-1" aria-labelledby="disclaimerLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
         <div class="d-flex align-items-center">
            <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/hospital_pin-green.png" style="width:30px; height:30px;">
            <h5 class="modal-title" id="disclaimerLabel">Class D — Sub-district Hospital</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="p-modal">Provides basic inpatient and emergency care with general practitioners and limited specialist support. Mainly located in sub-districts serving as the first referral point before higher-level hospitals.</p>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="level4Modal" tabindex="-1" aria-labelledby="disclaimerLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
         <div class="d-flex align-items-center">
            <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/hospital_pin-purple.png" style="width:30px; height:30px;">
            <h5 class="modal-title" id="disclaimerLabel">Class C — District-Level Hospital</h5>
         </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="p-modal">Provides core specialist care in internal medicine, surgery, obstetrics, and pediatrics. Manages common medical conditions, refers complex cases to higher-level hospitals.</p>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="level5Modal" tabindex="-1" aria-labelledby="disclaimerLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <div class="d-flex align-items-center">
            <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/hospital_pin-blue.png" style="width:30px; height:30px;">
            <h5 class="modal-title" id="disclaimerLabel">Class B — Provincial Referral Hospital</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="p-modal">Provides broad specialist and limited subspecialist services, functions as regional referral centers, includes ICUs, operating theaters, and diagnostic facilities. </p>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="level6Modal" tabindex="-1" aria-labelledby="disclaimerLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <div class="d-flex align-items-center">
            <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/hospital-pin-red.png" style="width:30px; height:30px;">
            <h5 class="modal-title" id="disclaimerLabel">Class A — National Referral Hospital</h5>
        </div>
         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="p-modal">Highest-level hospital providing, extensive specialist and subspecialist services supported by advanced technology and large bed capacity. Class A hospitals also often serve as teaching and research centers. </p>
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
    // === Inisialisasi Peta ===
    const map = L.map('map', { fullscreenControl: true })
        .setView([-6.80188562253168, 144.0733101155011], 6);

    const osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors', maxZoom: 19
    }).addTo(map);

    const satelliteLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        attribution: 'Tiles © Esri', maxZoom: 19
    });

    L.control.layers({ "Street Map": osmLayer, "Satellite Map": satelliteLayer }).addTo(map);

    // === Global Variables ===
    let hospitalMarkers = L.featureGroup().addTo(map);
    let radiusCircle = null;
    let radiusPinMarker = null;
    let lastClickedLocation = null;
    let drawnPolygonGeoJSON = null;

    // === Leaflet Draw ===
    const drawnItems = new L.FeatureGroup().addTo(map);
    const drawControl = new L.Control.Draw({
        draw: {
            polygon: { allowIntersection: false, shapeOptions: { color: '#346abb', fillColor: '#346abb', fillOpacity: 0.2 } },
            polyline: false, rectangle: false, circle: false, marker: false, circlemarker: false
        },
        edit: { featureGroup: drawnItems }
    });
    map.addControl(drawControl);

    // === Event Polygon ===
    map.on(L.Draw.Event.CREATED, e => {
        drawnItems.clearLayers();
        drawnItems.addLayer(e.layer);
        drawnPolygonGeoJSON = e.layer.toGeoJSON();
        applyFiltersWithMapControl();
    });

    map.on(L.Draw.Event.EDITED, e => {
        e.layers.eachLayer(layer => drawnPolygonGeoJSON = layer.toGeoJSON());
        applyFiltersWithMapControl();
    });

    map.on(L.Draw.Event.DELETED, () => {
        drawnItems.clearLayers();
        drawnPolygonGeoJSON = null;
        applyFiltersWithMapControl();
    });

    // === Radius Circle Update ===
    function updateRadiusCircleAndPin(radius = 0) {
        if (radiusCircle) map.removeLayer(radiusCircle);
        if (radiusPinMarker) map.removeLayer(radiusPinMarker);
        radiusCircle = radiusPinMarker = null;

        if (radius > 0 && lastClickedLocation) {
            radiusCircle = L.circle(lastClickedLocation, {
                color: 'red', fillColor: '#f03', fillOpacity: 0.3, radius: radius * 1000
            }).addTo(map);

            const redIcon = L.icon({
                iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34], shadowSize: [41, 41]
            });

            radiusPinMarker = L.marker(lastClickedLocation, { icon: redIcon }).addTo(map);
        }
    }

    map.on('click', e => {
        lastClickedLocation = { lat: e.latlng.lat, lng: e.latlng.lng };
        const radius = parseInt(document.querySelector('#radiusRangeMap').value || 0);
        document.querySelector('#radiusValueMap').textContent = radius;
        updateRadiusCircleAndPin(radius);
        applyFiltersWithMapControl();
    });

    // === Fetch Data ===
    async function fetchData(url, filters = {}) {
        const params = new URLSearchParams();
        Object.entries(filters).forEach(([k, v]) => {
            if (Array.isArray(v)) v.forEach(x => params.append(`${k}[]`, x));
            else if (v !== '' && v != null) params.append(k, v);
        });
        if (drawnPolygonGeoJSON) params.append('polygon', JSON.stringify(drawnPolygonGeoJSON));

        try {
            const res = await fetch(`${url}?${params.toString()}`);
            return res.ok ? await res.json() : [];
        } catch (e) {
            console.error(`Error fetching ${url}:`, e);
            return [];
        }
    }

    // === Tambah Marker Rumah Sakit ===
    function addHospitalMarkers(data) {
        hospitalMarkers.clearLayers();
        data.forEach(hospital => {
            if (!hospital.latitude || !hospital.longitude) return;

            const icon = L.icon({
                iconUrl: hospital.icon || 'https://unpkg.com/leaflet/dist/images/marker-icon.png',
                iconSize: [24, 24], iconAnchor: [12, 24], popupAnchor: [0, -20]
            });

            const marker = L.marker([hospital.latitude, hospital.longitude], { icon }).addTo(hospitalMarkers);

            marker.on('click', () => {
                lastClickedLocation = { lat: hospital.latitude, lng: hospital.longitude };
                updateRadiusCircleAndPin(parseInt(document.querySelector('#radiusRangeMap').value || 0));
            });

            marker.bindPopup(`
                <h5 style="border-bottom:1px solid #ccc;">${hospital.name || 'N/A'}</h5>
                <strong>Global Classification:</strong> ${hospital.facility_category || 'N/A'}<br>
                <strong>Facility Level:</strong> ${hospital.facility_level || 'N/A'}<br>
                <strong>Address:</strong> ${hospital.address || 'N/A'}<br>
                <strong>Province:</strong> ${hospital.provinces_region || 'N/A'}<br>
                <strong>Coords:</strong> ${hospital.latitude}, ${hospital.longitude}<br>
                ${hospital.id ? `<a href="/hospitals/${hospital.id}" class="btn btn-primary btn-sm mt-2" style="color:white;">Read More</a>` : ''}
            `);
        });

        if (hospitalMarkers.getLayers().length > 0)
            map.fitBounds(hospitalMarkers.getBounds(), { padding: [50, 50] });
    }

    // === Apply Filter ===
    async function applyFiltersWithMapControl() {
        const name = document.querySelector('#hospital_name_map')?.value || '';
        const facilityLevels = Array.from(document.querySelectorAll('input[name="hospitalLevel"]:checked')).map(cb => cb.value);
        const provinces = Array.from(document.querySelectorAll('.province-checkbox:checked')).map(cb => cb.value);
        const radius = parseInt(document.querySelector('#radiusRangeMap')?.value || 0);

        const filters = { name, category: facilityLevels, provinces };

        if (radius > 0 && lastClickedLocation) {
            filters.radius = radius;
            filters.center_lat = lastClickedLocation.lat;
            filters.center_lng = lastClickedLocation.lng;
        }

        const hospitals = await fetchData('/api/hospital', filters);
        document.querySelector('#totalHospitals').textContent = hospitals.length;
        addHospitalMarkers(hospitals);
        updateRadiusCircleAndPin(radius);
    }

    // === Radius Panel ===
    const RadiusPanel = L.Control.extend({
        options: { position: 'topright' },
        onAdd: function () {
            const div = L.DomUtil.create('div', 'leaflet-bar leaflet-control leaflet-control-custom');
            div.style.background = 'white';
            div.style.padding = '10px';
            div.style.borderRadius = '8px';
            div.style.boxShadow = '0 2px 6px rgba(0,0,0,0.2)';
            div.style.minWidth = '160px';
            div.style.textAlign = 'center';

            div.innerHTML = `
                <strong>Radius Filter <span id="radiusValueMap">0</span> km</strong><br>
                <input type="range" id="radiusRangeMap" min="0" max="500" value="0" style="width:100%;margin-bottom:6px;">
                <div style="display:flex;gap:5px;">
                    <button id="applyRadiusMap" class="btn btn-sm btn-primary flex-fill">Apply</button>
                    <button id="resetRadiusMap" class="btn btn-sm btn-danger flex-fill">Reset</button>
                </div>
            `;

            L.DomEvent.disableClickPropagation(div);
            return div;
        }
    });
    map.addControl(new RadiusPanel());

    // === Filter Panel ===
    map.addControl(new (L.Control.extend({
        options: { position: 'topright' },
        onAdd: function () {
            const container = L.DomUtil.create('div', 'leaflet-bar leaflet-control leaflet-control-custom');
            Object.assign(container.style, {
                background: 'white', borderRadius: '8px',
                boxShadow: '0 2px 8px rgba(0,0,0,0.2)', overflow: 'hidden', zIndex: '9999', maxWidth: '280px'
            });

            const btn = L.DomUtil.create('button', '', container);
            btn.textContent = 'Filter';
            Object.assign(btn.style, {
                background: '#007bff', color: 'white', border: 'none',
                width: '100%', padding: '6px', cursor: 'pointer'
            });

            const panel = L.DomUtil.create('div', '', container);
            Object.assign(panel.style, { display: 'none', padding: '10px', maxHeight: '440px', overflowY: 'auto' });

            panel.innerHTML = `
                <h6>Filter Hospitals</h6>
                <label class="form-label">Hospital Name</label>
                <select id="hospital_name_map" class="form-select form-select-sm mb-2 select-search-hospital">
                    <option value="">Select Hospital</option>
                    @foreach($hospitalNames as $n)
                        <option value="{{ $n }}">{{ $n }}</option>
                    @endforeach
                </select>

                <strong>Facility Level:</strong><br>
                ${['Class A','Class B','Class C','Class D','Public Health Center (PUSKESMAS)'].map(c => `
                    <label style="display:block;font-size:13px;">
                        <input type="checkbox" name="hospitalLevel" value="${c}"> ${c}
                    </label>`).join('')}

                <hr>
                <strong>Province</strong>
                <div style="max-height:120px;overflow-y:auto;border:1px solid #ccc;padding:5px;border-radius:5px;margin-top:6px;">
                    @foreach ($provinces as $p)
                        <div class="form-check">
                            <input class="form-check-input province-checkbox" type="checkbox" value="{{ $p->id }}">
                            <label class="form-check-label">{{ $p->provinces_region }}</label>
                        </div>
                    @endforeach
                </div>

                <hr>
                <button id="resetHospitalFilter" class="btn btn-sm btn-secondary mt-3 w-100">Reset Filter</button>

                <p class="mt-2 mb-0 text-center"><strong>Total:</strong> <span id="totalHospitals">0</span> facilities</p>
            `;

            btn.addEventListener('click', () => {
                panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
            });

            setTimeout(() => $('.select-search-hospital').select2({ width: '100%', placeholder: 'Search Hospital' }), 300);

            panel.querySelector('#resetHospitalFilter').addEventListener('click', () => {
                panel.querySelectorAll('input[type=checkbox]').forEach(c => c.checked = false);
                $(panel).find('.select-search-hospital').val(null).trigger('change');
                drawnItems.clearLayers();
                drawnPolygonGeoJSON = null;
                if (radiusCircle) { map.removeLayer(radiusCircle); radiusCircle = null; }
                if (radiusPinMarker) { map.removeLayer(radiusPinMarker); radiusPinMarker = null; }
                lastClickedLocation = null;
                applyFiltersWithMapControl();
            });

            L.DomEvent.disableClickPropagation(container);
            return container;
        }
    }))());

    // === Event Filter Change ===
    document.addEventListener('change', e => {
        if (e.target.id === 'hospital_name_map' || e.target.name === 'hospitalLevel' || e.target.classList.contains('province-checkbox'))
            applyFiltersWithMapControl();
    });

    // --- Event listeners for radius panel ---
    document.addEventListener('input', e => {
        if (e.target.id === 'radiusRangeMap') {
            const radius = parseInt(e.target.value || 0);
            document.getElementById('radiusValueMap').textContent = radius;
            updateRadiusCircleAndPin(radius); // hanya update tampilan, belum apply
        }
    });

    document.addEventListener('click', e => {
        if (e.target.id === 'applyRadiusMap') {
            const radius = parseInt(document.getElementById('radiusRangeMap').value || 0);
            applyFiltersWithMapControl('all', [], [], [], radius); // apply filter radius baru
        }

        if (e.target.id === 'resetRadiusMap') {
            document.getElementById('radiusRangeMap').value = 0;
            document.getElementById('radiusValueMap').textContent = 0;
            if (radiusCircle) map.removeLayer(radiusCircle);
            if (radiusPinMarker) map.removeLayer(radiusPinMarker);
            lastClickedLocation = null;
            applyFiltersWithMapControl('all');
        }
    });

    $(document).on('select2:select select2:unselect', '#hospital_name_map', function () {
        applyFiltersWithMapControl();
    });

    // === Load Awal ===
    document.addEventListener('DOMContentLoaded', applyFiltersWithMapControl);
</script>

@endpush
