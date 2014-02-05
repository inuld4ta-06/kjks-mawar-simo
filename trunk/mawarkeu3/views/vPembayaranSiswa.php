<div style="padding: 5px;">
    <script>
        function kelik() {
            $.post(
                    'controller/cPembayaranSiswa.php?act=givealert',
                    function(result) {
                        alert(result);
                    }
            );
        }

        $('#pembsis_siswa').combogrid({
            panelWidth: 600,
            idField: 'ms_id',
            textField: 'ms_nama',
            url: 'controller/cPembayaranSiswa.php?act=comboSiswa',
            method: 'get', mode: 'remote',
            columns: [[
                    {field: 'ms_nis', title: 'NIS', width: 50},
                    {field: 'ms_nisn', title: 'NISN', width: 120},
                    {field: 'ms_nama', title: 'Nama', width: 200},
                    {field: 'ms_departemen', title: 'Departemen', width: 90},
                    {field: 'ms_jurusan', title: 'Jurusan', width: 60},
                    {field: 'ms_kelas', title: 'Kelas', width: 40}
                ]],
            onChange: function(i) {
//                loadJnsPmbyrn(i);
                var count = $('#pembsis_div_jenisDet').tabs('tabs').length;
                for (var f = count -1; f >= 0; f--) {
                    $('#pembsis_div_jenisDet').tabs('close', f);
                }
//                alert(count);
                $('#pembsis_div_jenis').panel('refresh', 'controller/cPembayaranSiswa.php?act=loadJnsPmbyrn&ms_id=' + i);
            }

        });

        var indexJnsPmb = 0;
        function openJnsPmb(jnsPmb, mt_id) {
            indexJnsPmb++;
            if ($('#pembsis_div_jenisDet').tabs('exists', jnsPmb)) {
                $('#pembsis_div_jenisDet').tabs('select', jnsPmb);
            } else {
                var g = $('#pembsis_siswa').combogrid('grid');
                var s = g.datagrid('getSelected');
                $('#pembsis_div_jenisDet').tabs('add', {
                    title: jnsPmb,
                    href: 'controller/cPembayaranSiswa.php?act=loadPmbyrn&mt_id='+mt_id+'&ms_id='+s.ms_id,
                    closable: true
                });
            }
        }
    </script>
    <div style="float: left;width: 230px;">siswa: <input id="pembsis_siswa"></div>
    <div id="pembsis_div_jenis" class="easyui-panel" title="Jenis Pembayaran" style="float: left;width:500px;height:200px;padding:10px;"></div>
    <div id="pembsis_div_jenisDet" class="easyui-tabs" data-options="fit: true"></div>
</div>