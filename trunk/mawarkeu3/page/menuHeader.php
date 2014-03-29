<a href="javascript:void(0)" class="easyui-linkbutton" data-options="plain:true" onclick="openMenu('Home', '');">Home</a>
<a href="javascript:void(0)" class="easyui-linkbutton" data-options="plain:true" onclick="openMenu('Pembayaran Siswa', 'pembayaranSiswa/cPembayaranSiswa');">Pembayaran Siswa</a>
<a href="javascript:void(0)" class="easyui-linkbutton" data-options="plain:true" onclick="openMenu('Laporan', 'cLaporan');">Laporan</a>
<a href="javascript:void(0)" class="easyui-menubutton" data-options="menu:'#menuDrop'">Setting</a>
<div id='menuDrop' style='width: 150px;'>
    <div onclick="openMenu('Jenis Pembayaran', 'cJenisPembayaran');">Jenis Pembayaran</div>
    <div onclick="openMenu('Default Pembayaran', 'cDefaultPembayaran');">Default Pembayaran</div>
</div>
