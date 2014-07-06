<?php

session_start();
require_once 'mPembayaranSiswa.php';

$md = new mPembayaranSiswa();

$action = isset($_REQUEST['act']) ? $_REQUEST['act'] : 'ui';

switch ($action) {
    case 'ui':
        include 'vPembayaranSiswa.php';
        break;
    case 'givealert':
        echo "action: " . $action;
        break;
    case 'comboSiswa':
        echo json_encode($md->getComboDataSiswa($_GET['q']));
        break;
    case 'dobayar':
        $msid = $_REQUEST['msid'];
        $jenis = $_REQUEST['jenis'];
        $bulan = $_REQUEST['bulan'];
        $tahun = $_REQUEST['tahun'];
        $nominal = $_REQUEST['nominal'];
        $keterangan = $_REQUEST['keterangan'];
        $petugas = $_SESSION['user_name'];
        echo json_encode($md->doBayar($msid, $jenis, $bulan, $tahun, $nominal, $keterangan, $petugas));
        break;
    case 'hapusPembayaran':
        $pbyrid = $_POST['pbyrid'];
        $petugas = $_SESSION['user_name'];
        echo json_encode($md->doHapusPbyr($pbyrid, $petugas));
        break;
    case 'getStrukData':
        $msid = $_REQUEST['msid'];
        $data = $md->getStrukData($msid, $print = false);
        echo $data;
        break;
    case 'cetakStruk':
        $msid = $_REQUEST['msid'];
        $data = $md->getStrukData($msid, $print = true);
        echo $data;
        break;
    case 'loadJnsPmbyrn':
        echo json_encode($md->loadJnsPmbyrn($_REQUEST['ms_id']));
        break;
    case 'loadStrukPmbyrn':
        echo $md->loadStrukPmbyrn($_REQUEST['ms_id']);
        break;
    case 'getDataPembayaran':
        $page = isset($_POST['page']) ? $_POST['page'] : 1;
        $rows = isset($_POST['rows']) ? $_POST['rows'] : 10;
        $offset = ($page - 1) * $rows;
        $msid = $_REQUEST['msid'];
        $jenis = $_REQUEST['jenis'];
        $bulan = $_REQUEST['bulan'];
        $tahun = $_REQUEST['tahun'];
        echo json_encode($md->getDataPembayaran($msid, $jenis, $bulan, $tahun, $offset, $rows));
        break;
    case 'getdefaultpembayaran':
        $msid = $_POST['msid'];
        $jenis = $_POST['jenis'];
        $data = $md->getDefaultPembayaran($msid, $jenis);
        echo $data[0]['nominal'];
        break;
    case 'getNewStrukId':
        $msid = $_REQUEST['ms_id'];
        $result = $md->getNewStrukId($msid);
        echo json_encode($result);
        break;
    case 'getSiswaPhoto':
        $msid = $_POST['msid'];
        echo "<img src='../images/siswa/" . $msid . "'>";
        break;
    default :
        break;
}

