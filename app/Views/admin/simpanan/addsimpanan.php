<div class="col-12">
    <div class="ibox">
        <div class="ibox-head">
            <div class="ibox-title"><?= $title;?></div>
            <div class="ibox-tools">
                <a class="ibox-collapse"><i class="fa fa-minus"></i></a>
            </div>
        </div>
        <div class="ibox-body">
            <form method="post" id="form-anggota" action="javascript:;" enctype="multipart/form-data" novalidate="novalidate">
                <input type="hidden" name="idanggota" id="idanggota" value="<?= $simpanan['idanggota']; ?>">
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Nama Anggota</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" value="<?= $simpanan['nm_anggota']; ?>" readonly="readonly">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Bayar Simpanan Pokok</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="number" name="pokok" id="pokok" placeholder="Simpanan Pokok" autocomplete="off">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Bayar Simpanan Wajib</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="number" name="wajib" id="wajib" placeholder="Simpanan wajib" autocomplete="off">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Bayar Simpanan Sukarela</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="number" name="sukarela" id="sukarela" placeholder="Simpanan Sukarela" autocomplete="off">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Status Pembayaran</label>
                    <div class="col-sm-4">
                        <label class="ui-radio ui-radio-inline">
                            <input type="radio" name="status_simpanan" value="1" checked="">
                        <span class="input-span"></span>Lunas</label>
                        <label class="ui-radio ui-radio-inline">
                            <input type="radio" name="status_simpanan" value="0">
                        <span class="input-span"></span>Belum Lunas</label>
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
        $("#select2_simpanan").select2({
            placeholder: "Select a Simpanan Anggota",
            allowClear: true
        });    
        $("#form-anggota").validate({
            rules: {
                wajib:{
                    required: !0
                },
            },
            messages:{
                wajib:{
                    required:"Simpanan Wajib Harus Diisi"
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
                var idanggota  = $('input[name=idanggota]').val();
                var pokok  = $('input[name=pokok]').val();
                var wajib  = $('input[name=wajib]').val();
                var sukarela  = $('input[name=sukarela]').val();
                var status = $('input[name=status_simpanan]:checked').val();
                var form = new FormData();
                form.append('idanggota',idanggota);
                form.append('pokok',pokok);
                form.append('wajib',wajib);
                form.append('sukarela',sukarela);
                form.append('status_simpanan',status);
                $.ajax({
                    type: "POST",
                    url : "<?= base_url('admin/savesimpanan')?>",
                    async:false,
                    enctype: 'multipart/form-data',
                    processData: false,
                    contentType: false,
                    data: form,
                    headers: {
                       'X-CSRF-TOKEN': '<?= csrf_hash() ?>' 
                    },
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
                              window.location.href="<?= base_url('admin/simpanan')?>";
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
        window.location = location.origin+'/'+pathparts[1].trim('/')+'/simpanan';
    }
</script>