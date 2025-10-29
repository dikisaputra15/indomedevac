@extends('layouts.master')

@section('title','Emergency Support')
@section('page-title', 'Papua New Guinea Medical Facility')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.fullscreen/Control.FullScreen.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css" />

<style>
    #map {
        height: 600px;
    }
    p{
        margin-bottom: 8px;
        line-height: 18px;
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

    .leaflet-routing-container-hide .leaflet-routing-collapse-btn
    {
        left: 8px;
        top: 8px;
    }

    .leaflet-control-container .leaflet-routing-container-hide {
        width: 48px;
        height: 48px;
    }
</style>
@endpush

@section('conten')

<div class="card">

    <div class="d-flex justify-content-between p-3" style="background-color: #dfeaf1;">

        <div class="d-flex flex-column gap-1">
            <h2 class="fw-bold mb-0">{{ $hospital->name }}</h2>
            <span class="fw-bold"><b>Global Classification:</b> {{ $hospital->facility_category }} | <b>Country Classification:</b> {{ $hospital->facility_level }}</span>
        </div>

        <div class="d-flex gap-2 ms-auto">
            <!-- Button 2 -->
            <a href="{{ url('hospitals') }}/{{$hospital->id}}" class="btn btn-outline-danger d-flex flex-column align-items-center p-3 {{ request()->is('hospitals/'.$hospital->id) ? 'active' : '' }}">
                 <img src="{{ asset('images/icon-menu-general-info.png') }}" style="width: 18px; height: 24px;">
                <small>General</small>
            </a>

            <!-- Button 3 -->
            <a href="{{ url('hospitals/clinic') }}/{{$hospital->id}}" class="btn btn-outline-danger d-flex flex-column align-items-center p-3 {{ request()->is('hospitals/clinic/'.$hospital->id) ? 'active' : '' }}">
                <img src="{{ asset('images/icon-menu-medical-facility-white.png') }}" style="width: 18px; height: 24px;">
                <small>Clinical</small>
            </a>

            <!-- Button 4 -->
            <a href="{{ url('hospitals/emergency') }}/{{$hospital->id}}" class="btn btn-outline-danger d-flex flex-column align-items-center p-3 {{ request()->is('hospitals/emergency/'.$hospital->id) ? 'active' : '' }}">
                <img src="{{ asset('images/icon-emergency-support-white.png') }}" style="width: 24px; height: 24px;">
                <small>Emergency</small>
            </a>

            <a href="{{ url('hospital') }}" class="btn btn-danger d-flex flex-column align-items-center p-3 {{ request()->is('hospital') ? 'active' : '' }}">
                 <img src="{{ asset('images/icon-medical.png') }}" style="width: 24px; height: 24px;">
                <small>Medical</small>
            </a>
            <!-- Button 5 -->
            <a href="{{ url('airports') }}" class="btn btn-danger d-flex flex-column align-items-center p-3 {{ request()->is('airports') ? 'active' : '' }}">
                <i class="bi bi-airplane fs-3"></i>
                <small>Airports</small>
            </a>

            <a href="{{ url('aircharter') }}" class="btn btn-danger d-flex flex-column align-items-center p-3 {{ request()->is('aircharter') ? 'active' : '' }}">
                 <img src="{{ asset('images/icon-air-charter.png') }}" style="width: 48px; height: 24px;">
                <small>Air Charter</small>
            </a>

            <!-- Button 7 -->
            <a href="{{ url('embassiees') }}" class="btn btn-danger d-flex flex-column align-items-center p-3 {{ request()->is('embassiees') ? 'active' : '' }}">
            <img src="{{ asset('images/icon-embassy.png') }}" style="width: 24px; height: 24px;">
                <small>Embassies</small>
            </a>
        </div>
    </div>

    <div class="card mb-4 position-relative">
        <div class="card-body" style="padding:0 7px;">
            <small><i>Last Updated {{ $hospital->created_at->format('M Y') }}</i></small>

            @role('admin')
            <a href="{{ route('hospitaldata.edit', $hospital->id) }}"
            style="position:absolute; right:7px;" title="edit">
                <i class="fas fa-edit"></i>
            </a>
            @endrole
        </div>
    </div>

    <div class="row">

        <div class="col-md-8">
             <div class="card">
                <div class="card-header fw-bold"><img src="{{ asset('images/icon-emergency-support.png') }}" style="width: 24px; height: 24px;"> Emergency Support Tools</div>
                <div class="card-body p-0">
                    <div id="map"></div>
                </div>
            </div>

            <div class="card">
                <div class="d-flex justify-content-between align-items-center" style="margin-bottom:0; margin-top:0;">

                        <div class="d-flex align-items-center gap-3">

                            <button class="btn p-1" data-bs-toggle="modal" data-bs-target="#level6Modal">
                                <img src="https://concord-consulting.com/static/img/cmt/icon/icon-international-airport-orange.png" style="width:18px; height:18px;">
                                <small>International</small>
                            </button>

                            <button class="btn p-1" data-bs-toggle="modal" data-bs-target="#level5Modal">
                                <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/regional-airport.png" style="width:18px; height:18px;">
                                <small>Domestic</small>
                            </button>

                            <button class="btn p-1" data-bs-toggle="modal" data-bs-target="#level4Modal">
                                <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/regional-domestic-airport.png" style="width:18px; height:18px;">
                                <small>Regional</small>
                            </button>

                            <button class="btn p-1" data-bs-toggle="modal" data-bs-target="#level2Modal">
                                <img src="https://pg.concordreview.com/wp-content/uploads/2024/10/civil-military-airport.png" style="width:18px; height:18px;">
                                <small>Combined (Civil-Military)</small>
                            </button>

                            <button class="btn p-1" data-bs-toggle="modal" data-bs-target="#level7Modal">
                                <img src="https://pg.concordreview.com/wp-content/uploads/2024/10/military-airport-red.png" style="width:18px; height:18px;">
                                <small>Military</small>
                            </button>

                            <button class="btn p-1" data-bs-toggle="modal" data-bs-target="#level1Modal">
                                <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/private-airport.png" style="width:18px; height:18px;">
                                <small>Private</small>
                            </button>

                             <button class="btn p-1" data-bs-toggle="modal" data-bs-target="#level66Modal">
                                <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/hospital-pin-red.png" style="width:24px; height:24px;">
                                <small>Class A</small>
                            </button>

                            <button class="btn p-1" data-bs-toggle="modal" data-bs-target="#level55Modal">
                                <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/hospital_pin-blue.png" style="width:24px; height:24px;">
                                <small>Class B</small>
                            </button>

                            <button class="btn p-1" data-bs-toggle="modal" data-bs-target="#level44Modal">
                                <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/hospital_pin-purple.png" style="width:24px; height:24px;">
                                <small>Class C</small>
                            </button>

                            <button class="btn p-1" data-bs-toggle="modal" data-bs-target="#level33Modal">
                                <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/hospital_pin-green.png" style="width:24px; height:24px;">
                                <small>Class D</small>
                            </button>

                            <!-- <button class="btn p-1" data-bs-toggle="modal" data-bs-target="#level22Modal">
                                <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/hospital_pin-orange.png" style="width:24px; height:24px;">
                                <small>Level 2</small>
                            </button> -->

                            <button class="btn p-1" data-bs-toggle="modal" data-bs-target="#level11Modal">
                                <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/hospital_pin-tosca.png" style="width:24px; height:24px;">
                                <small>PUSKESMAS</small>
                            </button>
                        </div>

                </div>
            </div>
        </div>

        <div class="col-md-4">
           <div class="card">
                <div class="card-header fw-bold"><img src="https://concord-consulting.com/static/img/cmt/icon/radar-icon.png" style="width: 24px; height: 24px;"> Nearest Airfields and Medical Facilities</div>
                <div class="card-body overflow-auto">
                    <?php echo $hospital->nearest_airfield; ?>
                </div>
            </div>
            <div class="card">
                <div class="card-header fw-bold"><img src="{{ asset('images/icon-medical-support-website.png') }}" style="width: 24px; height: 24px;"> Emergency Medical Support</div>
                <div class="card-body" style="max-height: 250px; overflow-y: auto;">
                    <?php echo $hospital->medical_support_website; ?>
                </div>
            </div>
            <div class="card">
                <div class="card-header fw-bold"><img src="{{ asset('images/icon-police.png') }}" style="width: 24px; height: 24px;"> Nearest Police station</div>
                <div class="card-body overflow-auto">
                    <?php echo $hospital->nearest_police_station; ?>
                </div>
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

<div class="modal fade" id="level11Modal" tabindex="-1" aria-labelledby="disclaimerLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
         <div class="d-flex align-items-center">
            <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/hospital_pin-tosca.png" style="width:30px; height:30px;">
            <h5 class="modal-title" id="disclaimerLabel">Class 1</h5>
         </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p><b>Village Health Post – Aid Post (VHP)</b></p>
        <p class="p-modal">Basic level primary health care including health promotion, health improvement, and health protection.</p>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="level22Modal" tabindex="-1" aria-labelledby="disclaimerLabel" aria-hidden="true">
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

<div class="modal fade" id="level33Modal" tabindex="-1" aria-labelledby="disclaimerLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
         <div class="d-flex align-items-center">
            <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/hospital_pin-green.png" style="width:30px; height:30px;">
            <h5 class="modal-title" id="disclaimerLabel">Class 3</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p><b>Health Center - Rural / Urban Clinic – Urban Centers (HC-UC)</b></p>
        <p class="p-modal">Primary health and ambulatory care in urban and rural settings, inpatient, maternity, and newborn care in major provincial urban communities.</p>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="level44Modal" tabindex="-1" aria-labelledby="disclaimerLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
         <div class="d-flex align-items-center">
            <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/hospital_pin-purple.png" style="width:30px; height:30px;">
            <h5 class="modal-title" id="disclaimerLabel">Class 4</h5>
         </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p><b>District Hospital - Rural Health Services (DH)</b></p>
        <p class="p-modal">Primary and secondary level clinical services and district wide public health programs.</p>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="level55Modal" tabindex="-1" aria-labelledby="disclaimerLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <div class="d-flex align-items-center">
            <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/hospital_pin-blue.png" style="width:30px; height:30px;">
            <h5 class="modal-title" id="disclaimerLabel">Class 5</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p><b>Provincial Hospital, Health Services and Public Health Programs (PHA)</b></p>
        <p class="p-modal">Secondary level & specialist clinical care services, supporting primary health care, integrating public health programs, and patient referral.</p>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="level66Modal" tabindex="-1" aria-labelledby="disclaimerLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <div class="d-flex align-items-center">
            <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/hospital-pin-red.png" style="width:30px; height:30px;">
            <h5 class="modal-title" id="disclaimerLabel">Class 6</h5>
        </div>
         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p><b>National Referral Specialist Tertiary and Teaching Hospital - Health Services (NHA)</b></p>
        <p class="p-modal">Complex tertiary level clinical services, supporting primary health care, public health programs, and a formalized patient referral arrangement.</p>
      </div>
    </div>
  </div>
</div>

@endsection

@push('service')
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.fullscreen/Control.FullScreen.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const hospitalData = {!! json_encode([
        'id'        => $hospital->id,
        'name'      => $hospital->name,
        'latitude'  => $hospital->latitude,
        'longitude' => $hospital->longitude,
        'icon'      => $hospital->icon ?? '',
    ]) !!};

    const nearbyHospitals = @json($nearbyHospitals);
    const nearbyAirports = @json($nearbyAirports);
    let radiusKm = {{ $radius_km }}; // ⬅️ default 500 km

    let map, mainMarker, radiusCircle, routingControl = null;
    let nearbyMarkersGroup = L.featureGroup();

    // Default icons
    const DEFAULT_HOSPITAL_ICON_URL = 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png';
    const DEFAULT_AIRPORT_ICON_URL = 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png';
    const DEFAULT_MAIN_HOSPITAL_ICON_URL = 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png';

    const mainHospitalIcon = new L.Icon({
        iconUrl: DEFAULT_MAIN_HOSPITAL_ICON_URL,
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
    });

    // Inisialisasi peta
    function initializeMap() {
        map = L.map('map', { fullscreenControl: true })
            .setView([hospitalData.latitude, hospitalData.longitude], 11);

        const osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors',
            maxZoom: 19
        });

        const satelliteLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            attribution: 'Tiles &copy; Esri',
            maxZoom: 19
        });

        satelliteLayer.addTo(map);
        L.control.layers({ "Satellite": satelliteLayer, "Street": osmLayer }).addTo(map);

        nearbyMarkersGroup.addTo(map);
    }

    // Tambahkan marker utama rumah sakit dan radius
    function addMainHospitalAndCircle() {
        mainMarker = L.marker([hospitalData.latitude, hospitalData.longitude], { icon: mainHospitalIcon })
            .addTo(map)
            .bindPopup(`<b>${hospitalData.name}</b><br>This is the main hospital.`);

        radiusCircle = L.circle([hospitalData.latitude, hospitalData.longitude], {
            color: 'red',
            fillColor: '#f03',
            fillOpacity: 0.1,
            radius: radiusKm * 1000
        }).addTo(map);
    }

    // Tambahkan marker sekitar dengan filter + radius check
    function addNearbyMarkers(data, defaultIconUrl, type, filters = {}) {
        data.forEach(item => {
            // Hitung jarak antar titik
            const distance = calculateDistance(
                hospitalData.latitude, hospitalData.longitude,
                item.latitude, item.longitude
            );

            // Filter berdasarkan radius
            if (distance > radiusKm) return; // ⬅️ hanya tampil dalam radius

            // Filter HOSPITAL berdasarkan facility_level
            if (type === 'Hospital' && filters.hospitalLevel && filters.hospitalLevel !== 'all') {
                if ((item.facility_level || '').toLowerCase() !== filters.hospitalLevel.toLowerCase()) return;
            }

            // Filter AIRPORT berdasarkan category
            if (type === 'Airport' && filters.airportClassifications && filters.airportClassifications.length > 0) {
                const airportCategories = (item.category || '')
                    .split(',')
                    .map(c => c.trim().toLowerCase());
                const allowed = filters.airportClassifications.map(c => c.toLowerCase());
                const hasMatch = airportCategories.some(cat => allowed.includes(cat));
                if (!hasMatch) return;
            }

            const icon = L.icon({
                iconUrl: item.icon || defaultIconUrl,
                iconSize: [24, 24],
                iconAnchor: [12, 24],
                popupAnchor: [0, -20]
            });

            const marker = L.marker([item.latitude, item.longitude], { icon });
            const name = item.name || item.airport_name || 'N/A';
            const level = item.facility_level || item.category || 'N/A';

            const detailUrl = (type === 'Airport')
                ? `/airports/${item.id}/detail`
                : `/hospitals/${item.id}`;

            const group = (type === 'Airport') ? `` : `${item.facility_category || ''} -`;

            const jarak = item.distance
                            ? `<strong>Distance:</strong> ${item.distance.toFixed(2)} km`
                            : '';

            marker.bindPopup(`
                <div style="font-size:13px;">
                    <a href="${detailUrl}" target="_blank">
                        ${name}
                    </a><br>
                    ${group} ${level}<br>
                    ${jarak}
                    <br><button class="btn btn-sm btn-primary mt-2"
                        onclick="getDirection(${item.latitude}, ${item.longitude}, '${name}')">
                        Get Direction
                    </button>
                </div>
            `);

            nearbyMarkersGroup.addLayer(marker);
        });
    }

    // Hitung jarak antar titik (Haversine)
    function calculateDistance(lat1, lon1, lat2, lon2) {
        const R = 6371;
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLon = (lon2 - lon1) * Math.PI / 180;
        const a =
            Math.sin(dLat / 2) ** 2 +
            Math.cos(lat1 * Math.PI / 180) *
            Math.cos(lat2 * Math.PI / 180) *
            Math.sin(dLon / 2) ** 2;
        return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    }

   // Routing direction tanpa pin waypoint routing bawaan
    window.getDirection = function(lat, lng) {
        // Hapus route sebelumnya
        if (routingControl) map.removeControl(routingControl);

        // Tambahkan routing baru tanpa marker bawaan
        routingControl = L.Routing.control({
            waypoints: [
                L.latLng(hospitalData.latitude, hospitalData.longitude),
                L.latLng(lat, lng)
            ],
            routeWhileDragging: false,
            addWaypoints: false,
            collapsible: true,
            show: false,
            createMarker: function() {
                // Return null supaya routing tidak menambahkan marker waypoint
                return null;
            },
            lineOptions: {
                styles: [{ color: 'red', opacity: 0.7, weight: 4 }]
            }
        }).addTo(map);

        // Pastikan garis route tidak menutupi marker
        routingControl.on('routesfound', () => {
            if (mainMarker && typeof mainMarker.bringToFront === 'function') {
                mainMarker.bringToFront();
            }
            if (nearbyMarkersGroup && typeof nearbyMarkersGroup.bringToFront === 'function') {
                nearbyMarkersGroup.bringToFront();
            }
        });
    };

    // Fit map ke semua marker
    function fitMapToBounds() {
        const bounds = L.featureGroup([mainMarker, nearbyMarkersGroup, radiusCircle]).getBounds();
        if (bounds.isValid()) map.fitBounds(bounds, { padding: [50, 50] });
    }

    // Render ulang marker sesuai filter + radius
    function updateMarkers(filterType, hospitalLevel, airportClassifications) {
        nearbyMarkersGroup.clearLayers();
        map.removeLayer(radiusCircle);
        addMainHospitalAndCircle(); // ⬅️ redraw radius

        const filters = { hospitalLevel, airportClassifications };

        if (filterType === 'hospital') {
            addNearbyMarkers(nearbyHospitals, DEFAULT_HOSPITAL_ICON_URL, 'Hospital', filters);
        } else if (filterType === 'airport') {
            addNearbyMarkers(nearbyAirports, DEFAULT_AIRPORT_ICON_URL, 'Airport', filters);
        } else {
            addNearbyMarkers(nearbyHospitals, DEFAULT_HOSPITAL_ICON_URL, 'Hospital', filters);
            addNearbyMarkers(nearbyAirports, DEFAULT_AIRPORT_ICON_URL, 'Airport', filters);
        }

        fitMapToBounds();
    }

   // === Filter Control di dalam Peta ===
    const FilterControl = L.Control.extend({
        options: { position: 'topright' },
        onAdd: function () {
            const container = L.DomUtil.create('div', 'leaflet-bar leaflet-control leaflet-control-custom');
            container.style.background = 'white';
            container.style.borderRadius = '8px';
            container.style.boxShadow = '0 2px 8px rgba(0,0,0,0.2)';
            container.style.overflow = 'hidden';

            // Tombol toggle
            const toggleButton = L.DomUtil.create('button', '', container);
            toggleButton.innerHTML = 'Filter';
            toggleButton.style.width = '100%';
            toggleButton.style.border = 'none';
            toggleButton.style.background = '#007bff';
            toggleButton.style.color = 'white';
            toggleButton.style.padding = '6px';
            toggleButton.style.cursor = 'pointer';
            toggleButton.style.fontSize = '13px';

            // Panel filter (default hidden)
            const panel = L.DomUtil.create('div', '', container);
            panel.style.display = 'none';
            panel.style.padding = '10px';
            panel.style.maxWidth = '220px';
            panel.style.maxHeight = '400px';
            panel.style.overflowY = 'auto';
            panel.innerHTML = `
                <h6 style="margin:0 0 5px 0;">Filter</h6>
                <label><strong>Radius:</strong> <span id="radiusLabel">${radiusKm}</span> km</label>
                <input type="range" id="radiusRange" min="10" max="500" value="${radiusKm}" step="10" class="form-range mb-2">

                <select id="mapFilter" class="form-select form-select-sm mb-2">
                    <option value="all">Show All</option>
                    <option value="hospital">Hospitals</option>
                    <option value="airport">Airports</option>
                </select>

                <div id="hospitalFilter" style="display:none;">
                    <strong>Facility Level:</strong><br>
                    ${['All','Class A','Class B','Class C','Class D','Public Health Center (PUSKESMAS)'].map(lvl => `
                        <label style="display:block;font-size:13px;">
                            <input type="radio" name="hospitalLevel" value="${lvl === 'All' ? 'all' : lvl}"> ${lvl}
                        </label>
                    `).join('')}
                </div>

                <div id="airportFilter" style="display:none; margin-top:8px;">
                    <strong>Category:</strong><br>
                    ${['International','Domestic','Military','Regional','Private'].map(cls => `
                        <label style="display:block;font-size:13px;">
                            <input type="checkbox" name="airportClass" value="${cls}"> ${cls}
                        </label>
                    `).join('')}
                </div>
            `;

            L.DomEvent.disableClickPropagation(container);

            // === Toggle Show/Hide ===
            toggleButton.addEventListener('click', () => {
                panel.style.display = (panel.style.display === 'none') ? 'block' : 'none';
            });

            // === Event filter logic ===
            const radiusSlider = panel.querySelector('#radiusRange');
            const radiusLabel = panel.querySelector('#radiusLabel');
            const filterSelect = panel.querySelector('#mapFilter');
            const hospitalDiv = panel.querySelector('#hospitalFilter');
            const airportDiv = panel.querySelector('#airportFilter');

            function refresh() {
                const selectedType = filterSelect.value;
                const selectedLevel = panel.querySelector('input[name="hospitalLevel"]:checked')?.value || 'all';
                const selectedClasses = Array.from(panel.querySelectorAll('input[name="airportClass"]:checked')).map(el => el.value);
                updateMarkers(selectedType, selectedLevel, selectedClasses);
            }

            filterSelect.addEventListener('change', () => {
                const val = filterSelect.value;
                hospitalDiv.style.display = val === 'hospital' ? 'block' : 'none';
                airportDiv.style.display = val === 'airport' ? 'block' : 'none';
                refresh();
            });

            panel.querySelectorAll('input[name="hospitalLevel"]').forEach(radio => {
                radio.addEventListener('change', refresh);
            });

            panel.querySelectorAll('input[name="airportClass"]').forEach(chk => {
                chk.addEventListener('change', refresh);
            });

            radiusSlider.addEventListener('input', () => {
                radiusKm = parseInt(radiusSlider.value);
                radiusLabel.textContent = radiusKm;
                refresh();
            });

            return container;
        }
    });

    // Jalankan
    initializeMap();
    addMainHospitalAndCircle();
    updateMarkers('all', 'all', []);
    map.addControl(new FilterControl());
});
</script>

@endpush
