@extends('layouts.master')

@section('title','More Details')
@section('page-title', 'Papua New Guinea Airports')

@push('styles')

<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.fullscreen/1.6.0/Control.FullScreen.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css" />
<style>
    #map {
        height: 600px;
    }

    table {
        border: 1px solid black;
        border-collapse: collapse;
    }
    td {
        border: 1px solid black;
        padding: 4px;
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

     /* Classification section */
    .classification {
      display: flex;
      width: 100%;
    }

    .class-column {
      flex: 1;
      text-align: center;

    }
    .class-column:last-child {
      border-right: none;
    }

    .class-header {
      font-weight: 600;
      padding: 0.1rem 0;
    }

    /* Color bars */
    .class-medical-classification {border: none; text-align: center;}
    .class-airport-category {border: none;}
    .class-advanced { border-bottom: 3px solid #0070c0; }
    .class-intermediate { border-bottom: 3px solid #00b050; }
    .class-basic { border-bottom: 3px solid #ffc000; }

    /* Hospital layout */
    .hospital-list {
      display: flex;
      flex-direction: column;
      align-items: center;

    }

    /* For side-by-side classes */
    .hospital-row {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 0;
    }

    .hospital-item {
      display: flex;
      align-items: center;
      gap: 0;
      font-size: 0.9rem;
      white-space: nowrap;
    }

    .hospital-icon {
      width: 18px;
      height: 18px;
      border-radius: 3px;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
    }

    /* Image inside icon box */
    .hospital-icon img {
      width: 100%;
      height: 100%;
      object-fit: contain;
    }

    /* Airfield icons */
    .category-item img {
      width: 16px;
      height: 16px;
      object-fit: contain;
    }
</style>

@endpush

@section('conten')

<div class="card">

<div class="d-flex justify-content-between p-3" style="background-color: #dfeaf1;">
       <div class="d-flex flex-column gap-1">
            <h2 class="fw-bold mb-0">{{ $airport->airport_name }}</h2>
            <span class="fw-bold"><b>Airfield Category:</b> {{ $airport->category }}</span>
        </div>

        <div class="d-flex gap-2 ms-auto">

              <!-- Button 2 -->
            <a href="{{ url('airports') }}/{{$airport->id}}/detail" class="btn btn-outline-danger d-flex flex-column align-items-center p-3 {{ request()->is('airports/'.$airport->id.'/detail') ? 'active' : '' }}">
                <img src="{{ asset('images/icon-menu-general-info.png') }}" style="width: 18px; height: 24px;">
                <small>General</small>
            </a>

            <!-- Button 3 -->
            <a href="{{ url('airports') }}/{{$airport->id}}/navigation" class="btn btn-outline-danger d-flex flex-column align-items-center p-3 {{ request()->is('airports/'.$airport->id.'/navigation') ? 'active' : '' }}">
                <img src="{{ asset('images/icon-navaids-white.png') }}" style="width: 24px; height: 24px;">
                <small>Navigation</small>
            </a>

             <!-- Button 4 -->
             <a href="{{ url('airports') }}/{{$airport->id}}/airlinesdestination" class="btn btn-outline-danger d-flex flex-column align-items-center p-3 {{ request()->is('airports/'.$airport->id.'/airlinesdestination') ? 'active' : '' }}">
                <img src="{{ asset('images/icon-destination-white.png') }}" style="width: 24px; height: 24px;">
                <small>Destination</small>
            </a>

            <!-- Button 5 -->
            <a href="{{ url('airports') }}/{{$airport->id}}/emergency" class="btn btn-outline-danger d-flex flex-column align-items-center p-3 {{ request()->is('airports/'.$airport->id.'/emergency') ? 'active' : '' }}">
                <img src="{{ asset('images/icon-emergency-support-white.png') }}" style="width: 24px; height: 24px;">
                <small>Emergency</small>
            </a>

             <!-- Button 5 -->
            <a href="{{ url('hospital') }}" class="btn btn-danger d-flex flex-column align-items-center p-3 {{ request()->is('hospital') ? 'active' : '' }}">
                 <img src="{{ asset('images/icon-medical.png') }}" style="width: 24px; height: 24px;">
                <small>Medical</small>
            </a>

            <a href="{{ url('airports') }}" class="btn btn-danger d-flex flex-column align-items-center p-3 {{ request()->is('airports') ? 'active' : '' }}">
                <i class="bi bi-airplane fs-3"></i>
                <small>Airports</small>
            </a>

            <!-- Button 6 -->
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
            <small><i>Last Updated {{ $airport->created_at->format('M Y') }}</i></small>

            @role('admin')
            <a href="{{ route('airportdata.edit', $airport->id) }}"
            style="position:absolute; right:7px;" title="edit">
                <i class="fas fa-edit"></i>
            </a>
            @endrole
        </div>
    </div>

    <div class="row">

        <div class="col-sm-8 d-flex flex-column gap-3">
            <div class="card">
                <div class="card-header fw-bold"><img src="{{ asset('images/icon-emergency-support.png') }}" style="width: 24px; height: 24px;"> Emergency Support Tools</div>

                <div class="classification">
                    <!-- Airfield Classification -->
                    <div class="classification" style="margin-right: 30px; width: 30%;">
                      <!-- Airport -->
                      <div class="class-column">
                        <div class="class-header class-airport-category">Airfield Classification</div>
                        <div class="hospital-list">
                          <div class="hospital-row" style="flex-direction: column;">
                            <!-- Airport row 1 -->
                            <div class="hospital-item">
                              <button class="btn p-1" data-bs-toggle="modal" data-bs-target="#level6Modal">
                                  <img src="https://pg.concordreview.com/wp-content/uploads/2024/10/International-Airport.png" style="width:18px; height:18px;">
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
                            </div>
                            <!-- Airport row 2 -->
                            <div class="hospital-item">
                              <button class="btn p-1" data-bs-toggle="modal" data-bs-target="#level2Modal">
                                  <img src="https://pg.concordreview.com/wp-content/uploads/2024/10/civil-military-airport.png" style="width:18px; height:18px;">
                                  <small>Civil-Military</small>
                              </button>

                              <button class="btn p-1" data-bs-toggle="modal" data-bs-target="#level3Modal">
                                  <img src="https://pg.concordreview.com/wp-content/uploads/2024/10/military-airport-red.png" style="width:18px; height:18px;">
                                  <small>Military</small>
                              </button>

                              <button class="btn p-1" data-bs-toggle="modal" data-bs-target="#level1Modal">
                                  <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/private-airport.png" style="width:18px; height:18px;">
                                  <small>Private</small>
                              </button>
                            </div>
                          </div>

                        </div>
                      </div>
                    </div>

                    <!-- Hospital Classification -->
                    <div class="classification" style="flex-direction: column; width:100%;">
                      <div class="class-header class-medical-classification">Medical Facility Classification</div>
                      <div class="classification">
                        <!-- Advanced -->
                        <div class="class-column">
                          <div class="class-header class-advanced">Advanced</div>
                          <div class="hospital-list">
                            <div class="hospital-item">
                              <button class="btn p-1" data-bs-toggle="modal" data-bs-target="#level66Modal">
                                <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/hospital-pin-red.png" style="width:24px; height:24px;">
                                <small>Class A</small>
                              </button>
                            </div>
                          </div>
                        </div>

                        <!-- Intermediate -->
                        <div class="class-column">
                          <div class="class-header class-intermediate">Intermediate</div>
                          <div class="hospital-list">
                            <div class="hospital-row">
                              <div class="hospital-item">
                                <button class="btn p-1" data-bs-toggle="modal" data-bs-target="#level55Modal">
                                  <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/hospital_pin-blue.png" style="width:24px; height:24px;">
                                  <small>Class B</small>
                                </button>
                              </div>
                              <div class="hospital-item">
                                <button class="btn p-1" data-bs-toggle="modal" data-bs-target="#level44Modal">
                                  <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/hospital_pin-purple.png" style="width:24px; height:24px;">
                                  <small>Class C</small>
                                </button>
                              </div>
                            </div>
                          </div>
                        </div>

                        <!-- Basic -->
                        <div class="class-column">
                          <div class="class-header class-basic">Basic</div>
                          <div class="hospital-list">
                            <div class="hospital-row">
                              <div class="hospital-item">
                                <button class="btn p-1" data-bs-toggle="modal" data-bs-target="#level33Modal">
                                  <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/hospital_pin-green.png" style="width:24px; height:24px;">
                                  <small>Class D</small>
                                </button>
                              </div>
                              <div class="hospital-item">
                                <button class="btn p-1" data-bs-toggle="modal" data-bs-target="#level11Modal">
                                    <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/hospital_pin-tosca.png" style="width:24px; height:24px;">
                                    <small>PUSKESMAS</small>
                                </button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                <div class="card-body p-0">
                    <div id="map"></div>
                </div>
            </div>
        </div>

        <div class="col-sm-4 d-flex flex-column gap-3">
            <div class="card">
                <div class="card-header fw-bold"><img src="https://concord-consulting.com/static/img/cmt/icon/radar-icon.png" style="width: 24px; height: 24px;"> Nearest Airfields and Medical Facilities</div>
                <div class="card-body overflow-auto">
                    <?php echo $airport->nearest_medical_facility; ?>
                </div>
            </div>

            <div class="card">
                <div class="card-header fw-bold"><img src="{{ asset('images/icon-medical-support-website.png') }}" style="width: 24px; height: 24px;"> Emergency Medical Support</div>
                <div class="card-body" style="max-height: 250px; overflow-y: auto;">
                        <?php echo $hospital->medical_support_website; ?>
                </div>
            </div>

             <div class="card">
                <div class="card-header fw-bold"><img src="{{ asset('images/icon-police.png') }}" style="width: 24px; height: 24px;"> Nearest Police Station</div>
                <div class="card-body overflow-auto">
                    <?php echo $airport->nearest_police_station; ?>
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
        <p class="p-modal text-justify">Also known as private airfields or airstrips are primarily used for general and private aviation are owned by private individuals, groups, corporations, or organizations operated for their exclusive use that may include limited access for authorized personnel by the owner or manager. Owners are responsible to ensure safe operation, maintenance, repair, and control of who can use the facilities. Typically, they are not open to the public or provide scheduled commercial airline services and cater to private pilots, business aviation, and sometimes small charter operations. Services may be provided if authorized by the appropriate regulatory authority.</p>

        <p class="p-modal text-justify">A large majority of private airports are grass or dirt strip fields without services or facilities, they may feature amenities such as hangars, fueling facilities, maintenance services, and ground transportation options tailored to the needs of their owners or users. Private airports are not subject to the same level of regulatory oversight as public airports, but must still comply with applicable aviation regulations, safety standards, and environmental requirements. In the event of an emergency, landing at a private airport is authorized without any prior approval and should be done if landing anywhere else compromises the safety of the aircraft, crew, passengers, or cargo.</p>
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
            <h5 class="modal-title" id="disclaimerLabel">Combined (Civil-Military) Airfield</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="p-modal text-justify">Also called "joint-use airport," are used by both civilian and military aircraft, where a formal agreement exists between the military and a local government agency allowing shared access to infrastructure and facilities, typically with separate passenger terminals and designated operating areas, airspace allocation, and aircraft scheduling. Features can include aircraft maintenance, air traffic control, communications, emergency response, and fuel storage.</p>
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
        <p class="p-modal text-justify">Facilities where military aircraft operate, also known as a military airport, airbase, or air station. Features include aircraft maintenance, air traffic control, communications, emergency response, fuel and weapon storage, defensive systems, aircraft shelters, and personnel facilities.</p>
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
        <p class="p-modal text-justify">A small or remote regional domestic airfield usually located in a geographically isolated area, far from major population centers, often with difficult terrain or vast distances from other airports with limited passenger traffic. May have shorter runways, basic facilities, and limited amenities, and basic infrastructure, serving primarily local communities providing access to essential services like medical transport or regional travel, rather than large-scale commercial flights.</p>
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
        <p class="p-modal text-justify">Exclusively manages flights that originate and end within the same country, does not have international customs or border control facilities. Airport often has smaller and shorter runways, suitable for smaller regional aircraft used on domestic routes, and cannot support larger haul aircraft having less developed support services. Features can include aircraft maintenance, air traffic control, communications, emergency response, and fuel storage.</p>
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
        <p class="p-modal text-justify">Meet standards set by the International Air Transport Association (IATA) and the International Civil Aviation Organization (ICAO), facilitate transnational travel managing flights between countries, have customs and border control facilities to manage passengers and cargo, and may have dedicated terminals for domestic and international flights. International airports have longer runways to accommodate larger, heavier aircraft, are often a main hub for air traffic, and can serve as a base for larger airlines. Features can include aircraft maintenance, air traffic control, communications, emergency response, and fuel storage</p>
      </div>
    </div>
  </div>
</div>

<!-- PUSKESMAS -->
<div class="modal fade" id="level11Modal" tabindex="-1" aria-labelledby="disclaimerLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width:800px;">
    <div class="modal-content">
      <div class="modal-header">
         <div class="d-flex align-items-center">
            <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/hospital_pin-tosca.png" style="width:30px; height:30px;">
            <h5 class="modal-title" id="disclaimerLabel">Public Health Center (PUSKESMAS)</h5>
         </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="p-modal text-justify">A Public Health Center (Pusat Kesehatan Masyarakat / Puskesmas) is a government-operated primary healthcare facility regulated by the Ministry of Health of the Republic of Indonesia (Kementerian Kesehatan Republik Indonesia), commonly referred to in English as the Indonesian Ministry of Health (MOH), under national health service regulations. Puskesmas function as a first-level healthcare provider (Fasilitas Kesehatan Tingkat Pertama / FKTP) within Indonesia’s health system and BPJS Kesehatan referral framework, it operates at the sub-district (kecamatan) level and serves as the backbone of community-based healthcare delivery. Puskesmas provides comprehensive primary care services, including promotive, preventive, curative, and rehabilitative care focusing on maternal and child health, immunization, and public health programs for the defined population it serves.</p>

        <p class="p-modal text-justify">
            Most Puskesmas are automatically BPJS-contracted as government facilities. Private clinics acting as FKTP must formally contract with BPJS to serve insured patients. BPJS participants generally must first access care at FKTP before being referred to a hospital, except in emergencies.
        </p>

        <p class="p-modal text-justify">
            <b>Note:</b> BPJS (Badan Penyelenggara Jaminan Sosial), Social Security Administering Body. In Indonesia, BPJS refers to the public agencies that administer the national social security system under the National Social Security System (SJSN). There are two main bodies:
            <ul>
                <li>BPJS Kesehatan – Administers national health insurance (JKN).</li>
                <li>BPJS Ketenagakerjaan – Administers employment-related social security (work injury, old-age savings, pension, death benefits).</li>
            </ul>
            <a href="{{ asset('files/moh-regulation-no3-2020.pdf') }}" target="_blank">Indonesia Ministry of Health (MOH) regulation (Permenkes No. 3 Tahun 2020)</a>
        </p>

        <p class="p-modal text-justify">
            <strong>Bed Capacity</strong>
            <ul>
                <li>
                    <strong>Non-Inpatient Puskesmas (Rawat Jalan)</strong>
                    <ul>
                        <li>No inpatient beds</li>
                        <li>Focused on outpatient and preventive services</li>
                    </ul>
                </li>
                <li class="mt-2">
                    <strong>Inpatient Puskesmas (Rawat Inap)</strong>
                    <ul>
                        <li>Typically 5–10 short-stay beds</li>
                        <li>Designed for basic observation, uncomplicated deliveries, and short-term stabilization</li>
                        <li>Bed capacity is limited and not comparable to hospital inpatient facilities</li>
                    </ul>
                </li>
            </ul>
        </p>

        <p class="p-modal text-justify">
            <strong>Clinical Services</strong>
            <ul>
                <li>
                    <strong>Primary Medical Services</strong>
                    <ul>
                        <li>General practitioner consultations</li>
                        <li>Basic diagnosis and treatment of common illnesses</li>
                        <li>Maternal and child health services</li>
                        <li>Immunization services</li>
                        <li>Family planning services</li>
                        <li>Basic dental services</li>
                    </ul>
                </li>
                <li class="mt-2">
                    <strong>Public Health & Preventive Services</strong>
                    <ul>
                        <li>Disease surveillance and outbreak response</li>
                        <li>Health promotion and education programs</li>
                        <li>Community nutrition programs</li>
                        <li>Environmental health services</li>
                        <li>School health programs (UKS)</li>
                        <li>Posyandu supervision</li>
                    </ul>
                </li>
                <li class="mt-2">
                    <strong>Emergency & Stabilization Services</strong>
                    <ul>
                        <li>Basic emergency care</li>
                        <li>Initial trauma stabilization</li>
                        <li>Basic life support</li>
                        <li>Referral coordination to hospitals (Class D/C/B/A)</li>
                    </ul>
                </li>
                <li class="mt-2">
                    <strong>Diagnostic & Support Services</strong>
                    <ul>
                        <li>Basic laboratory testing</li>
                        <li>Basic pharmacy services</li>
                        <li>Basic medical procedures (wound care, minor procedures)</li>
                        <li>Antenatal and postnatal care services</li>
                    </ul>
                </li>
                <li class="mt-2">
                    <strong>Outreach & Community Services</strong>
                    <ul>
                        <li>Mobile health services (Puskesmas Keliling)</li>
                        <li>Home visits</li>
                        <li>Integrated community health programs</li>
                    </ul>
                </li>
            </ul>
        </p>

        <p class="p-modal text-justify">
            <strong>Public Health Center (PUSKESMAS) Role</strong>
            <ul>
                <li>First-level entry point into Indonesia’s healthcare system</li>
                <li>Primary gatekeeper in the BPJS referral system</li>
                <li>Community health program implementation center</li>
                <li>Preventive and promotive health service hub</li>
                <li>Early detection and disease surveillance center</li>
                <li>Referral coordinator to higher-level hospitals</li>
            </ul>
        </P>
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
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width:800px;">
    <div class="modal-content">
      <div class="modal-header">
         <div class="d-flex align-items-center">
            <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/hospital_pin-green.png" style="width:30px; height:30px;">
            <h5 class="modal-title" id="disclaimerLabel">Class D — Sub-district Hospital</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="p-modal text-justify">
            A Class D Hospital (Rumah Sakit Kelas D), regulated by the Ministry of Health of the Republic of Indonesia (Kementerian Kesehatan Republik Indonesia), commonly referred to in English as the Indonesian Ministry of Health (MOH). Class D hospitals provide basic inpatient, outpatient, and emergency services with general practitioners and limited specialist support, including basic medical and surgical capability.
        </p>
        <p class="p-modal text-justify">
            Class D hospitals operate mainly at the sub-district level, it serves as an entry-level facility within the referral system, managing uncomplicated cases, stabilizing emergency patients, and referring more complex conditions to higher-level hospitals. This classification applies to both public and private institutions that meet the established minimum infrastructure, staffing, and service standards.
        </p>
        <p class="p-modal text-justify">
            Public Class D hospitals commonly contract with BPJS. Private Class D hospitals may choose whether to participate. In the referral system, they receive patients from Puskesmas or other first-level facilities if contracted.
        </p>
        <p class="p-modal text-justify">
            Only hospitals that have formal cooperation agreements with BPJS Kesehatan can receive BPJS-referred patients.
        </p>
        <p class="p-modal text-justify">
            <b>Note:</b> BPJS (Badan Penyelenggara Jaminan Sosial), Social Security Administering Body. In Indonesia, BPJS refers to the public agencies that administer the national social security system under the National Social Security System (SJSN). There are two main bodies:
            <ul>
                <li>BPJS Kesehatan – Administers national health insurance (JKN).</li>
                <li>BPJS Ketenagakerjaan – Administers employment-related social security (work injury, old-age savings, pension, death benefits).</li>
            </ul>
            <a href="{{ asset('files/moh-regulation-no3-2020.pdf') }}" target="_blank">Indonesia Ministry of Health (MOH) regulation (Permenkes No. 3 Tahun 2020)</a>
        </p>
        <p class="p-modal text-justify">
            <p><strong>Bed Capacity</strong></p>
            Minimum 50 inpatient beds (Most Class D hospitals operate between 50–100 beds)
        </p>
        <p class="p-modal text-justify">
            <p><strong>Clinical Services</strong></p>
             <ul>
                <li>
                    <strong>Core Medical Services</strong>
                    <ul>
                        <li>At least 2 basic specialist services (typically Internal Medicine and Surgery, or adjusted based on regional need)</li>
                        <li>General practitioner-led services</li>
                        <li>Basic maternal and child health services</li>
                    </ul>
                </li>
                <li class="mt-2">
                    <strong>Emergency & Critical Care</strong>
                    <ul>
                        <li>24/7 Emergency Unit (basic capability)</li>
                        <li>Initial stabilization of trauma and acute cases</li>
                        <li>Referral coordination to Class C/B hospitals</li>
                    </ul>
                </li>
                <li class="mt-2">
                    <strong>Diagnostic Services</strong>
                    <ul>
                        <li>Basic laboratory</li>
                        <li>Basic radiology / X-ray (limited)</li>
                        <li>Standard ultrasound (if available)</li>
                    </ul>
                </li>
                <li class="mt-2">
                    <strong>Surgical & Therapeutic Facilities</strong>
                    <ul>
                        <li>Minor surgical procedures</li>
                        <li>Basic obstetric procedures</li>
                        <li>Wound care and emergency interventions</li>
                    </ul>
                </li>
                <li class="mt-2">
                    <strong>Supporting Medical Infrastructure</strong>
                    <ul>
                        <li>Pharmacy</li>
                        <li>Basic sterilization services</li>
                        <li>Medical records system</li>
                    </ul>
                </li>
            </ul>
        </p>
        <p class="p-modal text-justify">
            <strong>Class D Hospital Role</strong>
            <ul>
                <li>First-level hospital within the referral system</li>
                <li>Bridging facility between primary care (Puskesmas/clinics) and higher-level hospitals</li>
                <li>Basic inpatient and emergency care provider</li>
                <li>Stabilization and referral coordination center</li>
                <li>Healthcare access expansion tool in remote or newly developed areas</li>
            </ul>
        </P>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="level44Modal" tabindex="-1" aria-labelledby="disclaimerLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width:800px;">
    <div class="modal-content">
      <div class="modal-header">
         <div class="d-flex align-items-center">
            <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/hospital_pin-purple.png" style="width:30px; height:30px;">
            <h5 class="modal-title" id="disclaimerLabel">Class C — District-Level Hospital</h5>
         </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="p-modal text-justify">
            A secondary-level hospital regulated by the Ministry of Health of the Republic of Indonesia (Kementerian Kesehatan Republik Indonesia), commonly referred to in English as the Indonesian Ministry of Health (MOH). Class C hospitals provide core specialist services in internal medicine, surgery, obstetrics, and pediatrics, managing common medical conditions across inpatient and outpatient settings.
        </p>
        <p class="p-modal text-justify">
            Class C hospitals function primarily as a regency/city (kabupaten/kota) referral hospital, a Class C facility performs common surgical procedures, stabilizes emergency patients, and refers more complex or subspecialty cases to Class B or Class A hospitals. This classification applies to both public and private hospitals that meet the prescribed infrastructure, staffing, and service standards.
        </p>
        <p class="p-modal text-justify">
            Many Class C hospitals (particularly public facilities) contract with BPJS and therefore serve as the most common hospital-level provider for BPJS participants. However, private Class C hospitals may operate partially or entirely outside the BPJS system depending on their contractual status.
        </p>
        <p class="p-modal text-justify">
            Only hospitals that have formal cooperation agreements with BPJS Kesehatan can receive BPJS-referred patients.
        </p>
        <p class="p-modal text-justify">
            Note: BPJS (Badan Penyelenggara Jaminan Sosial), Social Security Administering Body. In Indonesia, BPJS refers to the public agencies that administer the national social security system under the National Social Security System (SJSN). There are two main bodies:
            <ul>
                <li>BPJS Kesehatan – Administers national health insurance (JKN).</li>
                <li>BPJS Ketenagakerjaan – Administers employment-related social security (work injury, old-age savings, pension, death benefits).</li>
            </ul>
            <a href="{{ asset('files/moh-regulation-no3-2020.pdf') }}" target="_blank">Indonesia Ministry of Health (MOH) regulation (Permenkes No. 3 Tahun 2020)</a>
        </p>
        <p class="p-modal text-justify">
            <p><strong>Bed Capacity</strong></p>
            Minimum 100 inpatient beds (Most Class C hospitals operate between 100–200 beds, depending on district demand)
        </p>
        <p class="p-modal text-justify">
            <p><strong>Clinical Services</strong></p>
             <ul>
                <li>
                    <strong>Core Medical Services</strong>
                    <ul>
                        <li>4 basic specialists: Internal Medicine, Surgery, Pediatrics, Obstetrics & Gynecology</li>
                        <li>General anesthesia services</li>
                        <li>Basic radiology and pathology services</li>
                    </ul>
                </li>
                <li class="mt-2">
                    <strong>Emergency & Critical Care</strong>
                    <ul>
                        <li>24/7 Emergency Department (IGD)</li>
                        <li>Basic resuscitation capability</li>
                        <li>Limited ICU or high-dependency care (depending on facility)</li>
                        <li>Maternal and neonatal emergency care</li>
                    </ul>
                </li>
                <li class="mt-2">
                    <strong>Diagnostic Services</strong>
                    <ul>
                        <li>Basic laboratory services</li>
                        <li>X-ray radiology</li>
                        <li>Standard ultrasound</li>
                        <li>Blood transfusion service (limited capacity)</li>
                    </ul>
                </li>
                <li class="mt-2">
                    <strong>Surgical & Therapeutic Facilities</strong>
                    <ul>
                        <li>Operating theatre(s) for general surgery</li>
                        <li>Obstetric surgery capability (C-section)</li>
                        <li>Minor orthopedic and emergency surgical procedures</li>
                        <li>Basic inpatient and outpatient treatment</li>
                    </ul>
                </li>
                <li class="mt-2">
                    <strong>Supporting Medical Infrastructure</strong>
                    <ul>
                        <li>Pharmacy</li>
                        <li>CSSD (basic sterilization services)</li>
                        <li>Medical records system</li>
                        <li>Nutrition services</li>
                    </ul>
                </li>
            </ul>
        </p>
        <p class="p-modal text-justify">
            <strong>Class C Hospital Role</strong>
            <ul>
                <li>District-level referral hospital</li>
                <li>Primary inpatient and surgical provider for local population</li>
                <li>Stabilization point before referral to Class B/A hospitals</li>
                <li>Key BPJS referral destination from primary care (Puskesmas/clinics)</li>
                <li>Essential maternal and emergency care provider at regional level</li>
            </ul>
        </P>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="level55Modal" tabindex="-1" aria-labelledby="disclaimerLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width:800px;">
    <div class="modal-content">
      <div class="modal-header">
        <div class="d-flex align-items-center">
            <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/hospital_pin-blue.png" style="width:30px; height:30px;">
            <h5 class="modal-title" id="disclaimerLabel">Class B — Provincial Referral Hospital</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="p-modal text-justify">
            Secondary–tertiary level referral hospital regulated by the Ministry of Health of the Republic of Indonesia (Kementerian Kesehatan Republik Indonesia), commonly referred to in English as the Indonesian Ministry of Health (MOH). Class B hospitals provide comprehensive specialist medical services and selected subspecialist services, supported by advanced diagnostic and therapeutic facilities.
        </p>
        <p class="p-modal text-justify">
           Class B hospitals function as provincial or inter-district referral centers, managing moderate to complex medical and surgical cases referred from lower-level hospitals (Class C and D), while referring highly complex subspecialty cases to Class A hospitals. This classification applies equally to public and private hospitals that meet the required standards of infrastructure, human resources, equipment, and service capability.
        </p>
        <p class="p-modal text-justify">
           Public Class B hospitals typically contract with BPJS. Private Class B hospitals may selectively contract or operate fully private services. BPJS patients are accepted only in contracted facilities and generally arrive through referrals from Class C or D hospitals.
        </p>
        <p class="p-modal text-justify">
           Only hospitals that have formal cooperation agreements with BPJS Kesehatan can receive BPJS-referred patients.
        </p>
        <p class="p-modal text-justify">
           <b>Note:</b> BPJS (Badan Penyelenggara Jaminan Sosial), Social Security Administering Body. In Indonesia, BPJS refers to the public agencies that administer the national social security system under the National Social Security System (SJSN). There are two main bodies:
            <ul>
                <li>BPJS Kesehatan – Administers national health insurance (JKN).</li>
                <li>BPJS Ketenagakerjaan – Administers employment-related social security (work injury, old-age savings, pension, death benefits).</li>
            </ul>
            <a href="{{ asset('files/moh-regulation-no3-2020.pdf') }}" target="_blank">Indonesia Ministry of Health (MOH) regulation (Permenkes No. 3 Tahun 2020)</a>
        </p>
        <p class="p-modal text-justify">
            <p><strong>Bed Capacity</strong></p>
            Minimum 200 inpatient beds. Most Class B hospitals operate between 200–400+ beds, depending on regional demand and provincial role.
        </p>
        <p class="p-modal text-justify">
            <p><strong>Clinical Services</strong></p>
             <ul>
                <li>
                    <strong>Core Medical Services</strong>
                    <ul>
                        <li>4 basic specialists: Internal Medicine, Surgery, Pediatrics, Obstetrics & Gynecology</li>
                        <li>Additional major specialties (e.g., Anesthesiology, Radiology, Pathology, Neurology, Psychiatry, Dermatology, ENT, Ophthalmology)</li>
                        <li>Selected subspecialty services (e.g., cardiology, orthopedics, urology, pulmonology — depending on hospital capability)</li>
                    </ul>
                </li>
                <li class="mt-2">
                    <strong>Emergency & Critical Care</strong>
                    <ul>
                        <li>24/7 Emergency Department (IGD)</li>
                        <li>ICU</li>
                        <li>NICU and/or PICU (depending on capacity)</li>
                        <li>HCU (High Care Unit)</li>
                        <li>Trauma stabilization capability</li>
                    </ul>
                </li>
                <li class="mt-2">
                    <strong>Diagnostic Services</strong>
                    <ul>
                        <li>CT Scan (standard in most Class B hospitals)</li>
                        <li>Advanced ultrasound</li>
                        <li>Comprehensive laboratory services</li>
                        <li>Blood bank/transfusion unit</li>
                        <li>Endoscopy services</li>
                        <li>Basic interventional procedures</li>
                    </ul>
                </li>
                <li class="mt-2">
                    <strong>Surgical & Therapeutic Facilities</strong>
                    <ul>
                        <li>Multiple operating theatres</li>
                        <li>Major general surgery capability</li>
                        <li>Orthopedic and obstetric surgery capability</li>
                        <li>Dialysis unit (in most provincial hospitals)</li>
                        <li>Chemotherapy (in hospitals with oncology service)</li>
                    </ul>
                </li>
                <li class="mt-2">
                    <strong>Supporting Medical Infrastructure</strong>
                    <ul>
                        <li>24-hour pharmacy</li>
                        <li>Central Sterile Supply Department (CSSD)</li>
                        <li>Medical rehabilitation service</li>
                        <li>Nutrition & dietetics service</li>
                        <li>Medical records system</li>
                    </ul>
                </li>
            </ul>
        </p>
        <p class="p-modal text-justify">
            <strong>Class B Hospital Role</strong>
            <ul>
                <li>Provincial-level referral hospital</li>
                <li>Secondary escalation point in the BPJS referral system (from Class C/D)</li>
                <li>Regional center for specialist services</li>
                <li>Stabilization and management center for moderate to complex cases</li>
                <li>Supporting teaching hospital (in many provinces)</li>
            </ul>
        </P>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="level66Modal" tabindex="-1" aria-labelledby="disclaimerLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width:800px;">
    <div class="modal-content">
      <div class="modal-header">
        <div class="d-flex align-items-center">
            <img src="https://pg.concordreview.com/wp-content/uploads/2025/01/hospital-pin-red.png" style="width:30px; height:30px;">
            <h5 class="modal-title" id="disclaimerLabel">Class A — National Referral Hospital</h5>
        </div>
         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="p-modal text-justify">
            A Class A Hospital (Rumah Sakit Kelas A), regulated by the Ministry of Health of the Republic of Indonesia (Kementerian Kesehatan Republik Indonesia), commonly referred to in English as the Indonesian Ministry of Health (MOH), represents the highest hospital classification in Indonesia.
        </p>
        <p class="p-modal text-justify">
            Class A hospitals function as national or apex referral centers within Indonesia’s tiered healthcare and Badan Penyelenggara Jaminan Sosial (BPJS) referral system, provide the most comprehensive range of specialist and subspecialist services, supported by advanced diagnostic, therapeutic, critical care capability, and large bed capacity. Serving as national and/or top-tier referral centers within the healthcare system.
        </p>
        <p class="p-modal text-justify">
            Class A hospitals manage highly complex, multidisciplinary medical and surgical cases referred from Class B, C, and D hospitals, and frequently function as teaching and research institutions.
        </p>
        <p class="p-modal text-justify">
            This classification applies to both public and private hospitals that meet the highest standards of infrastructure, medical personnel, equipment, and service capability.
        </p>
        <p class="p-modal text-justify">
            Public Class A hospitals generally participate in BPJS Kesehatan, receive BPJS patients primarily through referral from Class B hospitals or directly in emergency cases.
        </p>
        <p class="p-modal text-justify">
            Private Class A hospitals may or may not contract with BPJS. Only hospitals that have formal cooperation agreements with BPJS Kesehatan can receive BPJS-referred patients.
        </p>
        <p class="p-modal text-justify">
            <b>Note:</b> BPJS (Badan Penyelenggara Jaminan Sosial), Social Security Administering Body. In Indonesia, BPJS refers to the public agencies that administer the national social security system under the National Social Security System (SJSN). There are two main bodies:
            <ul>
                <li>BPJS Kesehatan – Administers national health insurance (JKN).</li>
                <li>BPJS Ketenagakerjaan – Administers employment-related social security (work injury, old-age savings, pension, death benefits).</li>
            </ul>
            <a href="{{ asset('files/moh-regulation-no3-2020.pdf') }}" target="_blank">Indonesia Ministry of Health (MOH) regulation (Permenkes No. 3 Tahun 2020)</a>
        </p>
        <p class="p-modal text-justify">
            <p><strong>Bed Capacity</strong></p>
            Minimum 250 inpatient beds. Major national referral hospitals often exceed 500–1,000 beds depending on scope and regional demand.
        </p>
        <p class="p-modal text-justify">
            <p><strong>Clinical Services</strong></p>
             <ul>
                <li>
                    <strong>Core Medical Services</strong>
                    <ul>
                        <li>4 basic specialists: Internal Medicine, Surgery, Pediatrics, Obstetrics & Gynecology (Ob/gyn)</li>
                        <li>Full range of medical subspecialties (cardiology, nephrology, pulmonology, oncology, etc.)</li>
                        <li>Full range of surgical subspecialties (neurosurgery, cardiothoracic, orthopedics, urology, plastic surgery, etc.)</li>
                        <li>Comprehensive non-surgical specialties (neurology, psychiatry, dermatology, ENT, ophthalmology, rehabilitation medicine)</li>
                    </ul>
                </li>
                <li class="mt-2">
                    <strong>Emergency & Critical Care</strong>
                    <ul>
                        <li>24/7 Emergency Department (IGD)</li>
                        <li>ICU, NICU, PICU, HCU</li>
                        <li>Advanced trauma and resuscitation capability</li>
                        <li>Disaster response readiness</li>
                    </ul>
                </li>
                <li class="mt-2">
                    <strong>Diagnostic Services</strong>
                    <ul>
                        <li>CT Scan & MRI</li>
                        <li>Cath Lab (cardiac catheterization)</li>
                        <li>Advanced radiology & interventional radiology</li>
                        <li>Full clinical & anatomical pathology labs</li>
                        <li>Blood bank</li>
                        <li>Endoscopy & advanced imaging</li>
                    </ul>
                </li>
                <li class="mt-2">
                    <strong>Surgical & Therapeutic Facilities</strong>
                    <ul>
                        <li>Multiple fully equipped operating theatres</li>
                        <li>Cardiac & neurosurgery capability</li>
                        <li>Dialysis units</li>
                        <li>Chemotherapy & oncology services</li>
                        <li>Radiotherapy (in comprehensive centers)</li>
                    </ul>
                </li>
                <li class="mt-2">
                    <strong>Supporting Medical Infrastructure</strong>
                    <ul>
                        <li>24-hour pharmacy</li>
                        <li>CSSD (Central Sterile Supply Department)</li>
                        <li>Medical rehabilitation center</li>
                        <li>Medical gas system</li>
                        <li>Electronic medical records (in modern facilities)</li>
                        <li>Nutrition & dietetics service</li>
                    </ul>
                </li>
            </ul>
        </p>
        <p class="p-modal text-justify">
            <strong>Class A Hospital Role</strong>
            <ul>
                <li>National and/or top-tier referral hospital</li>
                <li>Highest escalation level in BPJS referral system</li>
                <li>Teaching hospital for medical students, residents, and specialists</li>
                <li>Research and clinical innovation center</li>
                <li>Complex case management center (multi-disciplinary cases)</li>
                <li>National disaster and emergency medical support hub</li>
            </ul>
        </P>
      </div>
    </div>
  </div>
</div>

@endsection

@push('service')

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.fullscreen/1.6.0/Control.FullScreen.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const airportData = {!! json_encode([
        'id'        => $airport->id,
        'name'      => $airport->airport_name,
        'latitude'  => $airport->latitude,
        'longitude' => $airport->longitude,
        'icon'      => $airport->icon ?? '',
        'image'     => $airport->image ?? '',
        'address'   => $airport->address ?? '',
        'telephone' => $airport->telephone ?? '',
        'website'   => $airport->website ?? '',
    ]) !!};

    const nearbyAirports = @json($nearbyAirports);
    const nearbyHospitals = @json($nearbyHospitals);
    let radiusKm = {{ $radius_km }};

    let map, mainAirportMarker, radiusCircle, routingControl = null;
    const nearbyMarkersGroup = L.featureGroup();

    const DEFAULT_MAIN_AIRPORT_ICON_URL = 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png';
    const DEFAULT_HOSPITAL_ICON_URL     = 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png';
    const DEFAULT_AIRPORT_ICON_URL      = 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png';

    const mainAirportIcon = new L.Icon({
        iconUrl: DEFAULT_MAIN_AIRPORT_ICON_URL,
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        iconSize: [25, 41], iconAnchor: [12, 41],
        popupAnchor: [1, -34], shadowSize: [41, 41]
    });

    // === Inisialisasi Peta ===
    function initializeMap() {
        map = L.map('map')
            .setView([airportData.latitude, airportData.longitude], 11);

        const osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors', maxZoom: 19
        });

        const satelliteLayer = L.tileLayer(
            'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}',
            { attribution: 'Tiles © Esri', maxZoom: 19 }
        ).addTo(map);

       L.control.layers(
            { "Street Map": osmLayer, "Satellite Map": satelliteLayer },
            null,
            { position: 'topleft' }
        ).addTo(map);

        L.control.fullscreen({ position: 'topleft' }).addTo(map);

        // === Styling posisi kontrol ===
        const style = document.createElement('style');
        style.textContent = `
        .leaflet-top.leaflet-left .leaflet-control-layers { margin-top: 5px !important; }
        .leaflet-top.leaflet-left .leaflet-control-zoom { margin-top: 10px !important; }
        `;
        document.head.appendChild(style);

        nearbyMarkersGroup.addTo(map);
    }

    // === Tambahkan Marker Utama + Radius ===
    function addMainAirportAndCircle() {
        mainAirportMarker = L.marker([airportData.latitude, airportData.longitude], { icon: mainAirportIcon })
            .addTo(map)
            .bindPopup(`<b>${airportData.name}</b><br>This is the main airport.`);

        radiusCircle = L.circle([airportData.latitude, airportData.longitude], {
            color: 'red', fillColor: '#f03', fillOpacity: 0.1, radius: radiusKm * 1000
        }).addTo(map);
    }

    // === Tambahkan Marker Sekitar ===
    function addNearbyMarkers(data, defaultIconUrl, type, filters = {}) {
        data.forEach(item => {
            const distance = calculateDistance(
                airportData.latitude, airportData.longitude,
                item.latitude, item.longitude
            );
            if (distance > radiusKm) return;

            // Filter hospital by facility level
            if (type === 'Hospital' && filters.hospitalLevels?.length > 0) {
                const itemLevel = (item.facility_level || '').toLowerCase();
                const allowed = filters.hospitalLevels.map(l => l.toLowerCase());
                if (!allowed.includes(itemLevel)) return;
            }

            // Filter airport by category
            if (type === 'Airport' && filters.airportClassifications?.length > 0) {
                const airportCategories = (item.category || '').split(',').map(c => c.trim().toLowerCase());
                const allowed = filters.airportClassifications.map(c => c.toLowerCase());
                if (!airportCategories.some(cat => allowed.includes(cat))) return;
            }

            const icon = L.icon({
                iconUrl: item.icon || defaultIconUrl,
                iconSize: [24, 24], iconAnchor: [12, 24], popupAnchor: [0, -20]
            });

            const marker = L.marker([item.latitude, item.longitude], { icon });
            const name = item.name || item.airport_name || 'N/A';
            const level = item.facility_level || item.category || 'N/A';
            const distanceText = `<strong>Distance:</strong> ${distance.toFixed(2)} km`;
            const detailUrl = (type === 'Airport')
                ? `/airports/${item.id}/detail`
                : `/hospitals/${item.id}`;

            marker.bindPopup(`
                <div style="font-size:13px;">
                    <a href="${detailUrl}" target="_blank">${name}</a><br>
                    ${level}<br>${distanceText}<br>
                    <button class="btn btn-sm btn-primary mt-2"
                        onclick="getDirection(${item.latitude}, ${item.longitude}, '${name}')">
                        Get Direction
                    </button>
                </div>
            `);

            nearbyMarkersGroup.addLayer(marker);
        });
    }

    function calculateDistance(lat1, lon1, lat2, lon2) {
        const R = 6371;
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLon = (lon2 - lon1) * Math.PI / 180;
        const a = Math.sin(dLat / 2) ** 2 +
            Math.cos(lat1 * Math.PI / 180) *
            Math.cos(lat2 * Math.PI / 180) *
            Math.sin(dLon / 2) ** 2;
        return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    }

    // === Routing ===
    window.getDirection = function(lat, lng, name) {
        if (routingControl) map.removeControl(routingControl);

        routingControl = L.Routing.control({
            waypoints: [
                L.latLng(airportData.latitude, airportData.longitude),
                L.latLng(lat, lng)
            ],
            routeWhileDragging: false,
            addWaypoints: false,
            collapsible: true,
            show: false,
            createMarker: () => null,
            lineOptions: { styles: [{ color: 'red', opacity: 0.7, weight: 4 }] }
        }).addTo(map);

        routingControl.on('routesfound', () => {
            if (mainAirportMarker?.bringToFront) mainAirportMarker.bringToFront();
            nearbyMarkersGroup.eachLayer(marker => marker.bringToFront && marker.bringToFront());
        });
    };

    function fitMapToBounds() {
        const bounds = L.featureGroup([mainAirportMarker, nearbyMarkersGroup, radiusCircle]).getBounds();
        if (bounds.isValid()) map.fitBounds(bounds, { padding: [50, 50] });
    }

    function updateMarkers(filterType, hospitalLevels, airportClassifications) {
        nearbyMarkersGroup.clearLayers();
        if (radiusCircle) map.removeLayer(radiusCircle);
        addMainAirportAndCircle();

        const filters = { hospitalLevels, airportClassifications };

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

    // === Gabungan Filter + Radius ===
    const FilterRadiusControl = L.Control.extend({
        options: { position: 'topright' },
        onAdd: function() {
            const div = L.DomUtil.create('div', 'leaflet-bar leaflet-control p-2 bg-white rounded');
            div.style.width = '260px';
            div.style.boxShadow = '0 2px 8px rgba(0,0,0,0.2)';
            div.style.maxHeight = '85vh';
            div.style.overflowY = 'auto';

            div.innerHTML = `
                <h6 style="text-align:center;">Map Filters</h6>
                <label><strong>Radius:</strong> <span id="radiusLabel">${radiusKm}</span> km</label><br>
                <input type="range" id="radiusRange" min="10" max="500" step="10" value="${radiusKm}" class="form-range mb-2"><br>

                <select id="mapFilter" class="form-select form-select-sm mb-2">
                    <option value="all">Show All</option>
                    <option value="hospital">Hospitals</option>
                    <option value="airport">Airports</option>
                </select>

                <div id="hospitalFilter" style="display:none;">
                    <strong>Facility Level:</strong><br>
                    ${['Class A','Class B','Class C','Class D','Public Health Center (PUSKESMAS)']
                        .map(lvl => `
                        <label style="display:block;font-size:13px;">
                            <input type="checkbox" name="hospitalLevel" value="${lvl}"> ${lvl}
                        </label>`).join('')}
                </div>

                <div id="airportFilter" style="display:none;margin-top:8px;">
                    <strong>Category:</strong><br>
                    ${['International','Domestic','Military','Regional','Private']
                        .map(cls => `
                        <label style="display:block;font-size:13px;">
                            <input type="checkbox" name="airportClass" value="${cls}"> ${cls}
                        </label>`).join('')}
                </div>

                <button id="resetFilter" class="btn btn-sm btn-secondary mt-3 w-100">Reset All</button>
            `;

            L.DomEvent.disableClickPropagation(div);
            return div;
        }
    });

    function refreshFilters() {
        const selectedType = document.querySelector('#mapFilter')?.value || 'all';
        const selectedHospitalLevels = Array.from(document.querySelectorAll('input[name="hospitalLevel"]:checked')).map(el => el.value);
        const selectedAirportClasses = Array.from(document.querySelectorAll('input[name="airportClass"]:checked')).map(el => el.value);
        updateMarkers(selectedType, selectedHospitalLevels, selectedAirportClasses);
    }

    initializeMap();
    addMainAirportAndCircle();
    updateMarkers('all', [], []);
    map.addControl(new FilterRadiusControl());

    // === Event Binding ===
    document.addEventListener('input', e => {
        if (e.target.id === 'radiusRange') {
            radiusKm = parseInt(e.target.value);
            document.getElementById('radiusLabel').textContent = radiusKm;
            refreshFilters();
        }
    });

    document.addEventListener('change', e => {
        if (e.target.id === 'mapFilter') {
            const val = e.target.value;
            document.getElementById('hospitalFilter').style.display = val === 'hospital' ? 'block' : 'none';
            document.getElementById('airportFilter').style.display = val === 'airport' ? 'block' : 'none';
            refreshFilters();
        }
        if (e.target.name === 'hospitalLevel' || e.target.name === 'airportClass') {
            refreshFilters();
        }
    });

    document.addEventListener('click', e => {
        if (e.target.id === 'resetFilter') {
            document.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);
            document.getElementById('mapFilter').value = 'all';
            document.getElementById('hospitalFilter').style.display = 'none';
            document.getElementById('airportFilter').style.display = 'none';
            radiusKm = {{ $radius_km }};
            document.getElementById('radiusRange').value = radiusKm;
            document.getElementById('radiusLabel').textContent = radiusKm;
            refreshFilters();
        }
    });
});
</script>

@endpush
