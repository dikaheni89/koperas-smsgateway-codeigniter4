<div class="col-12">
    <div class="ibox">
        <div class="ibox-head">
            <div class="ibox-title"><?= $title;?> (<?= $anggota['nm_anggota']; ?>)</div>
            <div class="ibox-tools">
                <a class="ibox-collapse"><i class="fa fa-minus"></i></a>
            </div>
        </div>
        <div class="ibox-body">
            <form method="post" id="form-anggota" action="javascript:;" novalidate="novalidate">
                <input type="hidden" name="id" value="<?= $anggota['idanggota']; ?>">
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Nominal Pinjaman</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="number" name="nominal_pinjam" id="nominal_pinjam" placeholder="Nominal Pinjaman" autocomplete="off">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Jumlah Angsuran</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="number" name="jml_angsuran" id="jml_angsuran" placeholder="Jumlah Angsuran" autocomplete="off">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Persentase Bunga</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="number" name="jml_bunga" id="jml_bunga" placeholder="Jumlah Bunga" autocomplete="off">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Angsuran yang di Bayarkan</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="number" name="nominal_angsuran" id="nominal_angsuran" placeholder="Angsuran yang di Bayarkan" autocomplete="off">
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
                nominal_pinjam:{
                    required: !0
                },
                jml_angsuran:{
                    required: !0,
                },
                jml_bunga:{
                    required: !0,
                },
                nominal_angsuran:{
                    required: !0,
                },
            },
            messages:{
                nominal_pinjam:{
                    required:"Nominal Pinjaman Harus Diisi",
                },
                jml_angsuran:{
                    required:"Jumlah Angsuran Harus Diisi",
                },
                jml_bunga:{
                    required: "Jumlah Bunga Harus Diisi",
                },
                nominal_angsuran:{
                    required: "Nominal Angsuran Harus Diisi",
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
                var idanggota  = $('input[name=id]').val();
                var nominal_pinjam  = $('input[name=nominal_pinjam]').val();
                var jml_angsuran  = $('input[name=jml_angsuran]').val();
                var jml_bunga = $('input[name=jml_bunga]').val();
                var nominal_angsuran = $('input[name=nominal_angsuran]').val();
                $.ajax({
                    type: "POST",
                    url : "<?= base_url('admin/savepengajuan')?>",
                    data: {<?= csrf_token() ?>:'<?= csrf_hash() ?>', idanggota:idanggota, nominal_pinjam:nominal_pinjam, jml_angsuran:jml_angsuran, jml_bunga:jml_bunga, nominal_angsuran:nominal_angsuran},
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
                              window.location.href="<?= base_url('admin/pengajuan')?>";
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

    $(function() {
        $("#tgl_masuk").datepicker({ 
            format: "yyyy-mm-dd",
            autoclose:true
        });
    });

    function backButton()
    {
        var pathparts = location.pathname.split('/');
        window.location = location.origin+'/'+pathparts[1].trim('/')+'/pengajuan';
    }
</script>