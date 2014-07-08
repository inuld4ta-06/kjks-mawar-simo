<?php

require 'mJenisPembayaran.php';
$mdlJenisPembayaran = new mJenisPembayaran();
switch ($_GET['act']){
    case 'data':
        $page = isset($_POST['page']) ? $_POST['page'] : '1';
        $row = isset($_POST['rows']) ? $_POST['rows'] : '10';
        $offset = ($page - 1) * $row;
        $key = isset($_POST['keySearch']) ? $_POST['keySearch'] : '';
        echo json_encode($mdlJenisPembayaran->getJenisPembayaranData($key, $offset, $row));
        break;
    case 'addNewJenisPembayaran':
        $dept = $_REQUEST['dept'];
        $mtjenis = $_REQUEST['mtjenis'];
        echo json_encode($mdlJenisPembayaran->doSaveJenisPembayaran($dept, $mtjenis));
        break;
    case 'editJenisPembayaran':
        $dept = $_REQUEST['dept'];
        $mtid = $_REQUEST['mtid'];
        $mtjenis = $_REQUEST['mtjenis'];
        echo json_encode($mdlJenisPembayaran->doEditJenisPembayaran($dept, $mtid, $mtjenis));
        break;
    case 'deleteJenisPembayaran':
        $mtid = $_REQUEST['mtid'];
        echo json_encode($mdlJenisPembayaran->doDeleteJenisPembayaran($mtid));
        break;
    case 'comboDepartemen':
        echo json_encode($mdlJenisPembayaran->getListDepartemen());
        break;
    default :
        include 'vJenisPembayaran.php';
        break;
}