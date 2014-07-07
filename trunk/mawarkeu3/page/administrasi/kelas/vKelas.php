<table id="admKelasMainGrid"></table>
<div id="admKelasMainGridToolbar" style="padding:5px;">
    cari 
    <input id="admKelasMainGridToolbarSearchJurusan" class="admKelas_comboJurusan" data-options="onChange:function(i,v){admKelas_searchKelas(i);}">
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" onclick="admKelas_openAddnewKelas();">Tambah Kelas</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" onclick="admKelas_openEditKelas();">Edit</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" onclick="admKelas_doDeleteKelas();">Hapus Kelas</a>
</div>
<div id="admKelas_dialogAddEdit" class="easyui-dialog" closed="true" modal="true"
     style="padding: 10px;height: 150px;"
     buttons="#admKelas_dialogAddEdit_bt">
    <style>
        .admKelas_dialogAddEdit_inputs {
            display: inline-block;
            width: 100px;
            padding-bottom: 10px;
        }
    </style>
    <input type="hidden" id="admKelas_dialog_inputKelasId">
    <span class="admKelas_dialogAddEdit_inputs">Jurusan</span><input id="admKelas_dialog_inputJurusan" class="admKelas_comboJurusan"><br>
    <span class="admKelas_dialogAddEdit_inputs">Kelas</span><input id="admKelas_dialog_inputKelas"><br>
    <div id="admKelas_dialogAddEdit_bt">
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" onclick="admKelas_doAddEditKelas();"></a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#admKelas_dialogAddEdit').dialog('close');"></a>
    </div>
    <input type="hidden" id="admKelas_dialog_inputOldKelas">
</div>

<script>
    $('#admKelasMainGrid').datagrid({
        columns: [[
                {field: 'md_nama', title: 'Departemen', width: 100},
                {field: 'mj_nama', title: 'Jurusan', width: 100},
                {field: 'mkls_nama', title: 'Kelas', width: 100}
            ]],
        toolbar: '#admKelasMainGridToolbar',pageSize:40,
        pagination: true, rownumbers: true, height: 400,
        url: '<?= createUrl() ?>&act=data',
        singleSelect: true
    });
    
    $('#admKelas_dialog_inputGender').combobox({
        textField:'text', valueField:'value', panelHeight:'auto',
        data:[
            {text:'Laki-Laki', value:'L', selected:true},
            {text:'Perempuan', value:'P'}
        ]
    });

    $('.admKelas_comboJurusan').combobox({
        textField:'mj_nama', valueField:'mj_id', panelHeight:'auto',
        url:'<?= createUrl() ?>&act=comboJurusan'
    });

    function admKelas_searchKelas(key){
        $('#admKelasMainGrid').datagrid({
            queryParams:{keySearch:key},
            pageNumber:1
        });
    }

    var url;

    function admKelas_openAddnewKelas() {
        $('#admKelas_dialog_inputJurusan').combobox('clear');
        $('#admKelas_dialog_inputKelas').val('');
        $('#admKelas_dialog_inputKelasId').val('');
        $('#admKelas_dialogAddEdit').dialog('open').dialog('setTitle', 'Tambah Kelas Baru');
        url = '<?= createUrl() ?>&act=addNewKelas';
    }
    
    function admKelas_openEditKelas(){
        var g = $('#admKelasMainGrid').datagrid('getSelected');
        if(g){
            url = '<?= createUrl() ?>&act=editKelas';
            $('#admKelas_dialogAddEdit').dialog('open').dialog('setTitle', 'Edit Kelas');
            $('#admKelas_dialog_inputJurusan').combobox('setValue',g.mj_id);
            $('#admKelas_dialog_inputKelas').val(g.mkls_nama);
            $('#admKelas_dialog_inputKelasId').val(g.mkls_id);
        } else {
            alert('Pilih dulu salah satu kelas yang akan di-edit.');
        }
    }
    
    function admKelas_doAddEditKelas(){
        var jurs = $('#admKelas_dialog_inputJurusan').combobox('getValue');
        var kels = $('#admKelas_dialog_inputKelas').val();
        var kelsid = $('#admKelas_dialog_inputKelasId').val();
        if (jurs == '') {
            alert('Jurusan harus dipilih');
            return false;
        }
        if (kels == '') {
            alert('Kelas harus diisi');
            $('#admKelas_dialog_inputKelas').focus();
            return false;
        }
        $.post(
            url,
            {
                jurs:jurs,
                kels:kels,
                kelsid:kelsid
            }, 
            function(result){
                if(result.success){
                    $('#admKelas_dialogAddEdit').dialog('close');
                    $('#admKelasMainGrid').datagrid('reload');
                } else {
                    alert(result.msg);;
                }
            },
            'json'
        );
    }
        
    function admKelas_doDeleteKelas(){
        var g = $('#admKelasMainGrid').datagrid('getSelected');
        if(g){
            $.post(
                '<?= createUrl() ?>&act=deleteKelas',
                {kelsid:g.mkls_id}, 
                function(result){
                    if(result.success){
                        $('#admKelas_dialogAddEdit').dialog('close');
                        $('#admKelasMainGrid').datagrid('reload');
                    } else {
                        alert(result.msg);;
                    }
                },
                'json'
            );
        }
    }
        
</script>
