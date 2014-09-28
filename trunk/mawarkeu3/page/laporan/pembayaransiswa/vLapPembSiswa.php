<table id="lapPembSiswaMainGrid"></table>
<div id="lapPembSiswaMainGridToolbar" style="padding:5px;">
    Departemen <input id="lapPembSiswaMainGridToolbarSearchDepartemen">
    Jurusan <input id="lapPembSiswaMainGridToolbarSearchJurusan">
    Kelas <input id="lapPembSiswaMainGridToolbarSearchKelas">
    Nama <input id="lapPembSiswaMainGridToolbarSearchNama">
    <br>
    Jenis Pembayaran <input id="lapPembSiswaMainGridToolbarSearchJnsPemby">
    Tanggal <input id="lapPembSiswaMainGridToolbarSearchTglFrom" class="easyui-datebox" style="width: 100px">
    s/d <input id="lapPembSiswaMainGridToolbarSearchTglTo" class="easyui-datebox" style="width: 100px">
    Status <input id="lapPembSiswaMainGridToolbarSearchStatus">
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-search" onclick="lapPembSiswa_doSearchPembSiswa();">Cari</a><br>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" onclick="lapPembSiswa_doDeletePembSiswa();">Delete</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-undo" onclick="lapPembSiswa_doUndoDeletePembSiswa();">Undo Delete</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-save" onclick="lapPembSiswa_doSaveLapPembSiswa();">Simpan ke Excel</a>
</div>

<script>
    $('#lapPembSiswaMainGrid').datagrid({
        frozenColumns:[[
                {field: 'ms_nis', title: 'NIS', width: 100},
                {field: 'ms_nama', title: 'Nama', width: 250},
                {field: 'ms_kelas', title: 'Kelas', width: 200}
        ]],
        columns: [[
                {field: 'mt_jenis', title: 'Jenis Pembayaran', width: 200},
                {field: 'pbyr_tahun', title: 'Tahun', width: 50},
                {field: 'pbyr_bulan', title: 'Bulan', width: 50},
                {field: 'pbyr_nominal', title: 'Nominal', width: 100, align:'right'},
                {field: 'pbyr_createby', title: 'Teller', width: 100},
                {field: 'pbyr_createdate', title: 'Tanggal Waktu', width: 150},
                {field: 'pbyr_deletedate', title: 'delete', width: 150,
                    styler:function(value, row, index){
                        if(value){
                            return 'color:red';
                        }
                    }
                },
                {field: 'pbyr_deleteby', title: 'delete by', width: 150}
            ]],
        toolbar: '#lapPembSiswaMainGridToolbar',
        pagination: true, rownumbers: true, fit: true,
        url: '<?= createUrl() ?>&act=data',
        singleSelect: true, pageSize:40
    });
    
    $('#lapPembSiswaMainGridToolbarSearchDepartemen').combobox({
        textField: 'md_nama', valueField: 'md_nama', panelHeight: 250,
        url: '<?= createUrl() ?>&act=comboSearchDepartemen',
        onSelect: function(rec) {
            $('#lapPembSiswaMainGridToolbarSearchJurusan').combobox('clear');
            $('#lapPembSiswaMainGridToolbarSearchKelas').combobox('clear');
            $('#lapPembSiswaMainGridToolbarSearchJnsPemby').combobox('clear');
            var jurusanUrl = "<?= createUrl() ?>&act=comboSearchJurusan&dept=" + rec.md_nama;
            $('#lapPembSiswaMainGridToolbarSearchJurusan').combobox('reload', jurusanUrl);
            var jnsPembyUrl = "<?= createUrl() ?>&act=comboSearchJnsPemby&dept=" + rec.md_nama;
            $('#lapPembSiswaMainGridToolbarSearchJnsPemby').combobox('reload', jnsPembyUrl);
        }
    });
    
    $('#lapPembSiswaMainGridToolbarSearchJurusan').combobox({
        textField: 'mj_nama', valueField: 'mj_nama', panelHeight: 250,
        url: '<?= createUrl() ?>&act=comboSearchJurusan',
        onSelect: function(rec) {
            $('#lapPembSiswaMainGridToolbarSearchKelas').combobox('clear');
            var deptnama = $('#lapPembSiswaMainGridToolbarSearchDepartemen').combobox('getValue');
            var kelasUrl = "<?= createUrl() ?>&act=comboSearchKelas&dept=" + deptnama + "&jurs=" + rec.mj_nama;
            $('#lapPembSiswaMainGridToolbarSearchKelas').combobox('reload', kelasUrl);
        }
    });
    
    $('#lapPembSiswaMainGridToolbarSearchKelas').combobox({
        textField: 'mkls_nama', valueField: 'mkls_nama', panelHeight: 250
    });
    
    $('#lapPembSiswaMainGridToolbarSearchJnsPemby').combobox({
        textField: 'mt_jenis', valueField: 'mt_jenis', panelHeight: 250, panelWidth: 450
    });
    
    $('#lapPembSiswaMainGridToolbarSearchStatus').combobox({
        textField: 'text', valueField: 'value', panelHeight: 'auto',
        data:[
            {text:'Tidak Terdelete', value:'notdeleted'},
            {text:'Sudah Terdelete', value:'deleted'},
            {text:'Lihat Semua', value:'all', selected: true}
        ]
    });
    
    function lapPembSiswa_doSearchPembSiswa() {
        var dept = $('#lapPembSiswaMainGridToolbarSearchDepartemen').combobox('getValue');
        var jurs = $('#lapPembSiswaMainGridToolbarSearchJurusan').combobox('getValue');
        var kels = $('#lapPembSiswaMainGridToolbarSearchKelas').combobox('getValue');
        var nama = $('#lapPembSiswaMainGridToolbarSearchNama').val();
        var jnsp = $('#lapPembSiswaMainGridToolbarSearchJnsPemby').combobox('getValue');
        var stts = $('#lapPembSiswaMainGridToolbarSearchStatus').combobox('getValue');
        var tgfr = $('#lapPembSiswaMainGridToolbarSearchTglFrom').datebox('getValue');
        var tgto = $('#lapPembSiswaMainGridToolbarSearchTglTo').datebox('getValue');
        $('#lapPembSiswaMainGrid').datagrid({
            queryParams:{dept:dept, jurs:jurs, kels:kels, nama:nama, jnsp:jnsp, stts: stts, tgfr:tgfr, tgto:tgto},
            pageNumber:1
        });
    }
    
    
    function lapPembSiswa_doDeletePembSiswa(){
        var g = $('#lapPembSiswaMainGrid').datagrid('getSelected');
        if(g){
            $.messager.confirm('konfirmasi', 'anda yakin akan menghapus data tersebut?', function(r){
                if(r){
                    $.post(
                        '<?= createUrl() ?>&act=delPembayaran',
                        {pbyrid:g.pbyr_id},
                        function(result){
                            if(result.success){
                                $('#lapPembSiswaMainGrid').datagrid('reload');
                                $.messager.show({
                                    title:'result',
                                    msg:'sukses'
                                });
                            } else {
                                alert(result.msg);
                            }
                        },
                        'json'
                    );
                }
            });
        } else {
            alert('pilih salah satu data terlebih dulu');
        }
    }
    
    function lapPembSiswa_doUndoDeletePembSiswa(){
        var g = $('#lapPembSiswaMainGrid').datagrid('getSelected');
        if(g){
            $.messager.confirm('konfirmasi', 'anda yakin data yang terhapus tersebut akan ditampilkan lagi?', function(r){
                if(r){
                    $.post(
                        '<?= createUrl() ?>&act=undoDelPembayaran',
                        {pbyrid:g.pbyr_id},
                        function(result){
                            if(result.success){
                                $('#lapPembSiswaMainGrid').datagrid('reload');
                                $.messager.show({
                                    title:'result',
                                    msg:'sukses'
                                });
                            } else {
                                alert(result.msg);
                            }
                        },
                        'json'
                    );
                }
            });
        } else {
            alert('pilih salah satu data terlebih dulu');
        }
    }
    
    function lapPembSiswa_doSaveLapPembSiswa(){
        var dept = $('#lapPembSiswaMainGridToolbarSearchDepartemen').combobox('getValue');
        var jurs = $('#lapPembSiswaMainGridToolbarSearchJurusan').combobox('getValue');
        var kels = $('#lapPembSiswaMainGridToolbarSearchKelas').combobox('getValue');
        var nama = $('#lapPembSiswaMainGridToolbarSearchNama').val();
        var jnsp = $('#lapPembSiswaMainGridToolbarSearchJnsPemby').combobox('getValue');
        var stts = $('#lapPembSiswaMainGridToolbarSearchStatus').combobox('getValue');
        var tgfr = $('#lapPembSiswaMainGridToolbarSearchTglFrom').datebox('getValue');
        var tgto = $('#lapPembSiswaMainGridToolbarSearchTglTo').datebox('getValue');
        window.open('<?= createUrl() ?>&act=saveLapPemb&dept=' + dept + '&jurs=' + jurs + '&kels=' + kels + '&nama=' + nama + '&jnsp=' + jnsp + '&stts=' + stts + '&tgfr=' + tgfr + '&tgto=' + tgto);
    }
    
</script>
