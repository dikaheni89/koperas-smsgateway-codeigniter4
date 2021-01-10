<div class="col-12">
  <div class="ibox">
      <div class="ibox-head">
          <div class="ibox-title"><?= $title; ?></div>
      </div>
      <div class="ibox-body">
        <table id="dgGrid"
          style="min-height: 1020px;"
            toolbar="#toolbar" 
            class="easyui-datagrid" 
            rowNumbers="true" 
            pagination="true" 
            url="<?= base_url('admin/getlaporanangsuran') ?>" 
            pageSize="15" 
            pageList="[10,15,20,50,75,100,125,150,200]" 
            nowrap="false" 
            singleSelect="true"
            data-options="
            collapsible:true,
            view:groupview,
            groupField:'tgl_angsuran',
            groupFormatter:function(value,rows){
                return value + ' - ' + rows.length + '(s)';
            }">
              <thead>
                  <tr>
                      <th field="nm_anggota" width="40%">Nama Anggota</th>
                      <th field="jml_pinjaman" width="20%">Jumlah Pinjaman</th>
                      <th field="jml_bayar" width="20%">Jumlah Bayar</th>
                      <th field="sisa_jml_bayar" width="20%">Sisa Pinjaman</th>
                  </tr>
              </thead>
          </table>
          
        <div id="toolbar" style="padding: 10px">
            <div class="row">
                <div class="col-sm-3">
                    <div id="filter_tgl" class="input-group" style="display: inline;">
                        <button class="btn btn-default" id="daterange-btn" style="line-height:16px;border:1px solid #ccc">
                            <i class="fa fa-calendar"></i> <span id="reportrange"><span> Pilih Tanggal</span></span>
                            <i class="fa fa-caret-down"></i>
                        </button>
                    </div>
                </div>
                <div class="col-sm-2">
                    <a href="javascript:void(0);" class="btn btn-info" onclick="doPrint('<?= base_url(); ?>/admin/laporanangsuranpdf')"><i class="fas fa-eye"></i> Lihat Total Pinjaman</a>
                </div>
            </div>
        </div>
      </div>
  </div>
</div>
<script type="text/javascript">
  $(document).ready(function (){
     fm_filter_tgl();
  });
</script>