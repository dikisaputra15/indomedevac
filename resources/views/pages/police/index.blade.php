@extends('layouts.master')

@section('title', 'Police')
@section('page-title', 'Papua New Guinea Police')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

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

    /* === Info modal with tabs (Polda) ===
       Lebarnya cukup untuk menampung satu baris tab, tingginya mengikuti isi. */
    .info-modal-dialog {
        max-width: 1180px;
        width: 95vw;
    }
    .info-modal-dialog .modal-content {
        max-height: 88vh;
        border: none;
        border-radius: 10px;
        overflow: hidden;
    }
    .info-modal-dialog .modal-header {
        flex: 0 0 auto;
        background: #f8f9fa;
    }

    .info-modal-tabs {
        background: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        flex: 0 0 auto;
        flex-wrap: nowrap;
        gap: 2px;
        overflow-x: auto;
    }
    .info-modal-tabs .nav-link {
        border: 1px solid transparent;
        border-bottom: none;
        border-radius: 6px 6px 0 0;
        color: #55606e;
        font-size: 13px;
        font-weight: 600;
        padding: 8px 14px;
        white-space: nowrap;
    }
    .info-modal-tabs .nav-link:hover {
        background: #eef2f7;
        color: #395272;
    }
    .info-modal-tabs .nav-link.active {
        background: #fff;
        color: #395272;
        border-color: #dee2e6 #dee2e6 #fff;
    }
    .info-modal-body {
        padding: 0;
        overflow: hidden;
        flex: 1 1 auto;
        min-height: 0;
    }
    .info-modal-content {
        overflow-y: auto;
        padding: 18px 24px 24px 24px;
        min-height: 260px;
        max-height: calc(88vh - 120px);
    }
    .info-modal-content ul {
        padding-left: 20px;
        margin-bottom: 12px;
    }
    .info-modal-content ul li {
        margin-bottom: 6px;
        line-height: 20px;
        text-align: justify;
    }
    .info-modal-content ul ul {
        margin-top: 6px;
        margin-bottom: 4px;
        list-style-type: circle;
        padding-left: 20px;
    }
    .info-modal-content ul ul li {
        margin-bottom: 4px;
    }
    .info-modal-figure {
        margin-top: 14px;
        text-align: center;
    }
    .info-modal-figure img {
        display: inline-block;
        max-width: 100%;
        height: auto;
        border: 1px solid #e3e8ee;
        border-radius: 6px;
    }
    .info-modal-note {
        margin: 8px 0 4px 0;
        padding: 8px 12px;
        background: #f4f8fb;
        border-left: 3px solid #395272;
        border-radius: 4px;
        font-size: 12.5px;
        line-height: 19px;
        text-align: justify;
        color: #445060;
    }
    /* Tabel klasifikasi Polda (gaya biru bertingkat) */
    .polda-class-table {
        width: 100%;
        margin: 4px 0 8px 0;
        border-collapse: collapse;
        font-size: 13px;
        line-height: 19px;
        color: #10333f;
    }
    .polda-class-table th,
    .polda-class-table td {
        padding: 10px 12px;
        border: 1px solid #fff;
        text-align: justify;
        vertical-align: top;
    }
    .polda-class-table thead th {
        background: #1c7fa4;
        color: #fff;
        font-weight: 700;
        text-align: left;
        vertical-align: middle;
    }
    .polda-class-table tbody tr:nth-child(odd) td {
        background: #62c2dd;
    }
    .polda-class-table tbody tr:nth-child(even) td {
        background: #cbe7f4;
    }

    /* Tabel klasifikasi unit Polres & Polsek (kolom pertama navy, baris biru bertingkat) */
    .unit-class-table {
        width: 100%;
        margin: 4px 0 8px 0;
        border-collapse: collapse;
        font-size: 13px;
        line-height: 19px;
        color: #10333f;
    }
    .unit-class-table th,
    .unit-class-table td {
        padding: 10px 12px;
        border: 1px solid #fff;
        vertical-align: top;
    }
    .unit-class-table thead th {
        background: #14506a;
        color: #fff;
        font-weight: 700;
        text-align: center;
        vertical-align: middle;
    }
    .unit-class-table tbody th {
        background: #14506a;
        color: #fff;
        font-weight: 700;
        text-align: left;
    }
    .unit-class-table tbody tr:nth-child(odd) td {
        background: #83c9e5;
    }
    .unit-class-table tbody tr:nth-child(even) td {
        background: #cfe7f5;
    }

    .info-modal-table {
        font-size: 13px;
    }
    .info-modal-table thead th {
        background: #395272;
        color: #fff;
        font-weight: 600;
        vertical-align: middle;
    }
    .info-modal-table td {
        vertical-align: top;
    }

    /* === Image-only modals (Police Area Layer, Cmd Flow) ===
       Dialog menyesuaikan lebar gambar, jadi tidak ada ruang kosong di kiri-kanan. */
    .image-modal-dialog {
        /* --img-ratio = lebar/tinggi gambar, di-set per modal.
           Lebar dialog = tinggi gambar maksimal x rasio + padding body. */
        --img-ratio: 1;
        width: calc(78vh * var(--img-ratio) + 24px);
        max-width: 96vw;
    }
    .image-modal-dialog .modal-content {
        border: none;
        border-radius: 10px;
        overflow: hidden;
    }
    .image-modal-dialog .modal-header {
        background: #f8f9fa;
        border-bottom: 1px solid #e6e6e6;
    }
    .image-modal-dialog .modal-title {
        font-size: 15px;
        font-weight: 600;
    }
    .image-modal-body {
        padding: 12px;
        display: flex;
        justify-content: center;
        align-items: center;
        overflow: auto;
    }
    .image-modal-body img {
        display: block;
        max-width: 100%;
        max-height: 78vh;
        width: auto;
        height: auto;
        border-radius: 6px;
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

        .select-input {
            border: 1px solid #ccc;
            border-radius: 6px;
            padding: 8px 10px;
            background: #fff;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .select-input input {
            border: none;
            width: 100%;
            cursor: pointer;
            background: transparent;
            outline: none;
        }

        .select-dropdown {
            display: none;
            position: absolute;
            width: 100%;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 6px;
            margin-top: 3px;
            z-index: 9999;
            max-height: 250px;
            overflow: hidden;
        }

        .select-dropdown.show {
            display: block;
        }

        .dropdown-search {
            width: 100%;
            border: none;
            border-bottom: 1px solid #ddd;
            padding: 8px;
            outline: none;
        }

        #provinceList {
            list-style: none;
            padding: 0;
            margin: 0;
            max-height: 180px;
            overflow-y: auto;
        }

        #provinceList li {
            padding: 5px 10px;
        }

        #provinceList li:hover {
            background: #f5f5f5;
        }

        #provinceList label {
            width: 100%;
            margin: 0;
            cursor: pointer;
        }

        /* ===== Google Places Autocomplete Fix ===== */
        .pac-container {
            z-index: 99999 !important;
            border-radius: 8px !important;
            box-shadow: 0 4px 16px rgba(0,0,0,0.2) !important;
            font-family: inherit !important;
            margin-top: 2px !important;
            border: 1px solid #ddd !important;
        }

        .pac-item {
            padding: 6px 12px !important;
            cursor: pointer !important;
            font-size: 13px !important;
            border-top: 1px solid #f0f0f0 !important;
        }

        .pac-item:hover {
            background: #f0f6ff !important;
        }

        .pac-item-query {
            font-size: 13px !important;
            font-weight: 600 !important;
            color: #333 !important;
        }

        .pac-matched {
            color: #1a73e8 !important;
            font-weight: 700 !important;
        }

        #locationSearchMap:focus {
            outline: none !important;
            border-color: #1a73e8 !important;
            box-shadow: 0 0 0 2px rgba(26,115,232,0.2) !important;
        }
</style>
@endpush

@section('conten')

<div class="card">

    <div class="d-flex justify-content-end p-3" style="background-color: #dfeaf1;">

        <div class="d-flex gap-2 mt-2">

            <a href="{{ url('home') }}" class="btn btn-danger d-flex flex-column align-items-center p-3 {{ request()->is('home') ? 'active' : '' }}">
                <i class="bi bi-house-door-fill fs-3"></i>
                <small>Home</small>
            </a>

            <a href="{{ url('airports') }}" class="btn btn-danger d-flex flex-column align-items-center p-3 {{ request()->is('airports') ? 'active' : '' }}">
                <i class="bi bi-airplane fs-3"></i>
                <small>Aviation</small>
            </a>

            <a href="{{ url('hospital') }}" class="btn btn-danger d-flex flex-column align-items-center p-3 {{ request()->is('hospital') ? 'active' : '' }}">
             <img src="{{ asset('images/icon-medical.png') }}" style="width: 24px; height: 24px;">
                <small>Medical</small>
            </a>

            <a href="{{ url('police') }}" class="btn btn-danger d-flex flex-column align-items-center p-3 {{ request()->is('police') ? 'active' : '' }}">
            <i class="bi bi-person-badge" style="width: 24px; height: 24px;"></i>
                <small>Police</small>
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

            <div class="d-flex align-items-center gap-3">
                <span class="fw-bold me-2">Map Legend:</span>

                <button class="btn p-1" data-bs-toggle="modal" data-bs-target="#police6Modal">
                    <img src="{{ asset('images/Layer1.png') }}" style="width:15px; height:15px;">
                    <small>National Police HQ (POLRI)</small>
                </button>

                <button class="btn p-1" data-bs-toggle="modal" data-bs-target="#police5Modal">
                    <img src="{{ asset('images/Layer2.png') }}" style="width:15px; height:15px;">
                    <small>Polda</small>
                </button>

                <button class="btn p-1" data-bs-toggle="modal" data-bs-target="#police4Modal">
                    <img src="{{ asset('images/Layer3.png') }}" style="width:15px; height:15px;">
                    <small>Polres</small>
                </button>

                <button class="btn p-1" data-bs-toggle="modal" data-bs-target="#police3Modal">
                    <img src="{{ asset('images/Layer4.png') }}" style="width:15px; height:15px;">
                    <small>Polsek</small>
                </button>

                <button class="btn p-1" data-bs-toggle="modal" data-bs-target="#police2Modal">
                    <img src="{{ asset('images/Brimob.png') }}" style="width:15px; height:15px;">
                    <small>Brimob</small>
                </button>

                <button class="btn p-1" data-bs-toggle="modal" data-bs-target="#police1Modal">
                    <img src="{{ asset('images/Gegana.png') }}" style="width:15px; height:15px;">
                    <small>Gegana</small>
                </button>

                <button type="button" class="btn btn-danger d-flex flex-column align-items-center p-3 {{ request()->is('hospital') ? 'active' : '' }}"
                    data-bs-toggle="modal" data-bs-target="#policeAreaLayerModal">
                <img src="{{ asset('images/icon-structure.png') }}" style="width: 20px; height: 20px;">
                    <small>Police Area Layer</small>
                </button>

               <button type="button" class="btn btn-danger d-flex flex-column align-items-center p-3 {{ request()->is('hospital') ? 'active' : '' }}"
                    data-bs-toggle="modal" data-bs-target="#cmdFlowModal">
                <img src="{{ asset('images/icon-flow.png') }}" style="width: 20px; height: 20px;">
                    <small>Cmd Flow</small>
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
       <p class="p-modal text-justify">Every attempt has been made to ensure the completeness and accuracy of the most updated information and data available. Clients are advised, however, that provided information, and data is subject to change.</p>
       <h5 class="modal-title" id="disclaimerLabel">Google Maps Link</h5>
       <p class="p-modal text-justify">Google Maps may automatically display or translate content based on the user’s current region, browser settings, or Google account preferences. This issue may occur when opening google maps link from TCMT platform using Microsoft Edge. For the best experience, we recommend opening the Google Chrome link while logged into your Google account. You can also use your browser’s translation feature to view Google Maps in your preferred language.</p>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="police1Modal" tabindex="-1" aria-labelledby="geganaLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered info-modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <div class="d-flex align-items-center gap-2">
            <img src="{{ asset('images/Gegana.png') }}" style="width:18px; height:18px;">
            <h5 class="modal-title mb-0" id="geganaLabel">Bomb Squad / Special Police Force &mdash; Pasukan Gegana</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <ul class="nav nav-tabs info-modal-tabs px-3 pt-2" id="geganaTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="gegana-definition-tab" data-bs-toggle="tab" data-bs-target="#gegana-definition"
                type="button" role="tab" aria-controls="gegana-definition" aria-selected="true">Definition &amp; Purpose</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="gegana-commander-tab" data-bs-toggle="tab" data-bs-target="#gegana-commander"
                type="button" role="tab" aria-controls="gegana-commander" aria-selected="false">Commander</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="gegana-structure-tab" data-bs-toggle="tab" data-bs-target="#gegana-structure"
                type="button" role="tab" aria-controls="gegana-structure" aria-selected="false">Gegana Unit Classification</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="gegana-roles-tab" data-bs-toggle="tab" data-bs-target="#gegana-roles"
                type="button" role="tab" aria-controls="gegana-roles" aria-selected="false">Responsibilities/Roles/Function</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="gegana-geographic-tab" data-bs-toggle="tab" data-bs-target="#gegana-geographic"
                type="button" role="tab" aria-controls="gegana-geographic" aria-selected="false">Operational Distribution</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="gegana-equivalent-tab" data-bs-toggle="tab" data-bs-target="#gegana-equivalent"
                type="button" role="tab" aria-controls="gegana-equivalent" aria-selected="false">Police &ndash; Civil &ndash; Military Equivalent</button>
        </li>
      </ul>

      <div class="modal-body info-modal-body">
        <div class="tab-content info-modal-content" id="geganaTabContent">
            <div class="tab-pane fade show active" id="gegana-definition" role="tabpanel" aria-labelledby="gegana-definition-tab" tabindex="0">
                <p class="p-modal text-justify">
                    <strong>Definition:</strong> Gegana is the specialized high-risk operational force of the Indonesian National Police (Polri) under the Mobile Brigade Corps (Korps Brigade Mobil &ndash; Korbrimob Polri). At the national level, Pasukan Gegana Korbrimob Polri is one of the main operational elements under the Commander of Korbrimob (Dankorbrimob Polri). Gegana is responsible for responding to high-intensity public-security threats involving firearms, explosives, terrorism, hostage situations, and Chemical, Biological, Radiological and Nuclear (CBRN/KBRN) hazards.
                </p>
                <p class="p-modal text-justify">
                    Unlike Polda, Polres, and Polsek, Gegana is not a territorial police command and does not administer a permanent geographic police jurisdiction. It is a specialized operational capability that can be deployed according to nature and level of threat. At the national level, Pasukan Gegana provides strategic capability, reinforcement, technical assistance, training, standardization, and functional supervision. At the regional level, Gegana function is maintained through Detasemen Gegana of the Polda&rsquo;s Satuan Brimob (Satbrimob).
                </p>
                <p class="p-modal text-justify">
                    <strong>Purpose:</strong> Gegana provides Polri with specialized tactical capabilities for high-risk incidents that exceed conventional policing capacity. Its purpose is to rapidly neutralize armed and terrorist threats, render explosives safe, respond to CBRN hazards, rescue hostages, provide specialized technical support, and reinforce police operations during high-intensity security incidents.
                </p>
                <p class="p-modal text-justify">
                    <strong>Command Level: National specialized operational command under Korbrimob Polri</strong>
                </p>
                <p class="p-modal text-justify">
                    Pasukan Gegana is a national-level operational element of Korbrimob Polri rather than a territorial command. Its forces may be deployed throughout Indonesia and may provide technical assistance for activities of national or international scale. The national force also exercises functional development and supervision over Gegana elements in Satbrimob Polda.
                </p>
            </div>

            <div class="tab-pane fade" id="gegana-commander" role="tabpanel" aria-labelledby="gegana-commander-tab" tabindex="0">
                <p class="p-modal text-justify">
                    <strong>Pasukan Gegana Korbrimob Polri:</strong> Led by the Commander of Pasukan Gegana (Komandan Pasukan Gegana &ndash; Danpas Gegana). The position is held at Brigadier General level (Brigadir Jenderal Polisi &ndash; Brigjen Pol), bearing the insignia of one (1) gold star. Danpas Gegana is subordinate to and receives operational direction from Dankorbrimob Polri.
                </p>
                <p class="p-modal text-justify">
                    Danpas Gegana is responsible for commanding, supervising and controlling the units under Pasukan Gegana; developing personnel capability and operational readiness; and deploying Gegana forces under the direction of Dankorbrimob Polri. The official organizational staffing structure allocates one Brigjen Pol position to Pasukan Gegana.
                </p>
                <p class="p-modal text-justify">
                    <strong>Regional Gegana:</strong> At Polda level, the Gegana element is organized as Detasemen Gegana under Satbrimob Polda and is led by a Komandan Detasemen Gegana (Danden Gegana). The commander rank is not uniform nationally and may vary according to the organizational structure and classification of the respective Satbrimob/Polda. Operational examples show Danden Gegana positions held by Kompol or AKBP.
                </p>
            </div>

            <div class="tab-pane fade" id="gegana-structure" role="tabpanel" aria-labelledby="gegana-structure-tab" tabindex="0">
                <p class="p-modal text-justify">
                    Pasukan Gegana is organized according to specialist operational capability rather than geographic jurisdiction. The current national structure contains four principal operational units: Satuan Wanteror, Satuan Jibom, Satuan KBRN, and Satuan Bantek. These units collectively provide tactical counter-terrorism, bomb disposal, CBRN response and specialized technical support capabilities.
                </p>

                <div class="table-responsive">
                    <table class="unit-class-table">
                        <thead>
                            <tr>
                                <th style="width:17%;">Unit Type</th>
                                <th style="width:23%;">Classification</th>
                                <th style="width:27%;">Head Position &amp; Typical Rank</th>
                                <th>Role</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">Pasukan Gegana Korbrimob Polri</th>
                                <td>National Gegana Command</td>
                                <td>Danpas Gegana &ndash; Brigjen Pol, one-star police general</td>
                                <td>National command, operational deployment, capability development, technical assistance and functional supervision of Gegana.</td>
                            </tr>
                            <tr>
                                <th scope="row">Satuan Wanteror</th>
                                <td>Counter-Terrorism / Tactical Assault</td>
                                <td>Dansat Wanteror &ndash; typically senior police officer at Kombes Pol level</td>
                                <td>Armed counter-terrorism operations, high-risk tactical intervention and hostage rescue.</td>
                            </tr>
                            <tr>
                                <th scope="row">Satuan Jibom</th>
                                <td>Bomb Disposal / EOD</td>
                                <td>Dansat Jibom &ndash; typically Kombes Pol</td>
                                <td>Detection, identification, neutralization and disposal of bombs, explosives and explosive threats.</td>
                            </tr>
                            <tr>
                                <th scope="row">Satuan KBRN</th>
                                <td>Chemical, Biological, Radiological and Nuclear (CBRN) Response</td>
                                <td>Dansat KBRN &ndash; typically Kombes Pol</td>
                                <td>Response to Chemical, Biological, Radiological and Nuclear threats and hazardous contamination.</td>
                            </tr>
                            <tr>
                                <th scope="row">Satuan Bantek</th>
                                <td>Technical Support</td>
                                <td>Dansat Bantek &ndash; typically Kombes Pol</td>
                                <td>Tactical and technical support for Gegana operations, specialist technology, reconnaissance and capability development.</td>
                            </tr>
                            <tr>
                                <th scope="row">Detasemen Gegana Satbrimob Polda</th>
                                <td>Regional Gegana Element</td>
                                <td>Danden Gegana &ndash; commonly Kompol/AKBP, depending on organizational structure</td>
                                <td>Provides Gegana capability at Polda level and supports regional police operations.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="info-modal-note">
                    <p class="mb-2"><strong>Note:</strong> Pasukan Gegana&rsquo;s national operational structure consists of:</p>
                    <ul class="mb-2">
                        <li><strong>Counter-Terrorism Unit &ndash; Satuan Wanteror</strong> &rarr; Detachment A, Detachment B and Detachment C Wanteror</li>
                        <li><strong>Bomb Disposal Unit &ndash; Satuan Jibom</strong> &rarr; Detachment A, Detachment B and Detachment C Jibom</li>
                        <li><strong>Chemical, Biological, Radiological and Nuclear (CBRN) Unit &ndash; Satuan KBRN</strong> &rarr; Detachment A, Detachment B and Detachment C KBRN</li>
                        <li><strong>Technical Support Unit &ndash; Satuan Bantek</strong> &rarr; Tactical Support Detachment &ndash; Detasemen Bantuan Taktis (Den Bantis) and Development Detachment &ndash; Detasemen Pengembangan (Denbang)</li>
                    </ul>
                    <p class="mb-0">This gives the four national operational units a total of 11 subordinate Detasemen.</p>
                </div>
            </div>

            <div class="tab-pane fade" id="gegana-roles" role="tabpanel" aria-labelledby="gegana-roles-tab" tabindex="0">
                <p class="p-modal text-justify">
                    Pasukan Gegana is a main specialized operational force under Korbrimob Polri. It is responsible for addressing high-intensity security and public-order threats (Gangguan Kamtibmas Intensitas Tinggi&mdash;GKIT) requiring specialist tactical, explosive-ordnance, CBRN or technical capabilities. It also develops and supervises Gegana capability in Satbrimob Polda.
                </p>
                <p class="p-modal"><strong>Responsibilities</strong></p>
                <ul>
                    <li><strong>Counter-Terrorism and High-Risk Tactical Operations:</strong> Conduct tactical intervention against organized armed threats and terrorist resistance requiring specialist assault capabilities.</li>
                    <li><strong>Hostage Rescue:</strong> Conduct high-risk intervention and hostage-release operations involving armed or terrorist offenders through the Wanteror capability.</li>
                    <li><strong>Bomb Disposal and Explosive Threat Response (Jibom):</strong> Detect, identify, secure, neutralize and dispose of bombs, improvised explosive devices, military ordnance and other explosive hazards.</li>
                    <li><strong>Explosive-Site Sterilization:</strong> Conduct preventive bomb sweeps and security sterilization of designated facilities, major events, strategic locations and locations assessed as vulnerable to explosive threats. Regional Gegana Jibom units regularly perform this function in support of Polda and Polres operations.</li>
                    <li><strong>CBRN/KBRN Response:</strong> Respond to incidents involving Chemical, Biological, Radiological and Nuclear hazards, including detection, identification, containment, technical assessment and specialist response measures.</li>
                    <li><strong>Technical Support (Bantek):</strong> Provide specialized technical support to Gegana operations, including tactical reconnaissance, technical intelligence support, specialist information technology, operational equipment and capability development.</li>
                    <li><strong>National Rapid-Response Capability:</strong> Maintain operational personnel, specialist equipment and support resources capable of rapid deployment to security incidents throughout Indonesia. Pasukan Gegana maintains on-call operational elements capable of assignment across the Republic of Indonesia.</li>
                </ul>
            </div>

            <div class="tab-pane fade" id="gegana-geographic" role="tabpanel" aria-labelledby="gegana-geographic-tab" tabindex="0">
                <p class="p-modal text-justify">
                    Unlike territorial units, Gegana does not follow Indonesia&rsquo;s civilian administrative boundaries as an independent territorial command. Its organization combines a national centralized force with regional Gegana elements embedded in Satbrimob Polda.
                </p>

                <p class="p-modal"><strong>National Level &ndash; Pasukan Gegana Korbrimob Polri</strong></p>
                <p class="p-modal text-justify">
                    National level Gegana headquarters is located at Cimanggis, Depok, West Java, as part of the Korbrimob Polri complex. National Gegana units constitute a strategic operational capability that may be deployed anywhere in Indonesia according to operational requirements.
                </p>
                <p class="p-modal">National Gegana force contains:</p>
                <div class="brimob-command-flow my-3">
                    <strong>Pasukan Gegana Korbrimob Polri</strong>
                    <div class="flow-arrow" aria-hidden="true">&darr;</div>
                    <strong>Satuan Wanteror</strong>
                    <strong>Satuan Jibom</strong>
                    <strong>Satuan KBRN</strong>
                    <strong>Satuan Bantek</strong>
                </div>
                <p class="p-modal text-justify">
                    National Gegana units provide strategic reinforcement and specialist capability when a regional incident exceeds available Polda resources or requires specific national-level expertise.
                </p>

                <p class="p-modal"><strong>Regional Level &ndash; Detasemen Gegana Satbrimob Polda</strong></p>
                <p class="p-modal text-justify">
                    At regional level, the Gegana function is organized under the Satuan Brimob Polda (Satbrimob Polda). The regional element is generally designated Detasemen Gegana Satbrimob Polda and responds primarily to incidents occurring in the respective Polda&rsquo;s operational area. Current Polri reporting confirms Detasemen Gegana operations under Satbrimob in multiple Polda, including Bali, Central Sulawesi, DIY and other regions.
                </p>
                <p class="p-modal">Regional Gegana elements provide immediate specialist response for:</p>
                <ul>
                    <li>Bomb threats and suspicious objects</li>
                    <li>Explosive ordnance and Unexploded Ordnance (UXO)</li>
                    <li>Bomb sterilization</li>
                    <li>Armed high-risk incidents</li>
                    <li>Terrorist contingencies</li>
                    <li>CBRN incidents</li>
                    <li>Specialist tactical support</li>
                    <li>Reinforcement of Polda and Polres operations</li>
                </ul>
                <p class="p-modal text-justify">
                    When complexity, scale or operational risk exceeds regional capability, Pasukan Gegana Korbrimob may provide additional personnel, equipment, technical expertise or national-level operational reinforcement.
                </p>
            </div>

            <div class="tab-pane fade" id="gegana-equivalent" role="tabpanel" aria-labelledby="gegana-equivalent-tab" tabindex="0">
                <p class="p-modal text-justify">
                    Because Gegana is a specialist operational capability rather than a territorial command, there is no direct civil or military administrative equivalent comparable to the Province&ndash;Kodam&ndash;Polda relationship.
                </p>
                <ul>
                    <li><strong>Civil Government:</strong> No direct equivalent. Civil emergency, disaster, health, nuclear/radiological and hazardous-material agencies may perform related technical or emergency functions, but they do not exercise Gegana&rsquo;s police tactical and law-enforcement authority.</li>
                    <li><strong>TNI:</strong> No single direct equivalent. Comparable specialist military capabilities are distributed among TNI counter-terrorism, explosive-ordnance, CBRN and special-operations elements according to military service and mission.</li>
                    <li><strong>Polri &ndash; Gegana:</strong> Specialized police operational capability for high-intensity threats involving armed resistance, terrorism, hostage situations, explosives, CBRN hazards and specialist technical response.</li>
                </ul>
                <div class="info-modal-note">
                    <strong>Note:</strong> Gegana should therefore be classified as a specialized national and regional operational capability of Korbrimob Polri, not as a territorial police layer equivalent to Polda, Polres or Polsek. Its authority and deployment are determined primarily by threat type, operational complexity and required specialist capability, rather than by independent administrative jurisdiction.
                </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="police2Modal" tabindex="-1" aria-labelledby="brimobLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered info-modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <div class="d-flex align-items-center gap-2">
            <img src="{{ asset('images/Brimob.png') }}" style="width:18px; height:18px;">
            <h5 class="modal-title mb-0" id="brimobLabel">Mobile Brigade Corps &mdash; Korps Brigade Mobil / Brimob</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <ul class="nav nav-tabs info-modal-tabs px-3 pt-2" id="brimobTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="brimob-definition-tab" data-bs-toggle="tab" data-bs-target="#brimob-definition"
                type="button" role="tab" aria-controls="brimob-definition" aria-selected="true">Definition &amp; Purpose</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="brimob-commander-tab" data-bs-toggle="tab" data-bs-target="#brimob-commander"
                type="button" role="tab" aria-controls="brimob-commander" aria-selected="false">Commander</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="brimob-structure-tab" data-bs-toggle="tab" data-bs-target="#brimob-structure"
                type="button" role="tab" aria-controls="brimob-structure" aria-selected="false">Brimob Structure &amp; Classification</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="brimob-roles-tab" data-bs-toggle="tab" data-bs-target="#brimob-roles"
                type="button" role="tab" aria-controls="brimob-roles" aria-selected="false">Responsibilities/Roles/Function</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="brimob-geographic-tab" data-bs-toggle="tab" data-bs-target="#brimob-geographic"
                type="button" role="tab" aria-controls="brimob-geographic" aria-selected="false">Geographic Distribution</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="brimob-equivalent-tab" data-bs-toggle="tab" data-bs-target="#brimob-equivalent"
                type="button" role="tab" aria-controls="brimob-equivalent" aria-selected="false">Police &ndash; Civil &ndash; Military Equivalent</button>
        </li>
      </ul>

      <div class="modal-body info-modal-body">
        <div class="tab-content info-modal-content" id="brimobTabContent">
            <div class="tab-pane fade show active" id="brimob-definition" role="tabpanel" aria-labelledby="brimob-definition-tab" tabindex="0">
                <p class="p-modal text-justify">
                    <strong>Definition:</strong> Korps Brigade Mobil (Korbrimob Polri), commonly known as Brimob, is the principal specialized operational force of the Indonesian National Police (Polri) responsible for responding to high-intensity threats to public security and order. At the national level, Korbrimob is an operational element of Polri at National Police Headquarters (Mabes Polri) level and is capable of deploying personnel and specialized capabilities throughout Indonesia.
                </p>
                <p class="p-modal text-justify">
                    Unlike Territorial units, Korbrimob is not a territorial police command and does not exercise general policing authority over a defined civilian administrative area. Its forces are organized as specialized tactical units that reinforce territorial police commands when incidents exceed normal policing capability or require specialist Brimob capabilities.
                </p>
                <p class="p-modal text-justify">
                    Korbrimob maintains national-level forces including Pasukan Pelopor, Pasukan Gegana, Pasukan Brimob I, Pasukan Brimob II, and Pasukan Brimob III, supported by operational, intelligence, training, logistics, communications, medical, and administrative elements. Pasukan Brimob I, II, and III provide strategically positioned reinforcement capacity for western, central, and eastern Indonesia.
                </p>
                <p class="p-modal text-justify">
                    At regional level, Satuan Brigade Mobil Polda (Satbrimob Polda) operates as the Brimob unit of a Polda and is an operational element under the Kapolda. Satbrimob provides specialized tactical support to Polda and subordinate territorial police units.
                </p>
                <p class="p-modal text-justify">
                    <strong>Purpose:</strong> Korbrimob provides Polri with a highly trained, rapidly deployable, and specialized force capable of responding to security situations requiring greater tactical capability, specialized equipment, unit discipline, mobility, and operational strength than conventional territorial policing.
                </p>
                <p class="p-modal text-justify">
                    Its purpose is to reinforce Polri in maintaining internal security, restore public order during high-intensity disturbances, counter armed and specialized threats, provide bomb-disposal and CBRN capability, support counterterrorism operations, conduct search and rescue, and provide tactical reinforcement during major emergencies and national police operations.
                </p>
                <p class="p-modal text-justify">
                    <strong>Command Level:</strong> National specialized operational command &ndash; Mabes Polri level. Korbrimob Polri is a national-level operational force rather than a territorial layer comparable to Polda, Polres, or Polsek.
                </p>
            </div>

            <div class="tab-pane fade" id="brimob-commander" role="tabpanel" aria-labelledby="brimob-commander-tab" tabindex="0">
                <p class="p-modal text-justify">
                    <strong>Mobile Brigade Corps Commander &ndash; Komandan Korps Brigade Mobil (Dankorbrimob):</strong> Korbrimob Polri is led by Dankorbrimob, a senior police general holding the rank of Police Commissioner General (Komisaris Jenderal Polisi &ndash; Komjen Pol) with the insignia of three (3) gold stars.
                </p>
                <p class="p-modal text-justify">
                    Dankorbrimob leads, develops, prepares, and controls Korbrimob capabilities and directs the deployment of Brimob forces in accordance with Polri operational requirements.
                </p>
                <p class="p-modal text-justify">
                    <strong>Mobile Brigade Corps Deputy Commander &ndash; Wakil Komandan Korps Brigade Mobil (Wadankorbrimob):</strong> Dankorbrimob is assisted by Wadankorbrimob, holding the rank of Police Inspector General (Inspektur Jenderal Polisi &ndash; Irjen Pol) with two (2) gold stars.
                </p>
                <p class="p-modal text-justify">
                    Wadankorbrimob assists the Dankorbrimob in organizational command, supervision, operational readiness, force development, and internal coordination.
                </p>
                <p class="p-modal"><strong>Commanders of Main Brimob Forces</strong></p>
                <p class="p-modal text-justify">
                    Major national operational forces under Korbrimob are generally commanded by Komandan Pasukan (Danpas) holding the rank of Police Brigadier General (Brigadir Jenderal Polisi&mdash;Brigjen Pol) with one (1) gold star. This includes commanders of Pasukan Pelopor, Pasukan Gegana, and the regional Pasukan Brimob I, II, and III.
                </p>
            </div>

            <div class="tab-pane fade" id="brimob-structure" role="tabpanel" aria-labelledby="brimob-structure-tab" tabindex="0">
                <p class="p-modal text-justify">
                    Brimob is not classified in the same manner as territorial police commands. Its organization is based primarily on command level, operational function, specialization, and geographic reinforcement responsibility.
                </p>
                <p class="p-modal text-justify">
                    At national level, Korbrimob maintains centralized specialist and tactical formations. At regional level, Satbrimob Polda structures are adjusted according to the organizational type of their respective Polda. Current regulations continue to distinguish Satbrimob structures for Polda Type A-Khusus, Type A, and Type B.
                </p>

                <div class="table-responsive">
                    <table class="unit-class-table">
                        <thead>
                            <tr>
                                <th style="width:14%;">Unit Type</th>
                                <th style="width:21%;">Classification</th>
                                <th style="width:27%;">Head Position &amp; Typical Rank</th>
                                <th>Role</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">Korbrimob Polri</th>
                                <td>National Brimob Command</td>
                                <td>Dankorbrimob &mdash; Komjen Pol, three-star police general</td>
                                <td>National command, capability development, readiness, reinforcement, and deployment of Brimob forces.</td>
                            </tr>
                            <tr>
                                <th scope="row">Pasukan Pelopor</th>
                                <td>National Tactical Force</td>
                                <td>Danpas Pelopor &mdash; Brigjen Pol, one-star police general</td>
                                <td>High-intensity public-order, armed-threat, and large-scale police-operation capability.</td>
                            </tr>
                            <tr>
                                <th scope="row">Pasukan Gegana</th>
                                <td>National Specialist Tactical Force</td>
                                <td>Danpas Gegana &mdash; Brigjen Pol, one-star police general</td>
                                <td>Bomb disposal, counterterror tactical capability, CBRN response, and specialist technical operations.</td>
                            </tr>
                            <tr>
                                <th scope="row">Pasukan Brimob I</th>
                                <td>Western Regional Reinforcement Force</td>
                                <td>Danpas Brimob I &mdash; Brigjen Pol</td>
                                <td>Strategic Brimob reinforcement for the western operational sector, principally Sumatra.</td>
                            </tr>
                            <tr>
                                <th scope="row">Pasukan Brimob II</th>
                                <td>Central Regional Reinforcement Force</td>
                                <td>Danpas Brimob II &mdash; Brigjen Pol</td>
                                <td>Strategic reinforcement covering Nusa Tenggara, Kalimantan, and Sulawesi.</td>
                            </tr>
                            <tr>
                                <th scope="row">Pasukan Brimob III</th>
                                <td>Eastern Regional Reinforcement Force</td>
                                <td>Danpas Brimob III &mdash; Brigjen Pol</td>
                                <td>Strategic reinforcement covering Maluku and Papua.</td>
                            </tr>
                            <tr>
                                <th scope="row">Satbrimob Polda</th>
                                <td>Regional Brimob Unit</td>
                                <td>Dansatbrimob &mdash; Kombes Pol in current operational Polda</td>
                                <td>Provides Brimob capability to the Polda and reinforces territorial police operations.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <p class="p-modal text-justify">
                    The geographic responsibilities of Pasukan Brimob I, II, and III were established to accelerate the movement and deployment of Brimob forces across Indonesia rather than concentrating national reinforcement capability primarily at Korbrimob Headquarters.
                </p>
                <p class="p-modal"><strong>Satbrimob Polda Type Classification</strong></p>
                <p class="p-modal text-justify">
                    The internal force structure of Satbrimob Polda is related to the classification and operational requirements of the parent Polda.
                </p>
                <ul>
                    <li><strong>Polda Type A-Khusus Satbrimob:</strong> Maintains a larger organizational structure, including Detasemen Gegana and Batalyon A, B, C, and D.</li>
                    <li><strong>Polda Type A Satbrimob:</strong> May include Detasemen Gegana and Batalyon A, B, C, and D.</li>
                    <li><strong>Polda Type B Satbrimob:</strong> May include Detasemen Gegana and Batalyon A, B, and C.</li>
                </ul>
                <p class="p-modal text-justify">
                    The additional Batalyon D for Type A and Batalyon C for Type B may be established based on organizational requirements through approval by Kapolri following a proposal from the relevant Kapolda.
                </p>
                <div class="info-modal-note">
                    <strong>Note:</strong> This classification describes the regional Satbrimob organizational structure, not separate categories of Korbrimob operational capability. The actual number, location, and composition of battalions and companies can vary according to security conditions, force development, geographic requirements, and organizational approval.
                </div>
            </div>

            <div class="tab-pane fade" id="brimob-roles" role="tabpanel" aria-labelledby="brimob-roles-tab" tabindex="0">
                <p class="p-modal text-justify">
                    Korbrimob is Polri&rsquo;s primary specialized force for dealing with high-intensity disturbances to public security and order and providing tactical reinforcement to territorial and functional police units. Its official functions include operational planning, force preparation, specialist training, Gegana operations, Pelopor operations, intelligence management, nationwide force deployment, and other Polri internal-security tasks.
                </p>

                <p class="p-modal"><strong>Korbrimob Main Operational Functions</strong></p>
                <p class="p-modal text-justify"><strong>Pelopor:</strong> Provides the principal general tactical-force capability of Brimob. Its responsibilities include:</p>
                <ul>
                    <li>High-intensity public-order operations</li>
                    <li>Riot and violent-disturbance response</li>
                    <li>Armed law-enforcement reinforcement</li>
                    <li>Tactical security operations</li>
                    <li>High-risk area security</li>
                    <li>Search and rescue</li>
                    <li>Disaster response</li>
                    <li>Tactical patrol and deployment</li>
                    <li>Reinforcement of territorial police operations</li>
                </ul>
                <p class="p-modal text-justify">
                    Korbrimob identifies Pelopor as its principal function for responding to high-intensity disturbances to public security and order.
                </p>

                <p class="p-modal text-justify"><strong>Gegana:</strong> Provides Brimob&rsquo;s specialist tactical and technical capability. Its principal capabilities include:</p>
                <ul>
                    <li><strong>Penjinakan Bom (Jibom):</strong> Bomb Disposal</li>
                    <li><strong>Perlawanan Teror (Wanteror):</strong> Counterterror Tactical Operations</li>
                    <li><strong>KBRN/CBRN:</strong> Chemical, Biological, Radiological and Nuclear Response</li>
                    <li>Specialist technical support</li>
                </ul>
                <p class="p-modal text-justify">
                    The national Pasukan Gegana operates as a principal operational element under Korbrimob, while regional Satbrimob normally maintain Detasemen Gegana to provide specialist capability at Polda level.
                </p>

                <p class="p-modal"><strong>Responsibilities</strong></p>
                <ul>
                    <li>
                        <strong>High-Intensity Public Security Operations</strong>
                        <ul>
                            <li>Respond to serious security disturbances beyond normal territorial police capability.</li>
                            <li>Restore security during major riots, violent unrest, armed disturbances, and other high-risk incidents.</li>
                            <li>Reinforce Polda and other Polri units during major security operations.</li>
                        </ul>
                    </li>
                    <li>
                        <strong>Tactical Police Reinforcement</strong>
                        <ul>
                            <li>Provide trained and equipped tactical personnel to support Polda, Polres, and other Polri units.</li>
                            <li>Provide additional tactical capability and force protection during high-risk operations.</li>
                            <li>Deploy personnel outside their home jurisdiction through Bawah Kendali Operasi (BKO) or other operational arrangements.</li>
                        </ul>
                    </li>
                    <li>
                        <strong>Public-Order and Riot Response</strong>
                        <ul>
                            <li>Control violent mass disturbances and serious public-order incidents.</li>
                            <li>Reinforce conventional police units when normal crowd-control capability is insufficient.</li>
                            <li>Support restoration of public order and security.</li>
                        </ul>
                    </li>
                    <li>
                        <strong>Armed and High-Risk Law-Enforcement Support</strong>
                        <ul>
                            <li>Support operations against armed, organized, or high-risk threats.</li>
                            <li>Conduct containment, tactical movement, area security, and armed reinforcement.</li>
                            <li>Support high-risk arrests and stabilization of security-threat areas.</li>
                        </ul>
                    </li>
                    <li>
                        <strong>Bomb Disposal &ndash; Penjinakan Bom (Jibom)</strong>
                        <ul>
                            <li>Detect, assess, secure, neutralize, and dispose of explosive threats.</li>
                            <li>Respond to bomb threats, suspicious objects, and explosive incidents.</li>
                            <li>Provide bomb-disposal security for major events and sensitive locations.</li>
                        </ul>
                    </li>
                    <li>
                        <strong>Counterterror Tactical Capability</strong>
                        <ul>
                            <li>Provide specialized tactical response to terrorism and other high-risk threats.</li>
                            <li>Support Polri counterterrorism operations requiring Brimob tactical capability.</li>
                            <li>Complement the investigative and counterterrorism role of Densus 88 AT Polri.</li>
                        </ul>
                    </li>
                    <li>
                        <strong>Chemical, Biological, Radiological and Nuclear (CBRN) Response</strong>
                        <ul>
                            <li>Detect and identify chemical, biological, radiological, and nuclear (CBRN/KBRN) threats.</li>
                            <li>Contain hazardous areas and conduct specialist intervention.</li>
                            <li>Support decontamination and other CBRN emergency operations.</li>
                        </ul>
                    </li>
                    <li>
                        <strong>Search and Rescue (SAR)</strong>
                        <ul>
                            <li>Conduct land and water search-and-rescue operations.</li>
                            <li>Provide evacuation, first aid, vertical rescue, and accident rescue.</li>
                            <li>Support emergency operations during floods, earthquakes, landslides, fires, and other disasters.</li>
                        </ul>
                    </li>
                    <li>
                        <strong>Disaster and Humanitarian Operations</strong>
                        <ul>
                            <li>Conduct search and rescue and evacuation.</li>
                            <li>Secure disaster-affected areas.</li>
                            <li>Provide emergency logistics and field kitchens.</li>
                            <li>Support water treatment and distribution.</li>
                            <li>Provide emergency communications.</li>
                            <li>Support medical and ambulance operations.</li>
                            <li>Protect humanitarian assistance activities.</li>
                            <li>Maintain and restore public order in disaster areas.</li>
                        </ul>
                    </li>
                    <li>
                        <strong>Specialized Operational Readiness</strong>
                        <ul>
                            <li>Maintain personnel, vehicles, weapons, communications, and specialist equipment at operational readiness.</li>
                            <li>Maintain tactical units capable of rapid deployment.</li>
                            <li>Conduct continuous technical, tactical, and unit training.</li>
                        </ul>
                    </li>
                    <li>
                        <strong>Brimob Intelligence Support</strong>
                        <ul>
                            <li>Collect and process intelligence supporting Brimob operations.</li>
                            <li>Conduct threat assessment and operational analysis.</li>
                            <li>Provide intelligence support to deployed Brimob units.</li>
                        </ul>
                    </li>
                    <li>
                        <strong>Security of Major National Activities</strong>
                        <ul>
                            <li>Provide tactical and public-order security for national and regional elections, major demonstrations, international conferences, state ceremonies, major religious events, large public gatherings, high-risk government activities, and other major Polri operations.</li>
                        </ul>
                    </li>
                    <li>
                        <strong>Protection of Strategic Locations</strong>
                        <ul>
                            <li>Reinforce security at critical infrastructure and strategic facilities.</li>
                            <li>Protect government installations and other high-risk locations.</li>
                            <li>Provide tactical capability when routine police protection is insufficient.</li>
                        </ul>
                    </li>
                    <li>
                        <strong>Regional Reinforcement</strong>
                        <ul>
                            <li>Deploy Korbrimob forces to reinforce Polda facing major security disturbances.</li>
                            <li>Deploy Satbrimob personnel across police jurisdictions when additional forces are required.</li>
                            <li>Use Pasukan Brimob I, II, and III as strategic reinforcement forces for western, central, and eastern Indonesia.</li>
                        </ul>
                    </li>
                </ul>
            </div>

            <div class="tab-pane fade" id="brimob-geographic" role="tabpanel" aria-labelledby="brimob-geographic-tab" tabindex="0">
                <p class="p-modal text-justify">
                    Korbrimob uses a combination of national centralized forces, regional strategic forces, and Polda-level Brimob units.
                </p>

                <p class="p-modal"><strong>Korbrimob Headquarters</strong></p>
                <p class="p-modal text-justify">
                    Korbrimob headquarters is located at Kelapa Dua, Depok, West Java, and serves as the national command and principal organizational center of the Mobile Brigade Corps.
                </p>

                <p class="p-modal"><strong>Strategic Regional Forces</strong></p>
                <p class="p-modal text-justify">Korbrimob distributes national reinforcement capability through three principal regional commands:</p>
                <ul>
                    <li><strong>Pasukan Brimob I &ndash; Western Sector:</strong> principally responsible for reinforcement in Sumatra.</li>
                    <li><strong>Pasukan Brimob II &ndash; Central Sector:</strong> principally responsible for Nusa Tenggara, Kalimantan, and Sulawesi.</li>
                    <li><strong>Pasukan Brimob III &ndash; Eastern Sector:</strong> principally responsible for Maluku and Papua.</li>
                </ul>
                <p class="p-modal text-justify">
                    These formations are intended to shorten deployment time and provide strategically positioned Brimob forces closer to potential operational areas.
                </p>

                <p class="p-modal"><strong>Satbrimob Polda</strong></p>
                <p class="p-modal text-justify">
                    At regional level, Polda maintain Satuan Brimob Polda (Satbrimob Polda) to provide immediately available Brimob capability in their police jurisdiction.
                </p>
                <p class="p-modal text-justify">
                    Satbrimob personnel and facilities are commonly distributed across several locations rather than concentrated entirely at the Polda headquarters. Batalyon Pelopor, companies, and Gegana elements may therefore be positioned in strategically important areas according to population, geography, security threats, infrastructure, operational history, and reinforcement requirements.
                </p>
                <p class="p-modal text-justify">
                    Unlike Polda territorial jurisdictions, Brimob deployment boundaries are not rigid administrative boundaries. Brimob personnel can be deployed across provincial and regional boundaries when required by national or inter-regional police operations.
                </p>

                <p class="p-modal"><strong>Command and Operational Distribution</strong></p>
                <p class="p-modal text-justify">Brimob operates through two interconnected organizational systems:</p>

                <div class="table-responsive">
                    <table class="brimob-structure-table">
                        <thead>
                            <tr>
                                <th>National Brimob Structure</th>
                                <th>Regional Brimob Structure</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="brimob-command-flow">
                                        <strong>Kapolri</strong>
                                        <div class="flow-arrow" aria-hidden="true">&darr;</div>
                                        <strong>Dankorbrimob Polri</strong>
                                        <div class="flow-arrow" aria-hidden="true">&darr;</div>
                                        <strong>Korbrimob Headquarters and National Operational Forces</strong>
                                        <div class="flow-arrow" aria-hidden="true">&darr;</div>
                                        <strong>Pasukan Pelopor / Pasukan Gegana / Pasukan Brimob I / Pasukan Brimob II / Pasukan Brimob III</strong>
                                    </div>
                                    <p class="structure-description">
                                        Korbrimob provides national force development, capability standardization, operational readiness, specialist training, strategic reinforcement, and nationwide deployment capability.
                                    </p>
                                </td>
                                <td>
                                    <div class="brimob-command-flow">
                                        <strong>Kapolda</strong>
                                        <div class="flow-arrow" aria-hidden="true">&darr;</div>
                                        <strong>Dansatbrimob Polda</strong>
                                        <div class="flow-arrow" aria-hidden="true">&darr;</div>
                                        <strong>Satbrimob Polda</strong>
                                        <div class="flow-arrow" aria-hidden="true">&darr;</div>
                                        <strong>Batalyon Pelopor / Detasemen Gegana</strong>
                                        <div class="flow-arrow" aria-hidden="true">&darr;</div>
                                        <strong>Kompi / Detasemen or subordinate tactical elements</strong>
                                    </div>
                                    <p class="structure-description">
                                        Satbrimob is formally an operational element at Polda level and operates under the Kapolda.
                                    </p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="tab-pane fade" id="brimob-equivalent" role="tabpanel" aria-labelledby="brimob-equivalent-tab" tabindex="0">
                <p class="p-modal text-justify">
                    Brimob does not have a direct civil administrative equivalent because it is a specialized operational force rather than a territorial government or police-administration layer.
                </p>

                <p class="p-modal"><strong>National Level</strong></p>
                <ul>
                    <li><strong>Civil:</strong> No direct equivalent</li>
                    <li><strong>TNI:</strong> No exact organizational equivalent; specialized operational/strategic military formations may perform broadly comparable high-readiness roles under a fundamentally different defense mandate</li>
                    <li><strong>Polri:</strong> <strong>Korbrimob Polri</strong> &ndash; national specialized high-intensity police operational force</li>
                </ul>

                <p class="p-modal"><strong>Regional Level</strong></p>
                <ul>
                    <li><strong>Civil Province:</strong> Provincial administrative authority</li>
                    <li><strong>Kodam:</strong> Regional TNI AD territorial command</li>
                    <li><strong>Polda:</strong> Regional Polri territorial command</li>
                    <li><strong>Satbrimob Polda:</strong> Specialized tactical force <strong>under the Polda</strong>, not a territorial command equivalent to the province, Kodam, or Polda</li>
                </ul>

                <div class="info-modal-note">
                    <strong>Note:</strong> Brimob should not be treated as the police equivalent of a TNI combat command. Polri and TNI operate under different constitutional, statutory, command, and operational mandates. Brimob remains an integral part of the Indonesian National Police and exercises police powers in support of law enforcement, public order, internal security, emergency response, and other Polri responsibilities.
                </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="police3Modal" tabindex="-1" aria-labelledby="level3Label" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered info-modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <div class="d-flex align-items-center gap-2">
            <img src="{{ asset('images/Layer4.png') }}" style="width:18px; height:18px;">
            <h5 class="modal-title mb-0" id="level3Label">Polsek &mdash; Sector Police</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <ul class="nav nav-tabs info-modal-tabs px-3 pt-2" id="polsekTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="polsek-definition-tab" data-bs-toggle="tab" data-bs-target="#polsek-definition"
                type="button" role="tab" aria-controls="polsek-definition" aria-selected="true">Definition &amp; Purpose</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="polsek-commander-tab" data-bs-toggle="tab" data-bs-target="#polsek-commander"
                type="button" role="tab" aria-controls="polsek-commander" aria-selected="false">Commander</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="polsek-classification-tab" data-bs-toggle="tab" data-bs-target="#polsek-classification"
                type="button" role="tab" aria-controls="polsek-classification" aria-selected="false">Polsek Classification</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="polsek-roles-tab" data-bs-toggle="tab" data-bs-target="#polsek-roles"
                type="button" role="tab" aria-controls="polsek-roles" aria-selected="false">Responsibilities/Roles/Function</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="polsek-geographic-tab" data-bs-toggle="tab" data-bs-target="#polsek-geographic"
                type="button" role="tab" aria-controls="polsek-geographic" aria-selected="false">Geographic Distribution</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="polsek-equivalent-tab" data-bs-toggle="tab" data-bs-target="#polsek-equivalent"
                type="button" role="tab" aria-controls="polsek-equivalent" aria-selected="false">Civil &ndash; TNI AD (Army) &ndash; Police Equivalent</button>
        </li>
      </ul>

      <div class="modal-body info-modal-body">
        <div class="tab-content info-modal-content" id="polsekTabContent">

            <!-- Definition & Purpose -->
            <div class="tab-pane fade show active" id="polsek-definition" role="tabpanel" aria-labelledby="polsek-definition-tab" tabindex="0">
                <p class="p-modal text-justify">
                    <strong>Definition:</strong> Polsek (Kepolisian Sektor) is the lowest territorial command of the Indonesian National Police (Polri) with full policing authority, operating at the sub-district (kecamatan) level. A Polsek is led by a Kapolsek (Chief of Sector Police), who reports directly to the Kapolres through the Polres command structure.
                </p>
                <p class="p-modal text-justify">
                    Polsek jurisdictions are generally aligned with civil administrative boundaries of kecamatan, mirroring the local governance structure. Unlike sub-district administrations&mdash;which are civilian governmental entities, Polsek are security institutions with executive authority in policing and law enforcement at the community level.
                </p>
                <p class="p-modal text-justify">
                    <strong>Purpose:</strong> Polsek maintain day-to-day public order, provide immediate law enforcement response, prevent crime, and serve as the closest police presence to the community, supporting local safety and social stability.
                </p>
                <p class="p-modal text-justify">
                    <strong>Command Level:</strong> Sub-regency territorial police command, normally responsible for one kecamatan or another designated police sector. Polsek operates under the relevant Polres, Polresta, Polrestabes, or Polres Metro. Polri formally places Polda at provincial level, Polres at regency/city level, and Polsek at kecamatan level.
                </p>
            </div>

            <!-- Commander -->
            <div class="tab-pane fade" id="polsek-commander" role="tabpanel" aria-labelledby="polsek-commander-tab" tabindex="0">
                <ul>
                    <li>
                        <strong>Type A Polsek:</strong> Led by Kapolsek, a senior middle-ranking police officer bearing the insignia of two gold jasmine flowers, holding the rank of Police Grand Commissioner or Police Senior Superintendent (Ajun Komisaris Besar Polisi&mdash;AKBP). Kapolsek reports directly and is responsible to Kapolres. A Type A Polsek includes a Wakapolsek position normally held by a Police Commissioner (Komisaris Polisi&mdash;Kompol).
                    </li>
                    <li>
                        <strong>Type B Polsek:</strong> Led by Kapolsek, a middle-ranking police officer bearing the insignia of one gold jasmine flower, holding the rank of Police Commissioner (Komisaris Polisi&mdash;Kompol). Kapolsek reports directly and is responsible to Kapolres. A Type B Polsek includes a Wakapolsek position normally held by an Assistant Police Commissioner (Ajun Komisaris Polisi&mdash;AKP).
                    </li>
                    <li>
                        <strong>Type C Polsek:</strong> Led by Kapolsek, a junior middle-ranking police officer bearing the insignia of three gold bars, holding the rank of Assistant Police Commissioner (Ajun Komisaris Polisi&mdash;AKP). Kapolsek reports directly and is responsible to Kapolres. A Type C Polsek includes a Wakapolsek position from the Police Inspector rank group.
                    </li>
                    <li>
                        <strong>Type D Polsek:</strong> Led by Kapolsek from the Police Inspector rank group, normally a Police First Inspector or Police Second Inspector (Inspektur Polisi Satu&mdash;IPTU / Inspektur Polisi Dua&mdash;IPDA). Kapolsek reports directly and is responsible to Kapolres. Unlike Types A, B, and C, a Type D Polsek has no Wakapolsek position under the standard organizational structure.
                    </li>
                </ul>
            </div>

            <!-- Polsek Classification -->
            <div class="tab-pane fade" id="polsek-classification" role="tabpanel" aria-labelledby="polsek-classification-tab" tabindex="0">
                <p class="p-modal text-justify">
                    Polsek classification is not determined solely by the size of the kecamatan (district) or the number of villages under its jurisdiction. Polri classifies and restructures territorial units through an organizational assessment that considers the operational burden and characteristics of the police area. Relevant factors include population, geography, crime levels, public-security conditions, traffic activity, economic and strategic importance, service demand, accessibility, and the capability required to perform policing duties.
                </p>
                <p class="p-modal text-justify">
                    Polri recognizes four Polsek organizational types: Type A, Type B, Type C, and Type D. The classification determines the authorized command rank, organizational structure, staffing strength, number of functional units, and whether a Wakapolsek position is provided. Under Perpol No. 2 of 2021, the standard Kapolsek ranks are AKBP for Type A, Kompol for Type B, AKP for Type C, and the Police Inspector rank group for Type D.
                </p>
                <p class="p-modal text-justify">
                    Type A has the largest standard establishment and more developed functional units. Type B retains a substantial operational structure but has lower authorized staffing and command rank. Type C has a more compact organization adapted to a moderate operational workload. Type D represents the smallest standard Polsek structure and is intended for sectors with comparatively limited organizational and operational requirements. Type D is commanded only by Kapolsek and does not have a Wakapolsek under the standard structure.
                </p>

                <div class="table-responsive">
                    <table class="unit-class-table">
                        <thead>
                            <tr>
                                <th style="width:16%;">Classification</th>
                                <th style="width:30%;">Head Position &amp; Typical Rank</th>
                                <th>Explanation</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">Polsek Type A</th>
                                <td>Kapolsek &mdash; AKBP / Senior Police Adjunct Commissioner</td>
                                <td>Highest Polsek classification. Used for the most important sector-level police commands, usually in areas with high operational complexity, major public-service demand, or strategic policing importance.</td>
                            </tr>
                            <tr>
                                <th scope="row">Polsek Type B</th>
                                <td>Kapolsek &mdash; Kompol / Police Commissioner</td>
                                <td>Important sector-level command below Type A. It usually has a larger structure and stronger officer presence than Type C and Type D.</td>
                            </tr>
                            <tr>
                                <th scope="row">Polsek Type C</th>
                                <td>Kapolsek &mdash; AKP / Police Adjunct Commissioner</td>
                                <td>Standard medium Polsek classification. It normally covers a district-level area with moderate workload and a smaller structure than Type A or Type B.</td>
                            </tr>
                            <tr>
                                <th scope="row">Polsek Type D</th>
                                <td>Kapolsek &mdash; IP / Police Inspector-level officer, usually Iptu or Ipda depending on placement</td>
                                <td>Smallest Polsek classification. Type D has a leaner structure, and its leadership element is carried only by the Kapolsek, without a separate Wakapolsek.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Responsibilities / Roles / Function -->
            <div class="tab-pane fade" id="polsek-roles" role="tabpanel" aria-labelledby="polsek-roles-tab" tabindex="0">
                <p class="p-modal"><strong>Responsibilities</strong></p>
                <ul>
                    <li>
                        <strong>Public Security and Order (Kamtibmas):</strong> Maintain public order, prevent disturbances, and ensure security within villages, neighborhoods, and urban wards.
                    </li>
                    <li>
                        <strong>First-Line Law Enforcement:</strong> Handle initial law enforcement actions, minor criminal cases, and first response to criminal incidents.
                    </li>
                    <li>
                        <strong>Crime Prevention and Community Engagement:</strong> Implement community policing activities, neighborhood patrols, and public outreach programs.
                    </li>
                    <li>
                        <strong>Public Service and Assistance:</strong> Provide immediate police services such as reports, complaints handling, and emergency response.
                    </li>
                    <li>
                        <strong>Local Conflict Management:</strong> Detect and mediate early signs of social conflict, disputes, and communal tensions.
                    </li>
                    <li>
                        <strong>Support for Disaster and Emergency Response:</strong> Assist evacuations, secure affected areas, and support emergency services during local disasters.
                    </li>
                </ul>

                <p class="p-modal"><strong>Roles &amp; Function</strong></p>
                <p class="p-modal text-justify">
                    Polsek function as the frontline operational unit of Polri, translating policing policy into direct daily interaction with the public:
                </p>
                <ul>
                    <li>
                        <strong>Community-Level Command and Control</strong>
                        <ul>
                            <li><strong>Territorial Policing Authority:</strong> Exercise policing authority within the sub-district, under the command and supervision of the Polres.</li>
                            <li><strong>Routine Patrol Management:</strong> Conduct routine patrols and visibility operations to deter crime and maintain public confidence.</li>
                        </ul>
                    </li>
                    <li>
                        <strong>Law Enforcement and Incident Response</strong>
                        <ul>
                            <li><strong>First Response Capability:</strong> Act as the first responder to criminal incidents, public disturbances, and emergencies.</li>
                            <li><strong>Preliminary Investigation:</strong> Conduct initial investigations, evidence securing, and case documentation before escalation to Polres if required.</li>
                        </ul>
                    </li>
                    <li>
                        <strong>Community Policing and Preventive Action</strong>
                        <ul>
                            <li><strong>Community Engagement:</strong> Build partnerships with community leaders, village officials, and local organizations.</li>
                            <li><strong>Early Warning and Prevention:</strong> Identify potential security risks and social tensions through community interaction and local intelligence.</li>
                        </ul>
                    </li>
                    <li>
                        <strong>Public Order and Local Event Security</strong>
                        <ul>
                            <li><strong>Local Event Security:</strong> Secure community events, religious gatherings, markets, and local celebrations.</li>
                            <li><strong>Crowd Monitoring:</strong> Monitor and manage small-scale crowds and demonstrations within the sub-district.</li>
                        </ul>
                    </li>
                    <li>
                        <strong>Public Services and Administration</strong>
                        <ul>
                            <li><strong>Police Services:</strong> Handle public reports, loss statements, and other basic administrative police services.</li>
                            <li><strong>Accessibility:</strong> Provide an easily accessible police presence for residents requiring assistance or protection.</li>
                        </ul>
                    </li>
                    <li>
                        <strong>Coordination with Local Authorities</strong>
                        <ul>
                            <li><strong>Kecamatan-Level Coordination:</strong> Coordinate with sub-district heads (Camat), village officials, and community leaders on security matters.</li>
                            <li><strong>Civil&ndash;Military Cooperation:</strong> Coordinate with Village Supervisory Non-Commissioned Officer (Babinsa) (TNI AD) and local territorial units for community security and emergency support.</li>
                        </ul>
                    </li>
                </ul>
            </div>

            <!-- Geographic Distribution -->
            <div class="tab-pane fade" id="polsek-geographic" role="tabpanel" aria-labelledby="polsek-geographic-tab" tabindex="0">
                <p class="p-modal text-justify">
                    Polsek are territorially organized to directly correspond with sub-district (kecamatan) boundaries, ensuring close alignment with Indonesia&rsquo;s grassroots administrative structure. In practice:
                </p>
                <ul>
                    <li>Most Polsek cover one sub district.</li>
                    <li>In densely populated urban areas, a Polsek may cover part of a sub district or be supplemented by sector posts.</li>
                    <li>Jurisdictional scope prioritizes neighborhood-level policing, including villages (desa) and urban wards (kelurahan).</li>
                    <li>Polsek authority focuses on land-based community security, with limited maritime or special functions where applicable.</li>
                </ul>
                <p class="p-modal text-justify">
                    This close territorial alignment positions Polsek as the primary interface between Polri and the community.
                </p>
            </div>

            <!-- Civil - TNI AD - Police Equivalent -->
            <div class="tab-pane fade" id="polsek-equivalent" role="tabpanel" aria-labelledby="polsek-equivalent-tab" tabindex="0">
                <ul>
                    <li><strong>Polsek:</strong> Police territorial unit at sub-district level</li>
                    <li><strong>Kecamatan:</strong> Civil administrative authority</li>
                    <li><strong>Koramil:</strong> Military territorial unit at sub-district level</li>
                </ul>
                <div class="info-modal-note">
                    <strong>Note:</strong> While geographically aligned, Polsek, Koramil, and Sub-District governments operate under distinct legal mandates, collectively forming the foundation of local governance, security, and community stability.
                </div>
                <div class="info-modal-figure">
                    <img src="{{ asset('images/polsekcivilarmy.png') }}" alt="Civil &ndash; TNI AD (Army) &ndash; Police equivalent at Polsek level">
                </div>
            </div>

        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="police4Modal" tabindex="-1" aria-labelledby="level4Label" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered info-modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <div class="d-flex align-items-center gap-2">
            <img src="{{ asset('images/Layer3.png') }}" style="width:18px; height:18px;">
            <h5 class="modal-title mb-0" id="level4Label">Polres &mdash; Regency / City Police</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <ul class="nav nav-tabs info-modal-tabs px-3 pt-2" id="polresTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="polres-definition-tab" data-bs-toggle="tab" data-bs-target="#polres-definition"
                type="button" role="tab" aria-controls="polres-definition" aria-selected="true">Definition &amp; Purpose</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="polres-commander-tab" data-bs-toggle="tab" data-bs-target="#polres-commander"
                type="button" role="tab" aria-controls="polres-commander" aria-selected="false">Commander</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="polres-classification-tab" data-bs-toggle="tab" data-bs-target="#polres-classification"
                type="button" role="tab" aria-controls="polres-classification" aria-selected="false">Polres Classification</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="polres-roles-tab" data-bs-toggle="tab" data-bs-target="#polres-roles"
                type="button" role="tab" aria-controls="polres-roles" aria-selected="false">Responsibilities/Roles/Function</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="polres-geographic-tab" data-bs-toggle="tab" data-bs-target="#polres-geographic"
                type="button" role="tab" aria-controls="polres-geographic" aria-selected="false">Geographic Distribution</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="polres-equivalent-tab" data-bs-toggle="tab" data-bs-target="#polres-equivalent"
                type="button" role="tab" aria-controls="polres-equivalent" aria-selected="false">Civil &ndash; TNI AD (Army) &ndash; Police Equivalent</button>
        </li>
      </ul>

      <div class="modal-body info-modal-body">
        <div class="tab-content info-modal-content" id="polresTabContent">

            <!-- Definition & Purpose -->
            <div class="tab-pane fade show active" id="polres-definition" role="tabpanel" aria-labelledby="polres-definition-tab" tabindex="0">
                <p class="p-modal text-justify">
                    <strong>Definition:</strong> Polres/Polresta is the primary territorial command of the Indonesian National Police (Polri) at the regency or city level, responsible for law enforcement, public security, and public order in regency or city. A Polres is led by Kapolres (Chief of Resor Police) and Polresta led by Kapolresta (Chief of Municipality Police), who reports directly to Kapolda through Polda command structure.
                </p>
                <p class="p-modal text-justify">
                    Polres/Polresta jurisdictions are generally aligned with civil administrative boundaries of regencies for Polres and cities for Polresta, reflecting local governance structure. Unlike regency or city governments which are civilian administrative entities, Polres/Polresta are security institutions exercising executive authority in policing and law enforcement.
                </p>
                <p class="p-modal text-justify">
                    <strong>Purpose:</strong> Polres maintain day-to-day public order, enforce criminal and traffic laws, protect communities, and serve as the frontline institution for internal security and public safety at the local level.
                </p>
                <p class="p-modal text-justify">
                    <strong>Command Level:</strong> Regency/city police command under the relevant Regional Police&mdash;Polda. A Polres normally exercises territorial police authority over a regency, city, metropolitan police jurisdiction, or another designated area. Under the current Polri structure, Polres consists of Type A, Type B, Type C, and Type D.
                </p>
            </div>

            <!-- Commander -->
            <div class="tab-pane fade" id="polres-commander" role="tabpanel" aria-labelledby="polres-commander-tab" tabindex="0">
                <ul>
                    <li>
                        <strong>Type A Polres - Polres Kota Besar (Polrestabes):</strong> Led by Kapolrestabes, a senior middle-ranking police officer bearing the insignia of (3) three gold jasmine flowers, holding the rank of Police Commissioner - Komisaris Besar Polisi (Kombes Pol). Kapolrestabes reports directly and is responsible to the relevant Kapolda.
                    </li>
                    <li>
                        <strong>Type B Polres - Polres Metropolitan (Polres Metro):</strong> Led by Kapolres Metro, a senior middle-ranking police officer bearing the insignia of (3) three gold jasmine flowers, holding the rank of Police Commissioner - Komisaris Besar Polisi (Kombes Pol). Kapolres Metro reports directly and is responsible to the Kapolda Metro Jaya.
                    </li>
                    <li>
                        <strong>Type C Polres - Polres Kota (Polresta):</strong> Led by Kapolresta, a senior middle-ranking police officer bearing the insignia of three gold jasmine flowers, holding the rank of Police Commissioner - Komisaris Besar Polisi (Kombes Pol). Kapolresta reports directly and is responsible to the relevant Kapolda.
                    </li>
                    <li>
                        <strong>Type D Polres - Polres:</strong> Led by Kapolres, a middle-ranking police officer bearing the insignia of two gold jasmine flowers, holding the rank of Police Senior Commissioner - Ajun Komisaris Besar Polisi (AKBP). Kapolres reports directly and is responsible to the relevant Kapolda.
                    </li>
                </ul>
                <p class="p-modal text-justify">
                    The current personnel schedules introduced through Perpol No. 7 of 2025 assign the Kapolres position at Kombes Pol level for Types A, B, and C, while the Type D Kapolres position is assigned at AKBP level. The regulation has been effective since 29 August 2025.
                </p>
                <div class="info-modal-note">
                    <strong>Note:</strong> Kombes Pol and AKBP use gold jasmine flowers, not general-officer stars. Three jasmine flowers indicate Kombes Pol, two jasmine flowers indicate AKBP.
                </div>
            </div>

            <!-- Polres Classification -->
            <div class="tab-pane fade" id="polres-classification" role="tabpanel" aria-labelledby="polres-classification-tab" tabindex="0">
                <p class="p-modal text-justify">
                    Polres classification is not determined solely by whether its jurisdiction is formally designated as a regency or city. Polri evaluates the characteristics and operational demands of the police jurisdiction, including territorial development, population, crime dynamics, public-security conditions, service requirements, organizational workload, personnel, facilities and operational readiness. Polri uses feasibility studies and territorial-unit classification data when forming a new Polres or upgrading an existing unit.
                </p>
                <p class="p-modal text-justify">
                    Polri formally recognizes the following classifications:
                </p>

                <div class="table-responsive">
                    <table class="unit-class-table">
                        <thead>
                            <tr>
                                <th style="width:15%;">Unit Type</th>
                                <th style="width:20%;">Classification</th>
                                <th style="width:27%;">Head Position &amp; Rank</th>
                                <th>Role</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">Polrestabes</th>
                                <td>Polres Type A &mdash; Polres Kota Besar</td>
                                <td>Kapolrestabes, usually Senior Police Commissioner (Kombes Pol)</td>
                                <td>Large city police command for major provincial-capital urban areas with high population and operational complexity.</td>
                            </tr>
                            <tr>
                                <th scope="row">Polres Metro</th>
                                <td>Polres Type B &mdash; Polres Metropolitan</td>
                                <td>Kapolres Metro, usually Senior Police Commissioner (Kombes Pol)</td>
                                <td>Metropolitan police command for major urban and metropolitan areas.</td>
                            </tr>
                            <tr>
                                <th scope="row">Polresta</th>
                                <td>Polres Type C &mdash; Polres Kota</td>
                                <td>Kapolresta, usually Senior Police</td>
                                <td>City police command for urban areas below Polrestabes and Polres Metro scale.</td>
                            </tr>
                            <tr>
                                <th scope="row">Polres</th>
                                <td>Polres Type D &mdash; Polres</td>
                                <td>Kapolres, usually Senior Police Adjunct Commissioner (AKBP)</td>
                                <td>Standard regency or city police command and the most common Polres-level unit.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Responsibilities / Roles / Function -->
            <div class="tab-pane fade" id="polres-roles" role="tabpanel" aria-labelledby="polres-roles-tab" tabindex="0">
                <p class="p-modal"><strong>Responsibilities</strong></p>
                <ul>
                    <li>
                        <strong>Public Security and Order (Kamtibmas):</strong> Maintain public order, prevent crime, and ensure community safety across neighborhoods, villages, and urban districts.
                    </li>
                    <li>
                        <strong>Law Enforcement:</strong> Investigate and enforce criminal law, including general crimes, narcotics offenses, and selected special crimes within their jurisdiction.
                    </li>
                    <li>
                        <strong>Traffic and Public Safety:</strong> Regulate traffic, enforce road safety laws, manage accidents, and oversee local licensing functions.
                    </li>
                    <li>
                        <strong>Crime Prevention and Community Policing:</strong> Implement community-based policing (Polmas), neighborhood patrols, and early-warning mechanisms.
                    </li>
                    <li>
                        <strong>Protection of Local Vital Objects:</strong> Secure local government facilities, public infrastructure, commercial centers, and strategic community assets.
                    </li>
                    <li>
                        <strong>Disaster and Emergency Support:</strong> Provide security, evacuation assistance, and law enforcement support during local emergencies and disasters.
                    </li>
                </ul>

                <p class="p-modal"><strong>Roles &amp; Function</strong></p>
                <p class="p-modal text-justify">
                    Polres is the operational backbone of Polri at regency or city, translating provincial policies into direct policing actions:
                </p>
                <ul>
                    <li>
                        <strong>Local Command and Control</strong>
                        <ul>
                            <li><strong>Territorial Policing Authority:</strong> Exercise command over subordinate Polsek (Sector Police) within their jurisdiction.</li>
                            <li><strong>Operational Planning:</strong> Develop local security and patrol plans based on crime trends, population density, and geographic conditions.</li>
                        </ul>
                    </li>
                    <li>
                        <strong>Criminal Investigation and Law Enforcement</strong>
                        <ul>
                            <li><strong>Investigation Execution:</strong> Conduct investigations through functional units including Satreskrim (General Crimes), Satresnarkoba (Narcotics), Satreskrimsus (Selected Special Crimes)</li>
                            <li><strong>Case Management:</strong> Handle the majority of criminal cases occurring within the regency or city, escalating complex cases to Polda when required.</li>
                        </ul>
                    </li>
                    <li>
                        <strong>Public Order and Crowd Management</strong>
                        <ul>
                            <li><strong>Mass Activity Security:</strong> Secure demonstrations, religious activities, local elections, and public events.</li>
                            <li><strong>Initial Disturbance Response:</strong> Act as the first responder to public disorder, with reinforcement from Brimob or Polda when necessary.</li>
                        </ul>
                    </li>
                    <li>
                        <strong>Traffic Management and Public Services</strong>
                        <ul>
                            <li><strong>Traffic Operations:</strong> Manage local traffic enforcement and accident response through Traffic Management unit (Satlantas).</li>
                            <li><strong>Public Service Delivery:</strong> Provide frontline police services including reports, permits, emergency response, and community assistance.</li>
                        </ul>
                    </li>
                    <li>
                        <strong>Sociopolitical Stability</strong>
                        <ul>
                            <li><strong>Local Election Security:</strong> Ensure security during regional head elections (Pilkada) and national elections at the local level.</li>
                            <li><strong>Conflict Prevention:</strong> Prevent and manage social conflicts, communal disputes, and public unrest through mediation and early intervention.</li>
                        </ul>
                    </li>
                    <li>
                        <strong>Disaster and Emergency Operations</strong>
                        <ul>
                            <li><strong>Local Disaster Response:</strong> Secure affected areas, assist evacuations, and protect relief distribution during disasters.</li>
                            <li><strong>Humanitarian Support:</strong> Maintain order and public safety during emergency relief and recovery phases.</li>
                        </ul>
                    </li>
                    <li>
                        <strong>Coordination with Local Government and Security Institutions</strong>
                        <ul>
                            <li><strong>Regional Leadership Coordination Forum (Forkopimda) Kabupaten/Kota Integration:</strong> Act as a key security element within the Regency/City Forkopimda, alongside the Regent/Mayor, Dandim, and local officials.</li>
                            <li><strong>Civil&ndash;Military Coordination:</strong> Coordinate closely with Kodim for territorial security support and emergency operations.</li>
                        </ul>
                    </li>
                </ul>
            </div>

            <!-- Geographic Distribution -->
            <div class="tab-pane fade" id="polres-geographic" role="tabpanel" aria-labelledby="polres-geographic-tab" tabindex="0">
                <p class="p-modal text-justify">
                    Polres are territorially organized to correspond directly with regency and city boundaries, ensuring alignment with Indonesia&rsquo;s local administrative structure. In practice:
                </p>
                <ul>
                    <li>Most Polres cover one Regency or one City.</li>
                    <li>Metropolitan areas may be designated as Polresta or Polrestabes, reflecting higher population density and security complexity.</li>
                    <li>Jurisdictional design accounts for urban centers, rural districts, coastal areas, and remote communities.</li>
                    <li>Polres authority spans land-based and limited maritime security responsibilities within local administrative boundaries.</li>
                </ul>
                <p class="p-modal text-justify">
                    This alignment enables Polres to function as the primary interface between Polri and local communities.
                </p>
            </div>

            <!-- Civil - TNI AD - Police Equivalent -->
            <div class="tab-pane fade" id="polres-equivalent" role="tabpanel" aria-labelledby="polres-equivalent-tab" tabindex="0">
                <p class="p-modal text-justify">
                    Polres are territorially organized to correspond directly with regency and city boundaries, ensuring alignment with Indonesia&rsquo;s local administrative structure. In practice:
                </p>
                <ul>
                    <li>Most Polres cover one Regency or one City.</li>
                    <li>Metropolitan areas may be designated as Polresta or Polrestabes, reflecting higher population density and security complexity.</li>
                    <li>Jurisdictional design accounts for urban centers, rural districts, coastal areas, and remote communities.</li>
                    <li>Polres authority spans land-based and limited maritime security responsibilities within local administrative boundaries.</li>
                </ul>
                <p class="p-modal text-justify">
                    This alignment enables Polres to function as the primary interface between Polri and local communities.
                </p>
                <div class="info-modal-figure">
                    <img src="{{ asset('images/polrescivilarmy.png') }}" alt="Civil &ndash; TNI AD (Army) &ndash; Police equivalent at Polres level">
                </div>
            </div>

        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="police5Modal" tabindex="-1" aria-labelledby="level5Label" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered info-modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <div class="d-flex align-items-center gap-2">
            <img src="{{ asset('images/Layer2.png') }}" style="width:18px; height:18px;">
            <h5 class="modal-title mb-0" id="level5Label">Polda &mdash; Regional Police</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <ul class="nav nav-tabs info-modal-tabs px-3 pt-2" id="poldaTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="polda-definition-tab" data-bs-toggle="tab" data-bs-target="#polda-definition"
                type="button" role="tab" aria-controls="polda-definition" aria-selected="true">Definition &amp; Purpose</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="polda-commander-tab" data-bs-toggle="tab" data-bs-target="#polda-commander"
                type="button" role="tab" aria-controls="polda-commander" aria-selected="false">Commander</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="polda-classification-tab" data-bs-toggle="tab" data-bs-target="#polda-classification"
                type="button" role="tab" aria-controls="polda-classification" aria-selected="false">Polda Classification</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="polda-roles-tab" data-bs-toggle="tab" data-bs-target="#polda-roles"
                type="button" role="tab" aria-controls="polda-roles" aria-selected="false">Responsibilities/Roles/Function</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="polda-geographic-tab" data-bs-toggle="tab" data-bs-target="#polda-geographic"
                type="button" role="tab" aria-controls="polda-geographic" aria-selected="false">Geographic Distribution</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="polda-equivalent-tab" data-bs-toggle="tab" data-bs-target="#polda-equivalent"
                type="button" role="tab" aria-controls="polda-equivalent" aria-selected="false">Civil &ndash; TNI AD (Army) &ndash; Police Equivalent</button>
        </li>
      </ul>

      <div class="modal-body info-modal-body">
        <div class="tab-content info-modal-content" id="poldaTabContent">

            <!-- Definition & Purpose -->
            <div class="tab-pane fade show active" id="polda-definition" role="tabpanel" aria-labelledby="polda-definition-tab" tabindex="0">
                <p class="p-modal text-justify">
                    <strong>Definition:</strong> Polda (Kepolisian Daerah) is the highest regional-level command of the Indonesian National Police (Polri), responsible for law enforcement, public security, and public order within one or more provinces. A Polda is led by a Kapolda, who reports directly to Kapolri.
                </p>
                <p class="p-modal text-justify">
                    Polda are generally aligned with provincial boundaries for administrative and operational efficiency, mirroring the civil governance structure of provinces. However, unlike provinces&mdash;which are civilian administrative entities, Polda are security institutions with executive authority in policing and law enforcement. Currently, Indonesia has 36 Polda overseeing 38 provinces, with several Polda exercising jurisdiction over more than one province due to historical development, metropolitan security requirements, or transitional administrative arrangements.
                </p>
                <p class="p-modal text-justify">
                    <strong>Purpose:</strong> Polda maintain public order, enforce national and regional laws, protect citizens, and ensure internal security within their jurisdiction, supporting national stability and the rule of law.
                </p>
                <p class="p-modal text-justify">
                    <strong>Command Level:</strong> Provincial police command (highest territorial command)
                </p>
            </div>

            <!-- Commander -->
            <div class="tab-pane fade" id="polda-commander" role="tabpanel" aria-labelledby="polda-commander-tab" tabindex="0">
                <ul>
                    <li>
                        <strong>Polda Metro (Country Capital):</strong> Led by Kapolda, a high-ranking police general with the insignia of three (3) gold stars, holding the rank of Police Commissioner General (Komisaris Jenderal Polisi&mdash;Komjen Pol). Kapolda reports directly and is responsible to Kapolri (Chief of the Indonesian National Police).
                    </li>
                    <li>
                        <strong>Type A Polda:</strong> Led by Kapolda, a high-ranking police general with the insignia of two (2) gold stars, holding the rank of Police Inspector General (Inspektur Jenderal Polisi&mdash;Irjen Pol). Kapolda reports directly and is responsible to Kapolri (Chief of the Indonesian National Police).
                    </li>
                    <li>
                        <strong>Type B Polda:</strong> Led by Kapolda, a high-ranking police general bearing the insignia of one (1) gold star, holding the rank of Police Brigadier General (Brigadir Jenderal Polisi&mdash;Brigjen Pol). Kapolda reports directly and is responsible to Kapolri (Chief of the Indonesian National Police).
                    </li>
                </ul>
            </div>

            <!-- Polda Classification -->
            <div class="tab-pane fade" id="polda-classification" role="tabpanel" aria-labelledby="polda-classification-tab" tabindex="0">
                <p class="p-modal text-justify">
                    Polda classification is not determined solely by provincial size. Polri assessed territorial police commands through a formal evaluation covering geographic conditions, population, natural resources, ideological, political, economic and sociocultural conditions, public-security workload and organizational capability. These dimensions measure the complexity of crime, traffic, public services, security threats, strategic importance and operational demands within each police jurisdiction.
                </p>
                <p class="p-modal text-justify">
                    Polri recognizes Polda Type A-Khusus (Special Type-A Polda), Type A and Type B. Polda Metro Jaya is the only Type A-Khusus or A+ Polda, reflecting its responsibility for Jakarta and the surrounding metropolitan area. On May 2026, Kapolda Metro Jaya position is held at the rank of Komisaris Jenderal Polisi. Type A Polda are normally commanded by an Inspektur Jenderal Polisi, while Type B Polda are structurally associated with a Brigadir Jenderal Polisi.
                </p>
                <p class="p-modal text-justify">
                    The remaining operational Type B Polda were previously upgraded to Type A. Nevertheless, Type B remains part of the organizational classification framework and continues to appear in some Polri administrative or comparative publications.
                </p>

                <div class="table-responsive">
                    <table class="polda-class-table">
                        <thead>
                            <tr>
                                <th style="width:18%;">Unit Type</th>
                                <th style="width:22%;">Classification</th>
                                <th style="width:30%;">Head Position &amp; Rank</th>
                                <th>Role</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Polda Metro Jaya</strong></td>
                                <td>Polda Type A-Khusus</td>
                                <td>Kapolda Metro Jaya &mdash; Komjen Pol, three-star police general</td>
                                <td>Special police command for Jakarta and its metropolitan area.</td>
                            </tr>
                            <tr>
                                <td><strong>Polda Type A</strong></td>
                                <td>Regional Police Type A</td>
                                <td>Kapolda &mdash; Irjen Pol, two-star police general</td>
                                <td>Provincial police command with high operational complexity.</td>
                            </tr>
                            <tr>
                                <td><strong>Polda Type B</strong></td>
                                <td>Regional Police Type B</td>
                                <td>Kapolda &mdash; Brigjen Pol, one-star police general</td>
                                <td>Provincial police command with a smaller structure and workload.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Responsibilities / Roles / Function -->
            <div class="tab-pane fade" id="polda-roles" role="tabpanel" aria-labelledby="polda-roles-tab" tabindex="0">
                <p class="p-modal text-justify">
                    A Polda is the main territorial police command under Mabes Polri, the National Police HQ. The Polda is responsible for Polri duties in its assigned police provincial area and the supervision of Municipality Police (Polres). Primary responsibilities include:
                </p>
                <p class="p-modal"><strong>Responsibilities:</strong></p>
                <ul>
                    <li>
                        <strong>Public Security and Order (Kamtibmas):</strong> Responsible for maintaining public order, prevent disturbances, and provide a safe and stable security environment in the provincial jurisdiction.
                    </li>
                    <li>
                        <strong>Regional Law Enforcement:</strong> Implement national laws (criminal and procedural) through criminal investigation support, arrest, prosecution support, regional patrols and emergency response, and intelligence and early warning
                    </li>
                    <li>
                        <strong>Traffic &amp; Public Safety (Polantas):</strong> regulate traffic, enforce road safety laws, prevent accidents, and provide safe movement of people and goods on public roads.
                    </li>
                    <li>
                        <strong>Crime Prevention &amp; Community Policing (Polmas):</strong> Conduct preventive policing, intelligence-led operations, and community policing to reduce crime and social disturbances.
                    </li>
                    <li>
                        <strong>Protect Vital &amp; Strategic Objects (Obvitnas):</strong> Secure vital and strategic objects, including critical infrastructure, government facilities, and economic assets, to protect national and regional interests.
                    </li>
                    <li>
                        <strong>Disaster and Emergency Support:</strong> Polri maintains internal disaster and emergency support capability through several functional elements. These elements support search and rescue, evacuation, public order, area security, traffic control, victim identification, medical support, air and water support, K9 search, logistics, community assistance, and inter-agency coordination during disasters and emergency situations.
                        <div class="info-modal-note">
                            <strong>Note:</strong> Polri has a formal Search and Rescue (SAR) capability. The SAR Polri includes SAR personnel, SAR land and water capability, evacuation, first aid, jungle rescue, fire rescue, vertical rescue, water rescue, accident rescue, and specialist SAR skills. It includes Korbrimob, Korlantas, Polair, Poludara, Sabhara, and Satwa/K9, and the medical and victim-identification arm, Bidang Disaster Victim Identification / Bid DVI.
                        </div>
                    </li>
                    <li>
                        <strong>Coordination:</strong> Coordination with governors, regional military commands (Kodam), prosecutors, courts, and local agencies
                    </li>
                    <li>
                        <strong>Regional Command and Control</strong>
                        <ul>
                            <li><strong>Territorial Policing Authority:</strong> Command and control over all subordinate police units within Polda jurisdiction, including Polres/Polresta/Polrestabes and Polsek.</li>
                            <li><strong>Operational Planning:</strong> Formulate regional security plans based on threat assessments, population density, and geographic characteristics.</li>
                        </ul>
                    </li>
                    <li>
                        <strong>Criminal Investigation and Law Enforcement</strong>
                        <ul>
                            <li><strong>Investigation Supervision:</strong> Oversee investigations conducted by regional directorates, including Ditreskrimum (General Crimes), Ditreskrimsus (Special Crimes), and Ditresnarkoba (Narcotics)</li>
                            <li><strong>Complex Case Handling:</strong> Handle high-profile, cross-district, or strategically significant criminal cases at the Polda level.</li>
                        </ul>
                    </li>
                    <li>
                        <strong>Public Order and Security Management</strong>
                        <ul>
                            <li><strong>Mass Activity Security:</strong> Secure demonstrations, elections, religious events, and other large public gatherings.</li>
                            <li><strong>High-Risk Security Operations:</strong> Deploy and command Mobile brigade (Brimob) units for riot control and armed law enforcement support when required.</li>
                        </ul>
                    </li>
                    <li>
                        <strong>Traffic Management and Public Services</strong>
                        <ul>
                            <li><strong>Traffic Regulation:</strong> Manage traffic operations through Traffic Management Division (Ditlantas), including enforcement, accident response, and congestion control.</li>
                            <li><strong>Public Service Delivery:</strong> Provide police services such as reporting, permits, identification support, and emergency response.</li>
                        </ul>
                    </li>
                    <li>
                        <strong>Sociopolitical Stability</strong>
                        <ul>
                            <li><strong>Election Security:</strong> Provide security coordination during regional and national elections with election bodies and local governments.</li>
                            <li><strong>Conflict Prevention and Mitigation:</strong> Prevent and manage communal conflict, political violence, and social unrest through early intervention and mediation.</li>
                        </ul>
                    </li>
                    <li>
                        <strong>Disaster and Emergency Operations</strong>
                        <ul>
                            <li><strong>Disaster Response Support:</strong> Secure disaster-affected areas, support evacuations, and protect humanitarian assistance operations.</li>
                            <li><strong>Humanitarian Assistance:</strong> Maintain public order and safety during crisis response and recovery phases.</li>
                        </ul>
                    </li>
                    <li>
                        <strong>Coordination with Civil and Security Institutions</strong>
                        <ul>
                            <li><strong>Regional Leadership Coordination Forum (Forkopimda) Integration:</strong> Act as a core security institution within the Provincial Forkopimda, alongside the Governor, Pangdam, and other regional leaders.</li>
                            <li><strong>Civil&ndash;Military Coordination:</strong> Coordinate with Kodam and Korem for internal security contingencies and emergency support operations.</li>
                        </ul>
                    </li>
                </ul>
            </div>

            <!-- Geographic Distribution -->
            <div class="tab-pane fade" id="polda-geographic" role="tabpanel" aria-labelledby="polda-geographic-tab" tabindex="0">
                <p class="p-modal text-justify">
                    In general, one Polda corresponds to one province. However, several Polda jurisdictions cover more than one province, reflecting unique security, demographic, or administrative conditions:
                </p>
                <p class="p-modal"><strong>Polda with Multi-Province or Cross-Provincial Coverage</strong></p>
                <ul>
                    <li>
                        <strong>Polda Metro Jaya:</strong> Covers DKI Jakarta and the Greater Jakarta metropolitan area, including designated urban jurisdictions in West Java and Banten. This arrangement reflects Jakarta&rsquo;s role as the national capital and the integrated security needs of the metropolitan region.
                    </li>
                    <li>
                        <strong>Polda Papua:</strong> Covers multiple provinces in the Papua region, including Papua, Papua Tengah, Papua Pegunungan, and Papua Selatan, follows by creation of new provinces. This structure remains transitional due to geographic scale, security sensitivity, and organizational considerations.
                    </li>
                    <li>
                        <strong>Polda Papua Barat:</strong> Covers Papua Barat and Papua Barat Daya, reflecting gradual adjustment of police territorial commands following regional administrative expansion.
                    </li>
                </ul>
                <p class="p-modal text-justify">
                    Other Polda exercise jurisdiction over a single province, aligned directly with Indonesia&rsquo;s civilian administrative boundaries.
                </p>
            </div>

            <!-- Civil - TNI AD - Police Equivalent -->
            <div class="tab-pane fade" id="polda-equivalent" role="tabpanel" aria-labelledby="polda-equivalent-tab" tabindex="0">
                <ul>
                    <li><strong>Province:</strong> Civil administrative authority (governance and public administration)</li>
                    <li><strong>Kodam:</strong> Regional military territorial command (defense and security support)</li>
                    <li><strong>Polda:</strong> Regional police territorial command (law enforcement and internal security)</li>
                </ul>
                <div class="info-modal-note">
                    <strong>Note:</strong> Although geographically aligned, Polda, Kodam, and Province operate under distinct legal authorities and mandates, forming an integrated but functionally differentiated regional governance and security framework.
                </div>
                <div class="info-modal-figure">
                    <img src="{{ asset('images/policecivilarmy.png') }}" alt="Civil &ndash; TNI AD (Army) &ndash; Police equivalent">
                </div>
            </div>

        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="police6Modal" tabindex="-1" aria-labelledby="polriHqLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered info-modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <div class="d-flex align-items-center gap-2">
            <img src="{{ asset('images/Layer1.png') }}" style="width:18px; height:18px;">
            <h5 class="modal-title mb-0" id="polriHqLabel">National Police HQ (POLRI)</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <ul class="nav nav-tabs info-modal-tabs px-3 pt-2" id="polriHqTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="polri-hq-definition-tab" data-bs-toggle="tab" data-bs-target="#polri-hq-definition"
                type="button" role="tab" aria-controls="polri-hq-definition" aria-selected="true">Definition &amp; Purpose</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="polri-hq-commander-tab" data-bs-toggle="tab" data-bs-target="#polri-hq-commander"
                type="button" role="tab" aria-controls="polri-hq-commander" aria-selected="false">Commander</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="polri-hq-roles-tab" data-bs-toggle="tab" data-bs-target="#polri-hq-roles"
                type="button" role="tab" aria-controls="polri-hq-roles" aria-selected="false">Responsibilities/Roles/Function</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="polri-hq-geographic-tab" data-bs-toggle="tab" data-bs-target="#polri-hq-geographic"
                type="button" role="tab" aria-controls="polri-hq-geographic" aria-selected="false">Geographic Distribution</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="polri-hq-equivalent-tab" data-bs-toggle="tab" data-bs-target="#polri-hq-equivalent"
                type="button" role="tab" aria-controls="polri-hq-equivalent" aria-selected="false">Civil &ndash; TNI AD (Army) &ndash; Police Equivalent</button>
        </li>
      </ul>

      <div class="modal-body info-modal-body">
        <div class="tab-content info-modal-content" id="polriHqTabContent">

            <div class="tab-pane fade show active" id="polri-hq-definition" role="tabpanel" aria-labelledby="polri-hq-definition-tab" tabindex="0">
                <p class="p-modal text-justify">
                    <strong>Definition:</strong> Polri is Indonesia&rsquo;s national police institution and the highest police authority responsible for maintaining public security and order, enforcing the law, and providing protection, assistance, and services to the public throughout the territory of the Republic of Indonesia.
                </p>
                <p class="p-modal text-justify">
                    Polri is legally established as national police force operating as one unified organization. Its jurisdiction extends throughout Indonesia, with the national territory divided into police jurisdictions according to operational requirements. Polri is directly under the President of the Republic of Indonesia and is led by the Chief of the Indonesian National Police (Kepala Kepolisian Negara Republik Indonesia &ndash; Kapolri) who is responsible to the President.
                </p>
                <p class="p-modal text-justify">
                    The principal legal basis remains Law No. 2 of 2002 on the Indonesian National Police, most recently amended by Law No. 5 of 2026, which entered into force on 17 June 2026. The current law reinforces Kapolri&rsquo;s authority to establish, implement, and control technical police policy and to lead national police operations, capability development, and the management of specialized police equipment.
                </p>
                <p class="p-modal text-justify">
                    <strong>Purpose:</strong> Polri maintains public security and order, enforces the law, protects and serves the population, prevents and responds to crime and security threats, and maintains the domestic security environment necessary for national stability, public safety, and the rule of law.
                </p>
                <p class="p-modal text-justify">
                    <strong>Command Level:</strong> National police command &ndash; highest police command in Indonesia.
                </p>
            </div>

            <div class="tab-pane fade" id="polri-hq-commander" role="tabpanel" aria-labelledby="polri-hq-commander-tab" tabindex="0">
                <p class="p-modal text-justify">
                    <strong>Chief of the Indonesian National Police (Kapolri):</strong> Polri is led by the Kapolri, the highest-ranking police officer in the Indonesian National Police. The position is held by a Police General (Jenderal Polisi) bearing the insignia of four (4) gold stars.
                </p>
                <p class="p-modal text-justify">
                    Kapolri leads Polri nationally and is directly responsible to the President of the Republic of Indonesia. Kapolri establishes, implements, and controls national technical police policy and exercises command over police operations, organizational capability development, and national police resources.
                </p>
                <p class="p-modal text-justify">
                    Kapolri is appointed and dismissed by the President with the approval of the House of Representatives (Dewan Perwakilan Rakyat&mdash;DPR RI), providing a constitutional and legislative mechanism for appointment of the national police chief.
                </p>
                <p class="p-modal text-justify">
                    The Kapolri is assisted by the Deputy Chief of the Indonesian National Police (Wakapolri) and the principal leadership, staff, operational, and supporting elements of National Police Headquarters (Mabes Polri).
                </p>
            </div>

            <div class="tab-pane fade" id="polri-hq-roles" role="tabpanel" aria-labelledby="polri-hq-roles-tab" tabindex="0">
                <p class="p-modal text-justify">
                    Polri is the national institution responsible for exercising police functions throughout Indonesia. Its three statutory core duties are to maintain public security and order, enforce the law, and provide protection, assistance, and services to the public. These responsibilities are implemented through Mabes Polri, national specialized units, Polda, and subordinate territorial police organizations.
                </p>

                <p class="p-modal"><strong>Responsibilities</strong></p>
                <ul>
                    <li><strong>National Public Security and Order (Kamtibmas):</strong> Maintain public security and order throughout Indonesia, prevent disturbances, protect public activities, and support a safe and stable domestic security environment.</li>
                    <li><strong>National Law Enforcement:</strong> Enforce criminal law and other applicable legislation through investigation, arrest, evidence gathering, criminal intelligence, specialized enforcement operations, and coordination with prosecutors, courts, and other law-enforcement institutions.</li>
                    <li><strong>Protection, Assistance and Public Service:</strong> Provide police protection, assistance, emergency response, public reporting services, licensing and administrative police services, and other policing services required by the population.</li>
                    <li><strong>Crime Prevention &amp; Community Policing (Polmas):</strong> Develop preventive policing, community engagement, patrol activities, early intervention, public-security partnerships, and community policing to reduce crime and prevent social disturbances.</li>
                    <li><strong>National Police Command and Control:</strong> Establish national policing policy, strategic priorities, operational standards, and command direction for all Polri organizations and territorial police commands.</li>
                    <li><strong>Criminal Investigation and Strategic Law Enforcement:</strong> Conduct and supervise investigations into serious, organized, interregional, transnational, economic, financial, narcotics, cyber, corruption, and other strategically significant crimes.</li>
                    <li><strong>Police Intelligence and Early Warning:</strong> Collect, assess, and develop security intelligence to identify threats to public security, political and social stability, major events, government activities, and national interests.</li>
                    <li><strong>Traffic &amp; Public Safety (Polantas):</strong> Maintain road traffic security, safety, order, and smooth movement; enforce traffic law; conduct accident response and investigation; support traffic engineering; and provide national traffic-related police services.</li>
                    <li><strong>Public Order and High-Risk Security Operations:</strong> Maintain public order during demonstrations, mass gatherings, major national events, elections, and other activities requiring coordinated security operations.</li>
                    <li><strong>High-Intensity Crime Response:</strong> Deploy specialized capabilities, particularly Korps Brimob Polri, against armed threats, terrorism-related contingencies, riots, major public disorder, explosives threats, and other high-risk law-enforcement situations.</li>
                    <li><strong>Counterterrorism:</strong> Conduct prevention, intelligence development, investigation, enforcement, and operational response against terrorism through specialized national counterterrorism capabilities, principally Detachment 88 Anti-Terror (Densus 88 AT Polri).</li>
                    <li><strong>Corruption Crime Enforcement:</strong> Conduct prevention, investigation, asset tracing, and enforcement against corruption and associated money laundering through Kortastipidkor and other authorized investigative elements. Kortastipidkor is an operational element directly under Kapolri.</li>
                    <li><strong>Protection of Vital &amp; Strategic Objects (Obvitnas):</strong> Protect nationally important infrastructure, government facilities, transportation systems, industrial facilities, energy infrastructure, economic assets, and other designated vital objects.</li>
                    <li><strong>Maritime and Air Police Support:</strong> Conduct water and air policing, maritime patrol, law-enforcement support, transportation security, surveillance, mobility, evacuation, and operational support through Polairud and related capabilities.</li>
                    <li><strong>National and Transnational Crime Control:</strong> Coordinate responses to organized crime, narcotics trafficking, human trafficking, cybercrime, financial crime, smuggling, terrorism, and other offences operating across provincial or international boundaries.</li>
                </ul>

                <p class="p-modal"><strong>National Command and Control</strong></p>
                <ul>
                    <li><strong>National Policing Authority:</strong> Exercise command and organizational control across Polri, including Mabes Polri elements, national specialized units, and territorial police organizations.</li>
                    <li><strong>National Operational Planning:</strong> Formulate policing strategies and security operations based on national threat assessments, intelligence, crime patterns, population, geographic conditions, critical infrastructure, and national strategic priorities.</li>
                    <li><strong>Territorial Command Supervision:</strong> Supervise Polda and ensure national policies, operational standards, law-enforcement requirements, and organizational directives are implemented consistently throughout police jurisdictions.</li>
                    <li><strong>National Mobilization of Police Resources:</strong> Reinforce regional commands with specialized personnel, Brimob, investigative capabilities, traffic units, air and maritime support, medical resources, K9 units, logistics, and other national assets when operational requirements exceed local capability.</li>
                </ul>

                <p class="p-modal"><strong>Criminal Investigation and Law Enforcement</strong></p>
                <ul>
                    <li><strong>National Investigation Coordination:</strong> Direct and coordinate criminal investigation policy and provide national-level support for serious or complex investigations.</li>
                    <li><strong>Cross-Jurisdictional Cases:</strong> Coordinate cases involving multiple Polda, international criminal networks, or offences that exceed the operational capability or jurisdiction of a single regional police command.</li>
                    <li><strong>Strategic Case Handling:</strong> Handle criminal cases affecting national security, major state interests, critical infrastructure, the national economy, or other strategically important sectors.</li>
                    <li><strong>Specialized Investigation:</strong> Maintain specialized capabilities for general crime, special crime, narcotics, cybercrime, corruption, terrorism, forensic investigation, fingerprints and identification, and other technical investigative disciplines.</li>
                </ul>

                <p class="p-modal"><strong>Public Order and Security Management</strong></p>
                <ul>
                    <li><strong>National Event Security:</strong> Plan and coordinate security for presidential and state activities, national elections, international summits, major sporting events, religious events, demonstrations, and other large-scale activities.</li>
                    <li><strong>Public Disorder Management:</strong> Coordinate police responses to riots, widespread disturbances, communal conflict, violent demonstrations, and other incidents that may require reinforcement across territorial jurisdictions.</li>
                    <li><strong>Strategic Security Operations:</strong> Establish national police operations or task forces where threats extend across several Polda or require specialized centralized command.</li>
                </ul>

                <p class="p-modal"><strong>Traffic Management and Public Services</strong></p>
                <ul>
                    <li><strong>National Traffic Policy:</strong> Establish national traffic-policing policy and coordinate traffic enforcement, road safety, accident management, and traffic operations through Korlantas Polri and territorial traffic units.</li>
                    <li><strong>Public Service Standards:</strong> Establish and supervise national standards for police services, including police reports, identification-related police services, licensing, traffic administration, emergency assistance, and public complaints.</li>
                </ul>

                <p class="p-modal"><strong>Sociopolitical Stability</strong></p>
                <ul>
                    <li><strong>Election Security:</strong> Coordinate national police security for presidential, legislative, and regional elections in cooperation with election authorities, government institutions, TNI where legally authorized, and regional governments.</li>
                    <li><strong>Conflict Prevention and Mitigation:</strong> Identify, prevent, and respond to communal conflict, political violence, mass disturbances, and other threats to public security through intelligence, preventive policing, mediation, and law enforcement.</li>
                    <li><strong>National Stability Support:</strong> Maintain the public-security environment required for government functions, economic activity, public services, transportation, and normal community life.</li>
                </ul>

                <p class="p-modal"><strong>Disaster and Emergency Operations</strong></p>
                <ul>
                    <li><strong>Disaster Response Support:</strong> Deploy police personnel and specialized resources to secure disaster areas, regulate access and traffic, support evacuation, protect victims and relief supplies, and maintain public order.</li>
                    <li><strong>Search and Rescue Support:</strong> Maintain police SAR-related capabilities through Brimob, Polairud, Sabhara/Samapta, K9, traffic, medical, aviation, and other specialized elements. These capabilities allow Polri to conduct rescue and evacuation activities and to support national search-and-rescue operations.</li>
                    <li><strong>Disaster Victim Identification (DVI):</strong> Conduct forensic identification of disaster victims through Polri medical and forensic capabilities and coordinate with relevant national and international institutions.</li>
                    <li><strong>Humanitarian Assistance:</strong> Provide security, medical assistance, evacuation support, logistics, public information, and community assistance during emergencies and disaster recovery.</li>
                </ul>
                <div class="info-modal-note">
                    <strong>Note:</strong> Polri possesses formal SAR and disaster-response capabilities, but Indonesia&rsquo;s national search-and-rescue system is led by the National Search and Rescue Agency (Badan Nasional Pencarian dan Pertolongan&mdash;Basarnas). Polri therefore performs both independent police emergency functions and supporting/inter-agency SAR functions according to the nature of the incident.
                </div>

                <p class="p-modal"><strong>International and Transnational Cooperation</strong></p>
                <ul>
                    <li><strong>International Police Cooperation:</strong> Coordinate police cooperation with foreign law-enforcement agencies, INTERPOL mechanisms, international organizations, and Indonesian diplomatic missions through Divhubinter Polri and related units.</li>
                    <li><strong>Transnational Crime Coordination:</strong> Exchange intelligence and coordinate investigations involving terrorism, cybercrime, narcotics trafficking, trafficking in persons, organized crime, fugitives, and other cross-border offences.</li>
                    <li><strong>International Peacekeeping:</strong> Provide police personnel for authorized international peacekeeping and international policing missions.</li>
                </ul>

                <p class="p-modal"><strong>Organizational Capability and National Support</strong></p>
                <ul>
                    <li><strong>Personnel Development:</strong> Establish national recruitment, education, training, career development, professional standards, and human-resource policies.</li>
                    <li><strong>Education and Training:</strong> Develop professional police education and specialized operational training through Polri education institutions and training commands.</li>
                    <li><strong>Logistics and Equipment:</strong> Plan, procure, maintain, distribute, and manage police equipment, vehicles, weapons, communications systems, specialized technology, and other operational resources. Law No. 5 of 2026 expressly places responsibility for planning, procurement, maintenance, and repair of specialized police equipment under Kapolri&rsquo;s national leadership responsibilities.</li>
                    <li><strong>Technology and Police Information Systems:</strong> Develop national police communications, information technology, data management, command systems, digital policing, and cyber capabilities.</li>
                </ul>

                <p class="p-modal"><strong>Oversight, Professionalism and Accountability</strong></p>
                <ul>
                    <li><strong>Internal Oversight:</strong> Maintain institutional oversight through inspectorate, investigation-supervision, professional, and internal-security functions.</li>
                    <li><strong>Professional Standards:</strong> Ensure police activities comply with professional, proportionality, transparency, and accountability principles.</li>
                    <li><strong>Organizational Accountability:</strong> Maintain systems for supervision, investigation control, professional conduct, and internal security. Law No. 5 of 2026 expressly strengthened these principles and allows police oversight systems to use policing technology and scientific capabilities.</li>
                    <li><strong>External Institutional Oversight:</strong> Cooperate with the National Police Commission (Komisi Kepolisian Nasional&mdash;Kompolnas), which is positioned under and responsible to the President and provides advice concerning Polri policy, institutional development, professional integrity, organizational culture, and performance. Kompolnas is not part of the operational chain of command of Polri.</li>
                </ul>

                <p class="p-modal"><strong>Coordination with Civil and Security Institutions</strong></p>
                <ul>
                    <li><strong>National Government Coordination:</strong> Coordinate with the President, ministries, national agencies, prosecutors, courts, and other state institutions concerning law enforcement, security operations, emergency management, and national policy implementation.</li>
                    <li><strong>TNI&ndash;Polri Coordination:</strong> Coordinate with the Indonesian National Armed Forces (Tentara Nasional Indonesia&mdash;TNI) where military assistance, joint security arrangements, border security, emergency support, or other legally authorized cooperation is required.</li>
                    <li><strong>National Emergency Coordination:</strong> Coordinate with Basarnas, BNPB, the Ministry of Health, Ministry of Transportation, regional governments, and other institutions during major disasters and national emergencies.</li>
                    <li><strong>Regional Coordination:</strong> Direct Polda to coordinate with governors, Kodam, prosecutors, courts, local governments, and other regional institutions through applicable regional coordination mechanisms.</li>
                </ul>
            </div>

            <div class="tab-pane fade" id="polri-hq-geographic" role="tabpanel" aria-labelledby="polri-hq-geographic-tab" tabindex="0">
                <p class="p-modal text-justify">
                    Polri exercises police functions throughout the entire territory of the Republic of Indonesia. National law establishes Polri as a single national police organization, while permitting the national territory to be divided into police jurisdictions according to the requirements of police operations.
                </p>
                <p class="p-modal text-justify">
                    National command is exercised from Mabes Polri, while territorial policing is implemented through Polda and their subordinate organizations.
                </p>
                <p class="p-modal"><strong>The general territorial command structure is:</strong></p>
                <div class="text-center my-3">
                    <strong>Polri / Mabes Polri</strong>
                    <div aria-hidden="true">&darr;</div>
                    <strong>Kepolisian Daerah (Polda)</strong>
                    <div aria-hidden="true">&darr;</div>
                    <strong>Polres / Polresta / Polrestabes / Polres Metro</strong>
                    <div aria-hidden="true">&darr;</div>
                    <strong>Polsek</strong>
                    <div aria-hidden="true">&darr;</div>
                    <strong>Polsubsektor / Pospol and local community-policing presence</strong>
                </div>
                <p class="p-modal text-justify">
                    As of 2026, Polri operates through 36 Polda throughout Indonesia. The territorial police system covers Indonesia&rsquo;s 38 provinces, although police jurisdictions do not always correspond exactly to provincial administrative boundaries. Several Polda continue to cover more than one province or cross provincial boundaries due to metropolitan, geographic, security, historical, or transitional administrative requirements. Official Polri activities in April 2026 continued to identify 36 Polda nationwide.
                </p>
                <p class="p-modal text-justify">
                    This structure allows Polri to function as one centrally governed national police organization while delegating territorial command and operational implementation to regional and local police units.
                </p>
            </div>

            <div class="tab-pane fade" id="polri-hq-equivalent" role="tabpanel" aria-labelledby="polri-hq-equivalent-tab" tabindex="0">
                <div class="info-modal-figure mb-3">
                    <img src="{{ asset('images/polricivilarmy.png') }}" alt="Civil, TNI, and Polri institutional equivalent at national level">
                </div>
                <p class="p-modal text-justify">
                    At the national level, the closest institutional comparison is:
                </p>
                <ul>
                    <li><strong>Polri / Markas Besar Kepolisian Negara Republik Indonesia (Mabes Polri):</strong> National police command responsible for law enforcement, public security and order, protection, and policing throughout Indonesia.</li>
                    <li><strong>Central Government / President of the Republic of Indonesia:</strong> National civil executive authority and head of government.</li>
                    <li><strong>TNI / Markas Besar Tentara Nasional Indonesia (Mabes TNI):</strong> National military command responsible for national defence and military operations.</li>
                </ul>
            </div>

        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="policeAreaLayerModal" tabindex="-1" aria-labelledby="policeAreaLayerLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered image-modal-dialog" style="--img-ratio:0.8;">
    <div class="modal-content">
      <div class="modal-header py-2">
        <div class="d-flex align-items-center gap-2">
            <img src="{{ asset('images/icon-structure.png') }}" style="width:18px; height:18px;">
            <h5 class="modal-title mb-0" id="policeAreaLayerLabel">Police Area Layer</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body image-modal-body">
            <img src="{{ asset('images/police-layer.png') }}" alt="Police Area Layer">
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="cmdFlowModal" tabindex="-1" aria-labelledby="cmdFlowLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered image-modal-dialog" style="--img-ratio:1.5;">
    <div class="modal-content">
      <div class="modal-header py-2">
        <div class="d-flex align-items-center gap-2">
            <img src="{{ asset('images/icon-flow.png') }}" style="width:18px; height:18px;">
            <h5 class="modal-title mb-0" id="cmdFlowLabel">Command Flow</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body image-modal-body">
            <img src="{{ asset('images/cmd-flow.png') }}" alt="Police Command Flow">
      </div>
    </div>
  </div>
</div>


    <div style="position:relative;">

    <div id="map"></div>

    <!-- Route Detail Panel -->
    <div id="routePanel" style="
        display:none;
        position:absolute;
        top:10px;
        left:10px;
        width:300px;
        max-height:calc(100% - 20px);
        background:#fff;
        border-radius:10px;
        box-shadow:0 4px 20px rgba(0,0,0,0.18);
        z-index:999;
        flex-direction:column;
        overflow:hidden;
        font-family:inherit;
    ">
        <!-- Header -->
        <div style="background:#1a73e8;padding:12px 14px;color:#fff;display:flex;justify-content:space-between;align-items:center;flex-shrink:0;">
            <div>
                <div style="font-size:11px;opacity:0.85;letter-spacing:0.5px;">DRIVING DIRECTIONS</div>
                <div id="routePanelTitle" style="font-size:13px;font-weight:600;margin-top:2px;">—</div>
            </div>
            <button onclick="closeRoutePanel()" style="background:rgba(255,255,255,0.2);border:none;color:#fff;width:26px;height:26px;border-radius:50%;cursor:pointer;font-size:15px;line-height:1;display:flex;align-items:center;justify-content:center;">&times;</button>
        </div>
        <!-- Summary -->
        <div id="routeSummary" style="padding:10px 14px;background:#f0f4ff;border-bottom:1px solid #dde8ff;display:flex;gap:16px;flex-shrink:0;">
            <div style="text-align:center;">
                <div style="font-size:18px;font-weight:700;color:#1a73e8;" id="routeDistance">—</div>
                <div style="font-size:10px;color:#666;text-transform:uppercase;letter-spacing:0.4px;">Distance</div>
            </div>
            <div style="text-align:center;">
                <div style="font-size:18px;font-weight:700;color:#395272;" id="routeDuration">—</div>
                <div style="font-size:10px;color:#666;text-transform:uppercase;letter-spacing:0.4px;">Est. Time</div>
            </div>
        </div>
        <!-- Steps -->
        <div id="routeSteps" style="overflow-y:auto;flex:1;padding:8px 0;"></div>
    </div>

    </div>
</div>

@endsection

@push('service')
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCd-WVlGgZFJwAtPZkbAEca2Np6OI7CBTM&libraries=places,geometry,drawing"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
// === Inisialisasi Peta ===
const map = new google.maps.Map(document.getElementById('map'), {
    center: { lat: -6.80188562253168, lng: 144.0733101155011 },
    zoom: 5,
    mapTypeId: 'roadmap',
    mapTypeControl: true,
    fullscreenControl: true,
    streetViewControl: false
});

const infoWindow = new google.maps.InfoWindow();

// === Directions (in-map routing) ===
const directionsService  = new google.maps.DirectionsService();
const directionsRenderer = new google.maps.DirectionsRenderer({
    suppressMarkers: false,
    polylineOptions: { strokeColor: '#1a73e8', strokeWeight: 5, strokeOpacity: 0.85 }
});
directionsRenderer.setMap(map);

// "Clear Route" button
const clearRouteBtn = document.createElement('div');
clearRouteBtn.id = 'clearRouteBtn';
clearRouteBtn.innerHTML = '✕ Clear Route';
Object.assign(clearRouteBtn.style, {
    display: 'none',
    background: '#fff',
    border: '2px solid rgba(0,0,0,0.2)',
    borderRadius: '6px',
    padding: '6px 12px',
    fontSize: '13px',
    fontWeight: '600',
    cursor: 'pointer',
    margin: '10px',
    color: '#d32f2f',
    boxShadow: '0 2px 6px rgba(0,0,0,0.15)'
});
clearRouteBtn.title = 'Clear the current route';
clearRouteBtn.addEventListener('click', () => {
    directionsRenderer.setDirections({ routes: [] });
    clearRouteBtn.style.display = 'none';
    closeRoutePanel();
});
map.controls[google.maps.ControlPosition.TOP_CENTER].push(clearRouteBtn);

// Helper: close route panel
function closeRoutePanel() {
    const panel = document.getElementById('routePanel');
    if (panel) panel.style.display = 'none';
    directionsRenderer.setDirections({ routes: [] });
    clearRouteBtn.style.display = 'none';
}

// Helper: draw route on map + show panel
function showRouteOnMap(originLat, originLng, destLat, destLng, destName) {
    directionsService.route({
        origin: new google.maps.LatLng(originLat, originLng),
        destination: new google.maps.LatLng(destLat, destLng),
        travelMode: google.maps.TravelMode.DRIVING
    }, (result, status) => {
        if (status === 'OK') {
            directionsRenderer.setDirections(result);
            clearRouteBtn.style.display = 'inline-block';
            infoWindow.close();

            const leg = result.routes[0].legs[0];
            const panel = document.getElementById('routePanel');
            document.getElementById('routePanelTitle').textContent = destName || 'Destination';
            document.getElementById('routeDistance').textContent  = leg.distance.text;
            document.getElementById('routeDuration').textContent  = leg.duration.text;

            const stepsEl = document.getElementById('routeSteps');
            stepsEl.innerHTML = leg.steps.map((step, i) => {
                const raw = (step.html_instructions || step.instructions || '');
                const instruction = raw.replace(/<[^>]*>/g, ' ').replace(/\s+/g, ' ').trim();
                if (!instruction) return '';
                const icons = {
                    'Turn left':        '↰',
                    'Turn right':       '↱',
                    'Keep left':        '↖',
                    'Keep right':       '↗',
                    'Continue':         '↑',
                    'Head':             '↑',
                    'Roundabout':       '↻',
                    'U-turn':           '⟳',
                    'Merge':            '↑',
                    'Ramp':             '↗',
                    'Destination':      '📍',
                };
                let icon = '•';
                for (const [key, val] of Object.entries(icons)) {
                    if (instruction.startsWith(key)) { icon = val; break; }
                }
                const isLast = i === leg.steps.length - 1;
                return `
                    <div style="display:flex;gap:10px;padding:8px 14px;
                                border-bottom:${isLast ? 'none' : '1px solid #f0f0f0'};
                                align-items:flex-start;">
                        <div style="min-width:22px;height:22px;background:${isLast ? '#395272' : '#e8f0fe'};
                                    border-radius:50%;display:flex;align-items:center;
                                    justify-content:center;font-size:12px;
                                    color:${isLast ? '#fff' : '#1a73e8'};flex-shrink:0;margin-top:1px;">
                            ${icon}
                        </div>
                        <div style="flex:1;">
                            <div style="font-size:12px;color:#222;line-height:1.4;">${instruction}</div>
                            <div style="font-size:11px;color:#888;margin-top:2px;">${step.distance.text}</div>
                        </div>
                    </div>`;
            }).join('');

            panel.style.display = 'flex';
        } else {
            if (status === 'ZERO_RESULTS') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Route Not Found',
                    text: 'No driving route could be found between your location and the destination. The two locations may not be connected by road.',
                    confirmButtonColor: '#1a73e8',
                    confirmButtonText: 'OK'
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Directions Error',
                    text: 'Could not get directions: ' + status,
                    confirmButtonColor: '#1a73e8',
                    confirmButtonText: 'OK'
                });
            }
        }
    });
}

// --- Nearby Category Bar (Google Maps style) — Hotels only ---
let categoryMarkers   = [];
let activeCategoryBtn = null;

const categoryBar = document.createElement('div');
categoryBar.id = 'nearbyCategBar';
Object.assign(categoryBar.style, {
    display:       'none',
    background:    'transparent',
    padding:       '8px 10px 0',
    gap:           '8px',
    flexWrap:      'nowrap',
    overflowX:     'auto',
    maxWidth:      '90vw',
    scrollbarWidth:'none'
});

const nearbyCategories = [
    { label: 'Hotels', icon: '🏨', type: 'lodging' }
];

nearbyCategories.forEach(cat => {
    const btn = document.createElement('button');
    btn.textContent = cat.icon + ' ' + cat.label;
    Object.assign(btn.style, {
        display:      'inline-flex',
        alignItems:   'center',
        gap:          '4px',
        padding:      '6px 14px',
        borderRadius: '20px',
        border:       '1px solid rgba(0,0,0,0.12)',
        background:   '#fff',
        color:        '#222',
        fontSize:     '13px',
        fontWeight:   '500',
        cursor:       'pointer',
        whiteSpace:   'nowrap',
        boxShadow:    '0 1px 4px rgba(0,0,0,0.15)',
        transition:   'all 0.15s'
    });

    btn.addEventListener('click', () => {
        if (activeCategoryBtn === btn) {
            clearCategoryMarkers();
            resetCategoryBtn(btn);
            activeCategoryBtn = null;
            return;
        }
        if (activeCategoryBtn) resetCategoryBtn(activeCategoryBtn);
        activeCategoryBtn = btn;
        btn.style.background = '#1a73e8';
        btn.style.color      = '#fff';
        btn.style.borderColor= '#1a73e8';
        showNearbyCategory(cat.type, cat.label);
    });

    categoryBar.appendChild(btn);
});

map.controls[google.maps.ControlPosition.TOP_CENTER].push(categoryBar);

function resetCategoryBtn(btn) {
    btn.style.background  = '#fff';
    btn.style.color       = '#222';
    btn.style.borderColor = 'rgba(0,0,0,0.12)';
}

function clearCategoryMarkers() {
    categoryMarkers.forEach(m => m.setMap(null));
    categoryMarkers = [];
}

function showNearbyCategory(type, label) {
    if (!lastClickedLocation) return;
    clearCategoryMarkers();

    const center  = new google.maps.LatLng(lastClickedLocation.lat, lastClickedLocation.lng);
    const service = new google.maps.places.PlacesService(map);

    const iconColors = { lodging: '#1a73e8' };
    const color = iconColors[type] || '#555';

    function makeSvgIcon(col) {
        const svg = `<svg xmlns='http://www.w3.org/2000/svg' width='32' height='40' viewBox='0 0 32 40'>`
                  + `<path d='M16 0C7.16 0 0 7.16 0 16c0 12 16 24 16 24S32 28 32 16C32 7.16 24.84 0 16 0z' fill='${col}'/>`
                  + `<circle cx='16' cy='16' r='7' fill='#fff'/>`
                  + `</svg>`;
        return 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(svg);
    }

    service.nearbySearch({ location: center, radius: 5000, type }, (results, status) => {
        if (status !== google.maps.places.PlacesServiceStatus.OK) {
            if (status === 'ZERO_RESULTS') {
                alert(`No ${label.toLowerCase()} found within 5 km.`);
            } else {
                alert(`Failed to load ${label.toLowerCase()}. Error status: ${status}. Please ensure "Places API" is enabled and billing is active.`);
                console.error('PlacesService nearbySearch failed with status:', status);
            }
            return;
        }
        if (!results.length) return;

        results.forEach(place => {
            if (!place.geometry?.location) return;

            const marker = new google.maps.Marker({
                position: place.geometry.location,
                map,
                title: place.name,
                icon: { url: makeSvgIcon(color), scaledSize: new google.maps.Size(32, 40) },
                animation: google.maps.Animation.DROP
            });

            const dist     = google.maps.geometry.spherical.computeDistanceBetween(center, place.geometry.location);
            const distText = dist >= 1000 ? (dist / 1000).toFixed(1) + ' km' : Math.round(dist) + ' m';
            const rating   = place.rating ? `⭐ ${place.rating.toFixed(1)}` : '';
            const destLat  = place.geometry.location.lat();
            const destLng  = place.geometry.location.lng();
            const safeName = (place.name || '').replace(/'/g, "\\'");

            marker.addListener('click', () => {
                infoWindow.setContent(`
                    <div style="font-size:13px;min-width:190px;">
                        <h5 style="border-bottom:1px solid #ccc;margin:0 0 6px;font-size:14px;">${place.name}</h5>
                        <div style="color:#666;font-size:12px;margin-bottom:3px;">${label}</div>
                        ${rating  ? `<div style="font-size:12px;">${rating}</div>` : ''}
                        <div style="margin-top:4px;font-size:12px;color:#555;"> ${distText} from search location</div>
                        <div style="margin-top:8px;">
                            <button onclick="showRouteOnMap(${center.lat()},${center.lng()},${destLat},${destLng},'${safeName}')"
                                    style="display:inline-flex;align-items:center;gap:5px;
                                           background:#1a73e8;color:#fff;border:none;
                                           padding:5px 12px;border-radius:6px;font-size:12px;
                                           font-weight:500;cursor:pointer;">
                                <svg xmlns='http://www.w3.org/2000/svg' width='13' height='13' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'>
                                    <polygon points='3 11 22 2 13 21 11 13 3 11'/>
                                </svg>
                                Get Directions
                            </button>
                        </div>
                    </div>`);
                infoWindow.open(map, marker);
            });

            categoryMarkers.push(marker);
        });
    });
}

// === Global Variable ===
let policeMarkers = [];
let radiusCircle = null;
let radiusPinMarker = null;
let lastClickedLocation = null;
let drawnPolygonGeoJSON = null;

// === Polygon Draw (Custom Point-by-Point) ===
let isDrawingPolygon = false;
let polygonLatLngs = [];
let activePolygon = null;
let activePolyline = null;
let cursorPolyline = null;
let startMarker = null;

const drawButton = document.createElement('div');
drawButton.innerHTML = '⬟';
Object.assign(drawButton.style, {
    backgroundColor: 'white', border: '2px solid rgba(0,0,0,0.2)', borderRadius: '4px',
    width: '34px', height: '34px', textAlign: 'center', lineHeight: '30px',
    fontSize: '18px', cursor: 'pointer', margin: '10px'
});
drawButton.title = 'Draw Polygon (Click point by point, click starting point to finish)';
map.controls[google.maps.ControlPosition.LEFT_TOP].push(drawButton);

const clearButton = document.createElement('div');
clearButton.innerHTML = '🗑️';
Object.assign(clearButton.style, {
    backgroundColor: 'white', border: '2px solid rgba(0,0,0,0.2)', borderRadius: '4px',
    width: '34px', height: '34px', textAlign: 'center', lineHeight: '30px',
    fontSize: '16px', cursor: 'pointer', margin: '10px 0'
});
clearButton.title = 'Clear Polygon';
map.controls[google.maps.ControlPosition.LEFT_TOP].push(clearButton);

drawButton.addEventListener('click', () => {
    isDrawingPolygon = !isDrawingPolygon;
    if (isDrawingPolygon) {
        map.setOptions({ draggable: false });
        drawButton.style.backgroundColor = '#ccc';
        map.getDiv().style.cursor = 'crosshair';
        polygonLatLngs = [];
        if (activePolygon) activePolygon.setMap(null);
        if (activePolyline) activePolyline.setMap(null);
        if (cursorPolyline) cursorPolyline.setMap(null);
        if (startMarker) startMarker.setMap(null);
        activePolygon = null;
        activePolyline = new google.maps.Polyline({
            path: polygonLatLngs, strokeColor: '#007bff', strokeOpacity: 0.8, strokeWeight: 3, clickable: false, map
        });
        cursorPolyline = new google.maps.Polyline({
            path: [], strokeColor: '#007bff', strokeOpacity: 0.5, strokeWeight: 3, clickable: false, map
        });
        startMarker = null;
        drawnPolygonGeoJSON = null;
    } else {
        finishPolygon();
    }
});

map.addListener('mousemove', (e) => {
    if (!isDrawingPolygon || polygonLatLngs.length === 0) return;
    const lastPoint = polygonLatLngs[polygonLatLngs.length - 1];
    cursorPolyline.setPath([lastPoint, e.latLng]);
});

map.addListener('rightclick', () => {
    if (isDrawingPolygon) finishPolygon();
});

async function finishPolygon() {
    if (!isDrawingPolygon) return;
    isDrawingPolygon = false;
    map.setOptions({ draggable: true });
    drawButton.style.backgroundColor = 'white';
    map.getDiv().style.cursor = '';
    if (cursorPolyline) cursorPolyline.setMap(null);
    if (startMarker) startMarker.setMap(null);

    if (polygonLatLngs.length > 2) {
        if (activePolyline) activePolyline.setMap(null);
        activePolygon = new google.maps.Polygon({
            paths: polygonLatLngs, strokeColor: '#007bff', strokeOpacity: 0.8, strokeWeight: 3,
            fillColor: '#007bff', fillOpacity: 0.2, editable: true, map
        });

        const coordinates = polygonLatLngs.map(p => [p.lng(), p.lat()]);
        coordinates.push([polygonLatLngs[0].lng(), polygonLatLngs[0].lat()]);

        drawnPolygonGeoJSON = {
            type: "Feature",
            geometry: { type: "Polygon", coordinates: [coordinates] },
            properties: {}
        };

        const updatePolygonFilter = async () => {
            if (!activePolygon) return;
            const path = activePolygon.getPath();
            if (path.getLength() > 2) {
                const newCoords = [];
                for (let i = 0; i < path.getLength(); i++) {
                    const xy = path.getAt(i);
                    newCoords.push([xy.lng(), xy.lat()]);
                }
                newCoords.push([path.getAt(0).lng(), path.getAt(0).lat()]);
                drawnPolygonGeoJSON.geometry.coordinates = [newCoords];
                await applyPoliceFilters();
            }
        };

        google.maps.event.addListener(activePolygon.getPath(), 'set_at', updatePolygonFilter);
        google.maps.event.addListener(activePolygon.getPath(), 'insert_at', updatePolygonFilter);
        google.maps.event.addListener(activePolygon.getPath(), 'remove_at', updatePolygonFilter);

        await applyPoliceFilters();
    } else {
        if (activePolyline) activePolyline.setMap(null);
        activePolyline = null;
        activePolygon = null;
        drawnPolygonGeoJSON = null;
    }
}

clearButton.addEventListener('click', async () => {
    if (activePolygon) activePolygon.setMap(null);
    if (activePolyline) activePolyline.setMap(null);
    if (cursorPolyline) cursorPolyline.setMap(null);
    if (startMarker) startMarker.setMap(null);
    activePolygon = null;
    activePolyline = null;
    cursorPolyline = null;
    startMarker = null;
    polygonLatLngs = [];
    drawnPolygonGeoJSON = null;
    isDrawingPolygon = false;
    map.setOptions({ draggable: true });
    drawButton.style.backgroundColor = 'white';
    map.getDiv().style.cursor = '';
    await applyPoliceFilters();
});

// === Radius Circle & Location Pin ===
function updateRadiusCircleAndPin(radius = 0) {
    if (radiusCircle) { radiusCircle.setMap(null); radiusCircle = null; }

    if (radius > 0 && lastClickedLocation) {
        radiusCircle = new google.maps.Circle({
            strokeColor: '#1565c0', strokeOpacity: 0.8, strokeWeight: 2,
            fillColor: '#1565c0', fillOpacity: 0.2,
            map, center: lastClickedLocation, radius: radius * 1000
        });
    }
}

function placeLocationPin(location, label) {
    if (radiusPinMarker) { radiusPinMarker.setMap(null); radiusPinMarker = null; }
    radiusPinMarker = new google.maps.Marker({
        position: location,
        map,
        title: label || 'Selected Location',
        icon: {
            url: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
            scaledSize: new google.maps.Size(25, 41)
        },
        zIndex: 9999,
        animation: google.maps.Animation.DROP
    });
}

map.addListener('click', e => {
    if (isDrawingPolygon) {
        polygonLatLngs.push(e.latLng);
        activePolyline.setPath(polygonLatLngs);

        if (polygonLatLngs.length === 1) {
            startMarker = new google.maps.Marker({
                position: e.latLng,
                map,
                icon: {
                    path: google.maps.SymbolPath.CIRCLE, scale: 6,
                    fillColor: '#FFFFFF', fillOpacity: 1, strokeColor: '#007bff', strokeWeight: 2
                },
                zIndex: 999
            });
            startMarker.addListener('click', () => {
                if (isDrawingPolygon) finishPolygon();
            });
        }
        return;
    }

    lastClickedLocation = { lat: e.latLng.lat(), lng: e.latLng.lng() };
    placeLocationPin(lastClickedLocation, 'Selected Location');
    const radius = parseInt(document.querySelector('#radiusRangeMap')?.value || 0);
    const radiusValEl = document.querySelector('#radiusValueMap');
    if (radiusValEl) radiusValEl.textContent = radius;
    updateRadiusCircleAndPin(radius);
    categoryBar.style.display = 'flex';
    applyPoliceFilters();
});

// === Fetch Data POLICE ===
async function fetchPoliceData(filters = {}) {
    const params = new URLSearchParams();

    Object.entries(filters).forEach(([k, v]) => {
        if (Array.isArray(v)) v.forEach(x => params.append(`${k}[]`, x));
        else if (v !== '' && v != null) params.append(k, v);
    });

    if (drawnPolygonGeoJSON) {
        params.append('polygon', JSON.stringify(drawnPolygonGeoJSON));
    }

    try {
        const res = await fetch(`/api/polices?${params.toString()}`);
        return res.ok ? await res.json() : [];
    } catch (e) {
        console.error('Error fetching police:', e);
        return [];
    }
}

// === Marker POLICE ===
function addPoliceMarkers(data) {
    policeMarkers.forEach(m => m.setMap(null));
    policeMarkers = [];

    const bounds = new google.maps.LatLngBounds();

    data.forEach(police => {
        if (!police.latitude || !police.longitude) return;

        const position = { lat: parseFloat(police.latitude), lng: parseFloat(police.longitude) };

        const marker = new google.maps.Marker({
            position,
            map,
            icon: {
                url: police.icon || 'https://png.pngtree.com/png-vector/20221211/ourmid/pngtree-minimal-location-map-icon-logo-symbol-vector-design-transparent-background-png-image_6520892.png',
                scaledSize: new google.maps.Size(12, 12)
            }
        });

        const itemName  = police.name_police || 'N/A';
        const detailUrl = `/police/${police.id}/detail`;

        const popupContent = `
            <h5 style="border-bottom:1px solid #cccccc;"><a href="${detailUrl}" style="color:inherit;text-decoration:none;transition:color 0.2s;" onmouseover="this.style.color='#1a73e8'" onmouseout="this.style.color='inherit'">${itemName}</a></h5>
            <strong>Category:</strong> ${police.category || 'N/A'}<br>
            <strong>Address:</strong>
                ${police.location || 'N/A'}
                ${police.city ? ', ' + police.city : ''}
                ${police.provinces_region ? ', ' + police.provinces_region : ''}, Indonesia <br>
            <strong>Phone:</strong> ${police.telephone || 'N/A'}<br>
            <strong>Fax:</strong> ${police.fax || 'N/A'}<br>
            <strong>Email:</strong> ${police.email || 'N/A'}<br>
            <strong>Website:</strong> ${police.website || 'N/A'}<br>
        `;

        marker.addListener('click', () => {
            const destLat = parseFloat(police.latitude);
            const destLng = parseFloat(police.longitude);

            let directionsBtn = '';
            if (lastClickedLocation && !isNaN(destLat) && !isNaN(destLng)) {
                const oLat = lastClickedLocation.lat;
                const oLng = lastClickedLocation.lng;
                directionsBtn = `
                    <div style="margin-top:8px;padding-top:8px;border-top:1px solid #eee;display:flex;gap:6px;flex-wrap:wrap;">
                        <button onclick="showRouteOnMap(${oLat},${oLng},${destLat},${destLng},'${(itemName||'').replace(/'/g,"\\'")}')"
                           style="display:inline-flex;align-items:center;gap:5px;
                                  background:#1a73e8;color:#fff;border:none;
                                  padding:5px 12px;border-radius:6px;font-size:12px;
                                  font-weight:500;cursor:pointer;">
                            <svg xmlns='http://www.w3.org/2000/svg' width='13' height='13' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'>
                                <polygon points='3 11 22 2 13 21 11 13 3 11'/>
                            </svg>
                            Get Directions
                        </button>
                        <a href="${detailUrl}"
                           style="display:inline-flex;align-items:center;gap:5px;
                                  background:#395272;color:#fff;text-decoration:none;
                                  padding:5px 12px;border-radius:6px;font-size:12px;
                                  font-weight:500;"
                           onmouseover="this.style.background='#5686c3'"
                           onmouseout="this.style.background='#395272'">
                            <svg xmlns='http://www.w3.org/2000/svg' width='13' height='13' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'>
                                <circle cx='12' cy='12' r='10'/><line x1='12' y1='8' x2='12' y2='12'/><line x1='12' y1='16' x2='12.01' y2='16'/>
                            </svg>
                            Read More
                        </a>
                    </div>`;
            } else {
                directionsBtn = `
                    <div style="margin-top:8px;padding-top:8px;border-top:1px solid #eee;">
                        <a href="${detailUrl}"
                           style="display:inline-flex;align-items:center;gap:5px;
                                  background:#395272;color:#fff;text-decoration:none;
                                  padding:5px 12px;border-radius:6px;font-size:12px;
                                  font-weight:500;"
                           onmouseover="this.style.background='#5686c3'"
                           onmouseout="this.style.background='#395272'">
                            <svg xmlns='http://www.w3.org/2000/svg' width='13' height='13' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'>
                                <circle cx='12' cy='12' r='10'/><line x1='12' y1='8' x2='12' y2='12'/><line x1='12' y1='16' x2='12.01' y2='16'/>
                            </svg>
                            Read More
                        </a>
                    </div>`;
            }

            infoWindow.setContent(`<div style="font-size:13px; min-width: 200px;">${popupContent}${directionsBtn}</div>`);
            infoWindow.open(map, marker);
        });

        policeMarkers.push(marker);
        bounds.extend(position);
    });

    if (policeMarkers.length > 0)
        map.fitBounds(bounds, 50);
}

// === Apply Filter POLICE ===
async function applyPoliceFilters() {
    const provs = [...document.querySelectorAll('.province-checkbox:checked')].map(e => e.value);
    const categories = [...document.querySelectorAll('input[name="policeCategory"]:checked')].map(e => e.value);
    const policeName = $('#police_name_map').val() || '';
    const radius = parseInt(document.getElementById('radiusRangeMap')?.value || 0);

    let filters = {};

    if (policeName) filters.name = policeName;
    if (provs.length > 0) filters.provinces = provs;
    if (categories.length > 0) filters.categories = categories;

    if (radius > 0 && lastClickedLocation) {
        filters.radius = radius;
        filters.center_lat = lastClickedLocation.lat;
        filters.center_lng = lastClickedLocation.lng;
    }

    const result = await fetchPoliceData(filters);

    const polices = result.polices;
    const categoryCounts = result.categoryCounts;

    addPoliceMarkers(polices);

    document.getElementById('totalCountDisplay').innerHTML =
        `<strong>Police:</strong> ${polices.length}`;

    Object.keys(categoryCounts).forEach(cat => {

        const id = cat.replace(/[^a-zA-Z0-9]/g,'-');

        const el = document.getElementById(`count-${id}`);

        if (el) {
            el.textContent = categoryCounts[cat];
        }
    });
}

// === Filter Panel (Custom Google Maps Control) ===
const combinedPanelDiv = document.createElement('div');
combinedPanelDiv.id = 'combinedPanelDiv';
Object.assign(combinedPanelDiv.style, {
    background: 'white',
    borderRadius: '8px',
    boxShadow: '0 2px 6px rgba(0,0,0,0.2)',
    minWidth: '260px',
    maxWidth: '290px',
    overflow: 'visible',
    margin: '10px'
});

combinedPanelDiv.innerHTML = `
    <button style="background:#007bff;color:white;border:none;width:100%;padding:8px;border-radius:8px 8px 0 0;font-weight:600;letter-spacing:0.3px;">Filter &amp; Radius</button>

    <!-- Search Location - NOT inside scrollable div so dropdown is never clipped -->
    <div id="searchSection" style="padding:10px 10px 6px 10px;background:white;position:relative;">
        <strong style="font-size:12px;text-transform:uppercase;letter-spacing:0.5px;color:#555;">Search Location</strong>
        <div style="position:relative;margin-top:5px;">
            <input
                type="text"
                id="locationSearchMap"
                placeholder="Search Location..."
                autocomplete="off"
                style="width:100%;padding:7px 30px 7px 9px;border:1.5px solid #ddd;border-radius:6px;font-size:13px;box-sizing:border-box;"
            >
            <span id="locationSearchClear" title="Clear"
                style="position:absolute;right:8px;top:50%;transform:translateY(-50%);cursor:pointer;font-size:15px;color:#aaa;display:none;">&times;</span>
        </div>
        <div id="locationFoundBadge" style="display:none;margin-top:6px;background:#e8f5e9;border:1px solid #a5d6a7;border-radius:5px;padding:4px 8px;font-size:12px;color:#2e7d32;">
            &#128204; <span id="locationFoundName"></span>
        </div>
    </div>

    <!-- Radius -->
    <div id="radiusSection" style="padding:0 10px 0 10px;">
        <hr style="margin:8px 0;">
        <strong style="font-size:12px;text-transform:uppercase;letter-spacing:0.5px;color:#555;">Radius: <span id="radiusValueMap">0</span> km</strong>
        <input type="range" id="radiusRangeMap" min="0" max="500" value="0" style="width:100%;margin:4px 0;">
        <div style="display:flex;justify-content:space-between;font-size:11px;color:#888;margin-bottom:5px;">
            <span>0</span><span>250 km</span><span>500 km</span>
        </div>
        <div style="display:flex;gap:5px;margin-bottom:6px;">
            <button id="applyRadiusMap" class="btn btn-sm btn-primary flex-fill">Apply</button>
            <button id="resetRadiusMap" class="btn btn-sm btn-danger flex-fill">Reset</button>
        </div>
    </div>

    <!-- Scrollable filters -->
    <div id="filterPanel" style="padding:0 10px 10px 10px;max-height:52vh;overflow-y:auto;border-top:1px solid #eee;">
        <div style="padding-top:8px;">
            <label>Police Name:</label>
            <select id="police_name_map" class="form-select form-select-sm mb-2 select-search-police">
                <option value="">Select Police</option>
                @foreach($policeNames as $n)
                    <option value="{{ $n }}">{{ $n }}</option>
                @endforeach
            </select>
            <label>Category:</label>
            ${[
                'Indonesian National Police (Polri) HQ',
                'Provincial Police (Polda)',
                'Municipality Police (Polres)',
                'District Police (Polsek)',
                'Police Mobile Brigade (Brimob)',
                'Police Bomb Squad (Gegana)'
            ].map(c => `
            <label style="display:block;font-size:13px;margin-bottom:5px;">
                <input type="checkbox" name="policeCategory" value="${c}">
                ${c} (<span id="count-${c.replace(/[^a-zA-Z0-9]/g,'-')}">0</span>)
            </label>
            `).join('')}
            <hr>
            <div class="filter-box" id="provinceSelect">
                <label class="filter-label">Province</label>

                <div class="select-input">
                    <input
                        type="text"
                        id="provinceSearch"
                        placeholder="Select Province"
                        readonly
                    >
                    <i class="bi bi-chevron-down"></i>
                </div>

                <div class="select-dropdown">
                    <input
                        type="text"
                        class="dropdown-search"
                        id="provinceSearchInput"
                        placeholder="Search Province..."
                    >

                    <ul id="provinceList">
                        @foreach ($provinces as $p)
                        <li>
                            <label>
                                <input
                                    type="checkbox"
                                    class="province-checkbox"
                                    value="{{ $p->id }}"
                                >
                                {{ $p->provinces_region }}
                            </label>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <hr>
            <button id="resetMapFilter" class="btn btn-sm btn-secondary w-100">Reset All</button>
            <div id="totalCountDisplay" style="margin-top:8px;text-align:center;font-size:13px;"></div>
        </div>
    </div>`;

google.maps.event.addDomListener(combinedPanelDiv, 'click', e => e.stopPropagation());
google.maps.event.addDomListener(combinedPanelDiv, 'dblclick', e => e.stopPropagation());
google.maps.event.addDomListener(combinedPanelDiv, 'mousedown', e => e.stopPropagation());
google.maps.event.addDomListener(combinedPanelDiv, 'touchstart', e => e.stopPropagation());
google.maps.event.addDomListener(combinedPanelDiv, 'wheel', e => e.stopPropagation());
map.controls[google.maps.ControlPosition.RIGHT_TOP].push(combinedPanelDiv);

// === Init Select2 (retry sampai panel benar-benar ada di DOM) ===
function initPoliceSelect2() {
    const el = document.getElementById('police_name_map');
    if (typeof $ === 'undefined' || !$.fn || !$.fn.select2 || !el) {
        setTimeout(initPoliceSelect2, 200);
        return;
    }
    if ($(el).hasClass('select2-hidden-accessible')) return;
    $(el).select2({
        width: '100%',
        placeholder: 'Search Police',
        allowClear: true
    });
}
initPoliceSelect2();

// Event select2 (delegated, jadi tidak tergantung timing DOM)
$(document).on('change', '#police_name_map', function() {
    applyPoliceFilters();
});

// === Init Location Search — Google Places Autocomplete ===
// .pac-container is repositioned to position:fixed via MutationObserver
// to bypass Google Maps container overflow:hidden clipping.
function initLocationSearch() {
    const input = document.getElementById('locationSearchMap');
    if (!input) {
        setTimeout(initLocationSearch, 300);
        return;
    }

    const clearBtn = document.getElementById('locationSearchClear');

    const autocomplete = new google.maps.places.Autocomplete(input, {
        types: ['geocode', 'establishment'],
        fields: ['geometry', 'name', 'formatted_address']
    });

    let pacContainer = null;

    function fixPacPosition() {
        if (!pacContainer) return;
        const rect = input.getBoundingClientRect();
        pacContainer.style.position   = 'fixed';
        pacContainer.style.zIndex     = '2147483647';
        pacContainer.style.top        = (rect.bottom + 2) + 'px';
        pacContainer.style.left       = rect.left + 'px';
        pacContainer.style.width      = rect.width + 'px';
        pacContainer.style.borderRadius = '0 0 8px 8px';
        pacContainer.style.boxShadow  = '0 8px 24px rgba(0,0,0,0.2)';
        pacContainer.style.fontFamily = 'inherit';
    }

    const observer = new MutationObserver(() => {
        if (!pacContainer) {
            pacContainer = document.querySelector('.pac-container');
            if (pacContainer) {
                fixPacPosition();
                new MutationObserver(fixPacPosition).observe(
                    pacContainer, { attributes: true, attributeFilter: ['style'] }
                );
            }
        }
    });
    observer.observe(document.body, { childList: true, subtree: false });

    window.addEventListener('scroll', fixPacPosition, true);
    window.addEventListener('resize', fixPacPosition);
    input.addEventListener('focus',  fixPacPosition);
    input.addEventListener('input',  fixPacPosition);

    google.maps.event.addDomListener(input, 'keydown',   e => e.stopPropagation());
    google.maps.event.addDomListener(input, 'mousedown', e => e.stopPropagation());

    input.addEventListener('focus', () => {
        input.style.borderColor = '#1a73e8';
        input.style.boxShadow   = '0 0 0 3px rgba(26,115,232,0.15)';
    });
    input.addEventListener('blur', () => {
        input.style.borderColor = '#ddd';
        input.style.boxShadow   = 'none';
    });

    input.addEventListener('input', () => {
        if (clearBtn) clearBtn.style.display = input.value.length ? 'inline' : 'none';
    });

    autocomplete.addListener('place_changed', () => {
        const place = autocomplete.getPlace();
        if (!place.geometry || !place.geometry.location) return;

        const loc = {
            lat: place.geometry.location.lat(),
            lng: place.geometry.location.lng()
        };
        lastClickedLocation = loc;

        map.panTo(loc);
        map.setZoom(10);

        const label = place.name || place.formatted_address || 'Location';
        placeLocationPin(loc, label);

        if (clearBtn) clearBtn.style.display = 'inline';

        const badge     = document.getElementById('locationFoundBadge');
        const badgeName = document.getElementById('locationFoundName');
        if (badge)     badge.style.display = 'block';
        if (badgeName) badgeName.textContent = label;

        const radius = parseInt(document.getElementById('radiusRangeMap')?.value || 0);
        updateRadiusCircleAndPin(radius);
        categoryBar.style.display = 'flex';
        applyPoliceFilters();
    });

    if (clearBtn) {
        clearBtn.addEventListener('click', () => {
            input.value = '';
            clearBtn.style.display = 'none';
            if (pacContainer) pacContainer.style.display = 'none';

            const badge = document.getElementById('locationFoundBadge');
            if (badge) badge.style.display = 'none';

            if (radiusPinMarker) { radiusPinMarker.setMap(null); radiusPinMarker = null; }
            if (radiusCircle)    { radiusCircle.setMap(null);    radiusCircle    = null; }
            lastClickedLocation = null;

            categoryBar.style.display = 'none';
            clearCategoryMarkers();
            if (activeCategoryBtn) { resetCategoryBtn(activeCategoryBtn); activeCategoryBtn = null; }

            const rEl    = document.getElementById('radiusRangeMap');
            const rValEl = document.getElementById('radiusValueMap');
            if (rEl)    rEl.value          = 0;
            if (rValEl) rValEl.textContent = '0';

            applyPoliceFilters();
            input.focus();
        });
    }
}

// === Events ===
document.addEventListener('input', e => {
    if (e.target.id === 'radiusRangeMap') {
        const r = parseInt(e.target.value || 0);
        document.getElementById('radiusValueMap').textContent = r;
        updateRadiusCircleAndPin(r);
    }
});

document.addEventListener('click', async e => {
    if (e.target.id === 'applyRadiusMap') {
        const radius = parseInt(document.getElementById('radiusRangeMap').value || 0);
        if (radius > 0 && !lastClickedLocation) {
            alert('Cari lokasi terlebih dahulu menggunakan kolom "Search Location", atau klik langsung pada peta untuk menentukan titik radius.');
            return;
        }
        await applyPoliceFilters();
    }

    if (e.target.id === 'resetRadiusMap') {
        document.getElementById('radiusRangeMap').value = 0;
        document.getElementById('radiusValueMap').textContent = '0';
        if (radiusCircle) { radiusCircle.setMap(null); radiusCircle = null; }
        if (radiusPinMarker) { radiusPinMarker.setMap(null); radiusPinMarker = null; }
        lastClickedLocation = null;

        const locInput = document.getElementById('locationSearchMap');
        const locClear = document.getElementById('locationSearchClear');
        const locBadge = document.getElementById('locationFoundBadge');
        if (locInput) locInput.value = '';
        if (locClear) locClear.style.display = 'none';
        if (locBadge) locBadge.style.display = 'none';

        categoryBar.style.display = 'none';
        clearCategoryMarkers();
        if (activeCategoryBtn) { resetCategoryBtn(activeCategoryBtn); activeCategoryBtn = null; }

        await applyPoliceFilters();
    }

    if (e.target.id === 'resetMapFilter') {
        document.querySelectorAll('#filterPanel input[type="checkbox"]').forEach(cb => cb.checked = false);
        if (typeof $ !== 'undefined' && $.fn && $.fn.select2) {
            $('.select-search-police').val(null).trigger('change');
        } else {
            document.getElementById('police_name_map').value = '';
        }

        const provinceSearch = document.getElementById('provinceSearch');
        if (provinceSearch) {
            provinceSearch.value = '';
            provinceSearch.placeholder = 'Select Province';
        }
        const provinceSearchInput = document.getElementById('provinceSearchInput');
        if (provinceSearchInput) provinceSearchInput.value = '';
        document.querySelectorAll('#provinceList li').forEach(li => { li.style.display = ''; });
        const provinceDropdown = document.querySelector('#provinceSelect .select-dropdown');
        if (provinceDropdown) provinceDropdown.classList.remove('show');

        document.getElementById('radiusRangeMap').value = 0;
        document.getElementById('radiusValueMap').textContent = '0';
        if (radiusCircle) { radiusCircle.setMap(null); radiusCircle = null; }
        if (radiusPinMarker) { radiusPinMarker.setMap(null); radiusPinMarker = null; }
        lastClickedLocation = null;

        const locInput = document.getElementById('locationSearchMap');
        const locClear = document.getElementById('locationSearchClear');
        const locBadge = document.getElementById('locationFoundBadge');
        if (locInput) locInput.value = '';
        if (locClear) locClear.style.display = 'none';
        if (locBadge) locBadge.style.display = 'none';

        categoryBar.style.display = 'none';
        clearCategoryMarkers();
        if (activeCategoryBtn) { resetCategoryBtn(activeCategoryBtn); activeCategoryBtn = null; }

        if (activePolygon) activePolygon.setMap(null);
        if (activePolyline) activePolyline.setMap(null);
        if (cursorPolyline) cursorPolyline.setMap(null);
        if (startMarker) startMarker.setMap(null);
        activePolygon = null;
        activePolyline = null;
        cursorPolyline = null;
        startMarker = null;
        polygonLatLngs = [];
        drawnPolygonGeoJSON = null;

        await applyPoliceFilters();
    }
}, true);

// === Checkbox & select change auto apply ===
document.addEventListener('change', e => {
    if (e.target.classList.contains('province-checkbox') || e.target.name === 'policeCategory') {
        applyPoliceFilters();
    }
});

// === Province: Select - Search Checkbox ===
document.addEventListener('click', (e) => {
    const provinceSelectInput = e.target.closest('#provinceSelect .select-input');
    const provinceDropdown = document.querySelector('#provinceSelect .select-dropdown');

    if (provinceSelectInput) {
        if (provinceDropdown) provinceDropdown.classList.toggle('show');
    } else {
        const provinceSelect = document.getElementById('provinceSelect');
        if (provinceSelect && !provinceSelect.contains(e.target) && provinceDropdown) {
            provinceDropdown.classList.remove('show');
        }
    }
}, true);

document.addEventListener('keyup', (e) => {
    if (e.target.id === 'provinceSearchInput') {
        const keyword = e.target.value.toLowerCase();
        document.querySelectorAll('#provinceList li').forEach(li => {
            const text = li.textContent.toLowerCase();
            li.style.display = text.includes(keyword) ? '' : 'none';
        });
    }
});

document.addEventListener('change', function(e) {
    if (e.target.classList.contains('province-checkbox')) {
        const selected = [...document.querySelectorAll('.province-checkbox:checked')]
            .map(cb => cb.parentElement.textContent.trim());
        const provinceSearch = document.getElementById('provinceSearch');
        if (provinceSearch) {
            if (selected.length === 0) {
                provinceSearch.value = '';
                provinceSearch.placeholder = 'Select Province';
            } else if (selected.length <= 2) {
                provinceSearch.value = selected.join(', ');
            } else {
                provinceSearch.value = selected.length + ' Province Selected';
            }
        }
    }
});

// === Init ===
setTimeout(() => {
    initLocationSearch();
}, 350);

// Retry sampai badge kategori (di dalam combinedPanelDiv) benar-benar ada di DOM,
// supaya jumlah per kategori tidak "nyangkut" di 0 saat load pertama.
function initialApplyFilters() {
    if (!document.querySelector('#filterPanel [id^="count-"]')) {
        setTimeout(initialApplyFilters, 200);
        return;
    }
    applyPoliceFilters();
}
initialApplyFilters();
</script>

@endpush
