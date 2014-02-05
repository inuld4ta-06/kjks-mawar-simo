<?php
session_start();
require_once '../classes/mPembayaranSiswa.php';

$md = new mPembayaranSiswa();

$action = isset($_REQUEST['act']) ? $_REQUEST['act'] : 'ui';

switch ($action) {
    case 'ui':
        include '../views/vPembayaranSiswa.php';
        break;
    case 'givealert':
        echo "action: " . $action;
        break;
    case 'comboSiswa':
        echo json_encode($md->getComboDataSiswa($_GET['q']));
        break;
    case 'loadJnsPmbyrn':
        echo $md->loadJnsPmbyrn($_REQUEST['ms_id']);
        break;
    case 'loadPmbyrn':
        $mtid = $_REQUEST['mt_id'];
        $msid = $_REQUEST['ms_id'];
        include '../views/pembayaranSiswa/'.$mtid.".php";
        break;
    default :
        break;
}

