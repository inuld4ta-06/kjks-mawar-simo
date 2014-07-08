<table id="setJenisPembayaranMainGrid"></table>
<div id="setJenisPembayaranMainGridToolbar" style="padding:5px;">
    cari <input id="setJenisPembayaranMainGridToolbarSearchKey" onchange="setJenisPembayaran_searchJenisPembayaran(this.value);">
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" onclick="setJenisPembayaran_openAddnewJenisPembayaran();">Tambah Jenis Pembayaran</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" onclick="setJenisPembayaran_openEditJenisPembayaran();">Edit Jenis Pembayaran</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" onclick="setJenisPembayaran_doDeleteJenisPembayaran();">Hapus Jenis Pembayaran</a>
</div>
<div id="setJenisPembayaran_dialogAddEdit" class="easyui-dialog" closed="true" modal="true"
     style="padding: 10px;height: 150px;"
     buttons="#setJenisPembayaran_dialogAddEdit_bt">
    <style>
        .setJenisPembayaran_dialogAddEdit_inputs {
            display: inline-block;
            width: 110px;
            padding-bottom: 10px;
        }
    </style>
    <input type="hidden" id="setJenisPembayaran_dialog_inputJenisPembayaranId">
    <span class="setJenisPembayaran_dialogAddEdit_inputs">Departemen</span><input id="setJenisPembayaran_dialog_inputDepartemen"><br>
    <span class="setJenisPembayaran_dialogAddEdit_inputs">Jenis Pembayaran</span><input id="setJenisPembayaran_dialog_inputJenisPembayaran"><br>
    <div id="setJenisPembayaran_dialogAddEdit_bt">
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" onclick="setJenisPembayaran_doAddEditJenisPembayaran();"></a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#setJenisPembayaran_dialogAddEdit').dialog('close');"></a>
    </div>
</div>

<script>
    $('#setJenisPembayaranMainGrid').datagrid({
        columns: [[
                {field: 'mt_id', title: 'ID', width: 100},
                {field: 'md_nama', title: 'Departemen', width: 100},
                {field: 'mt_jenis', title: 'Jenis Pembayaran', width: 400}
            ]],
        toolbar: '#setJenisPembayaranMainGridToolbar',
        pagination: true, rownumbers: true, height: 400,
        url: '<?= createUrl() ?>&act=data',
        singleSelect: true
    });
    
    $('#setJenisPembayaran_dialog_inputDepartemen').combobox({
        url:'<?= createUrl() ?>&act=comboDepartemen',
        textField:'md_nama', valueField:'md_id', panelHeight:'auto'
    });
    
    function setJenisPembayaran_searchJenisPembayaran(key){
        $('#setJenisPembayaranMainGrid').datagrid({
            queryParams:{keySearch:key},
            pageNumber:1
        });
    }

    var url;

    function setJenisPembayaran_openAddnewJenisPembayaran() {
        $('#setJenisPembayaran_dialog_inputJenisPembayaranId').val('');
        $('#setJenisPembayaran_dialog_inputJenisPembayaran').val('');
        $('#setJenisPembayaran_dialog_inputDepartemen').combobox('clear');
        $('#setJenisPembayaran_dialogAddEdit').dialog('open').dialog('setTitle', 'Tambah Jenis Pembayaran Baru');
        url = '<?= createUrl() ?>&act=addNewJenisPembayaran';
    }
    
    function setJenisPembayaran_openEditJenisPembayaran(){
        var g = $('#setJenisPembayaranMainGrid').datagrid('getSelected');
        if(g){
            url = '<?= createUrl() ?>&act=editJenisPembayaran';
            $('#setJenisPembayaran_dialogAddEdit').dialog('open').dialog('setTitle', 'Edit Jenis Pembayaran');
            $('#setJenisPembayaran_dialog_inputJenisPembayaranId').val(g.mt_id);
            $('#setJenisPembayaran_dialog_inputJenisPembayaran').val(g.mt_jenis);
            $('#setJenisPembayaran_dialog_inputDepartemen').combobox('setValue',g.md_id);
        } else {
            alert('Pilih dulu salah satu jenis pempayaran yang akan di-edit.');
        }
    }
    
    function setJenisPembayaran_doAddEditJenisPembayaran(){
        var jenispembayaran = $('#setJenisPembayaran_dialog_inputJenisPembayaran').val();
        var jenispembayaranid = $('#setJenisPembayaran_dialog_inputJenisPembayaranId').val();
        var departemen = $('#setJenisPembayaran_dialog_inputDepartemen').combobox('getValue');
        if (jenispembayaran == '') {
            alert('Nama JenisPembayaran harus diisi');
            return false;
        }
        if (departemen == '') {
            alert('Departemen harus dipilih');
            return false;
        }
        $.post(
            url,
            {dept:departemen, mtjenis:jenispembayaran, mtid:jenispembayaranid}, 
            function(result){
                if(result.success){
                    $('#setJenisPembayaran_dialogAddEdit').dialog('close');
                    $('#setJenisPembayaranMainGrid').datagrid('reload');
                } else {
                    alert(result.msg);;
                }
            },
            'json'
        );
    }
        
    function setJenisPembayaran_doDeleteJenisPembayaran(){
        var g = $('#setJenisPembayaranMainGrid').datagrid('getSelected');
        if(g){
            $.post(
                '<?= createUrl() ?>&act=deleteJenisPembayaran',
                {mtid:g.mt_id}, 
                function(result){
                    if(result.success){
                        $('#setJenisPembayaran_dialogAddEdit').dialog('close');
                        $('#setJenisPembayaranMainGrid').datagrid('reload');
                    } else {
                        alert(result.msg);;
                    }
                },
                'json'
            );
        }
    }
        
</script>
