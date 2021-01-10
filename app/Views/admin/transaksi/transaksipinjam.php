<div class="col-12">
  <div class="ibox">
      <div class="ibox-head">
          <div class="ibox-title"><?= $title; ?></div>
      </div>
      <div class="ibox-body">
        <p></p>
        <table id="transaksipinjam" class="table table-striped table-bordered dataTable display nowrap" cellspacing="0" width="100%">
	        <thead>
	            <tr>
                  <th>No</th>
                  <th></th>
                  <th>Nama Anggota</th>
                  <th>Tanggal Pinjam</th>
                  <th>Nominal Peminjaman</th>
                  <th>Pembayaran Ke-</th>
	            </tr>
	        </thead>
	    </table>
  </div>
</div>
<script type="text/javascript">
  $(document).ready(function (){
    var table = $('#transaksipinjam').DataTable({
        "processing": true,
        "serverSide": true,
        responsive: true,
        "ajax":{
        "url": "<?= base_url('admin/gettransaksipinjam') ?>",
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
            { "data": "nm_anggota", "width": "20%"},
            { "data": "tgl_pinjam", "width": "15%"},
            { "data": "jml_pinjaman", "width": "20%"},           
            { "data": "pembayaran_ke", "width": "10%"},
           ],
            columnDefs: [
            {  targets: 1,
              "align":"center",
               render: function (data, type, row, meta) {
                  return '<button class="btn btn-success detail" id=id-' + meta.row + '/><i class="ti-clip"></i> Bayar Angsuran</button>';
               }

            }
          ]
      });
    $('#transaksipinjam tbody').on('click', '.detail', function () {
      var id = $(this).attr("id").match(/\d+/)[0];
      var data = $('#transaksipinjam').DataTable().row( id ).data();
      window.location.href="<?= base_url('admin/bayar')?>/"+data.idpinjam;
    });
  });
</script>
