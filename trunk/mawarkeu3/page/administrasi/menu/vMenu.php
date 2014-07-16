<table id="admMenuMainGrid"></table>
<div id="admMenuMainGridToolbar" style="padding:5px;">
    cari <input id="admMenuMainGridToolbarSearchKey">
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" onclick="admMenu_openAddnewMenu();">Tambah Menu</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" onclick="admMenu_openEditMenu();">Edit Menu</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" onclick="admMenu_removeMenu();">Hapus Menu</a>
</div>
<div id="admMenu_dialogAddEdit" style="padding:20px;">
    <style>
        .admMenu_dialogAddEdit_inputs {
            display: inline-block;
            width: 50px;
            padding-bottom: 10px;
        }
    </style>
    <span class="admMenu_dialogAddEdit_inputs">Lokasi</span><input id="admMenu_dialog_inputLokasi"><br>
    <span class="admMenu_dialogAddEdit_inputs">Nama</span><input id="admMenu_dialog_inputNama"><br>
    <span class="admMenu_dialogAddEdit_inputs">Induk</span><input id="admMenu_dialog_inputInduk"><br>
    <span class="admMenu_dialogAddEdit_inputs">Type</span><input id="admMenu_dialog_inputType"><br>
    <span class="admMenu_dialogAddEdit_inputs">Order</span><input id="admMenu_dialog_inputOrder"><br>
    <span class="admMenu_dialogAddEdit_inputs">Aktif?</span><input id="admMenu_dialog_inputAktif" type="checkbox">
    <input type="hidden" id="admMenu_dialog_inputOldLokasi">
</div>
<div id="admMenu_dialogAddRole" style="padding: 20px">
    <input id="admMenu_dialogAddRole_inputRole">
    <input type="hidden" id="admMenu_dialogAddRole_indexDgrid">
    <input type="hidden" id="admMenu_dialogAddRole_menuID">
</div>

<script>
    $('#admMenu_dialogAddEdit').dialog({
        closed:true, modal:true, width:300, height:280,
        buttons:[
            {
                iconCls:'icon-ok',
                handler:function(){
                    admMenu_doAddEditMenu();
                }
            },
            {
                iconCls:'icon-cancel',
                handler:function(){
                    $('#admMenu_dialogAddEdit').dialog('close');
                }
            }
        ]
    });
    
    $('#admMenu_dialogAddRole').dialog({
        closed:true, modal:true, width:250, height:130,
        buttons:[
            {
                text:'Tambah',
                iconCls:'icon-ok', 
                handler:function(){
                    admMenu_doAddRole();
                }
            }
        ]
    });
    
    $('#admMenuMainGrid').datagrid({
        columns: [[
                {field: 'menu_id', title: 'ID', width: 100},
                {field: 'path', title: 'Lokasi', width: 300},
                {field: 'name', title: 'Nama', width: 350},
                {field: 'created_date', title: 'Dibuat pada', width: 150},
                {field: 'parent_id', title: 'Induk', width: 100},
                {field: 'type', title: 'Tipe', width: 120},
                {field: 'order', title: 'Order', width: 50},
                {field: 'enabled', title: 'Aktif', width: 50}
            ]],
        toolbar: '#admMenuMainGridToolbar',
        pagination: true, rownumbers: true, fit: true, pageSize:40,
        url: '<?= createUrl() ?>&act=data',
        view: detailview, singleSelect: true,
        detailFormatter: function(index, row) {
            return '<div id="admMenuMainGrid_detailgrid_' + index + '" style="padding:5px 0"></div>';
        },
        onExpandRow: function(index, row) {
            $('#admMenuMainGrid_detailgrid_' + index).panel({
                border: false,
                cache: false,
                href: '<?= createUrl() ?>&act=detailgrid&menuID=' + row.menu_id + '&index=' + index,
                onLoad: function() {
                    $('#admMenuMainGrid').datagrid('fixDetailRowHeight', index);
                }
            });
            $('#admMenuMainGrid').datagrid('fixDetailRowHeight', index);
        }
    });

    $('#admMenu_dialog_inputType').combobox({
        textField: 'value', valueField: 'value', panelHeight: 'auto',
        data: [
            {'value': 'MENU_MENU', 'selected': true},
            {'value': 'MENU_HEADER'}
        ]
    });
    
    $('#admMenu_dialogAddRole_inputRole').combogrid({
        textField: 'role_id', idField: 'role_id', panelWidth: 500,
        url:'<?= createUrl() ?>&act=comboListRole',
        columns:[[
            {field:'role_id', title:'Role', width:100},
            {field:'role_desc', title:'Deskripsi', width:450}
        ]]
    });
    
    $('#admMenu_dialog_inputInduk').combobox({
        url:'<?= createUrl() ?>&act=comboListParentMenu',
        textField:'name', valueField:'menu_id', panelHeight:250
    });
    
    var url;

    function admMenu_openAddnewMenu() {
        $('#admMenu_dialog_inputLokasi').val();
        $('#admMenu_dialog_inputNama').val();
        $('#admMenu_dialog_inputInduk').combobox('clear');
        $('#admMenu_dialog_inputType').combobox('clear');
        $('#admMenu_dialog_inputOrder').val();
        $('#admMenu_dialog_inputAktif').attr("checked", "true");
        $('#admMenu_dialogAddEdit').dialog('open').dialog('setTitle', 'Tambah Menu Baru');
        url = '<?= createUrl() ?>&act=addNewMenu';
    }
    
    function admMenu_openEditMenu(){
        var g = $('#admMenuMainGrid').datagrid('getSelected');
        if(g){
            url = '<?= createUrl() ?>&act=editMenu';
            $('#admMenu_dialogAddEdit').dialog('open').dialog('setTitle', 'Edit Menu');
            $('#admMenu_dialog_inputLokasi').val(g.path);
            $('#admMenu_dialog_inputOldLokasi').val(g.path);
            $('#admMenu_dialog_inputNama').val(g.name);
            $('#admMenu_dialog_inputInduk').combobox('setValue', g.parent_id);
            $('#admMenu_dialog_inputType').combobox('setValue', g.type);
            $('#admMenu_dialog_inputOrder').val(g.order);
            if(g.enabled == 1){
                $('#admMenu_dialog_inputAktif').attr("checked", true);
            } else {
                $('#admMenu_dialog_inputAktif').attr("checked", false);
            }
        } else {
            alert('Pilih dulu salah satu menu yang akan di-edit.');
        }
    }
    
    function admMenu_doAddEditMenu(){
        var path = $('#admMenu_dialog_inputLokasi').val();
        var oldpath = $('#admMenu_dialog_inputOldLokasi').val();
        var name = $('#admMenu_dialog_inputNama').val();
        var prid = $('#admMenu_dialog_inputInduk').combobox('getValue');
        var type = $('#admMenu_dialog_inputType').combobox('getValue');
        var ordr = $('#admMenu_dialog_inputOrder').val();
        var enab;
        if($('#admMenu_dialog_inputAktif').is(':checked')){
            enab = 1;
        } else {
            enab = 0;
        }
        if (path == '') {
            alert('Lokasi harus diisi');
        } else if (name == '') {
            alert('Nama Menu harus diisi');
        } else if (prid == '') {
            alert('Induk Menu harus diisi');
        }
        $.post(
            url,
            {pathx:path, oldpathx:oldpath, namex:name, pridx:prid, typex:type, ordrx:ordr, enabx:enab}, 
            function(result){
                if(result.success){
                    $('#admMenu_dialogAddEdit').dialog('close');
                    $('#admMenuMainGrid').datagrid('reload');
                } else {
                    alert(result.msg);;
                }
            },
            'json'
        );
    }
    
    function admMenu_removeMenu() {
        var g = $('#admMenuMainGrid').datagrid('getSelected');
        if(g){
            $.post(
                '<?= createUrl() ?>&act=deleteMenu',
                {menuid:g.menu_id}, 
                function(result){
                    if(result.success){
                        $('#admMenuMainGrid').datagrid('reload');
                    } else {
                        alert(result.msg);;
                    }
                },
                'json'
            );
        } else {
            alert('Pilih dulu salah satu menu yang akan dihapus.');
        }
    }
    
    function admMenu_openAddRole(menuid, indexDgrid){
        $('#admMenu_dialogAddRole').dialog('open').dialog('setTitle', 'Tambah Role');
        url = '<?= createUrl() ?>&act=addRole';
        $('#admMenu_dialogAddRole_indexDgrid').val(indexDgrid);
        $('#admMenu_dialogAddRole_menuID').val(menuid);
    }
    
    function admMenu_doAddRole(){
        var indexGrid = $('#admMenu_dialogAddRole_indexDgrid').val();
        var menuID = $('#admMenu_dialogAddRole_menuID').val();
        var roleID = $('#admMenu_dialogAddRole_inputRole').combogrid('getValue');
        $.post(
            url,
            {menuid:menuID, roleid: roleID, indexgrid: indexGrid},
            function(result){
                if(result.success){
                    $('#admMenu_dialogAddRole').dialog('close');
                    $('#admMenuMainGrid_detailgrid_' + indexGrid).panel({
                        border: false,
                        cache: false,
                        href: '<?= createUrl() ?>&act=detailgrid&menuID=' + menuID + '&index=' + indexGrid,
                        onLoad: function() {
                            $('#admMenuMainGrid').datagrid('fixDetailRowHeight', indexGrid);
                        }
                    });
                } else {
                    alert('Role sudah pernah ditambahkan.');
                }
            },
            'json'
        );
    }
    
    function admMenu_delRole(menuID, roleID, indexGrid){
        $.post(
            '<?= createUrl() ?>&act=delrole',
            {menuid: menuID, roleid: roleID},
            function(result){
                if(result.success){
                    $('#admMenuMainGrid_detailgrid_' + indexGrid).panel({
                        border: false,
                        cache: false,
                        href: '<?= createUrl() ?>&act=detailgrid&menuID=' + menuID + '&index=' + indexGrid,
                        onLoad: function() {
                            $('#admMenuMainGrid').datagrid('fixDetailRowHeight', indexGrid);
                        }
                    });
                } else {
                    alert('Terjadi kesalahan.');
                }
            },
            'json'
        );
    }
    
</script>
