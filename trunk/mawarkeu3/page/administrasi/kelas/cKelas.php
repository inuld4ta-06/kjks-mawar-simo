<?php

require_once 'mKelas.php';
$mdlKelas = new mKelas();
switch ($_GET['act']){
    case 'data':
        $page = isset($_POST['page']) ? $_POST['page'] : '1';
        $row = isset($_POST['rows']) ? $_POST['rows'] : '10';
        $offset = ($page - 1) * $row;
        $key = isset($_POST['keySearch']) ? $_POST['keySearch'] : '';
        echo json_encode($mdlKelas->getKelasData($key, $offset, $row));
        break;
    case 'addNewKelas':
        $jurs = $_REQUEST['jurs'];
        $kels = $_REQUEST['kels'];
        echo json_encode($mdlKelas->doSaveKelas($jurs, $kels));
        break;
    case 'editKelas':
        $jurs = $_REQUEST['jurs'];
        $kelsid = $_REQUEST['kelsid'];
        $kels = $_REQUEST['kels'];
        echo json_encode($mdlKelas->doEditKelas($jurs, $kelsid, $kels));
        break;
    case 'deleteKelas':
        $kelsid = $_REQUEST['kelsid'];
        echo json_encode($mdlKelas->doDeleteKelas($kelsid));
        break;
    case 'comboJurusan':
        echo json_encode($mdlKelas->getListJurusan());
        break;
    default :
        include 'vKelas.php';
        break;
}