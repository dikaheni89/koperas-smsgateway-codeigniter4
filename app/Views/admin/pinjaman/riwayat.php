<div class="col-12">
  <div class="ibox">
      <div class="ibox-head">
          <div class="ibox-title"><?= $title; ?> (<?= $anggota['nm_anggota']; ?>)</div>
      </div>
      <div class="ibox-body">
        <?php if ($pinjam['acc'] == 0 || $pinjam['status_pembayaran'] == 1) : ?>
          <a href="<?= base_url('admin/pengajuanpinjaman/'.$anggota['idanggota']);?>" class="btn btn-success delete"><i class="ti-plus"></i> Pengajuan Pinjaman</a>
        <?php else : ?>
        <?php endif ; ?>
        <p></p>
        <table id="riwayat" class="table table-striped table-bordered dataTable display nowrap" cellspacing="0" width="100%">
	        <thead>
	            <tr>
                  <th>No</th>
                  <th></th>
                  <th>Nama Anggota</th>
                  <th>Tanggal Pinjam</th>
                  <th>Tanggal Tempo</th>
                  <th>Nominal Peminjaman</th>
                  <th>Jumlah Angsuran</th>
                  <th>Persentase Bunga %</th>
                  <th>Nominal Angsuran</th>
                  <th>Pembayaran Ke-</th>
                  <th>Status Validasi</th>
                  <th>Status Peminjaman</th>
	            </tr>
	        </thead>
	    </table>
  </div>
</div>
<script type="text/javascript">
  $(document).ready(function (){
    var table = $('#riwayat').DataTable({
        "processing": true,
        "serverSide": true,
        responsive: true,
        "ajax":{
        "url": "<?= base_url('admin/getriwayat/'.$anggota['idanggota']) ?>",
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
            { "data": "tgl_tempo", "width": "15%"},
            { "data": "jml_pinjaman", "width": "20%"},
            { "data": "jml_angsuran", "width": "15%"},
            { "data": "jml_bunga", "width": "15%"},            
            { "data": "nominal_angsuran", "width": "20%"},            
            { "data": "pembayaran_ke", "width": "10%"},            
            { "data": "acc",
              "orderable":false,
              "render": function (data, type, row) {
                if (row.acc == 1){
                  return '<span class="badge badge-info m-l-5">Sudah di Verifikasi</span>';
                }else{
                  return '<span class="badge badge-danger m-l-5">Belum di Verifikasi</span>';
                }
              }
            },
            { "data": "status_pembayaran",
              "orderable":false,
              "render": function (data, type, row) {
                if (row.status_pembayaran == 1){
                  return '<span class="badge badge-success m-l-5">Lunas</span>';
                }else{
                  return '<span class="badge badge-danger m-l-5">Belum Lunas</span>';
                }
              }
            },
           ],
            columnDefs: [
            {  targets: 1,
              "align":"center",
               render: function (data, type, row, meta) {
                  if (row.acc == 1) {
                    return '<button class="btn btn-success detail" id=id-' + meta.row + '/><i class="ti-clip"></i> Detail Angsuran</button>';
                  }else{
                    if (row.level == 3) {
                      return '<button class="btn btn-info verifikasi" id=id-' + meta.row + '/><i class="ti-clip"></i> Verifikasi</button>';
                    }else{

                    }
                    return '<button class="btn btn-warning"/><i class="ti-clip"></i> Lakukan Verifikasi Dahulu</button>';
                  }
               }

            }
          ]
      });
    $('#riwayat tbody').on('click', '.verifikasi', function () {
      var id = $(this).attr("id").match(/\d+/)[0];
      var data = $('#riwayat').DataTable().row( id ).data();
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
    $('#riwayat tbody').on('click', '.detail', function () {
      var id = $(this).attr("id").match(/\d+/)[0];
      var data = $('#riwayat').DataTable().row( id ).data();
      window.location.href="<?= base_url('admin/angsuran')?>/"+data.idpinjam;
    });
  });
</script>
