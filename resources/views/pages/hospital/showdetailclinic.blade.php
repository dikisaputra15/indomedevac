@extends('layouts.master')

@section('title','Detail Clinic')
@section('page-title', 'Papua New Guinea Medical Facility')

@push('styles')

<style>
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

    .clinical-service-table{

    }

    .clinical-service-table td{
        padding: 6px 0;
        border-bottom: 1px solid #dee2e6;
        border-top:none;
        line-height: 18px;
    }

    .card-header{
        padding: 0.25rem 1.25rem;
        color: #3c66b5;
        font-weight: bold;
    }

    .mb-4{
        margin-bottom: 0.5rem !important;
    }

    .clinical-service-table td{
        padding: 6px;
    }
</style>

@endpush

@section('conten')

<div class="card">

     <div class="d-flex justify-content-between p-3" style="background-color: #dfeaf1;">
        <div class="d-flex flex-column gap-1">
            <h2 class="fw-bold mb-0">{{ $hospital->name }}</h2>
            <span class="fw-bold"><b>Facility Global Classification:</b> {{ $hospital->facility_category }} | <b>Facility Country Classification:</b> {{ $hospital->facility_level }}</span>
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
        <div class="col-sm-6">
            <div class="card">
                <div class="card-header fw-bold"><img src="{{ asset('images/icon-menu-medical-facility.png') }}" style="width: 24px; height: 24px;"> Clinical Services</div>
              <div class="card-body overflow-auto">
                <div class="row">
                <div class="col-sm-6">
                    <table class="table table-hover clinical-service-table">
                        <tr>
                            <td>Inpatient</td>
                            <td>{{ $hospital->inpatient_services }}</td>
                        </tr>
                        <tr>
                            <td>Outpatient</td>
                            <td>{{ $hospital->outpatient_services }}</td>
                        </tr>
                        <tr>
                            <td>24 hr ER</td>
                            <td>{{ $hospital->hour_emergency_services }}</td>
                        </tr>
                        <tr>
                            <td>Ambulance</td>
                            <td>{{ $hospital->ambulance }}</td>
                        </tr>
                        <tr>
                            <td>Helipad</td>
                            <td>{{ $hospital->helipad }}</td>
                        </tr>

                        @if (!empty($hospital->comments))
                        <tr>
                            <td>Note</td>
                            <td>{{ $hospital->comments }}</td>
                        </tr>
                        @endif

                        <tr>
                            <td>ICU</td>
                            <td>{{ $hospital->icu }}</td>
                        </tr>
                        <tr>
                            <td>Medical</td>
                            <td>{{ $hospital->medical }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-sm-6">
                    <table class="table table-hover clinical-service-table">
                        <tr>
                            <td>Pediatric</td>
                            <td>{{ $hospital->pediatric }}</td>
                        </tr>
                        <tr>
                            <td>Dental</td>
                            <td>{{ $hospital->dental }}</td>
                        </tr>
                        <tr>
                            <td>Optical</td>
                            <td>{{ $hospital->optical }}</td>
                        </tr>
                        <tr>
                            <td>Integrated Outreach Clinic (IOC)</td>
                            <td>{{ $hospital->ioc }}</td>
                        </tr>
                        <tr>
                            <td>Laboratory</td>
                            <td>{{ $hospital->laboratory }}</td>
                        </tr>
                        <tr>
                            <td>Pharmacy</td>
                            <td>{{ $hospital->pharmacy }}</td>
                        </tr>
                        <tr>
                            <td>Medical Imaging</td>
                            <td>{{ $hospital->medical_imaging }}</td>
                        </tr>
                        <tr>
                            <td>Medical Student Training</td>
                            <td>{{ $hospital->medical_student_training }}</td>
                        </tr>
                    </table>
                </div>
                </div>
            </div>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="card">
                  <div class="card-header fw-bold"><img src="{{ asset('images/icon-medical-personel.png') }}" style="width: 32px; height: 24px;"> Medical Personnel</div>
             <div class="card-body overflow-auto">
                 <div class="row">
                <div class="col-sm-6">
                <table class="table table-hover clinical-service-table">
                    <tr>
                        <td>Doctors</td>
                        <td>{{ $hospital->doctors }}</td>
                    </tr>
                    <tr>
                        <td>Nurses</td>
                        <td>{{ $hospital->nurses }}</td>
                    </tr>
                    <tr>
                        <td>Dental Therapist</td>
                        <td>{{ $hospital->dental_therapist }}</td>
                    </tr>
                    <tr>
                        <td>Laboratory Assistants</td>
                        <td>{{ $hospital->laboratory_assistants }}</td>
                    </tr>
                    <tr>
                        <td>Community Health Workers/Orderlies</td>
                        <td>{{ $hospital->community_health }}</td>
                    </tr>
                </table>
                </div>
                <div class="col-sm-6">
                <table class="table table-hover clinical-service-table">
                    <tr>
                        <td>Health Inspectors</td>
                        <td>{{ $hospital->health_inspectors }}</td>
                    </tr>
                    <tr>
                        <td>Malaria Control Officers</td>
                        <td>{{ $hospital->malaria_control_officers ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>Health Extension Officers</td>
                        <td>{{ $hospital->health_extention_officers }}</td>
                    </tr>
                    <tr>
                        <td>Casuals</td>
                        <td>{{ $hospital->casuals }}</td>
                    </tr>
                </table>
                </div>
                </div>
            </div>
            </div>

             <div class="card">
                <div class="card-body overflow-auto">
                    <div class="row">
                        <div class="col-sm-12">
                            <p>{!! $hospital->medical_personel_disclaimer; !!}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>


@endsection

@push('service')

@endpush
