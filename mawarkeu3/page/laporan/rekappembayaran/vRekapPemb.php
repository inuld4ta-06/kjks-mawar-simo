<style>
    .rekapPemb_toolbar_spacer {
        display: block;
        height: 10px;
    }
</style>
<table id="rekappemb_maingrid"></table>
<div id="rekappemb_maingrid_toolbar" style="padding: 10px; text-align: center;">
    <span style="font-weight: bold;font-size: 15px;">REKAP PEMBAYARAN</span>
    <div class="rekapPemb_toolbar_spacer"></div>
    Departemen <input id="rekappemb_search_departemen">
    Tahun Ajaran <input id="rekappemb_search_tahunajaran">
    <div class="rekapPemb_toolbar_spacer"></div>
    Jenis Pembayaran <input id="rekappemb_search_pembayaran">
    <div class="rekapPemb_toolbar_spacer"></div>
    Kelas <input id="rekappemb_search_kelas">
    Nama Siswa <input id="rekappemb_search_siswa">
    <div class="rekapPemb_toolbar_spacer"></div>
    Per Tanggal <input id="rekappemb_search_tglPer" style="width: 100px;"> s/d <input id="rekappemb_search_tglPerTo" style="width: 100px;">
    <div class="rekapPemb_toolbar_spacer"></div>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-search" onclick="rekappemb_doSearch()">Cari</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-save" onclick="rekappemb_doSave()">Simpan</a>
</div>
<script>
    $('#rekappemb_maingrid').datagrid({
        frozenColumns:[[
            {field:'ms_jurusan', title:'Jurusan', width:100},
            {field:'ms_kelas', title:'Kelas', width:100},
            {field:'ms_nis', title:'No. Induk', width:80},
            {field:'ms_nama', title:'Nama', width:270},
        ]],
        columns:[[
            {field:'ms_alamat', title:'Alamat', width:270, rowspan: 2},
            {field:'ms_telp_wali', title:'Telp. Wali', width:270, rowspan: 2},
            {title:'Bulan', colspan: 12}
        ],[
            {field:'pemb_07', title:'Juli', width:100},
            {field:'pemb_08', title:'Agustus', width:100},
            {field:'pemb_09', title:'September', width:100},
            {field:'pemb_10', title:'Oktober', width:100},
            {field:'pemb_11', title:'November', width:100},
            {field:'pemb_12', title:'Desember', width:100},
            {field:'pemb_01', title:'Januari', width:100},
            {field:'pemb_02', title:'Februari', width:100},
            {field:'pemb_03', title:'Maret', width:100},
            {field:'pemb_04', title:'April', width:100},
            {field:'pemb_05', title:'Mei', width:100},
            {field:'pemb_06', title:'Juni', width:100}
        ]],
        fit: true, rownumbers: true, singleSelect: true, toolbar:'#rekappemb_maingrid_toolbar',
        pagination:true
    });
    
    $('#rekappemb_search_departemen').combobox({
        textField: 'md_nama', valueField: 'md_nama', panelHeight: 200, width: 120,
        url: '<?= createUrl() ?>&act=comboSearchDepartemen',
        onHidePanel:function(){
            var dept = $('#rekappemb_search_departemen').combobox('getValue');
            var ta = $('#rekappemb_search_tahunajaran').combobox('getValue');
            $('#rekappemb_search_pembayaran').combobox('clear');
            $('#rekappemb_search_kelas').combobox('clear');
            $('#rekappemb_search_siswa').combogrid('clear');
            $('#rekappemb_search_pembayaran').combobox('reload', '<?= createUrl() ?>&act=comboSearchPembayaran&dept=' + dept);
            $('#rekappemb_search_kelas').combobox('reload', '<?= createUrl() ?>&act=comboSearchKelas&dept=' + dept);
            $('#rekappemb_search_siswa').combogrid({url:'<?= createUrl() ?>&act=comboSearchSiswa&dept=' + dept + '&ta=' + ta});
        }
    });
    
    $('#rekappemb_search_tahunajaran').combobox({
        textField: 'ta_nama', valueField: 'ta_nama', panelHeight: 200, width: 100,
        url: '<?= createUrl() ?>&act=comboSearchTahunajaran'
    });
    
    $('#rekappemb_search_pembayaran').combobox({
        textField: 'mt_jenis', valueField: 'mt_id', panelHeight: 200, width: 400
    });
    
    $('#rekappemb_search_kelas').combobox({
        textField: 'kelas', valueField: 'kelas', panelHeight: 200, width: 200,
        onHidePanel: function() {
            var dept = $('#rekappemb_search_departemen').combobox('getValue');
            var kels = $('#rekappemb_search_kelas').combobox('getValue');
            var ta = $('#rekappemb_search_tahunajaran').combobox('getValue');
            $('#rekappemb_search_siswa').combogrid('clear');
            $('#rekappemb_search_siswa').combogrid({url:'<?= createUrl() ?>&act=comboSearchSiswa&dept=' + dept + '&kels=' + kels + '&ta=' + ta});
        }
    });
    
    $('#rekappemb_search_siswa').combogrid({
        textField: 'ms_nama', idField: 'ms_id',
        mode:'remote', fitColumns:true,width:300, panelWidth:600,
        columns:[[
            {field:'ms_nama', title:'Nama', width:250},
            {field:'ms_nis', title:'NIS', width:80},
            {field:'ms_jurusan', title:'Jurusan', width:100},
            {field:'ms_kelas', title:'Kelas', width:100}
        ]]
    });
    
    $('#rekappemb_search_tglPer').datebox().datebox('setValue','<?= date("Y-m-d") ?>');
    $('#rekappemb_search_tglPerTo').datebox().datebox('setValue','<?= date("Y-m-d") ?>');
    
    function rekappemb_doSearch(){
        var dept = $('#rekappemb_search_departemen').combobox('getValue');
        var ta = $('#rekappemb_search_tahunajaran').combobox('getValue');
        var trans = $('#rekappemb_search_pembayaran').combobox('getValue');
        var kels = $('#rekappemb_search_kelas').combobox('getValue');
        var tglPer = $('#rekappemb_search_tglPer').datebox('getValue');
        var tglPerTo = $('#rekappemb_search_tglPerTo').datebox('getValue');
        
        if (dept == '' && ta == '' && trans == '') {
            alert('Silakan pilih departemen, tahun ajaran, dan jenis pembayaran terlebih dulu');
            return false;
        } else if (dept == '' && ta == '' && trans != '') {
            alert('Silakan pilih tahun ajaran dan tahun ajaran terlebih dulu');
            return false;
        } else if (dept == '' && ta != '' && trans == '') {
            alert('Silakan pilih departemen dan jenis pembayaran terlebih dulu');
            return false;
        } else if (dept == '' && ta != '' && trans != '') {
            alert('Silakan pilih departemen terlebih dulu');
            return false;
        } else if (dept != '' && ta == '' && trans == '') {
            alert('Silakan pilih tahun ajaran dan jenis pembayaran terlebih dulu');
            return false;
        } else if (dept != '' && ta == '' && trans != '') {
            alert('Silakan pilih tahun ajaran terlebih dulu');
            return false;
        } else if (dept != '' && ta != '' && trans == '') {
            alert('Silakan pilih jenis pembayaran terlebih dulu');
            return false;
        }
        
        var siswa = $('#rekappemb_search_siswa').combogrid('getValue');
        
        $('#rekappemb_maingrid').datagrid({
            url:'<?= createUrl() ?>&act=doSearchRekapPemb',
            queryParams:{dept: dept, kels: kels, ta: ta, trans:trans, siswa:siswa, tglPer:tglPer, tglPerTo:tglPerTo},
            pageNumber:1
        });
    }
    
    function rekappemb_doSave(){
        var dept = $('#rekappemb_search_departemen').combobox('getValue');
        var ta = $('#rekappemb_search_tahunajaran').combobox('getValue');
        var trans = $('#rekappemb_search_pembayaran').combobox('getValue');
        var kels = $('#rekappemb_search_kelas').combobox('getValue');
        var tglPer = $('#rekappemb_search_tglPer').datebox('getValue');
        var tglPerTo = $('#rekappemb_search_tglPerTo').datebox('getValue');
        
        if (dept == '' && ta == '' && trans == '') {
            alert('Silakan pilih departemen, tahun ajaran, dan jenis pembayaran terlebih dulu');
            return false;
        } else if (dept == '' && ta == '' && trans != '') {
            alert('Silakan pilih tahun ajaran dan tahun ajaran terlebih dulu');
            return false;
        } else if (dept == '' && ta != '' && trans == '') {
            alert('Silakan pilih departemen dan jenis pembayaran terlebih dulu');
            return false;
        } else if (dept == '' && ta != '' && trans != '') {
            alert('Silakan pilih departemen terlebih dulu');
            return false;
        } else if (dept != '' && ta == '' && trans == '') {
            alert('Silakan pilih tahun ajaran dan jenis pembayaran terlebih dulu');
            return false;
        } else if (dept != '' && ta == '' && trans != '') {
            alert('Silakan pilih tahun ajaran terlebih dulu');
            return false;
        } else if (dept != '' && ta != '' && trans == '') {
            alert('Silakan pilih jenis pembayaran terlebih dulu');
            return false;
        }
        
        var siswa = $('#rekappemb_search_siswa').combogrid('getValue');
        
        window.open(
            '<?= createUrl() ?>&act=doSaveRekapPemb\n\
&trans=' + trans + '\n\
&siswa=' + siswa + '\n\
&dept=' + dept + '\n\
&kels=' + kels + '\n\
&tglPer=' + tglPer + '\n\
&tglPerTo=' + tglPerTo + '\n\
&ta=' + ta
        );
    }
</script>
