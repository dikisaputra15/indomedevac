@extends('layouts.master')

@section('title','More Details')
@section('page-title', 'Papua New Guinea Airports')

@push('styles')

<style>
    #map {
        height: 700px;
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

    table tr:nth-child(even) {
        background-color: #ffffff; /* light gray */
    }

    table tr:nth-child(odd) {
        background-color: #c1e4f5; /* white */
    }

    table td{
        text-align: left;
        border-color: #78d9e9;
        vertical-align: top;
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
    <!-- Kolom kiri dengan 3 card -->
    <div class="col-md-3">
        <div class="card mb-3">
            <div class="card-header fw-bold"><img src="{{ asset('images/icon-domestic-flight-png.png') }}" style="width: 30px; height: 30px;"> Domestic</div>
            <div class="card-body overflow-auto" style="max-height: 300px;">
                <?php echo $airport->domestic_flights; ?>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header fw-bold"><img src="{{ asset('images/icon-international-flight.png') }}" style="width: 30px; height: 30px;"> International</div>
            <div class="card-body overflow-auto" style="max-height: 300px;">
                <?php echo $airport->international_flight; ?>
            </div>
        </div>
    </div>

    <div class="col-md-2">
        <div class="card">
            <div class="card-header fw-bold"><img src="{{ asset('images/suport-service-icon.png') }}" style="width: 30px; height: 30px;"> Information Support</div>
            <div class="card-body overflow-auto">
                <h5>Specific Flight Information</h5>
                <?php echo $airport->other_flight_information; ?>

                <h5>General Flight Information</h5>
                <?php echo $airport->general_flight_information; ?>

                <h5>Aircraft Information</h5>
                <?php echo $airport->aircraft_information; ?>
            </div>
        </div>
    </div>

    <!-- Kolom kanan dengan 1 card besar -->
    <div class="col-md-7">
        <div class="card">
            <div class="card-header fw-bold"><img src="{{ asset('images/flight-tracker-icon.png') }}" style="width: 30px; height: 30px;"> Flight Tracker</div>
            <div class="card-body overflow-auto">
                <iframe src="https://globe.adsbexchange.com/?lat=-6.2&lon=106.8&zoom=7" width="100%" height="600" frameborder="0"></iframe>
            </div>
        </div>
    </div>
</div>


</div>


@endsection

@push('service')


@endpush
