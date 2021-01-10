function doSearchData(){
	$('#dgGrid').datagrid('load',{
		search_data: $('#search').val()
	});
}

function submitForm(){
	var string = $("#ff").serialize();
	$('#ff').form('submit',{
		url: url,
		onSubmit: function(){
			return $(this).form('validate');
		},
		success: function(result){
			console.log(result);
			var result = eval('('+result+')');
			if (result.errorMsg){
				Toast.fire({
	              type: 'error',
	              title: ''+result.errorMsg+'.'
	              })
			} else {
				Toast.fire({
                  type: 'success',
                  title: ''+result.message+'.'
                })
				$('#dialog-form').dialog('close');		// close the dialog
				$('#dgGrid').datagrid('reload');	// reload the user data
			}
		}
	});
}

function newForm(){
	var pathparts = location.pathname.split('/');
    window.location = location.origin+'/'+pathparts[1].trim('/')+'/newUser';
}

function editForm(){
	var row = $('#dgGrid').datagrid('getSelected');
		if (row){
			$('#dialog-form').dialog('open').dialog('setTitle','Edit Users' + row.username);
			$('#ff').form('load',row);
			$('#pass').passwordbox('setValue', '');
			url = 'updateUser?id='+row._id;
		}
}

function ActiveUsers(){
	var row = $('#dgGrid').datagrid('getSelected');
    if (row){
        $.messager.confirm('Confirm','Are you sure you want to Active or Non Aktive ? '+ row.full_name,function(r){
            if (r){
                $.post('activeUsers',{id:row._id,'is_aktif':row.is_aktif},function(result){
                    if (result.errorMsg){
                        Toast.fire({
			              type: 'error',
			              title: ''+result.errorMsg+'.'
			            })
                    } else {
                        Toast.fire({
		                  type: 'success',
		                  title: ''+result.message+'.'
		                })
                        $('#dgGrid').datagrid('reload');
                    }
                },'json');
            }
        });
    }
}

function destroy(){
    var row = $('#dgGrid').datagrid('getSelected');
    if (row){
        $.messager.confirm('Confirm','Are you sure you want to destroy this Users ? '+ row.username,function(r){
            if (r){
                $.post('destroyuser',{id:row._id},function(result){
                    if (result.errorMsg){
                        Toast.fire({
			              type: 'error',
			              title: ''+result.errorMsg+'.'
			            })
                    } else {
                        Toast.fire({
		                  type: 'success',
		                  title: ''+result.message+'.'
		                })
                        $('#dgGrid').datagrid('reload');
                    }
                },'json');
            }
        });
    }else{
		Toast.fire({
			type: 'error',
			title: 'Please Select data.'
		});
	}
}

function formatDetailactive(index, row){
	if (row.is_aktif == 1){
		return '<span class="l-btn-left"><span class="l-btn-text text text-success"><i class="ti-face-smile"></i> Active</span></span>';
	}else{
		return '<span class="l-btn-left"><span class="l-btn-text text text-danger"><i class="ti-face-sad"></i> Non Active</span></span>';
	}
}

function formatAvatars(index,row){
	if (row.photo == '' || row.photo == null){
		return '<a href="#" class="pop" data-backdrop="static" onClick="zoomImage()"><img src="http://localhost:8080/uploads/avatars/profil.jpg" width="25"></a>';		
	}else{
		return '<a href="#" class="pop" data-backdrop="static" onClick="zoomImage()"><img src="http://localhost:8080/uploads/avatars/'+row.photo+'" width="25"></a>';
	}
}