@extends('layouts.master-admin')

@section('title','Add Airport')

@section('conten')

<div class="card">
    <div class="card-header bg-white">
        <h3>Add Airport</h3>
    </div>

<form action="{{ route('airportdata.store') }}" enctype="multipart/form-data" method="POST">
    @csrf
    <div class="card-body">
        <div class="col-md-12">
            <div class="form-group">
                <label>Airport Name</label>
                <input type="text" class="form-control" name="airport_name">
            </div>
        </div>

         <div class="col-md-12">
            <div class="form-group">
                <label>Provinces, Region</label>
                <select class="form-control" name="province_id" id="province">
                        <option value="0">-Choosse Provinces Region-</option>
                    @foreach($provinces as $prov)
                        <option value="{{$prov->id}}">{{$prov->provinces_region}}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label for="city">City/Regency</label>
                <select name="city" id="city" class="form-control">
                    <option value="">-Choosse City/Regency-</option>
                </select>
            </div>
        </div>

         <div class="col-md-12">
            <div class="form-group">
                <label>Address</label>
                <input type="text" class="form-control" name="address">
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label>Latitude</label>
                <input type="text" class="form-control" name="latitude">
            </div>
        </div>

         <div class="col-md-12">
            <div class="form-group">
                <label>Longitude</label>
                <input type="text" class="form-control" name="longitude">
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label>Category</label>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="category[]" id="international" value="International">
                    <label class="form-check-label" for="international">International</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="category[]" id="domestic" value="Domestic">
                    <label class="form-check-label" for="domestic">Domestic</label>
                </div>
                 <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="category[]" id="regionaldomestic" value="Regional Domestic">
                    <label class="form-check-label" for="domestic">Regional Domestic</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="category[]" id="military" value="Military">
                    <label class="form-check-label" for="military">Military</label>
                </div>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="category[]" id="private" value="Private">
                    <label class="form-check-label" for="military">Private</label>
                </div>
            </div>
        </div>

         <div class="col-md-12">
            <div class="form-group">
                <label>Icon</label><br>

                @php
                    $icons = [
                        ['url' => 'https://pg.concordreview.com/wp-content/uploads/2024/10/International-Airport.png', 'label' => 'International'],
                        ['url' => 'https://pg.concordreview.com/wp-content/uploads/2025/01/regional-airport.png', 'label' => 'Domestic'],
                        ['url' => 'https://pg.concordreview.com/wp-content/uploads/2025/01/regional-domestic-airport.png', 'label' => 'Regional Domestic'],
                        ['url' => 'https://pg.concordreview.com/wp-content/uploads/2024/10/military-airport-red.png', 'label' => 'Military'],
                        ['url' => 'https://pg.concordreview.com/wp-content/uploads/2024/10/civil-military-airport.png', 'label' => 'Combined (Civil - Military)'],
                        ['url' => 'https://pg.concordreview.com/wp-content/uploads/2025/01/private-airport.png', 'label' => 'Private'],
                    ];
                @endphp

                @foreach($icons as $icon)
                    <label style="margin-right: 15px;">
                        <input type="radio" name="icon" value="{{ $icon['url'] }}">
                        <img src="{{ $icon['url'] }}" style="width:24px; height:24px;"> {{ $icon['label'] }}
                    </label>
                @endforeach
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label>IATA Code</label>
                <input type="text" class="form-control" name="iata_code">
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label>ICAO Code</label>
                <input type="text" class="form-control" name="icao_code">
            </div>
        </div>

        <div class="col-md-12">
          <div class="card card-outline card-info">
            <div class="card-header">
              <h3 class="card-title">
                Hours Of Operation
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <textarea id="summernote6" name="hrs_of_operation">
                </textarea>

            </div>

          </div>
        </div>

          <div class="col-md-12">
          <div class="card card-outline card-info">
            <div class="card-header">
              <h3 class="card-title">
                Distance From
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <textarea id="summernote7" name="distance_from">
                </textarea>

            </div>

          </div>
        </div>

         <div class="col-md-12">
            <div class="form-group">
                <label>Elevation</label>
                <input type="text" class="form-control" name="elevation">
            </div>
        </div>

         <div class="col-md-12">
            <div class="form-group">
                <label>Time Zone</label>
                <input type="text" class="form-control" name="time_zone">
            </div>
        </div>

         <div class="col-md-12" hidden>
            <div class="form-group">
                <label>Operator</label>
                <input type="text" class="form-control" name="operator">
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label>Magnetic Variation</label>
                <input type="text" class="form-control" name="magnetic_variation">
            </div>
        </div>

         <div class="col-md-12">
            <div class="form-group">
                <label>Beacon</label><br>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="beacon" id="beaconYes" value="Yes">
                    <label class="form-check-label" for="beaconYes">Yes</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="beacon" id="beaconNo" value="No">
                    <label class="form-check-label" for="beaconNo">No</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="beacon" id="beaconUnknown" value="Data Not Identified">
                    <label class="form-check-label" for="beaconUnknown">Data Not Identified</label>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label>Max Aircraft Capability</label>
                <input type="text" class="form-control" name="max_aircraft_capability">
            </div>
        </div>

        <div class="col-md-12">
          <div class="card card-outline card-info">
            <div class="card-header">
              <h3 class="card-title">
                Directorate General of Civil Aviation
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <textarea id="summernote24" name="dgoca">
                </textarea>

            </div>

          </div>
        </div>

        <div class="col-md-12">
          <div class="card card-outline card-info">
            <div class="card-header">
              <h3 class="card-title">
                Operator
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <textarea id="summernote25" name="soao">
                </textarea>

            </div>

          </div>
        </div>

        <div class="col-md-12">
          <div class="card card-outline card-info">
            <div class="card-header">
              <h3 class="card-title">
                Other Airport Info
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <textarea id="summernote14" name="other_reference_website">
                </textarea>

            </div>

          </div>
        </div>

        <div class="col-md-12">
          <div class="card card-outline card-info">
            <div class="card-header">
              <h3 class="card-title">
                Note
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <textarea id="summernote11" name="note">
                </textarea>

            </div>

          </div>
        </div>

         <div class="col-md-12">
          <div class="card card-outline card-info">
            <div class="card-header">
              <h3 class="card-title">
                Telephone
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <textarea id="summernote20" name="telephone">
                </textarea>

            </div>

          </div>
        </div>

         <div class="col-md-12">
          <div class="card card-outline card-info">
            <div class="card-header">
              <h3 class="card-title">
                Fax
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <textarea id="summernote21" name="fax">
                </textarea>

            </div>

          </div>
        </div>

        <div class="col-md-12">
          <div class="card card-outline card-info">
            <div class="card-header">
              <h3 class="card-title">
                Email
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <textarea id="summernote22" name="email">
                </textarea>

            </div>

          </div>
        </div>

          <div class="col-md-12">
          <div class="card card-outline card-info">
            <div class="card-header">
              <h3 class="card-title">
                Website
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <textarea id="summernote23" name="website">
                </textarea>

            </div>

          </div>
        </div>

        <div class="col-md-12">
          <div class="card card-outline card-info">
            <div class="card-header">
              <h3 class="card-title">
                Nearest Accommodation
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <textarea id="summernote12" name="nearest_accommodation">
                </textarea>

            </div>

          </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label>Air Traffic</label><br>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="air_traffic" value="Available">
                    <label class="form-check-label" for="Available">Available</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="air_traffic" value="Not Available">
                    <label class="form-check-label" for="NotAvailable">Not Available</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="air_traffic" value="Data Not Identified">
                    <label class="form-check-label" for="beaconUnknown">Data Not Identified</label>
                </div>
            </div>
        </div>

         <div class="col-md-12">
            <div class="form-group">
                <label>Meteorology Services</label><br>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="meteorology_services" value="Available">
                    <label class="form-check-label" for="Available">Available</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="meteorology_services" value="Not Available">
                    <label class="form-check-label" for="NotAvailable">Not Available</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="meteorology_services" value="Data Not Identified">
                    <label class="form-check-label" for="beaconUnknown">Data Not Identified</label>
                </div>
            </div>
        </div>

         <div class="col-md-12">
            <div class="form-group">
                <label>Aviation Fuel Depot</label><br>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="aviation_fuel_depot" value="Available">
                    <label class="form-check-label" for="Available">Available</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="aviation_fuel_depot" value="Not Available">
                    <label class="form-check-label" for="NotAvailable">Not Available</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="aviation_fuel_depot" value="Data Not Identified">
                    <label class="form-check-label" for="beaconUnknown">Data Not Identified</label>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label>Internet</label><br>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="internet_services" value="Available">
                    <label class="form-check-label" for="Available">Available</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="internet_services" value="Not Available">
                    <label class="form-check-label" for="NotAvailable">Not Available</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="internet_services" value="Data Not Identified">
                    <label class="form-check-label" for="beaconUnknown">Data Not Identified</label>
                </div>
            </div>
        </div>

        <div class="col-md-12">
          <div class="card card-outline card-info">
            <div class="card-header">
              <h3 class="card-title">
                Supplies Equipment
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <textarea id="summernote8" name="supplies_eqipment">
                </textarea>

            </div>

          </div>
        </div>

        <div class="col-md-12">
          <div class="card card-outline card-info">
            <div class="card-header">
              <h3 class="card-title">
                Public Facilities
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <textarea id="summernote9" name="public_facilities">
                </textarea>

            </div>

          </div>
        </div>

        <div class="col-md-12">
          <div class="card card-outline card-info">
            <div class="card-header">
              <h3 class="card-title">
                Public Transportation
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <textarea id="summernote10" name="public_transportation">
                </textarea>

            </div>

          </div>
        </div>

        <div class="col-md-12">
          <div class="card card-outline card-info">
            <div class="card-header">
              <h3 class="card-title">
                Navigation Aids (NAVAIDs)
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <textarea id="summernote2" name="navigation_aids">
                </textarea>

            </div>

          </div>
        </div>

        <div class="col-md-12">
          <div class="card card-outline card-info">
            <div class="card-header">
              <h3 class="card-title">
                Communication
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <textarea id="summernote18" name="communication">
                </textarea>

            </div>

          </div>
        </div>

          <div class="col-md-12">
            <div class="form-group">
                <label>Runway Edge Lights</label><br>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="runway_edge_lights" id="runway_edge_lightsYes" value="Yes">
                    <label class="form-check-label" for="runway_edge_lightsYes">Yes</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="runway_edge_lights" id="runway_edge_lightsNo" value="No">
                    <label class="form-check-label" for="runway_edge_lightsNo">No</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="runway_edge_lights" id="runway_edge_lightsUnknown" value="Data Not Identified">
                    <label class="form-check-label" for="runway_edge_lightsUnknown">Data Not Identified</label>
                </div>
            </div>
        </div>

          <div class="col-md-12">
            <div class="form-group">
                <label>Reil</label><br>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="reil" id="reilYes" value="Yes">
                    <label class="form-check-label" for="reilYes">Yes</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="reil" id="reilNo" value="No">
                    <label class="form-check-label" for="reilNo">No</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="reil" id="reilUnknown" value="Data Not Identified">
                    <label class="form-check-label" for="reilUnknown">Data Not Identified</label>
                </div>
            </div>
        </div>

         <div class="col-md-12">
          <div class="card card-outline card-info">
            <div class="card-header">
              <h3 class="card-title">
                Runways
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <textarea id="summernote17" name="runways">
                </textarea>

            </div>

          </div>
        </div>

         <div class="col-md-12">
          <div class="card card-outline card-info">
            <div class="card-header">
              <h3 class="card-title">
                Domestic Airlines / Destination
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <textarea id="summernote4" name="domestic_flights">
                </textarea>

            </div>

          </div>
        </div>

         <div class="col-md-12">
          <div class="card card-outline card-info">
            <div class="card-header">
              <h3 class="card-title">
                International Flight
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <textarea id="summernote16" name="international_flight">
                </textarea>

            </div>

          </div>
        </div>

         <div class="col-md-12">
          <div class="card card-outline card-info">
            <div class="card-header">
              <h3 class="card-title">
                Specific Airport Flight Information
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <textarea id="summernote13" name="other_flight_information">
                </textarea>

            </div>

          </div>
        </div>

         <div class="col-md-12">
          <div class="card card-outline card-info">
            <div class="card-header">
              <h3 class="card-title">
                General Flight Information
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <textarea id="summernote26" name="general_flight_information">
                </textarea>

            </div>

          </div>
        </div>

        <div class="col-md-12">
          <div class="card card-outline card-info">
            <div class="card-header">
              <h3 class="card-title">
                Aircraft Information
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <textarea id="summernote27" name="aircraft_information">
                </textarea>

            </div>

          </div>
        </div>

         <!-- <div class="col-md-12">
          <div class="card card-outline card-info">
            <div class="card-header">
              <h3 class="card-title">
                Flight Information
              </h3>
            </div>

            <div class="card-body">

                <textarea id="summernote15" name="flight_information">
                </textarea>

            </div>

          </div>
        </div> -->

         <div class="col-md-12">
          <div class="card card-outline card-info">
            <div class="card-header">
              <h3 class="card-title">
                Nearest Police Station
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <textarea id="summernote3" name="nearest_police_station">
                </textarea>

            </div>

          </div>
        </div>

          <div class="col-md-12">
          <div class="card card-outline card-info">
            <div class="card-header">
              <h3 class="card-title">
                Nearest Medical Facility
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <textarea id="summernote5" name="nearest_medical_facility">
                </textarea>

            </div>

          </div>
        </div>

        <div class="col-md-12">
          <div class="card card-outline card-info">
            <div class="card-header">
              <h3 class="card-title">
                Nearest Airports
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <textarea id="summernote" name="nearest_airport">
                </textarea>

            </div>

          </div>
        </div>

         <div class="col-md-12">
          <div class="card card-outline card-info">
            <div class="card-header">
              <h3 class="card-title">
                Nearby Airport Navigation Aids
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <textarea id="summernote19" name="nearby_airport_navigation_aids">
                </textarea>

            </div>

          </div>
        </div>

         <div class="col-md-12">
            <div class="form-group">
                <label>Image</label>
                <input type="file" class="form-control" name="image">
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</form>
</div>
@endsection

@push('service')
<script>
  $(function () {
    // Summernote
    $('#summernote').summernote()
    $('#summernote2').summernote()
    $('#summernote3').summernote()
    $('#summernote4').summernote()
    $('#summernote5').summernote()
    $('#summernote6').summernote()
    $('#summernote7').summernote()
    $('#summernote8').summernote()
    $('#summernote9').summernote()
    $('#summernote10').summernote()
    $('#summernote11').summernote()
    $('#summernote12').summernote()
    $('#summernote13').summernote()
    $('#summernote14').summernote()
    $('#summernote15').summernote()
    $('#summernote16').summernote()
    $('#summernote17').summernote()
    $('#summernote18').summernote()
    $('#summernote19').summernote()
    $('#summernote20').summernote()
    $('#summernote21').summernote()
    $('#summernote22').summernote()
    $('#summernote23').summernote()
    $('#summernote24').summernote()
    $('#summernote25').summernote()
    $('#summernote26').summernote()
    $('#summernote27').summernote()

  })
</script>

<script>
    $('#province').on('change', function () {
        var provinceId = $(this).val();
        if (provinceId) {
            $.ajax({
                url: '/get-cities/' + provinceId,
                type: 'GET',
                success: function (data) {
                    $('#city').empty();
                    $('#city').append('<option value="">-- Choosse City/Regency --</option>');
                    $.each(data, function (key, city) {
                        $('#city').append('<option value="' + city.id + '">' + city.city + '</option>');
                    });
                }
            });
        } else {
            $('#city').empty();
            $('#city').append('<option value="">-- Choosse City/Regency  --</option>');
        }
    });
</script>
@endpush
