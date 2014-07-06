<table id="admJurusanMainGrid"></table>
<div id="admJurusanMainGridToolbar" style="padding:5px;">
    cari <input id="admJurusanMainGridToolbarSearchKey" onchange="admJurusan_searchJurusan(this.value);">
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" onclick="admJurusan_openAddnewJurusan();">Tambah Jurusan</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" onclick="admJurusan_openEditJurusan();">Edit</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" onclick="admJurusan_doDeleteJurusan();">Hapus Jurusan</a>
</div>
<div id="admJurusan_dialogAddEdit" class="easyui-dialog" closed="true" modal="true"
     style="padding: 10px;height: 150px;"
     buttons="#admJurusan_dialogAddEdit_bt">
    <style>
        .admJurusan_dialogAddEdit_inputs {
            display: inline-block;
            width: 100px;
            padding-bottom: 10px;
        }
    </style>
    <input type="hidden" id="admJurusan_dialog_inputJurusanId">
    <span class="admJurusan_dialogAddEdit_inputs">Departemen</span><input id="admJurusan_dialog_inputDepartemen"><br>
    <span class="admJurusan_dialogAddEdit_inputs">Jurusan</span><input id="admJurusan_dialog_inputJurusan"><br>
    <div id="admJurusan_dialogAddEdit_bt">
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" onclick="admJurusan_doAddEditJurusan();"></a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#admJurusan_dialogAddEdit').dialog('close');"></a>
    </div>
    <input type="hidden" id="admJurusan_dialog_inputOldJurusan">
</div>

<script>
    $('#admJurusanMainGrid').datagrid({
        columns: [[
                {field: 'mj_id', title: 'ID', width: 100},
                {field: 'md_nama', title: 'Departemen', width: 100},
                {field: 'mj_nama', title: 'Jurusan', width: 100}
            ]],
        toolbar: '#admJurusanMainGridToolbar',
        pagination: true, rownumbers: true, height: 400,
        url: '<?= createUrl() ?>&act=data',
        singleSelect: true
    });
    
    $('#admJurusan_dialog_inputDepartemen').combobox({
        url:'<?= createUrl() ?>&act=comboDepartemen',
        textField:'md_nama', valueField:'md_id', panelHeight:'auto'
    });

    function admJurusan_searchJurusan(key){
        $('#admJurusanMainGrid').datagrid({
            queryParams:{keySearch:key},
            pageNumber:1
        });
    }

    var url;

    function admJurusan_openAddnewJurusan() {
        $('#admJurusan_dialog_inputDepartemen').combobox('clear');
        $('#admJurusan_dialog_inputJurusan').val('');
        $('#admJurusan_dialog_inputJurusanId').val('');
        $('#admJurusan_dialogAddEdit').dialog('open').dialog('setTitle', 'Tambah Jurusan Baru');
        url = '<?= createUrl() ?>&act=addNewJurusan';
    }
    
    function admJurusan_openEditJurusan(){
        var g = $('#admJurusanMainGrid').datagrid('getSelected');
        if(g){
            url = '<?= createUrl() ?>&act=editJurusan';
            $('#admJurusan_dialogAddEdit').dialog('open').dialog('setTitle', 'Edit Jurusan');
            $('#admJurusan_dialog_inputDepartemen').combobox('setValue',g.md_id);
            $('#admJurusan_dialog_inputJurusan').val(g.mj_nama);
            $('#admJurusan_dialog_inputJurusanId').val(g.mj_id);
        } else {
            alert('Pilih dulu salah satu jurusan yang akan di-edit.');
        }
    }
    
    function admJurusan_doAddEditJurusan(){
        var dept = $('#admJurusan_dialog_inputDepartemen').combobox('getValue');
        var jurs = $('#admJurusan_dialog_inputJurusan').val();
        var jursid = $('#admJurusan_dialog_inputJurusanId').val();
        if (jurs == '') {
            alert('Nama Jurusan harus diisi');
            $('#admJurusan_dialog_inputJurusan').focus();
            return false;
        }
        if (!dept) {
            alert('Jurusan harus diisi');
            return false;
        }
        $.post(
            url,
            {
                dept:dept,
                jursid:jursid,
                jurs:jurs
            }, 
            function(result){
                if(result.success){
                    $('#admJurusan_dialogAddEdit').dialog('close');
                    $('#admJurusanMainGrid').datagrid('reload');
                } else {
                    alert(result.msg);;
                }
            },
            'json'
        );
    }
        
    function admJurusan_doDeleteJurusan(){
        var g = $('#admJurusanMainGrid').datagrid('getSelected');
        if(g){
            $.post(
                '<?= createUrl() ?>&act=deleteJurusan',
                {jursid:g.mj_id}, 
                function(result){
                    if(result.success){
                        $('#admJurusan_dialogAddEdit').dialog('close');
                        $('#admJurusanMainGrid').datagrid('reload');
                    } else {
                        alert(result.msg);;
                    }
                },
                'json'
            );
        }
    }
        
</script>
