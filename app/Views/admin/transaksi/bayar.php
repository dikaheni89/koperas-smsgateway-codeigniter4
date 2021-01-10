<div class="col-12">
    <div class="ibox">
        <div class="ibox-head">
            <div class="ibox-title"><?= $title;?></div>
            <div class="ibox-tools">
                <a class="ibox-collapse"><i class="fa fa-minus"></i></a>
            </div>
        </div>
        <div class="ibox-body">
            <form method="post" id="form-anggota" action="javascript:;" novalidate="novalidate">
                <input type="hidden" name="id" value="<?= $pinjam['idpinjam']; ?>">
                <input type="hidden" name="phone" value="<?= $pinjam['no_telp']; ?>">
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Nama Anggota</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" value="<?= $pinjam['nm_anggota']; ?>" readonly="readonly">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Pembayaran Ke-</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="pembayaran_ke" id="pembayaran_ke" value="<?= $pinjam['pembayaran_ke']+1; ?>" readonly="readonly">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Jumlah Pembayaran</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="number" name="jml_bayar" id="jml_bayar" placeholder="Jumlah Pembayaran" autocomplete="off">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Sisa Nominal Peminjaman</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="sisa_jml_bayar" id="sisa_jml_bayar" value="<?= $pinjam['nominal_pinjam']; ?>" readonly="readonly" >
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Status Pembayaran</label>
                    <div class="col-sm-4">
                        <label class="ui-radio ui-radio-inline">
                            <input type="radio" name="status_pembayaran" value="0" checked="">
                        <span class="input-span"></span>Belum Lunas</label>
                        <label class="ui-radio ui-radio-inline">
                            <input type="radio" name="status_pembayaran" value="1">
                        <span class="input-span"></span>Lunas</label>
                      </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-10 ml-sm-auto">
                        <a href="javascript:void(0)" class="btn btn-warning" onclick="backButton()"><i class="fa fa-history"></i> Back</a>
                        <button class="btn btn-info submit" type="botton" name="submit"> <i class="fa fa-save"></i> Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function (){  
        $("#form-anggota").validate({
            rules: {
                jml_bayar:{
                    required: !0,
                },
            },
            messages:{
                jml_bayar:{
                    required:"Jumlah Bayar Harus Diisi",
                },
            },
            errorClass: "help-block error",
            highlight: function(e) {
                $(e).closest(".form-group.row").addClass("has-error")
            },
            unhighlight: function(e) {
                $(e).closest(".form-group.row").removeClass("has-error")
            },
            submitHandler:function(form){
                var idpinjam  = $('input[name=id]').val();
                var no_telp  = $('input[name=phone]').val();
                var pembayaran_ke  = $('input[name=pembayaran_ke]').val();
                var jml_bayar  = $('input[name=jml_bayar]').val();
                var sisa_jml_bayar  = $('input[name=sisa_jml_bayar]').val();
                var radio = $('input[name=status_pembayaran]:checked').val();
                $.ajax({
                    type: "POST",
                    url : "<?= base_url('admin/savepembayaran')?>",
                    data: {<?= csrf_token() ?>:'<?= csrf_hash() ?>', idpinjam:idpinjam, no_telp:no_telp, pembayaran_ke:pembayaran_ke, jml_bayar:jml_bayar, sisa_jml_bayar:sisa_jml_bayar, status_pembayaran:radio},
                    success: function(msg){
                        var msg = eval('('+msg+')');
                        if (msg.errorMsg){
                            Swal.fire(
                                'Error!',
                                ''+msg.errorMsg+'.',
                                'error'
                              )
                        } else {
                            Swal.fire(
                                'Sukses!',
                                ''+msg.message+'.',
                                'success'
                              )
                            window.setTimeout(function(){
                              window.location.href="<?= base_url('admin/transaksipinjam')?>";
                            },1000);
                        }
                    },
                    error:function(msg)
                    {
                        console.log(msg);
                    }
                }); 
            }
        });
    });
    function backButton()
    {
        var pathparts = location.pathname.split('/');
        window.location = location.origin+'/'+pathparts[1].trim('/')+'/transaksipinjam';
    }
</script>