<div class="col-12">
  <div class="ibox">
      <div class="ibox-head">
          <div class="ibox-title"><?= $title; ?></div>
      </div>
      <div class="ibox-body">
        <p></p>
        <table id="angsuran" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
	        <thead>
	            <tr>
                  <th>No</th>
                  <th>Nama Anggota</th>
                  <th>Tanggal Angsuran</th>
                  <th>Jumlah di Bayarkan</th>
                  <th>Sisa Pembayaran</th>
	            </tr>
	        </thead>
	    </table>
  </div>
</div>
<script type="text/javascript">
  $(document).ready(function (){
    var table = $('#angsuran').DataTable({
        "processing": true,
        "serverSide": true,
        responsive: true,
        "ajax":{
        "url": "<?= base_url('admin/getangsuran/'.$id) ?>",
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
            { "data": "nm_anggota", "width": "20%"},
            { "data": "tgl_angsuran", "width": "15%"},
            { "data": "jml_bayar", "width": "15%"},
            { "data": "sisa_jml_bayar", "width": "20%"},
           ]
      });
    $('#angsuran tbody').on('click', '.verifikasi', function () {
      var id = $(this).attr("id").match(/\d+/)[0];
      var data = $('#angsuran').DataTable().row( id ).data();
      Swal.fire({
        title: 'Apakah yakin?',
        text: "Apakah anda ingin melakukan Verifikasi data peminjaman Anggota dengan nama "+data.nm_anggota+" ?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText:'Tidak',
        confirmButtonText: 'Ya, Lakukan Verifikasi',
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            type: "POST",
            url : "<?= base_url('admin/verifikasidata')?>",
            dataType:'json',
            data: {<?= csrf_token() ?>: '<?= csrf_hash() ?>' , id:data.idpinjam},
            success: function(msg){
              Swal.fire(
                'Berhasil!',
                'Data Pengajuan Berhasil Di Verifikasi.',
                'success'
              )
              table.ajax.reload( null, false );
            },
            error: function(){
              Swal.fire(
                'Gagal',
                'Data yang anda pilih gagal Di Verifikasi.',
                'error'
              )
            }
          });
        }
      })
    });
    $('#angsuran tbody').on('click', '.riwayat', function () {
      var id = $(this).attr("id").match(/\d+/)[0];
      var data = $('#angsuran').DataTable().row( id ).data();
      window.location.href="<?= base_url('admin/riwayat')?>/"+data.idanggota;
    });
  });
</script>
