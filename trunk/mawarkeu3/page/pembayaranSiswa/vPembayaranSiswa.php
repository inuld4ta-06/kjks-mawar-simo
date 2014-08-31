<script>

    $(function() {
        $('#pembsis_mainLayout').layout({
            fit:true, border:false
        });
        
        $('#pembsis_div_jenisDet_maingrid').datagrid({
            fit: true, pagination: true, rownumbers: true,
            toolbar: '#pembsis_div_jenisDet_maingrid_toolbar',
            columns: [[
                    {field: 'mt_jenis', title: 'Transaksi', width: 400},
                    {field: 'pbyr_tahun', title: 'Tahun', width: 100},
                    {field: 'pbyr_bulan', title: 'Bulan', width: 100},
                    {field: 'pbyr_nominal', title: 'Nominal', width: 100},
                    {field: 'pbyr_text', title: 'Keterangan', width: 100},
                    {field: 'pbyr_createby', title: 'Petugas', width: 100},
                    {field: 'pbyr_createdate', title: 'Tanggal Bayar', width: 150}
                ]]
        });

        $('#pembsis_div_jenisDet_inputJenis').combobox({
            valueField: 'mt_id', textField: 'mt_jenis',
            panelWidth: 500, panelHeight: 'auto',
            onSelect: function(i) {
                getDataPembayaran();
                getDefaultPembayaran();
            }
        });
    });

    $('#pembsis_siswa').combogrid({
        panelWidth: 650,
        idField: 'ms_id',
        textField: 'ms_nama',
        url: '<?= createUrl() ?>&act=comboSiswa',
        method: 'get', mode: 'remote',
        columns: [[
                {field: 'ms_id', title: 'ID Siswa', width: 50},
                {field: 'ms_nis', title: 'NIS', width: 50},
                {field: 'ms_nisn', title: 'NISN', width: 120},
                {field: 'ms_nama', title: 'Nama', width: 200},
                {field: 'ms_departemen', title: 'Departemen', width: 90},
                {field: 'ms_jurusan', title: 'Jurusan', width: 60},
                {field: 'ms_kelas', title: 'Kelas', width: 40}
            ]],
        onHidePanel: function() {
            var g = $(this).combogrid('grid');
            var s = g.datagrid('getSelected');
            var i = s.ms_id;
            loadSiswaDetail(i);
        }
    });

    function loadSiswaDetail(i) {
        // ambil data siswa dari combogrid untuk ditampilkan di detail di bawahnya
        var g = $('#pembsis_siswa').combogrid('grid');
        var c = g.datagrid('getSelected');
        if (c) {
            $('#pembsis_detsis_nid').html(c.ms_nis);
            $('#pembsis_detsis_kls').html(c.ms_kelas);
            $('#pembsis_detsis_dep').html(c.ms_departemen);
//                var forPhoto = "<img src='images/siswa/" + c.ms_id + ".jpg' onerror='this.src=\"images/face.png\"' width='90px' height='90px' style='text-align:top'>";
//                $('#pembsis_detsis_pto').html(forPhoto);
        }
        $('#pembsis_div_jenisDet_inputJenis').combobox({
            url: '<?= createUrl() ?>&act=loadJnsPmbyrn&ms_id=' + c.ms_id
        });
    }

    var w = $(window).width();

    function getCriteriaPencarian() {
        // get id_siswa
        var g = $('#pembsis_siswa').combogrid('grid');
        var c = g.datagrid('getSelected');
        // get jenis pembayaran
        var j = $('#pembsis_div_jenisDet_inputJenis').combobox('getValue');
        // get input bulan
        var b = $('#pembsis_div_jenisDet_inputBulan').val();
        // get input tahun
        var t = $('#pembsis_div_jenisDet_inputTahun').val();
        // get input keterangan
        var k = $('#pembsis_div_jenisDet_inputKeterangan').val();
        // get input nominal
        var n = $('#pembsis_div_jenisDet_inputNominal').numberbox('getValue');
        var d = {msid: c.ms_id, jenis: j, bulan: b, tahun: t, keterangan: k, nominal: n};
        return d;
    }

    function getDataPembayaran() {
        var d = getCriteriaPencarian();
        $('#pembsis_div_jenisDet_maingrid').datagrid({
            url: '<?= createUrl() ?>&act=getDataPembayaran',
            queryParams: d,
            pageNumber: 1
        });
    }

    function getDefaultPembayaran() {
        var dft = $('#pembsis_div_jenisDet_inputTypeNominal').val();
        if (dft == 0) {
            var d = getCriteriaPencarian();
            $.post(
                    'page/pembayaranSiswa/cPembayaranSiswa.php',
                    {act: 'getdefaultpembayaran', msid: d.msid, jenis: d.jenis},
            function(result) {
                $('#pembsis_div_jenisDet_inputNominal').numberbox('setValue', result);
            }
            );
        } else {

        }
    }

    function pembsis_doBayar() {
        var d = getCriteriaPencarian();
        $.post(
                '<?= createUrl() ?>&act=dobayar',
                d,
                function(result) {
                    if (result.success) {
                        $('#pembsis_div_jenisDet_maingrid').datagrid('reload');
                        pembSis_update_struk();
                    } else {
                        alert(result.msg);
                    }
                },
                'json'
                );
    }

    function pembsis_doHapus() {
        var g = $('#pembsis_div_jenisDet_maingrid').datagrid('getSelected');
        if (g) {
            $.messager.confirm('Konfirmasi', 'Anda yakin?', function(r) {
                if (r) {
                    $.post(
                            '<?= createUrl() ?>&act=hapusPembayaran',
                            {pbyrid: g.pbyr_id},
                    function(result) {
                        if (result.success) {
                            $('#pembsis_div_jenisDet_maingrid').datagrid('reload');
                            pembSis_update_struk();
                        } else {
                            alert('terjadi kesalahan');
                        }
                    },
                            'json'
                            );
                }
            });
        } else {
            alert('silakan pilih dulu salah satu data yg ingin dihapus.');
        }
    }

    function pembSis_update_struk() {
        var d = getCriteriaPencarian();
        $.post(
                '<?= createUrl() ?>&act=getStrukData',
                d,
                function(result) {
                    $('#pembsis_div_struk').html(result);
                }
        );
    }

    function cetakStruk() {
        var d = getCriteriaPencarian();
        window.open('<?= createUrl() ?>&act=cetakStruk&msid=' + d.msid);
    }

</script>
<div id='pembsis_mainLayout'>
    <div data-options="region:'west', border:false" style="width:700px;padding: 5px;">
        <table style="width: 100%">
            <tr>
                <td>Siswa</td>
                <td>: <input id="pembsis_siswa" style="width: 175px"> 
                    <span style="font-style: italic">* Anda bisa mengetikkan nama / induk / scan barcode</span>
                </td>
            </tr>
            <tr>
                <td>No.Induk</td>
                <td>: <span id="pembsis_detsis_nid"></span></td>
            </tr>
            <tr>
                <td>Kelas</td>
                <td>: <span id="pembsis_detsis_kls"></span></td>
            </tr>
            <tr>
                <td>Departemen</td>
                <td>: <span id="pembsis_detsis_dep"></span></td>
            </tr>
            <tr>
                <td>Photo</td>
                <td>:</td>
            </tr>
        </table>
        <span id="pembsis_detsis_pto"></span>
    </div>
    <div data-options="region:'center', border:false" style="padding: 5px;">
        <div id="pembsis_div_struk" class="easyui-panel" title="Struk Pembayaran" 
             style="height:200px;" tools="#pembsis_div_struk_tool">
        </div>
        <div id="pembsis_div_struk_tool">
            <a href="javascript:void(0)" iconCls="icon-print" onclick="cetakStruk();"></a>
        </div>
    </div>
    <div data-options="region:'south', border:false" style="height: 335px;">
        <table id="pembsis_div_jenisDet_maingrid"></table>
        <div id="pembsis_div_jenisDet_maingrid_toolbar" style="padding: 5px;">
            <form>
                Jenis Pembayaran: <input id="pembsis_div_jenisDet_inputJenis">
                Tahun: <select id='pembsis_div_jenisDet_inputTahun' onchange="getDataPembayaran();">
                    <?php for ($taahun = date("Y") - 2; $taahun < date("Y") + 2; $taahun++) { ?>
                        <option value="<?= $taahun ?>" <?= date("Y") == $taahun ? 'selected="selected"' : '' ?>><?= $taahun ?></option>
                    <?php } ?>
                </select>
                Bulan: <select id='pembsis_div_jenisDet_inputBulan' onchange="getDataPembayaran();">
                    <option value="all">Semua</option>
                    <option value="1" <?= date("m") == 1 ? 'selected="selected"' : '' ?>>Januari</option>
                    <option value="2" <?= date("m") == 2 ? 'selected="selected"' : '' ?>>Februari</option>
                    <option value="3" <?= date("m") == 3 ? 'selected="selected"' : '' ?>>Maret</option>
                    <option value="4" <?= date("m") == 4 ? 'selected="selected"' : '' ?>>April</option>
                    <option value="5" <?= date("m") == 5 ? 'selected="selected"' : '' ?>>Mei</option>
                    <option value="6" <?= date("m") == 6 ? 'selected="selected"' : '' ?>>Juni</option>
                    <option value="7" <?= date("m") == 7 ? 'selected="selected"' : '' ?>>Juli</option>
                    <option value="8" <?= date("m") == 8 ? 'selected="selected"' : '' ?>>Agustus</option>
                    <option value="9" <?= date("m") == 9 ? 'selected="selected"' : '' ?>>September</option>
                    <option value="10" <?= date("m") == 10 ? 'selected="selected"' : '' ?>>Oktober</option>
                    <option value="11" <?= date("m") == 11 ? 'selected="selected"' : '' ?>>November</option>
                    <option value="12" <?= date("m") == 12 ? 'selected="selected"' : '' ?>>Desember</option>
                </select>
                Nominal: 
                <select onchange='getDefaultPembayaran();' id='pembsis_div_jenisDet_inputTypeNominal'>
                    <option value='0'>Default</option>
                    <option value='1'>Custom</option>
                </select>
                <input class="easyui-numberbox" id="pembsis_div_jenisDet_inputNominal" data-options="groupSeparator:'.'">
                Keterangan: <input id="pembsis_div_jenisDet_inputKeterangan">
                <a href='javascript:void(0)' class='easyui-linkbutton' onclick="pembsis_doBayar();" iconCls="icon-ok">Bayar</a>
                <a href='javascript:void(0)' class='easyui-linkbutton' onclick="pembsis_doHapus();" iconCls="icon-cancel">Hapus</a>
            </form>
        </div>
    </div>
</div>
