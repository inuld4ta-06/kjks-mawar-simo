<?php
session_start();
require_once '../classes/mJenisPembayaran.php';

$md = new mJenisPembayaran();

$action = isset($_REQUEST['act']) ? $_REQUEST['act'] : 'ui';

switch ($action) {
    case 'ui':
        include '../views/vJenisPembayaran.php';
        break;
    case 'comboDepartemen':
        echo json_encode($md->getListDepartemen());
        break;
    case 'maingrid':
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;
        echo json_encode($md->getListJnsPbyr($offset, $rows));
        break;
    case 'save':
        $dept = $_REQUEST['dept'];
        $namaTrans = $_REQUEST['trans'];
        echo json_encode($md->saveJnsPbyrn($dept, $namaTrans));
        break;
    case 'update':
        $mtid = $_REQUEST['mt_id'];
//        $dept = $_REQUEST['dept'];
        $namaTrans = $_REQUEST['trans'];
        echo json_encode($md->updateJnsPbyrn($mtid, $namaTrans));
        break;
    case 'delete':
        $mtid = $_REQUEST['mtid'];
        echo json_encode($md->deleteJnsPbyrn($mtid));
        break;
    default :
        break;
}

