<?php

require_once 'mLapPembSiswa.php';
$mdlLapPembSiswa = new mLapPembSiswa();
$act = filter_input(INPUT_GET, 'act');
$post = filter_input_array(INPUT_POST);
$get = filter_input_array(INPUT_GET);
switch ($act){
    case 'data':
        $page = isset($post['page']) ? $post['page'] : '1';
        $row = isset($post['rows']) ? $post['rows'] : '10';
        $offset = ($page - 1) * $row;
        $tllr = isset($post['tllr']) ? $post['tllr'] : '';
        $dept = isset($post['dept']) ? $post['dept'] : '';
        $jurs = isset($post['jurs']) ? $post['jurs'] : '';
        $kels = isset($post['kels']) ? $post['kels'] : '';
        $nama = isset($post['nama']) ? $post['nama'] : '';
        $jnsp = isset($post['jnsp']) ? $post['jnsp'] : '';
        $stts = isset($post['stts']) ? $post['stts'] : 'all';
        $tgfr = isset($post['tgfr']) ? $post['tgfr'] : '';
        $tgto = isset($post['tgto']) ? $post['tgto'] : '';
        echo json_encode($mdlLapPembSiswa->getLapPembSiswaData($tllr, $dept, $jurs, $kels, $nama, $jnsp, $stts, $tgfr, $tgto, $offset, $row));
        break;
    case 'comboSearchTeller':
        echo json_encode($mdlLapPembSiswa->getListTeller());
        break;
    case 'comboSearchDepartemen':
        echo json_encode($mdlLapPembSiswa->getListDepartemen());
        break;
    case 'comboSearchJurusan':
        $dept = filter_input(INPUT_GET, 'dept');
        echo json_encode($mdlLapPembSiswa->getListJurusan($dept));
        break;
    case 'comboSearchKelas':
        $dept = filter_input(INPUT_GET, 'dept');
        $jurs = filter_input(INPUT_GET, 'jurs');
        echo json_encode($mdlLapPembSiswa->getListKelas($dept, $jurs));
        break;
    case 'comboSearchJnsPemby':
        $dept = filter_input(INPUT_GET, 'dept');
        echo json_encode($mdlLapPembSiswa->getListJnsPemby($dept));
        break;
    case 'delPembayaran':
        $pbyrid = $post['pbyrid'];
        echo json_encode($mdlLapPembSiswa->doDelPembayaran($pbyrid));
        break;
    case 'saveLapPemb':
        $mdlLapPembSiswa->doSaveLapPemby($get);
        break;
    case 'undoDelPembayaran':
        $pbyrid = $post['pbyrid'];
        echo json_encode($mdlLapPembSiswa->doUndoDelPembayaran($pbyrid));
        break;
    default :
        include 'vLapPembSiswa.php';
        break;
}