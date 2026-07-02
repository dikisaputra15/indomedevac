@extends('layouts.master-admin')

@section('title','Edit Police')

@section('conten')

<div class="card">
    <div class="card-header bg-white">
        <h3>Edit Police</h3>
    </div>

<form action="{{ route('policedata.update', $police->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="card-body">
        <div class="col-md-12">
            <div class="form-group">
                <label>Edit Name</label>
                <input type="text" class="form-control" name="name_police" value="{{ $police->name_police; }}">
            </div>
        </div>

         <div class="col-md-12">
            <div class="form-group">
                <label>Province</label>
                <select class="form-control" name="province_id" id="province">
                    <?php
                        foreach ($provinces as $prov) {

                            if ($prov->id==$police->province_id) {
                                $select="selected";
                            }else{
                                $select="";
                            }

                        ?>
                            <option <?php echo $select; ?> value="<?php echo $prov->id;?>"><?php echo $prov->provinces_region; ?></option>

                    <?php } ?>

                </select>
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label>Edit Regency / City</label>
                <select class="form-control" name="city" id="city">
                    <?php
                        foreach ($cities as $city) {

                            if ($city->id==$police->city_id) {
                                $select="selected";
                            }else{
                                $select="";
                            }

                        ?>
                            <option <?php echo $select; ?> value="<?php echo $city->id;?>"><?php echo $city->city; ?></option>

                    <?php } ?>

                </select>
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label>Edit Latitude</label>
                <input type="text" class="form-control" name="latitude" value="{{$police->latitude}}">
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label>Edit Longitude</label>
                <input type="text" class="form-control" name="longitude" value="{{$police->longitude}}">
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label>Edit Location</label>
                <input type="text" class="form-control" name="location" value="{{ $police->location; }}">
            </div>
        </div>

         <div class="col-md-12">
            <div class="form-group">
                <label>Edit Police Classification (Global)</label><br>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="level" value="Layer 1"
                        {{ old('Layer 1', $police->level ?? '') == 'Layer 1' ? 'checked' : '' }}>
                    <label class="form-check-label">Layer 1</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="level" value="Layer 2"
                        {{ old('Layer 2', $police->level ?? '') == 'Layer 2' ? 'checked' : '' }}>
                    <label class="form-check-label">Layer 2</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="level" value="Layer 3"
                        {{ old('Layer 3', $police->level ?? '') == 'Layer 3' ? 'checked' : '' }}>
                    <label class="form-check-label">Layer 3</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="level" value="Layer 4"
                        {{ old('Layer 4', $police->level ?? '') == 'Layer 4' ? 'checked' : '' }}>
                    <label class="form-check-label">Layer 4</label>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label>Edit Police Classification (Country)</label><br>

                <input type="hidden" name="icon" id="icon" value="{{ $police->icon }}">
               <div class="form-check form-check-inline police-option {{ $police->category == 'Kepolisian Negara Republik Indonesia (Mabes Polri)' ? 'selected' : '' }}">
                    <input class="form-check-input category-radio"
                        type="radio"
                        name="category"
                        value="Kepolisian Negara Republik Indonesia (Mabes Polri)"
                        data-icon="{{ asset('images/Layer1.png') }}"
                        {{ $police->category == 'Kepolisian Negara Republik Indonesia (Mabes Polri)' ? 'checked' : '' }}>
                    <img src="{{ asset('images/Layer1.png') }}" width="16">
                    <label>Kepolisian Negara Republik Indonesia (Mabes Polri)</label>
                </div>

                <div class="form-check form-check-inline police-option {{ $police->category == 'Polda' ? 'selected' : '' }}">
                    <input class="form-check-input category-radio"
                        type="radio"
                        name="category"
                        value="Polda"
                        data-icon="{{ asset('images/Layer2.png') }}"
                        {{ $police->category == 'Polda' ? 'checked' : '' }}>
                    <img src="{{ asset('images/Layer2.png') }}" width="16">
                    <label>Polda</label>
                </div>

                <div class="form-check form-check-inline police-option {{ $police->category == 'Polres' ? 'selected' : '' }}">
                    <input class="form-check-input category-radio"
                        type="radio"
                        name="category"
                        value="Polres"
                        data-icon="{{ asset('images/Layer3.png') }}"
                        {{ $police->category == 'Polres' ? 'checked' : '' }}>
                    <img src="{{ asset('images/Layer3.png') }}" width="16">
                    <label>Polres</label>
                </div>

                <div class="form-check form-check-inline police-option {{ $police->category == 'Polsek' ? 'selected' : '' }}">
                    <input class="form-check-input category-radio"
                        type="radio"
                        name="category"
                        value="Polsek"
                        data-icon="{{ asset('images/Layer4.png') }}"
                        {{ $police->category == 'Polsek' ? 'checked' : '' }}>
                    <img src="{{ asset('images/Layer4.png') }}" width="16">
                    <label>Polsek</label>
                </div>

                <div class="form-check form-check-inline police-option {{ $police->category == 'Police Mobil Brigade (Brimob)' ? 'selected' : '' }}">
                    <input class="form-check-input category-radio"
                        type="radio"
                        name="category"
                        value="Police Mobil Brigade (Brimob)"
                        data-icon="{{ asset('images/Brimob.png') }}"
                        {{ $police->category == 'Police Mobil Brigade (Brimob)' ? 'checked' : '' }}>
                    <img src="{{ asset('images/Brimob.png') }}" width="16">
                    <label>Police Mobil Brigade (Brimob)</label>
                </div>

                <div class="form-check form-check-inline police-option {{ $police->category == 'Police Bomb Squad (Gegana)' ? 'selected' : '' }}">
                    <input class="form-check-input category-radio"
                        type="radio"
                        name="category"
                        value="Police Bomb Squad (Gegana)"
                        data-icon="{{ asset('images/Gegana.png') }}"
                        {{ $police->category == 'Police Bomb Squad (Gegana)' ? 'checked' : '' }}>
                    <img src="{{ asset('images/Gegana.png') }}" width="16">
                    <label>Police Bomb Squad (Gegana)</label>
                </div>

            </div>
        </div>

        <div class="col-md-12">
          <div class="card card-outline card-info">
            <div class="card-header">
              <h3 class="card-title">
                Edit Telephone
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <textarea id="summernote" name="telephone">
                    <?php echo $police->telephone; ?>
                </textarea>

            </div>

          </div>
        </div>

         <div class="col-md-12">
          <div class="card card-outline card-info">
            <div class="card-header">
              <h3 class="card-title">
                Edit Fax
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <textarea id="summernote4" name="fax">
                    <?php echo $police->fax; ?>
                </textarea>

            </div>

          </div>
        </div>

        <div class="col-md-12">
          <div class="card card-outline card-info">
            <div class="card-header">
              <h3 class="card-title">
                Edit Email
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <textarea id="summernote2" name="email">
                    <?php echo $police->email; ?>
                </textarea>

            </div>

          </div>
        </div>

         <div class="col-md-12">
          <div class="card card-outline card-info">
            <div class="card-header">
              <h3 class="card-title">
                Edit Website
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <textarea id="summernote3" name="website">
                    <?php echo $police->website; ?>
                </textarea>

            </div>

          </div>
        </div>

        <div class="col-md-12">
          <div class="card card-outline card-info">
            <div class="card-header">
              <h3 class="card-title">
                Edit Hours of Operation
              </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <textarea id="summernote5" name="hrs_of_operation">
                    <?php echo $police->hrs_of_operation; ?>
                </textarea>

            </div>

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

  })
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.category-radio').forEach(radio => {

        radio.addEventListener('change', function () {

            document.getElementById('icon').value = this.dataset.icon;

            document.querySelectorAll('.police-option')
                .forEach(item => item.classList.remove('selected'));

            this.closest('.police-option')
                .classList.add('selected');
        });

    });

});
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
                    $('#city').append('<option value="">-- Choosse Regency / City --</option>');
                    $.each(data, function (key, city) {
                        $('#city').append('<option value="' + city.id + '">' + city.city + '</option>');
                    });
                }
            });
        } else {
            $('#city').empty();
            $('#city').append('<option value="">-- Choosse Regency / City  --</option>');
        }
    });
</script>
@endpush
