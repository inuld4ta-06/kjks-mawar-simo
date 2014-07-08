<?php

require_once 'mSiswa.php';
$mdlSiswa = new mSiswa();
switch ($_GET['act']) {
    case 'data':
        $page = isset($_POST['page']) ? $_POST['page'] : '1';
        $row = isset($_POST['rows']) ? $_POST['rows'] : '10';
        $offset = ($page - 1) * $row;
        $key = isset($_POST['keySearch']) ? $_POST['keySearch'] : '';
        echo json_encode($mdlSiswa->getSiswaData($key, $offset, $row));
        break;
    case 'addNewSiswa':
        $nis = $_REQUEST['nis'];
        $nisn = $_REQUEST['nisn'];
        $nama = $_REQUEST['nama'];
        $gend = $_REQUEST['gend'];
        $tmpl = $_REQUEST['tmpl'];
        $tgll = $_REQUEST['tgll'];
        $kels = $_REQUEST['kels'];
        echo json_encode($mdlSiswa->doSaveSiswa($nis, $nisn, $nama, $gend, $tmpl, $tgll, $kels));
        break;
    case 'editSiswa':
        $msid = $_REQUEST['msid'];
        $nis = $_REQUEST['nis'];
        $nisn = $_REQUEST['nisn'];
        $nama = $_REQUEST['nama'];
        $gend = $_REQUEST['gend'];
        $tmpl = $_REQUEST['tmpl'];
        $tgll = $_REQUEST['tgll'];
        $kels = $_REQUEST['kels'];
        echo json_encode($mdlSiswa->doEditSiswa($msid, $nis, $nisn, $nama, $gend, $tmpl, $tgll, $kels));
        break;
    case 'deleteSiswa':
        $msid = $_REQUEST['msid'];
        echo json_encode($mdlSiswa->doDeleteSiswa($msid));
        break;
    case 'comboKelas':
        echo json_encode($mdlSiswa->getListKelas());
        break;
    default :
        include 'vSiswa.php';
        break;
};
