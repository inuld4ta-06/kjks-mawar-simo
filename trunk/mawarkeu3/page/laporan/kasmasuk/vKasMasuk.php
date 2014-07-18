<table id="lapKasMasukMainGrid"></table>
<div id="lapKasMasukMainGridToolbar" style="padding:5px;">
    Departemen <input id="lapKasMasukMainGridToolbarSearchDepartemen">
    Jurusan <input id="lapKasMasukMainGridToolbarSearchJurusan">
    Kelas <input id="lapKasMasukMainGridToolbarSearchKelas">
    perTanggal <input id="lapKasMasukMainGridToolbarSearchTglFrom" style="width: 100px;"> s/d <input id="lapKasMasukMainGridToolbarSearchTglTo">
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-search" onclick="lapKasMasuk_searchDo();">Tampilkan</a>
</div>

<script>
    $('#lapKasMasukMainGrid').datagrid({
        columns: [[
                {field: 'pbyr_createdate', title: 'Tanggal', width: 100},
                {field: 'mt_jenis', title: 'Jenis Pembayaran', width: 250},
                {field: 'debet', title: 'Debet', width: 100, align: 'right'},
                {field: 'kredit', title: 'Kredit', width: 100, align: 'right'},
                {field: 'saldo', title: 'Saldo', width: 100, align: 'right'}
            ]],
        toolbar: '#lapKasMasukMainGridToolbar',
        rownumbers: true, fit: true,
        url: '<?= createUrl() ?>&act=data',
        singleSelect: true
    });

    $('#lapKasMasukMainGridToolbarSearchDepartemen').combobox({
        textField: 'md_nama', valueField: 'md_nama', panelHeight: 250,
        url: '<?= createUrl() ?>&act=comboSearchDepartemen',
        onSelect: function(rec) {
            $('#lapKasMasukMainGridToolbarSearchJurusan').combobox('clear');
            $('#lapKasMasukMainGridToolbarSearchKelas').combobox('clear');
            var jurusanUrl = "<?= createUrl() ?>&act=comboSearchJurusan&dept=" + rec.md_nama;
            $('#lapKasMasukMainGridToolbarSearchJurusan').combobox('reload', jurusanUrl);
        }
    });

    $('#lapKasMasukMainGridToolbarSearchJurusan').combobox({
        textField: 'mj_nama', valueField: 'mj_nama', panelHeight: 250,
        url: '<?= createUrl() ?>&act=comboSearchJurusan',
        onSelect: function(rec) {
            $('#lapKasMasukMainGridToolbarSearchKelas').combobox('clear');
            var deptnama = $('#lapKasMasukMainGridToolbarSearchDepartemen').combobox('getValue');
            var kelasUrl = "<?= createUrl() ?>&act=comboSearchKelas&dept=" + deptnama + "&jurs=" + rec.mj_nama;
            $('#lapKasMasukMainGridToolbarSearchKelas').combobox('reload', kelasUrl);
        }
    });

    $('#lapKasMasukMainGridToolbarSearchKelas').combobox({
        textField: 'mkls_nama', valueField: 'mkls_nama', panelHeight: 250
    });

    var d = new Date();
    var year = d.getFullYear();
    var month = d.getMonth() + 1;
    var day = d.getDate();
    var output = year + '-' + (month < 10 ? '0' : '') + month + '-' + (day < 10 ? '0' : '') + day;
    $('#lapKasMasukMainGridToolbarSearchTglFrom').datebox();
    $('#lapKasMasukMainGridToolbarSearchTglFrom').datebox('setValue', output);
    $('#lapKasMasukMainGridToolbarSearchTglTo').datebox();
    $('#lapKasMasukMainGridToolbarSearchTglTo').datebox('setValue', output);

    function lapKasMasuk_searchDo() {
        var dept = $('#lapKasMasukMainGridToolbarSearchDepartemen').combobox('getValue');
        var jurs = $('#lapKasMasukMainGridToolbarSearchJurusan').combobox('getValue');
        var kels = $('#lapKasMasukMainGridToolbarSearchKelas').combobox('getValue');
        var tglfr = $('#lapKasMasukMainGridToolbarSearchTglFrom').datebox('getValue');
        var tglto = $('#lapKasMasukMainGridToolbarSearchTglTo').datebox('getValue');
        $('#lapKasMasukMainGrid').datagrid({
            queryParams: {
                dept: dept,
                jurs: jurs,
                kels: kels,
                tglfr: tglfr,
                tglto: tglto
            },
            pageNumber: 1
        });
    }

</script>
