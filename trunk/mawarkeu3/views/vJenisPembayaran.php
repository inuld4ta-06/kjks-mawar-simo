<div style="padding: 5px;">
    <script>
        $('#jnspbyr_maingrid').datagrid({
            url: 'controller/cJenisPembayaran.php?act=maingrid',
            toolbar:'#jnspbyr_maingrid_toolbar',height:500,
            rownumbers:true, pagination:true,
            columns: [[
                    {field: 'departemen', title: 'Departemen', width: 150},
                    {field: 'mt_jenis', title: 'Jenis Pembayaran', width: 500}
                ]]
        });
        
        var url;
        var tipe;
        function jnspbyr_tambah(){
            $('#jnspbyr_dialog1_departemen').combobox('clear');
            $('#jnspbyr_dialog1_namatransaksi').val('');
            $('#jnspbyr_dialog1').dialog('open').dialog('setTitle','Tambah Jenis Pembayaran Baru');
            url = 'controller/cJenisPembayaran.php?act=save';
            tipe = "tambah";
        }
        
        function jnspbyr_edit(){
            var row = $('#jnspbyr_maingrid').datagrid('getSelected');
            if (row){
                $('#jnspbyr_dialog1_departemen').combobox('clear');
                $('#jnspbyr_dialog1_departemen').combobox('setValue',row.mt_departemen);
                $('#jnspbyr_dialog1_departemen').combobox('disable');
                $('#jnspbyr_dialog1_namatransaksi').val('');
                $('#jnspbyr_dialog1_namatransaksi').val(row.mt_jenis);
                $('#jnspbyr_dialog1').dialog('open').dialog('setTitle','Update Jenis Pembayaran');
                url = 'controller/cJenisPembayaran.php?act=update&mt_id='+row.mt_id;
                tipe = "update";
            } else {
                alert('pilih dulu salah satu data.');
            }
        }
        
        function jnspbyr_doSave(){
            var dept = $('#jnspbyr_dialog1_departemen').combobox('getValue');
            var namatransaksi = $('#jnspbyr_dialog1_namatransaksi').val();
            $.post(url,{dept:dept, trans:namatransaksi}, function(result){
                if(result.success){
                    $('#jnspbyr_dialog1').dialog('close');
                    $('#jnspbyr_maingrid').datagrid('reload');
                    $.messager.alert('info','Jenis Pembayaran ' + namatransaksi + ' telah berhasil di' + tipe + '.');
                } else {
                    alert('Ada Kesalahan.');
                }
            }, 'json');
        }
        
        function jnspbyr_hapus(){
            var row = $('#jnspbyr_maingrid').datagrid('getSelected');
            if (row){
                $.messager.confirm('Konfirmasi','Anda yakin akan menghapus Jenis Pembayaran tersebut?',function(r){
                    if(r){
                        $.post('controller/cJenisPembayaran.php?act=delete',{mtid:row.mt_id}, function(result){
                            if(result.success){
                                $('#jnspbyr_maingrid').datagrid('reload');
                                $.messager.alert('info','Jenis Pembayaran ' + row.mt_jenis + ' untuk ' + row.departemen + ' telah berhasil dihapus.');
                            } else {
                                alert('Ada Kesalahan saat menghapus jenis transaksi.');
                            }
                        },'json');
                    }
                })
            } else {
                alert('pilih dulu salah satu data.');
            }
        }
    </script>
    <table id="jnspbyr_maingrid"></table>
    <div id='jnspbyr_maingrid_toolbar' style="padding: 5px;">
        <a href="javascript:void(0)" class='easyui-linkbutton' iconCls="icon-add" onclick="jnspbyr_tambah();">Tambah</a>
        <a href="javascript:void(0)" class='easyui-linkbutton' iconCls="icon-edit" onclick="jnspbyr_edit();">Edit</a>
        <a href="javascript:void(0)" class='easyui-linkbutton' iconCls="icon-remove" onclick="jnspbyr_hapus();">Hapus</a>
    </div>
</div>
<div class="easyui-dialog" id="jnspbyr_dialog1" data-options="
     closed:true,buttons:'#jnspbyr_dialog1_button'
     " style="padding: 10px;width: 350px;" modal="true">
    <table>
        <tr>
            <td>Departemen</td><td><input id="jnspbyr_dialog1_departemen" class="easyui-combobox" data-options="
           valueField:'md_id', textField:'md_nama', url:'controller/cJenisPembayaran.php?act=comboDepartemen',
           panelHeight:'auto'
           "></td>
        </tr>
        <tr>
            <td>Nama Transaksi</td><td><input id='jnspbyr_dialog1_namatransaksi'></td>
        </tr>
    </table>
    <div id="jnspbyr_dialog1_button">
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" onclick='jnspbyr_doSave();'></a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel"></a>
    </div>
</div>