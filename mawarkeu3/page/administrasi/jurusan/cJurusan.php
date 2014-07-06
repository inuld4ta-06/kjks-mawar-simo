<?php

require_once 'mJurusan.php';
$mdlJurusan = new mJurusan();
switch ($_GET['act']){
    case 'data':
        $page = isset($_POST['page']) ? $_POST['page'] : '1';
        $row = isset($_POST['rows']) ? $_POST['rows'] : '10';
        $offset = ($page - 1) * $row;
        $key = isset($_POST['keySearch']) ? $_POST['keySearch'] : '';
        echo json_encode($mdlJurusan->getJurusanData($key, $offset, $row));
        break;
    case 'addNewJurusan':
        $dept = $_REQUEST['dept'];
        $jurs = $_REQUEST['jurs'];
        echo json_encode($mdlJurusan->doSaveJurusan($dept, $jurs));
        break;
    case 'editJurusan':
        $dept = $_REQUEST['dept'];
        $jursid = $_REQUEST['jursid'];
        $jurs = $_REQUEST['jurs'];
        echo json_encode($mdlJurusan->doEditJurusan($dept, $jursid, $jurs));
        break;
    case 'deleteJurusan':
        $jursid = $_REQUEST['jursid'];
        echo json_encode($mdlJurusan->doDeleteJurusan($jursid));
        break;
    case 'comboDepartemen':
        echo json_encode($mdlJurusan->getListDepartemen());
        break;
    default :
        include 'vJurusan.php';
        break;
}