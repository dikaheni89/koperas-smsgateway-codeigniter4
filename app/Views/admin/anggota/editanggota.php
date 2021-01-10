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
                <input type="hidden" name="id" value="<?= $anggota['idanggota']; ?>">
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Nomor Identitas (KTP, SIM, PASPORT)</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="number" name="no_identitas" id="no_identitas" value="<?= $anggota['no_identitas']; ?>" placeholder="Nomor Identitas (KTP, SIM, PASPORT)" autocomplete="off">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Nama Lengkap</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="nm_anggota" id="nm_anggota" value="<?= $anggota['nm_anggota']; ?>" placeholder="Nama Anggota" autocomplete="off">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Jenis Kelamin</label>
                    <div class="col-sm-4">
                        <label class="ui-radio ui-radio-inline">
                            <input type="radio" name="jk" value="Pria" checked="">
                        <span class="input-span"></span>Pria</label>
                        <label class="ui-radio ui-radio-inline">
                            <input type="radio" name="jk" value="Perempuan">
                        <span class="input-span"></span>Perempuan</label>
                      </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Alamat Lengkap</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="alamat" id="alamat" value="<?= $anggota['alamat']; ?>" placeholder="Alamat Lengkap" autocomplete="off">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">No. Telp</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="number" name="no_telp" id="no_telp" value="<?= $anggota['no_telp']; ?>" placeholder="Nomor Telp" autocomplete="off">
                        <span class="span"></span>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Photo</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="file" name="photo" id="photo" placeholder="Cover Image">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Tanggal Menjadi Anggota</label>
                    <div class="col-sm-5">
                        <input class="form-control" type="text" name="tgl_masuk" value="<?= $anggota['tgl_masuk']; ?>" placeholder="Tanggal Menjadi Anggota" id="tgl_masuk" autocomplete="off">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Status</label>
                    <div class="col-sm-4">
                        <label class="ui-radio ui-radio-inline">
                            <input type="radio" name="status_anggota" value="1" checked="">
                        <span class="input-span"></span>Active</label>
                        <label class="ui-radio ui-radio-inline">
                            <input type="radio" name="status_anggota" value="0">
                        <span class="input-span"></span>Non Active</label>
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
                no_identitas:{
                    required: !0,
                    minlength: 4
                },
                nm_anggota:{
                    required: !0,
                    minlength: 4
                },
                jk:{
                    required: !0,
                },
                no_telp:{
                    required: !0,
                    minlength: 9
                },
                tgl_masuk:{
                    required: !0,
                },
            },
            messages:{
                no_identitas:{
                    required:"Nomor Identitas Harus Diisi",
                    minlength:"Username Minimal 4 Karakter"
                },
                nm_anggota:{
                    required:"Nama Anggota Harus Diisi",
                    minlength:"Nama Anggota Minimal 4 Karakter"
                },
                jk:{
                    required: "Jenis Kelamin Harus Diisi",
                },
                no_telp:{
                    required: "Nomor Telp Harus Diisi",
                    minlength: "Nomor Telp Minimal 9 Angka"
                },
                tgl_masuk:{
                    required: "Tanggal Menjadi Anggota Harus Diisi",
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
                var id = $('input[name=id]').val();
                var no_identitas  = $('input[name=no_identitas]').val();
                var nm_anggota  = $('input[name=nm_anggota]').val();
                var jk = $('input[name=jk]:checked').val();
                var alamat = $('input[name=alamat]').val();
                var no_telp = $('input[name=no_telp]').val();
                var file_data = $('#photo').prop('files')[0];
                var tgl_masuk = $('input[name=tgl_masuk]').val();
                var status_anggota = $('input[name=status_anggota]:checked').val();
                var form = new FormData();
                form.append('id',id);
                form.append('no_identitas',no_identitas);
                form.append('nm_anggota',nm_anggota);
                form.append('jk',jk);
                form.append('alamat',alamat);
                form.append('no_telp',no_telp);
                form.append('photo',file_data);
                form.append('tgl_masuk',tgl_masuk);
                form.append('status_anggota',status_anggota);
                $.ajax({
                    type: "POST",
                    url : "<?= base_url('admin/updateanggota')?>",
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
                              window.location.href="<?= base_url('admin/anggota')?>";
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
        window.location = location.origin+'/'+pathparts[1].trim('/')+'/anggota';
    }
</script>