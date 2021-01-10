<div class="col-12">
<div class="ibox">
    <div class="ibox-head">
        <div class="ibox-title"><?= $title;?></div>
        <div class="ibox-tools">
            <a class="ibox-collapse"><i class="fa fa-minus"></i></a>
        </div>
    </div>
    <div class="ibox-body">
		<form method="post" id="form-validation" action="javascript:;" novalidate="novalidate">
            <input type="hidden" name="id" value="<?= $jenis['idjenis']; ?>">
			<div class="form-group row">
                <label class="col-sm-2 col-form-label">Jenis Simpanan</label>
                <div class="col-sm-6">
                    <input class="form-control" type="text" name="nm_simpanan" value="<?= $jenis['nm_simpanan']; ?>" placeholder="Jenis Simpanan" autocomplete="off">
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
        $("#form-validation").validate({
            rules: {
                nm_simpanan:{
                    required: !0
                },
            },
            messages:{
                nm_simpanan:{
                    required:"Jenis simpanan harus diisi"
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
                var nm_simpanan = $('input[name=nm_simpanan]').val();
                $.ajax({
                    type: "POST",
                    url : "<?= base_url('admin/updatejenissimpanan')?>",
                    data: {<?= csrf_token() ?>:'<?= csrf_hash() ?>',id:id, nm_simpanan:nm_simpanan},
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
                              window.location.href="<?= base_url('admin/jenissimpanan')?>";
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
        window.location = location.origin+'/'+pathparts[1].trim('/')+'/jenissimpanan';
    }
</script>