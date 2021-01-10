<div class="col-12">
  <div class="ibox">
      <div class="ibox-head">
          <div class="ibox-title"><?= $title; ?></div>
      </div>
      <div class="ibox-body">
        <a href="<?= base_url('admin/addadmin');?>" class="btn btn-success delete"><i class="ti-plus"></i> Tambah Adminsitrator</a>
        <p></p>
        <table id="users" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
	        <thead>
	            <tr>
                  <th>No</th>
                  <th>Full Name</th>
                  <th>Username</th>
                  <th>Email</th>
                  <th>Phone</th>
                  <th>Status</th>
	                <th></th>
	            </tr>
	        </thead>
	    </table>
  </div>
</div>
<script type="text/javascript">
  $(document).ready(function (){
    var table = $('#users').DataTable({
        "processing": true,
        "serverSide": true,
        responsive: true,
        "ajax":{
          "url": "<?= base_url('admin/getusers') ?>",
         "dataType": "json",
         "type": "POST",
         "data":{'<?= csrf_token(); ?>' : '<?= csrf_hash() ?>' }
      },
      "columns": [
            { "data": "no",
              "searchable": false,
              "orderable":false
            },
            { "data": "full_name" },
            { "data": "user" },
            { "data": "email" },
            { "data": "phone",
              "orderable":false,
              "render": function (data, type, row) {
                if (row.phone != ''){
                  var length = 8;
                  var trimmedString = row.phone.length > length ? 
                                      row.phone.substring(0, length) + "-xxxx" : 
                                      row.phone;
                  return trimmedString;
                }else{
                  return 'Tidak Ada';
                }
              }
            },
            { "data": "is_active",
              "orderable":false,
              "render": function (data, type, row) {
                if (row.is_active == 1){
                  return 'Active';
                }else{
                  return 'Non Aktive';
                }
              }
            },
           ],
            createdRow: function ( row, data, index ) {
                if ( data['is_active'] == '1' ) {
                    $(row).find('td:eq(5)').css('color', 'green');
                } else {
                    $(row).find('td:eq(5)').css('color', 'red');
                }

            },
            columnDefs: [
            {  targets: 6,
               render: function (data, type, row, meta) {
                  return '<button class="btn btn-danger delete" id=id-' + meta.row + '/><i class="ti-trash"></i> Delete</button> <button class="btn btn-info edit" id=id-' + meta.row + '/><i class="ti-reload"></i> Reset Password</button>';
               }

            }
          ]
      });
    $('#users tbody').on('click', '.delete', function () {
      var id = $(this).attr("id").match(/\d+/)[0];
      var data = $('#users').DataTable().row( id ).data();
      Swal.fire({
        title: 'Apakah yakin?',
        text: "Apakah anda ingin menghapus data user "+data.full_name+" ?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText:'Tidak',
        confirmButtonText: 'Ya, Hapus Sekarang',
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            type: "POST",
            url : "<?= base_url('admin/deleteduser')?>",
            dataType:'json',
            data: {<?= csrf_token() ?>: '<?= csrf_hash() ?>' , id:data._id},
            success: function(msg){
              Swal.fire(
                'Terhapus!',
                'Data yang anda pilih telah dihapus.',
                'success'
              )
              table.ajax.reload( null, false );
            },
            error: function(){
              Swal.fire(
                'Gagal',
                'Data yang anda pilih gagal terhapus.',
                'error'
              )
            }
          });
        }
      })
    });
    $('#users tbody').on('click', '.edit', function () {
      var id = $(this).attr("id").match(/\d+/)[0];
      var data = $('#users').DataTable().row( id ).data();
      Swal.fire({
        title: 'Apakah yakin?',
        text: "Apakah anda ingin melakukan resert password data user "+data.full_name+" ?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText:'Tidak',
        confirmButtonText: 'Ya, Lakukan Reset',
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            type: "POST",
            url : "<?= base_url('admin/resetuser')?>",
            dataType:'json',
            data: {<?= csrf_token() ?>: '<?= csrf_hash() ?>' , id:data._id, user:data.user},
            success: function(msg){
              Swal.fire(
                'Berhasil!',
                'Password telah di Reset ke default.',
                'success'
              )
              table.ajax.reload( null, false );
            },
            error: function(){
              Swal.fire(
                'Gagal',
                'Data yang anda pilih gagal melakukan reset password.',
                'error'
              )
            }
          });
        }
      })
    });
  });
</script>
