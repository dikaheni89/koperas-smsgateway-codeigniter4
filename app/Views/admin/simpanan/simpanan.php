<div class="col-12">
  <div class="ibox">
      <div class="ibox-head">
          <div class="ibox-title"><?= $title; ?></div>
      </div>
      <div class="ibox-body">
        <p></p>
        <table id="anggota" class="table table-striped table-bordered dataTable display nowrap" cellspacing="0" width="100%">
	        <thead>
	            <tr>
                  <th>No</th>
                  <th></th>
                  <th>Nama Lengkap</th>
                  <th>Alamat Lengkap</th>
                  <th>Tanggal Masuk</th>
                  <th>Phone</th>
	            </tr>
	        </thead>
	    </table>
  </div>
</div>
<script type="text/javascript">
  $(document).ready(function (){
    var table = $('#anggota').DataTable({
        "processing": true,
        "serverSide": true,
        responsive: true,
        "ajax":{
        "url": "<?= base_url('admin/getanggota') ?>",
        "dataType": "json",
        "type": "POST",
        "data":{'<?= csrf_token(); ?>' : '<?= csrf_hash() ?>' }
      },
      "columns": [
            { "data": "no",
              "searchable": false,
              "orderable":false,
              "width": "10%"
            },
            { "data": "no",
              "searchable": false,
              "orderable":false,
              "width": "20%"
            },
            { "data": "nm_anggota", "width": "30%"},
            { "data": "alamat", "width": "40%"},
            { "data": "tgl_masuk", "width": "15%"},
            { "data": "no_telp",
              "orderable":false,
              "render": function (data, type, row) {
                if (row.no_telp != ''){
                  var length = 8;
                  var trimmedString = row.no_telp.length > length ? 
                                      row.no_telp.substring(0, length) + "-xxxx" : 
                                      row.no_telp;
                  return trimmedString;
                }else{
                  return 'Tidak Ada';
                }
              }
            },
           ],
            columnDefs: [
            {  targets: 1,
              "align":"center",
               render: function (data, type, row, meta) {
                  return '<button class="btn btn-success btn-sm simpanan" id=id-' + meta.row + '/><i class="fas fa-dollar-sign"></i> Bayar Simpanan</button>';
               }

            }
          ]
      });
    $('#anggota tbody').on('click', '.simpanan', function () {
      var id = $(this).attr("id").match(/\d+/)[0];
      var data = $('#anggota').DataTable().row( id ).data();
      window.location.href="<?= base_url('admin/addsimpanan')?>/"+data.idanggota;
    });
  });
</script>
