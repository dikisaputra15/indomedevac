@extends('layouts.master-admin')

@section('title','User')

@section('conten')

<div class="card">
    <div class="card-header bg-white">
        <h3>User List</h3>
    </div>

     <div id="session" data-session="{{ session('success') }}"></div>

    <div class="card-body">
         <div class="mb-3 d-flex justify-content-start">
            <a href="{{ route('user.create') }}" class="btn btn-primary btn-sm ml-2">Tambah data</a>
        </div>
            <table id="userTable" class="table" style="width:100%">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Role</th>
                      <th>Action</th>
                    </tr>
                  </thead>

            </table>
    </div>
</div>

        <!-- Modal Ubah Role -->
<div class="modal fade" id="modalUbahRole" tabindex="-1" role="dialog" aria-labelledby="ubahRoleLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="formUbahRole">
        @csrf
        <input type="hidden" id="edit_user_id" name="user_id">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Ubah Role Pengguna</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
              <div class="form-group">
                  <label for="edit_role">Role</label>
                  <select class="form-control" id="edit_role" name="role">
                      @foreach ($roles as $role)
                          <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                      @endforeach
                  </select>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan</button>
          </div>
        </div>
    </form>
  </div>
</div>
@endsection

@push('service')
  <script>
        $(document).ready(function() {

            let session = $('#session').data('session');

            if (session) {
                Swal.fire({
                    title: "Sukses!",
                    text: session,
                    icon: "success",
                    timer: 3000,
                    showConfirmButton: true
                });
            }

            // table data
            $('#userTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "/user",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'roles',
                        name: 'roles'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

             // Buka modal & set data
$('#userTable').on('click', '.edit-role-btn', function () {
    var userId = $(this).data('id');
    var currentRole = $(this).data('role');

    $('#edit_user_id').val(userId);
    $('#edit_role').val(currentRole);
    $('#modalUbahRole').modal('show');
});

// Submit update role
$('#formUbahRole').on('submit', function (e) {
    e.preventDefault();
    var userId = $('#edit_user_id').val();
    var role = $('#edit_role').val();

    $.ajax({
        url: '/user/' + userId + '/update-role',
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            role: role
        },
        success: function (response) {
            $('#modalUbahRole').modal('hide');
            $('#userTable').DataTable().ajax.reload(null, false);

            Swal.fire({
                title: 'Sukses!',
                text: response.message,
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            });
        },
        error: function () {
            alert('Gagal mengubah role.');
        }
    });
});

     // Event listener untuk tombol hapus
            $('#userTable').on('click', '.delete-btn', function () {
                var roleId = $(this).data('id');

                Swal.fire({
                    title: "Are you sure?",
                    text: "Data will be deleted permanently!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Yes, Delete!",
                    cancelButtonText: "Cancel"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/user/' + roleId,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response){
                            if(response.success == 1){
                                alert("Record deleted.");
                                var oTable = $('#userTable').dataTable();
                                oTable.fnDraw(false);
                            }else{
                                    alert("Invalid ID.");
                                }
                            },

                        });
                    }
                });
            });


        });
    </script>
@endpush
