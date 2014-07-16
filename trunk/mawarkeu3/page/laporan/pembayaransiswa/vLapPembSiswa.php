<table id="lapPembSiswaMainGrid"></table>
<div id="lapPembSiswaMainGridToolbar" style="padding:5px;">
    Departemen <input id="lapPembSiswaMainGridToolbarSearchDepartemen">
    Status <input id="lapPembSiswaMainGridToolbarSearchStatus">
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-search" onclick="lapPembSiswa_doSearchPembSiswa();">Go</a><br>
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
        textField: 'md_nama', valueField: 'md_nama', panelHeight: 'auto',
        url: '<?= createUrl() ?>&act=comboSearchDepartemen'
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
        var stts = $('#lapPembSiswaMainGridToolbarSearchStatus').combobox('getValue');
        $('#lapPembSiswaMainGrid').datagrid({
            queryParams:{dept:dept,stts: stts},
            pageNumber:1
        });
    }
    
    function lapPembSiswa_searchDepartemen() {
        var dept = $('#lapPembSiswaMainGridToolbarSearchDepartemen').combobox('getValue');
        var stts = $('#lapPembSiswaMainGridToolbarSearchStatus').combobox('getValue');
        $('#lapPembSiswaMainGrid').datagrid({
            queryParams: {
                dept: dept,
                stts: stts
            },
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
    
</script>
