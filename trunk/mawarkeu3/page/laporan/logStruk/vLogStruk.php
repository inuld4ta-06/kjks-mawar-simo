<table id="lapLogStrukMainGrid"></table>
<div id="lapLogStrukMainGridToolbar" style="padding:5px;">
    Nomor Struk <input id="lapLogStrukMainGridToolbarSearchNoStruk" onchange="lapLogStruk_searchStruk();">
    Nama Siswa <input id="lapLogStrukMainGridToolbarSearchNamaSiswa" onchange="lapLogStruk_searchStruk();">
    Departemen <input id="lapLogStrukMainGridToolbarSearchDepartemen" onchange="lapLogStruk_searchStruk();">
    Tanggal <input id="lapLogStrukMainGridToolbarSearchTglBeg" class="easyui-datebox" onchange="lapLogStruk_searchStruk();">
    s/d <input id="lapLogStrukMainGridToolbarSearchTglEnd" class="easyui-datebox" onchange="lapLogStruk_searchStruk();">
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-search" onclick="lapLogStruk_searchStruk();">Cari</a>
</div>

<script>
    $('#lapLogStrukMainGrid').datagrid({
        columns: [[
                {field: 'struk_id', title: 'No Struk', width: 100},
                {field: 'ms_nama', title: 'Nama Siswa', width: 300},
                {field: 'md_nama', title: 'Departemen', width: 200},
                {field: 'pbyr_createdate', title: 'Tanggal', width: 150}
            ]],
        toolbar: '#lapLogStrukMainGridToolbar',
        pagination: true, rownumbers: true, fit: true,
        url: '<?= createUrl() ?>&act=data',
        singleSelect: true,
        view: detailview,
        detailFormatter: function (index, row) {
            return '<div id="lapLogStrukMainGrid_detailgrid_' + index + '" style="padding:5px 0"></div>';
        },
        onExpandRow: function (index, row) {
            $('#lapLogStrukMainGrid_detailgrid_' + index).panel({
                border: false,
                cache: false,
                href: '<?= createUrl() ?>&act=detailgrid&struk_id=' + row.struk_id + '&index=' + index,
                onLoad: function () {
                    $('#lapLogStrukMainGrid').datagrid('fixDetailRowHeight', index);
                }
            });
            $('#lapLogStrukMainGrid').datagrid('fixDetailRowHeight', index);
        }
    });

    function lapLogStruk_searchStruk() {
        var strk = $('#lapLogStrukMainGridToolbarSearchNoStruk').val();
        var nama = $('#lapLogStrukMainGridToolbarSearchNamaSiswa').val();
        var dept = $('#lapLogStrukMainGridToolbarSearchDepartemen').val();
        var tgl1 = $('#lapLogStrukMainGridToolbarSearchTglBeg').datebox('getValue');
        var tgl2 = $('#lapLogStrukMainGridToolbarSearchTglEnd').datebox('getValue');
        $('#lapLogStrukMainGrid').datagrid({
            queryParams: {
                strk: strk,
                nama: nama,
                dept: dept,
                tgl1: tgl1,
                tgl2: tgl2
            },
            pageNumber: 1
        });
    }

    var url;

    function lapLogStruk_openAddnewDepartemen() {
        $('#lapLogStruk_dialog_inputDepartemenId').val('');
        $('#lapLogStruk_dialog_inputDepartemen').val('');
        $('#lapLogStruk_dialogAddEdit').dialog('open').dialog('setTitle', 'Tambah Departemen Baru');
        $('#lapLogStruk_dialog_inputDepartemen').focus();
        url = '<?= createUrl() ?>&act=addNewDepartemen';
    }

    function lapLogStruk_openEditDepartemen() {
        var g = $('#lapLogStrukMainGrid').datagrid('getSelected');
        if (g) {
            url = '<?= createUrl() ?>&act=editDepartemen';
            $('#lapLogStruk_dialogAddEdit').dialog('open').dialog('setTitle', 'Edit Departemen');
            $('#lapLogStruk_dialog_inputDepartemenId').val(g.md_id);
            $('#lapLogStruk_dialog_inputDepartemen').val(g.md_nama);
        } else {
            alert('Pilih dulu salah satu departemen yang akan di-edit.');
        }
    }

    function lapLogStruk_doAddEditDepartemen() {
        var departemen = $('#lapLogStruk_dialog_inputDepartemen').val();
        var departemenid = $('#lapLogStruk_dialog_inputDepartemenId').val();
        if (departemen == '') {
            alert('Departemen harus diisi');
            $('#lapLogStruk_dialog_inputDepartemen').focus();
            return false;
        }
        $.post(
                url,
                {
                    departemen: departemen,
                    departemenid: departemenid
                },
        function (result) {
            if (result.success) {
                $('#lapLogStruk_dialogAddEdit').dialog('close');
                $('#lapLogStrukMainGrid').datagrid('reload');
            } else {
                alert(result.msg);
                ;
            }
        },
                'json'
                );
    }

    function lapLogStruk_doDeleteDepartemen() {
        var g = $('#lapLogStrukMainGrid').datagrid('getSelected');
        if (g) {
            $.post(
                    '<?= createUrl() ?>&act=deleteDepartemen',
                    {departemenid: g.md_id},
            function (result) {
                if (result.success) {
                    $('#lapLogStruk_dialogAddEdit').dialog('close');
                    $('#lapLogStrukMainGrid').datagrid('reload');
                } else {
                    alert(result.msg);
                    ;
                }
            },
                    'json'
                    );
        }
    }

    function lapLogStruk_printStruk(strukid) {
        w = window.open();
        w.document.write($('#lapLogStruk_printArea_' + strukid).html());
        w.print();
        w.close();
    }

</script>