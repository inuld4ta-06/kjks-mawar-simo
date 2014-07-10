<?php

require_once 'mSiswa.php';
$mdlSiswa = new mSiswa();
switch ($_GET['act']) {
    case 'data':
        $dept = isset($_POST['dept']) ? $_POST['dept'] : '';
        $jurs = isset($_POST['jurs']) ? $_POST['jurs'] : '';
        $kels = isset($_POST['kels']) ? $_POST['kels'] : '';
        $name = isset($_POST['name']) ? $_POST['name'] : '';
        $order = isset($_POST['order']) ? $_POST['order'] : '';
        $sort = isset($_POST['sort']) ? $_POST['sort'] : '';
        $page = isset($_POST['page']) ? $_POST['page'] : 1;
        if($page == 0){
            $page = 1;
        }
        $row = isset($_POST['rows']) ? $_POST['rows'] : 10;
        $offset = ($page - 1) * $row;
        echo json_encode($mdlSiswa->getSiswaData($dept, $jurs, $kels, $name, $order, $sort, $offset, $row));
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
    case 'comboDept':
        echo json_encode($mdlSiswa->getListDept());
        break;
    case 'comboJurs':
        $mdid = $_REQUEST['mdid'];
        echo json_encode($mdlSiswa->getListJurs($mdid));
        break;
    case 'comboKels':
        $mdid = $_REQUEST['mdid'];
        $mjid = $_REQUEST['mjid'];
        echo json_encode($mdlSiswa->getListKels($mdid, $mjid));
        break;
    case 'comboKelas':
        echo json_encode($mdlSiswa->getListKelas());
        break;
    default :
        include 'vSiswa.php';
        break;
};
