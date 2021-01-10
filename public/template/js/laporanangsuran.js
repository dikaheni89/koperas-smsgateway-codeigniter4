function fm_filter_tgl() {
    $('#daterange-btn').daterangepicker({
		ranges: {
			'Hari ini': [moment(), moment()],
			'Kemarin': [moment().subtract('days', 1), moment().subtract('days', 1)],
			'7 Hari yang lalu': [moment().subtract('days', 6), moment()],
			'30 Hari yang lalu': [moment().subtract('days', 29), moment()],
			'Bulan ini': [moment().startOf('month'), moment().endOf('month')],
			'Bulan kemarin': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')],
			'Tahun ini': [moment().startOf('year').startOf('month'), moment().endOf('year').endOf('month')],
			'Tahun kemarin': [moment().subtract('year', 1).startOf('year').startOf('month'), moment().subtract('year', 1).endOf('year').endOf('month')]
		},
		showDropdowns: true,
		format: 'YYYY-MM-DD',
		startDate: moment().startOf('year').startOf('month'),
		endDate: moment().endOf('year').endOf('month')
	},

	function(start, end) {
		$('#reportrange span').html(start.format('D MMM YYYY') + ' - ' + end.format('D MMM YYYY'));
		doSearch();
	});
}

function doSearch(){
	$('#dgGrid').datagrid('load',{
		start: 	$('input[name=daterangepicker_start]').val(),
		end: $('input[name=daterangepicker_end]').val()
    });
}

function doPrint(url) {
	var start = $('input[name=daterangepicker_start]').val();
	var end= $('input[name=daterangepicker_end]').val();
	$.ajax({
		async:false,
		type:'GET',
		url:'laporanangsuranpdf',
		// dataType:'json',
		data:{start:start, end:end},
		success: function (msg){
			var link =url+"?start="+start+"&end="+end
			var win = window.open(link, '_blank');
			if (win){
				win.focus();
			}else{
				alert('Mohon untuk allow popups untuk aplikasi ini');
			}
		},
	});
}

function formatDate(value,row){
    var d = new Date(value);
    return $.fn.datebox.defaults.formatter(d);
}

