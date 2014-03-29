<div style="padding: 5px;">
    <script>
        
        var msid = '0';
        var struk_id;
        
        $('#pembsis_siswa').combogrid({
            panelWidth: 650,
            idField: 'ms_id',
            textField: 'ms_nama',
            url: 'controller/cPembayaranSiswa.php?act=comboSiswa',
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
                loadNewTransaction(i);
            }
        });
        
        function loadNewTransaction(i){
            if(msid == i){
            } else if(msid == 0 && msid != i){
                msid = i
                newTransaction(i);
            } else if(msid != i && msid != 0){
                $.messager.confirm('Konfirmasi', 'Anda yakin akan berpindah siswa tanpa mencetak struk?', function(r){
                    if(r){
                        msid = i;
                        newTransaction(i);
                    }
                });
            }
        }
        
        function newTransaction(i){
            $.post(
                'controller/cPembayaranSiswa.php?act=getNewStrukId',
                {ms_id:i},
                function(result){
                    if(result.success){
                        // ambil data siswa dari combogrid untuk ditampilkan di detail di bawahnya
                        var g = $('#pembsis_siswa').combogrid('grid');
                        var c = g.datagrid('getSelected');
                        if (c) {
                            $('#pembsis_detsis_nid').html(c.ms_nis);
                            $('#pembsis_detsis_kls').html(c.ms_kelas);
                            $('#pembsis_detsis_dep').html(c.ms_departemen);
                            var forPhoto = "<img src='images/siswa/" + c.ms_id + ".jpg' onerror='this.src=\"images/face.png\"' width='90px' height='90px' style='text-align:top'>";
                            $('#pembsis_detsis_pto').html(forPhoto);
                            $('#pembsis_div_struk').panel({
                                title:'Struk Pembayaran: ' + result.struk_id + ' untuk: ' + c.ms_nama
                            });
                        }
                        struk_id = result.struk_id;
                        $('#pembsis_div_jenisDet_srcJenis').combobox({
                            url:'controller/cPembayaranSiswa.php?act=loadJnsPmbyrn&ms_id='+c.ms_id
                        });
                        $('#pembsis_div_struk').panel('refresh', 'controller/cPembayaranSiswa.php?act=loadStrukPmbyrn&ms_id=' + i + '&struk_id=' + struk_id);
                    } else {
                        alert(result.msg);
                    }
                },
                'json'
            );
        }

        var w = $(window).width();
        
        $('#pembsis_div_jenisDet').panel({
            height: $(window).height() - 310,
            width: $(window).width() - 15
        });
        
        function cetakStruk(){
            alert("struk sedang dicetak");
            struk_msid = 0;
        }
    </script>
    <div style="float: left;width: 50%;">
        <div>
            <table style="width: 100%">
                <tr>
                    <td style="width: 10%;">Siswa</td>
                    <td style="width: 90%;">: <input id="pembsis_siswa" style="width: 175px"> 
                        <span style="font-style: italic">* Anda bisa mengetikkan nama / induk / scan barcode</span>
                    </td>
                </tr>
            </table>
            <table>
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
    </div>
    <div style="float: left;width:50%;">
        <div id="pembsis_div_struk" class="easyui-panel" title="Struk Pembayaran" 
            style="height:200px;" tools="#pembsis_div_struk_tool">
        </div>
        <div id="pembsis_div_struk_tool">
            <a href="javascript:void(0)" iconCls="icon-print" onclick="cetakStruk();"></a>
        </div>
    </div>
    <div id="pembsis_div_jenisDet" data-options='border: false' style="padding-top: 5px;">
        <table id="pembsis_div_jenisDet_maingrid" class="easyui-datagrid"
               data-options="
               fit:true,pagination: true,rownumbers: true,
               toolbar:'#pembsis_div_jenisDet_maingrid_toolbar',
               columns:[[
               {field:'field1', title:'Tahun', width:100},
               {field:'field2', title:'Bulan', width:100},
               {field:'field3', title:'Nominal', width:100},
               {field:'field3', title:'Keterangan', width:100},
               {field:'field4', title:'Petugas', width:100},
               {field:'field4', title:'Tanggal Bayar', width:100}
               ]]
               
               "></table>
        <div id="pembsis_div_jenisDet_maingrid_toolbar" style="padding: 5px;">
            <form>
            Jenis Pembayaran: <input id="pembsis_div_jenisDet_srcJenis" class="easyui-combobox" data-options="
                   valueField:'mt_id', textField:'mt_jenis',
                   panelWidth:500, panelHeight: 'auto'
            ">
            Tahun: <select>
                <?php for($taahun=date("Y") - 2; $taahun < date("Y") + 2; $taahun++){?>
                <option value="<?= $taahun ?>" <?= date("Y") == $taahun ? 'selected="selected"' : '' ?>><?= $taahun ?></option>
                <?php } ?>
            </select>
            Bulan: <select>
                <?php for($buulan=1; $buulan <= 12; $buulan++){?>
                <option value="<?= $buulan ?>" <?= date("m") == $buulan ? 'selected="selected"' : '' ?>><?= date("M",mktime(0,0,0,$buulan, date("d"), date("Y"))) ?></option>
                <?php } ?>
            </select>
            Nominal: 
            <select onchange='alert(this.value)'>
                <option value='0'>Default</option>
                <option value='1'>Custom</option>
            </select>
            <input class="easyui-numberbox">
            Keterangan: <input>
            <a href='javascript:void(0)' class='easyui-linkbutton'>Bayar</a>
            </form>
        </div>
    </div>
</div>