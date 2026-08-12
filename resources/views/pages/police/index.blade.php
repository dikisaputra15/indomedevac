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

                <button class="btn p-1" data-bs-toggle="modal" data-bs-target="#level6Modal">
                    <img src="{{ asset('images/Layer1.png') }}" style="width:15px; height:15px;">
                    <small>Polri HQ (National)</small>
                </button>

                <button class="btn p-1" data-bs-toggle="modal" data-bs-target="#level5Modal">
                    <img src="{{ asset('images/Layer2.png') }}" style="width:15px; height:15px;">
                    <small>Polda</small>
                </button>

                <button class="btn p-1" data-bs-toggle="modal" data-bs-target="#level4Modal">
                    <img src="{{ asset('images/Layer3.png') }}" style="width:15px; height:15px;">
                    <small>Polres</small>
                </button>

                <button class="btn p-1" data-bs-toggle="modal" data-bs-target="#level3Modal">
                    <img src="{{ asset('images/Layer4.png') }}" style="width:15px; height:15px;">
                    <small>Polsek</small>
                </button>

                <button class="btn p-1" data-bs-toggle="modal" data-bs-target="#level2Modal">
                    <img src="{{ asset('images/Brimob.png') }}" style="width:15px; height:15px;">
                    <small>Brimob</small>
                </button>

                <button class="btn p-1" data-bs-toggle="modal" data-bs-target="#level1Modal">
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

<div class="modal fade" id="level1Modal" tabindex="-1" aria-labelledby="disclaimerLabel" aria-hidden="true">
 <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width:800px;">
    <div class="modal-content">
      <div class="modal-header">
        <div class="d-flex align-items-center">
             <img src="{{ asset('images/Gegana.png') }}" style="width:15px; height:15px;">
            <h5 class="modal-title" id="disclaimerLabel">Bomb Squad / Special Police Force — Pasukan Gegana</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
            <p class="p-modal text-justify">
                Gegana is a specialized force under Korbrimob Polri responsible for bomb disposal. Gegana can be described as Polri’s specialist Brimob unit for bomb disposal and high-risk special police operations. Bomb disposal is only one of its core capabilities, Gegana has a wider special police role covering counter-terrorism, hostage rescue, armed high-risk incidents, bomb disposal, tactical technical support, and response to chemical, biological, radiological, and nuclear threats.
            </p>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="level2Modal" tabindex="-1" aria-labelledby="disclaimerLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width:800px;">
    <div class="modal-content">
      <div class="modal-header">
        <div class="d-flex align-items-center">
             <img src="{{ asset('images/Brimob.png') }}" style="width:15px; height:15px;">
            <h5 class="modal-title" id="disclaimerLabel">Mobile Brigade Corps — Korps Brigade Mobil / Brimob</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
            <p class="p-modal text-justify">
                The Mobile Brigade Corps, or Brimob, is Polri’s national paramilitary-style tactical police force for high-intensity internal security operations. It is deployed when regular territorial police units require heavier tactical capability, including riot control, armed criminal threats, counter-insurgency support, counter-terrorism support, disaster response, and other major security disturbances.
            </p>
            <p class="p-modal text-justify">
               Brimob is part of the Indonesian National Police, not the Indonesian Armed Forces. It remains a police force, but it is trained, equipped, and organized for high-risk operations that require rapid deployment, disciplined formations, tactical weapons capability, and specialist field support.
            </p>
             <p class="p-modal text-justify">
               Brimob has its own command structure separate from territorial police layered commands. At the national level, Brimob is organized under Korbrimob Polri. At the regional level, Brimob capability is represented by Satuan brimob Polda.
            </p>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="level3Modal" tabindex="-1" aria-labelledby="level3Label" aria-hidden="true">
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

<div class="modal fade" id="level4Modal" tabindex="-1" aria-labelledby="level4Label" aria-hidden="true">
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

<div class="modal fade" id="level5Modal" tabindex="-1" aria-labelledby="level5Label" aria-hidden="true">
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

<div class="modal fade" id="level6Modal" tabindex="-1" aria-labelledby="disclaimerLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width:800px;">
    <div class="modal-content">
      <div class="modal-header">
        <div class="d-flex align-items-center">
            <img src="{{ asset('images/Layer1.png') }}" style="width:15px; height:15px;">
            <h5 class="modal-title" id="disclaimerLabel">Polri HQ (National)</h5>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
            <p class="p-modal text-justify">
                <strong>Command level:</strong> National headquarters
            </p>
            <p class="p-modal text-justify">
                <strong>Location:</strong> Jakarta
            </p>
            <p class="p-modal text-justify">
                <strong>Head rank:</strong> Police General (Jenderal Polisi) at Kapolri level
            </p>
            <p class="p-modal text-justify">
                Subordinate senior leadership: Komisaris Jenderal, Inspektur Jenderal, Brigadir Jenderal, and senior commissioner-level officers
            </p>
            <p class="p-modal text-justify">
                Mabes Polri is the national command, planning, administrative, operational, and coordination center of Polri. It supports Kapolri and Wakapolri in controlling the full police institution, from national-level operational corps to Polda, Polres, and Polsek.
            </p>
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
