@extends('layouts.master')

@section('title','Edit Embessy')

@section('conten')

<div class="card">
    <div class="card-header bg-white">
        <h3>Edit Embessy</h3>
    </div>

<form action="{{ route('embessydata.update', $embassy->id) }}" enctype="multipart/form-data" method="POST">
    @csrf
    @method('PUT')
    <div class="card-body">
        <div class="col-md-12">
            <div class="form-group">
                <label>Embassy Name</label>
                <input type="text" class="form-control" name="embassy_name" value="{{ $embassy->name_embassiees; }}">
            </div>
        </div>

         <div class="col-md-12">
            <div class="form-group">
                <label>Edit Provinces, Region</label>
                <select class="form-control" name="province_id">
                    <?php
                        foreach ($provinces as $prov) {

                            if ($prov->id==$embassy->province_id) {
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
                <label>Latitude</label>
                <input type="text" class="form-control" name="latitude" value="{{$embassy->latitude}}">
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label>Longitude</label>
                <input type="text" class="form-control" name="longitude" value="{{$embassy->longitude}}">
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label>Location</label>
                <input type="text" class="form-control" name="location" value="{{ $embassy->location; }}">
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

                <textarea id="summernote" name="telephone">
                    <?php echo $embassy->telephone; ?>
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

                <textarea id="summernote4" name="fax">
                    <?php echo $embassy->fax; ?>
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

                <textarea id="summernote2" name="email">
                    <?php echo $embassy->email; ?>
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

                <textarea id="summernote3" name="website">
                    <?php echo $embassy->website; ?>
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

  })
</script>
@endpush
