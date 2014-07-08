<table id="admSiswaMainGrid"></table>
<div id="admSiswaMainGridToolbar" style="padding:5px;">
    cari <input id="admSiswaMainGridToolbarSearchKey" onchange="admRole_searchSiswa(this.value);">
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" onclick="admSiswa_openAddnewSiswa();">Tambah Siswa</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" onclick="admSiswa_openEditSiswa();">Edit</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" onclick="admSiswa_doDeleteSiswa();">Hapus Siswa</a>
</div>
<div id="admSiswa_dialogAddEdit" class="easyui-dialog" closed="true" modal="true"
     style="padding: 10px;height: 300px;"
     buttons="#admSiswa_dialogAddEdit_bt">
    <style>
        .admSiswa_dialogAddEdit_inputs {
            display: inline-block;
            width: 100px;
            padding-bottom: 10px;
        }
    </style>
    <input type="hidden" id="admSiswa_dialog_inputSiswaId">
    <span class="admSiswa_dialogAddEdit_inputs">NIS</span><input id="admSiswa_dialog_inputNis"><br>
    <span class="admSiswa_dialogAddEdit_inputs">NISN</span><input id="admSiswa_dialog_inputNisn"><br>
    <span class="admSiswa_dialogAddEdit_inputs">Nama</span><input id="admSiswa_dialog_inputNama"><br>
    <span class="admSiswa_dialogAddEdit_inputs">Gender</span><input id="admSiswa_dialog_inputGender"><br>
    <span class="admSiswa_dialogAddEdit_inputs">Tempat Lahir</span><input id="admSiswa_dialog_inputTmptlahir"><br>
    <span class="admSiswa_dialogAddEdit_inputs">Tanggal Lahir</span><input id="admSiswa_dialog_inputTgllahir" class="easyui-datebox"><br>
    <span class="admSiswa_dialogAddEdit_inputs">Kelas</span><input id="admSiswa_dialog_inputKelas"><br>
    <div id="admSiswa_dialogAddEdit_bt">
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" onclick="admSiswa_doAddEditSiswa();"></a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#admSiswa_dialogAddEdit').dialog('close');"></a>
    </div>
    <input type="hidden" id="admSiswa_dialog_inputOldSiswa">
</div>

<script>
    $('#admSiswaMainGrid').datagrid({
        columns: [[
                {field: 'ms_id', title: 'ID', width: 100},
                {field: 'ms_nis', title: 'NIS', width: 100},
                {field: 'ms_nisn', title: 'NISN', width: 100},
                {field: 'ms_nama', title: 'Nama', width: 300},
                {field: 'ms_jeniskelamin', title: 'Jns Kel', width: 50},
                {field: 'ms_birthplace', title: 'Tmp Lahir', width: 100},
                {field: 'ms_birthdate', title: 'Tgl Lahir', width: 100},
                {field: 'ms_departemen', title: 'departemen', width: 100},
                {field: 'ms_jurusan', title: 'Jurusan', width: 100},
                {field: 'ms_kelas', title: 'Kelas', width: 50}
            ]],
        toolbar: '#admSiswaMainGridToolbar',
        pagination: true, rownumbers: true, height: 400,
        url: '<?= createUrl() ?>&act=data',
        singleSelect: true
    });

    $('#admSiswa_dialog_inputGender').combobox({
        textField: 'text', valueField: 'value', panelHeight: 'auto',
        data: [
            {text: 'Laki-Laki', value: 'L', selected: true},
            {text: 'Perempuan', value: 'P'}
        ]
    });

    $('#admSiswa_dialog_inputKelas').combobox({
        textField: 'mkls_nama', valueField: 'mkls_id', panelHeight: 'auto',
        url: '<?= createUrl() ?>&act=comboKelas'
    });

    function admRole_searchSiswa(key){
        $('#admSiswaMainGrid').datagrid({
            queryParams:{keySearch:key},
            pageNumber:1
        });
    }
    
    var url;

    function admSiswa_openAddnewSiswa() {
        $('#admSiswa_dialog_inputSiswaId').val('');
        $('#admSiswa_dialog_inputNis').val('');
        $('#admSiswa_dialog_inputNisn').val('');
        $('#admSiswa_dialog_inputNama').val('');
        $('#admSiswa_dialog_inputGender').combobox('clear');
        $('#admSiswa_dialog_inputTmptlahir').val('');
        $('#admSiswa_dialog_inputTgllahir').val('');
        $('#admSiswa_dialog_inputKelas').combobox('clear');
        $('#admSiswa_dialogAddEdit').dialog('open').dialog('setTitle', 'Tambah Siswa Baru');
        url = '<?= createUrl() ?>&act=addNewSiswa';
    }

    function admSiswa_openEditSiswa() {
        var g = $('#admSiswaMainGrid').datagrid('getSelected');
        if (g) {
            url = '<?= createUrl() ?>&act=editSiswa';
            $('#admSiswa_dialogAddEdit').dialog('open').dialog('setTitle', 'Edit Siswa');
            $('#admSiswa_dialog_inputSiswaId').val(g.ms_id);
            $('#admSiswa_dialog_inputNis').val(g.ms_nis);
            $('#admSiswa_dialog_inputNisn').val(g.ms_nisn);
            $('#admSiswa_dialog_inputNama').val(g.ms_nama);
            $('#admSiswa_dialog_inputGender').combobox('setValue', g.ms_jeniskelamin);
            $('#admSiswa_dialog_inputTmptlahir').val(g.ms_birthplace);
            $('#admSiswa_dialog_inputTgllahir').datebox('setValue', g.ms_birthdate);
            $('#admSiswa_dialog_inputKelas').combobox('setValue', g.mkls_id);
        } else {
            alert('Pilih dulu salah satu siswa yang akan di-edit.');
        }
    }

    function admSiswa_doAddEditSiswa() {
        var msid = $('#admSiswa_dialog_inputSiswaId').val();
        var nis = $('#admSiswa_dialog_inputNis').val();
        var nisn = $('#admSiswa_dialog_inputNisn').val();
        var nama = $('#admSiswa_dialog_inputNama').val();
        var gend = $('#admSiswa_dialog_inputGender').combobox('getValue');
        var tmpl = $('#admSiswa_dialog_inputTmptlahir').val();
        var tgll = $('#admSiswa_dialog_inputTgllahir').datebox('getValue');
        var kels = $('#admSiswa_dialog_inputKelas').combobox('getValue');
        if (nama == '') {
            alert('Nama Siswa harus diisi');
            $('#admSiswa_dialog_inputSiswa').focus();
            return false;
        }
        if (nis == '') {
            alert('NIS Siswa harus diisi');
            $('#admSiswa_dialog_inputNis').focus();
            return false;
        }
        if (kels == '') {
            alert('Kelas harus diisi');
            $('#admSiswa_dialog_inputSiswa').focus();
            return false;
        }
        $.post(
                url,
                {
                    msid: msid,
                    nis: nis,
                    nisn: nisn,
                    nama: nama,
                    gend: gend,
                    tmpl: tmpl,
                    tgll: tgll,
                    kels: kels
                },
        function(result) {
            if (result.success) {
                $('#admSiswa_dialogAddEdit').dialog('close');
                $('#admSiswaMainGrid').datagrid('reload');
            } else {
                alert(result.msg);
                ;
            }
        },
                'json'
                );
    }

    function admSiswa_doDeleteSiswa() {
        var g = $('#admSiswaMainGrid').datagrid('getSelected');
        if (g) {
            $.post(
                    '<?= createUrl() ?>&act=deleteSiswa',
                    {msid: g.ms_id},
            function(result) {
                if (result.success) {
                    $('#admSiswa_dialogAddEdit').dialog('close');
                    $('#admSiswaMainGrid').datagrid('reload');
                } else {
                    alert(result.msg);
                    ;
                }
            },
                    'json'
                    );
        }
    }

</script>
