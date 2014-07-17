<table id="admUser_maingrid"></table>
<div id="admUser_MainGridToolbar" style="padding: 10px;">
    cari <input id="admUser_searchUsername" onchange="admUser_doSearchKey(this.value);">
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" onclick="admUser_openNewAddUser();">Tambah User</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" onclick="admUser_openeditUser();">Edit User</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" onclick="admUser_removeUser();">Hapus User</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-reload" onclick="admUser_resetPasswdUser();">Reset Password User</a>
</div>
<div id="admUser_dialogAddEdit" class="easyui-dialog" closed="true" modal="true"
     style="padding: 20px;width: 400px;height: 230px;"
     buttons="#admUser_dialogAddEdit_bt">
    <input type="hidden" id="admUser_dialog_inputUserid">
    <table width="100%">
        <tr id="admUser_dialogAddEdit_trUsername">
            <td>Username</td><td><input id="admUser_dialog_inputUsername" title="(hanya huruf dan angka, 2 sampai 64 karakter)"></td>
        </tr>
        <tr id="admUser_dialogAddEdit_trEmail">
            <td>Email</td><td><input id="admUser_dialog_inputEmail"></td>
        </tr>
        <tr id="admUser_dialogAddEdit_trDepartemen">
            <td>Departemen</td><td><input id="admUser_dialog_inputDepartemen"></td>
        </tr>
        <tr id="admUser_dialogAddEdit_trPasswordNew">
            <td>Password</td><td><input id="admUser_dialog_inputPasswordNew" title="(min. 6 karakter)"></td>
        </tr>
        <tr id="admUser_dialogAddEdit_trPasswordRepeat">
            <td>Ulangi Password</td><td><input id="admUser_dialog_inputPasswordRepeat"></td>
        </tr>
    </table>
    <div id="admUser_dialogAddEdit_bt">
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" onclick="admUser_doSaveUser();"></a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#admUser_dialogAddEdit').dialog('close');"></a>
    </div>
    <input type="hidden" id="admMenu_dialog_inputOldLokasi">
</div>
<div id="admUser_dialogAddRole" class="easyui-dialog" closed="true" modal="true"
     style="padding: 20px; width: 330px; height: 100px;" title="Tambah Role">
    <input id="admUser_dialogAddRole_inputRole">
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" onclick="admUser_doAddRole()">Tambah</a>
</div>
<script>
    $('#admUser_maingrid').datagrid({
        columns: [[
                {field: 'user_name', title: 'Username', width: 150},
                {field: 'user_password_hash', title: 'Password Hash', width: 500},
                {field: 'user_email', title: 'Email', width: 350},
                {field: 'departemen', title: 'Departemen', width: 100},
                {field: 'locked', title: 'Locked?', width: 150}
            ]],
        toolbar: '#admUser_MainGridToolbar', fit: true,
        pagination: true, rownumbers: true, height: 400, pageSize: 40,
        url: '<?= createUrl() ?>&act=data',
        view: detailview, singleSelect: true,
        detailFormatter: function(index, row) {
            return '<div id="admUser_MainGrid_detailgrid_' + index + '" style="padding:5px 0"></div>';
        },
        onExpandRow: function(index, row) {
            $('#admUser_MainGrid_detailgrid_' + index).panel({
                border: false,
                cache: false,
                href: '<?= createUrl() ?>&act=detailgrid&username=' + row.user_name + '&index=' + index,
                onLoad: function() {
                    $('#admUser_maingrid').datagrid('fixDetailRowHeight', index);
                }
            });
            $('#admUser_maingrid').datagrid('fixDetailRowHeight', index);
        }
    });

    $('#admUser_dialogAddRole_inputRole').combogrid({
        url: '<?= createUrl() ?>&act=comboRole',
        textField: 'role_id', idField: 'role_id', panelHeight: 250, panelWidth: 400,
        columns: [[
                {field: 'role_id', titlw: 'Role Id', width: 150},
                {field: 'role_desc', titlw: 'Deskripsi', width: 300}
            ]]
    });
    
    $('#admUser_dialog_inputDepartemen').combobox({
        url:'<?= createUrl() ?>&act=comboDepartemen',
        valueField:'md_nama', textField:'md_nama', panelHeight:'auto'
    });

    function admUser_doSearchKey(key) {
        $('#admUser_maingrid').datagrid({
            queryParams: {key: key},
            pageNumber: 1
        });
    }

    var url;

    function admUser_openNewAddUser() {
        $('#admUser_dialog_inputUserid').val('');
        $('#admUser_dialog_inputUsername').val('');
        $('#admUser_dialog_inputEmail').val('');
        $('#admUser_dialog_inputDepartemen').combobox('clear');
        $('#admUser_dialog_inputPasswordNew').val('');
        $('#admUser_dialog_inputPasswordRepeat').val('');
        $('#admUser_dialogAddEdit').dialog('open').dialog('setTitle', 'Tambah User');
        $('#admUser_dialogAddEdit_trUsername').show();
        $('#admUser_dialogAddEdit_trEmail').show();
        $('#admUser_dialogAddEdit_trDepartemen').show();
        $('#admUser_dialogAddEdit_trPasswordNew').show();
        $('#admUser_dialogAddEdit_trPasswordRepeat').show();
        url = "<?= createUrl() ?>&act=addNewUser";
    }

    function admUser_openeditUser() {
        var g = $('#admUser_maingrid').datagrid('getSelected');
        if(g){
            $('#admUser_dialogAddEdit_trUsername').show();
            $('#admUser_dialogAddEdit_trEmail').show();
            $('#admUser_dialogAddEdit_trDepartemen').show();
            $('#admUser_dialogAddEdit_trPasswordNew').hide();
            $('#admUser_dialogAddEdit_trPasswordRepeat').hide();
            $('#admUser_dialog_inputUserid').val(g.user_id);
            $('#admUser_dialog_inputUsername').val(g.user_name);
            $('#admUser_dialog_inputEmail').val(g.user_email);
            $('#admUser_dialog_inputDepartemen').combobox('setValue',g.departemen);
            $('#admUser_dialogAddEdit').dialog('open').dialog('setTitle', 'Edit User');
            url = "<?= createUrl() ?>&act=editUser";
        } else {
            alert('Silakan pilih dulu salah satu user yg akan diedit');
        }
    }
    
    function admUser_resetPasswdUser(){
        var g = $('#admUser_maingrid').datagrid('getSelected');
        if(g){
            $('#admUser_dialogAddEdit_trUsername').hide();
            $('#admUser_dialogAddEdit_trEmail').hide();
            $('#admUser_dialogAddEdit_trDepartemen').hide();
            $('#admUser_dialogAddEdit_trPasswordNew').show();
            $('#admUser_dialogAddEdit_trPasswordRepeat').show();
            $('#admUser_dialog_inputUserid').val(g.user_id);
            $('#admUser_dialog_inputPasswordNew').val('');
            $('#admUser_dialog_inputPasswordRepeat').val('');
            $('#admUser_dialogAddEdit').dialog('open').dialog('setTitle', 'Reset Password');
            url = "<?= createUrl() ?>&act=resetPassword";
        } else {
            alert('Silakan pilih dulu salah satu user yg akan di-reset password-nya');
        }
    }
    
    function admUser_doSaveUser(){
        var userid = $('#admUser_dialog_inputUserid').val();
        var username = $('#admUser_dialog_inputUsername').val();
        var email = $('#admUser_dialog_inputEmail').val();
        var password = $('#admUser_dialog_inputPasswordNew').val();
        var repassword = $('#admUser_dialog_inputPasswordRepeat').val();
        var dept = $('#admUser_dialog_inputDepartemen').combobox('getValue');
        $.post(
            url,
            {userid:userid, user_name:username, user_email:email, dept:dept, user_password_new:password, user_password_repeat:repassword},
            function(result){
                if(result.success){
                    $('#admUser_dialogAddEdit').dialog('close');
                    $('#admUser_maingrid').datagrid('reload');
                } else {
                    alert(result.msg);
                }
            },
            'json'
        );
    }

    function admUser_removeUser() {
        var g = $('#admUser_maingrid').datagrid('getSelected');
        if (g) {
            $.messager.confirm('Konfirmasi', 'Apakah Anda yakin akan menghapus ' + g.user_name + '?', function(r) {
                if (r) {
                    $.post(
                        '<?= createUrl() ?>&act=delUser',
                        {user: g.user_name},
                        function(result){
                            if(result.success){
                                $.messager.show({
                                    title:'sukses',
                                    msg:result.msg
                                });
                                $('#admUser_maingrid').datagrid('reload');
                            } else {
                                alert(result.msg);
                            }
                        },
                        'json'
                    );
                }
            });
        } else {
            alert('silakan pilih dulu salah satu user yg ingin dihapus.');
        }
    }

    function admUser_openAddRole(index, user) {
        url = "<?= createUrl() ?>&act=addRole&index=" + index + "&user=" + user;
        $('#admUser_dialogAddRole').dialog('open');
    }

    function admUser_doAddRole() {
        var role = $('#admUser_dialogAddRole_inputRole').combobox('getValue');
        $.post(
            url,
            {role: role},
            function(result) {
                if (result.success) {
                    $('#admUser_dialogAddRole').dialog('close');
                    $('#admUser_MainGrid_detailgrid_' + result.index).panel({
                        border: false,
                        cache: false,
                        href: '<?= createUrl() ?>&act=detailgrid&username=' + result.user_name + '&index=' + result.index,
                        onLoad: function() {
                            $('#admUser_maingrid').datagrid('fixDetailRowHeight', result.index);
                        }
                    });
                } else {
                    alert(result.msg);
                }
            },
            'json'
        );
    }

    function admUser_doDeleteRoleFromUser(index, user, role) {
        $.post(
            '<?= createUrl() ?>&act=doDelRole',
            {user: user, role: role},
            function(result) {
                if (result.success) {
                    $('#admUser_MainGrid_detailgrid_' + index).panel({
                        border: false,
                        cache: false,
                        href: '<?= createUrl() ?>&act=detailgrid&username=' + user + '&index=' + index,
                        onLoad: function() {
                            $('#admUser_maingrid').datagrid('fixDetailRowHeight', index);
                        }
                    });
                } else {
                    alert(result.msg);
                }
            },
            'json'
        );
    }
    
</script>