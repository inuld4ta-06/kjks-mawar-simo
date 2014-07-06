<table id="admRoleMainGrid"></table>
<div id="admRoleMainGridToolbar" style="padding:5px;">
    cari <input id="admRoleMainGridToolbarSearchKey" onchange="admRole_searchRole(this.value);">
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" onclick="admRole_openAddnewRole();">Tambah Role</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" onclick="admRole_openEditRole();">Edit Role</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" onclick="admRole_doDeleteRole();">Hapus Role</a>
</div>
<div id="admRole_dialogAddEdit" class="easyui-dialog" closed="true" modal="true"
     style="padding: 20px;width: 400px;height: 200px;"
     buttons="#admRole_dialogAddEdit_bt">
    <style>
        .admRole_dialogAddEdit_inputs {
            display: inline-block;
            width: 100px;
            padding-bottom: 10px;
        }
    </style>
    <input type="hidden" id="admRole_dialog_inputRoleOld">
    <span class="admRole_dialogAddEdit_inputs">Nama Role</span><input id="admRole_dialog_inputRole"><br>
    <span class="admRole_dialogAddEdit_inputs">Deskripsi</span><input id="admRole_dialog_inputDeskripsi"><br>
    <div id="admRole_dialogAddEdit_bt">
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" onclick="admRole_doAddEditRole();"></a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#admRole_dialogAddEdit').dialog('close');"></a>
    </div>
    <input type="hidden" id="admRole_dialog_inputOldRole">
</div>

<script>
    $('#admRoleMainGrid').datagrid({
        columns: [[
                {field: 'role_id', title: 'Role', width: 100},
                {field: 'role_desc', title: 'Deskripsi', width: 300}
            ]],
        toolbar: '#admRoleMainGridToolbar',
        pagination: true, rownumbers: true, height: 400,
        url: '<?= createUrl() ?>&act=data',
        singleSelect: true
    });
    
    function admRole_searchRole(key){
        $('#admRoleMainGrid').datagrid({
            queryParams:{keySearch:key},
            pageNumber:1
        });
    }

    var url;

    function admRole_openAddnewRole() {
        $('#admRole_dialog_inputRole').val();
        $('#admRole_dialog_inputDeskripsi').val();
        $('#admRole_dialogAddEdit').dialog('open').dialog('setTitle', 'Tambah Role Baru');
        url = '<?= createUrl() ?>&act=addNewRole';
    }
    
    function admRole_openEditRole(){
        var g = $('#admRoleMainGrid').datagrid('getSelected');
        if(g){
            url = '<?= createUrl() ?>&act=editRole';
            $('#admRole_dialogAddEdit').dialog('open').dialog('setTitle', 'Edit Role');
            $('#admRole_dialog_inputRole').val(g.role_id);
            $('#admRole_dialog_inputRoleOld').val(g.role_id);
            $('#admRole_dialog_inputDeskripsi').val(g.role_desc);
        } else {
            alert('Pilih dulu salah satu role yang akan di-edit.');
        }
    }
    
    function admRole_doAddEditRole(){
        var role = $('#admRole_dialog_inputRole').val();
        var oldrole = $('#admRole_dialog_inputRoleOld').val();
        var deskripsi = $('#admRole_dialog_inputDeskripsi').val();
        if (role == '') {
            alert('Nama Role harus diisi');
            return false;
        }
        if (deskripsi == '') {
            alert('Deskripsi harus diisi');
            return false;
        }
        $.post(
            url,
            {rolex:role, oldrolex:oldrole, deskripsix:deskripsi}, 
            function(result){
                if(result.success){
                    $('#admRole_dialogAddEdit').dialog('close');
                    $('#admRoleMainGrid').datagrid('reload');
                } else {
                    alert(result.msg);;
                }
            },
            'json'
        );
    }
        
    function admRole_doDeleteRole(){
        var g = $('#admRoleMainGrid').datagrid('getSelected');
        if(g){
            $.post(
                '<?= createUrl() ?>&act=deleteRole',
                {rolex:g.role_id}, 
                function(result){
                    if(result.success){
                        $('#admRole_dialogAddEdit').dialog('close');
                        $('#admRoleMainGrid').datagrid('reload');
                    } else {
                        alert(result.msg);;
                    }
                },
                'json'
            );
        }
    }
        
</script>
