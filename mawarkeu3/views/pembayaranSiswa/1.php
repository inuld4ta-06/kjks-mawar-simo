<?php // Pembayaran Syahriyah ?>
<script>
    $('#syahriyah_maingrid').datagrid({
        toolbar:'#syahriyah_maingrid_toolbar',
        columns:[[
                {title:'Tahun',width:50},
                {title:'Bulan',width:100},
                {title:'Jumlah',width:100}
        ]]
    });
</script>
<table id="syahriyah_maingrid"></table>
<div id="syahriyah_maingrid_toolbar" style="padding: 5px;">
    <select id="syahriyah_input_tahun">
        <?php
        $year = date('Y');
        for ($y = $year - 3; $y < $year + 3; $y++){
            ?>
        <option value="<?= $y ?>" <?php if($y==$year){ echo 'selected="selected"'; } ?>><?= $y ?></option>
            <?php
        }
        ?>
    </select>
    <select id="syahriyah_input_bulan">
        <option value="1">Januari</option>
        <option value="2">Februari</option>
        <option value="3">Maret</option>
        <option value="4">April</option>
        <option value="5">Mei</option>
        <option value="6">Juni</option>
        <option value="7">Juli</option>
        <option value="8">Agustus</option>
        <option value="9">September</option>
        <option value="10">Oktober</option>
        <option value="11">November</option>
        <option value="12">Desember</option>
    </select>
    <input type="text" id="syahriyah_input_nominal" class="easyui-numberbox" data-options="prefix: 'Rp ',groupSeparator:'.'">
    <a href="javascript:void(0)" class="easyui-linkbutton">Bayar</a>
</div>
