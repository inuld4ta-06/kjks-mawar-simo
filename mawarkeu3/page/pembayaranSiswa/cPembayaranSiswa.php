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
    case 'loadJnsPmbyrn':
        echo json_encode($md->loadJnsPmbyrn($_REQUEST['ms_id']));
        break;
    case 'loadStrukPmbyrn':
        echo $md->loadStrukPmbyrn($_REQUEST['ms_id']);
        break;
    case 'loadPmbyrn':
        $mtid = $_REQUEST['mt_id'];
        $msid = $_REQUEST['ms_id'];
        include '../views/pembayaranSiswa/' . $mtid . ".php";
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
    case 'getSyahriyahMainGrid':
        $msid = $_GET['msid'];
        echo json_encode(array('rows' => $md->getDetailSyahriyah($msid), 'footer' => $md->getFooterSyahriyah($msid)));
        break;
    case 'getSyahriyahSiswaDefaultNominal' :
        $msid = $_POST['msid'];
        $data = $md->getSyahriyahSiswaDefaultNominal($msid);
        echo $data[0]['ms_defaultSyahriyahNominal'];
        break;
    case 'doSyahriyahBayar':
        $msid = $_POST['msid'];
        $nominal = $_POST['nominal'];
        $tahun = $_POST['tahun'];
        $bulan = $_POST['bulan'];
        $result = $md->doSyahriyahBayar($msid, $tahun, $bulan, $nominal);
        echo json_encode($result);
        break;
    default :
        break;
}

