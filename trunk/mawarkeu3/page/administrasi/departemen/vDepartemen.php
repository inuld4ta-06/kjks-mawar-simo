<table id="admDepartemenMainGrid"></table>
<div id="admDepartemenMainGridToolbar" style="padding:5px;">
    cari <input id="admDepartemenMainGridToolbarSearchKey" onchange="admDepartemen_searchDepartemen(this.value);">
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" onclick="admDepartemen_openAddnewDepartemen();">Tambah Departemen</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" onclick="admDepartemen_openEditDepartemen();">Edit</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" onclick="admDepartemen_doDeleteDepartemen();">Hapus Departemen</a>
</div>
<div id="admDepartemen_dialogAddEdit" class="easyui-dialog" closed="true" modal="true"
     style="padding: 10px;height:120px;"
     buttons="#admDepartemen_dialogAddEdit_bt">
    <input type="hidden" id="admDepartemen_dialog_inputDepartemenId">
    Departemen <input id="admDepartemen_dialog_inputDepartemen"><br>
    <div id="admDepartemen_dialogAddEdit_bt">
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" onclick="admDepartemen_doAddEditDepartemen();"></a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#admDepartemen_dialogAddEdit').dialog('close');"></a>
    </div>
    <input type="hidden" id="admDepartemen_dialog_inputOldDepartemen">
</div>

<script>
    $('#admDepartemenMainGrid').datagrid({
        columns: [[
                {field: 'md_id', title: 'ID', width: 100},
                {field: 'md_nama', title: 'Departemen', width: 100}
            ]],
        toolbar: '#admDepartemenMainGridToolbar',
        pagination: true, rownumbers: true, fit: true,
        url: '<?= createUrl() ?>&act=data',
        singleSelect: true
    });
    
    function admDepartemen_searchDepartemen(key){
        $('#admDepartemenMainGrid').datagrid({
            queryParams:{keySearch:key},
            pageNumber:1
        });
    }

    var url;

    function admDepartemen_openAddnewDepartemen() {
        $('#admDepartemen_dialog_inputDepartemenId').val('');
        $('#admDepartemen_dialog_inputDepartemen').val('');
        $('#admDepartemen_dialogAddEdit').dialog('open').dialog('setTitle', 'Tambah Departemen Baru');
        $('#admDepartemen_dialog_inputDepartemen').focus();
        url = '<?= createUrl() ?>&act=addNewDepartemen';
    }
    
    function admDepartemen_openEditDepartemen(){
        var g = $('#admDepartemenMainGrid').datagrid('getSelected');
        if(g){
            url = '<?= createUrl() ?>&act=editDepartemen';
            $('#admDepartemen_dialogAddEdit').dialog('open').dialog('setTitle', 'Edit Departemen');
            $('#admDepartemen_dialog_inputDepartemenId').val(g.md_id);
            $('#admDepartemen_dialog_inputDepartemen').val(g.md_nama);
        } else {
            alert('Pilih dulu salah satu departemen yang akan di-edit.');
        }
    }
    
    function admDepartemen_doAddEditDepartemen(){
        var departemen = $('#admDepartemen_dialog_inputDepartemen').val();
        var departemenid = $('#admDepartemen_dialog_inputDepartemenId').val();
        if (departemen == '') {
            alert('Departemen harus diisi');
            $('#admDepartemen_dialog_inputDepartemen').focus();
            return false;
        }
        $.post(
            url,
            {
                departemen:departemen, 
                departemenid:departemenid 
            }, 
            function(result){
                if(result.success){
                    $('#admDepartemen_dialogAddEdit').dialog('close');
                    $('#admDepartemenMainGrid').datagrid('reload');
                } else {
                    alert(result.msg);;
                }
            },
            'json'
        );
    }
        
    function admDepartemen_doDeleteDepartemen(){
        var g = $('#admDepartemenMainGrid').datagrid('getSelected');
        if(g){
            $.post(
                '<?= createUrl() ?>&act=deleteDepartemen',
                {departemenid:g.md_id}, 
                function(result){
                    if(result.success){
                        $('#admDepartemen_dialogAddEdit').dialog('close');
                        $('#admDepartemenMainGrid').datagrid('reload');
                    } else {
                        alert(result.msg);;
                    }
                },
                'json'
            );
        }
    }
        
</script>
