<?php

require_once 'mRekapPemb.php';
$mdlRekapPemb = new mRekapPemb();
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
        echo json_encode($mdlRekapPemb->getLapPembSiswaData($tllr, $dept, $jurs, $kels, $nama, $jnsp, $stts, $tgfr, $tgto, $offset, $row));
        break;
    case 'comboSearchDepartemen':
        require_once dirname(__FILE__) . '/../pembayaransiswa/mLapPembSiswa.php';
        $mdlLapPembSiswa = new mLapPembSiswa();
        echo json_encode($mdlLapPembSiswa->getListDepartemen());
        break;
    case 'comboSearchPembayaran':
        $dept = !empty($get['dept']) ? $get['dept'] : '';
        echo json_encode($mdlRekapPemb->getListJenisPembayaran($dept));
        break;
    case 'comboSearchKelas':
        $dept = !empty($get['dept']) ? $get['dept'] : '';
        echo json_encode($mdlRekapPemb->getListKelas($dept));
        break;
    case 'comboSearchSiswa':
        $kels = !empty($get['kels']) ? $get['kels'] : '';
        $dept = !empty($get['dept']) ? $get['dept'] : '';
        $ta = !empty($get['ta']) ? $get['ta'] : '';
        $siswa = !empty($post['q']) ? $post['q'] : '';
        echo json_encode($mdlRekapPemb->getListSiswa($dept, $kels, $ta, $siswa));
        break;
    case 'comboSearchTahunajaran':
        echo json_encode($mdlRekapPemb->getListTahunajaran());
        break;
    case 'doSearchRekapPemb':
        $page = isset($_POST['page']) ? intval($post['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($post['rows']) : 10;
        $offset = ($page - 1) * $rows;
        
        $dept = $post['dept'];
        $tahunajaran = $post['ta'];
        $trans = $post['trans'];
        $kelas = $post['kels'];
        $siswa = $post['siswa'];
        $tglPer = $post['tglPer'];
        $tglPerTo = $post['tglPerTo'];
        echo json_encode($mdlRekapPemb->doSearchRekapPemb($dept, $kelas, $tahunajaran, $trans, $siswa, $tglPer, $tglPerTo, $offset, $rows));
        break;
    case 'doSaveRekapPemb':
        $dept = $get['dept'];
        $kels = $get['kels'];
        $tahunajaran = $get['ta'];
        $trans = $get['trans'];
        $siswa = $get['siswa'];
        $tglPer = $get['tglPer'];
        $tglPerTo = $get['tglPerTo'];
        $mdlRekapPemb->doSaveRekapPemb($dept, $kels, $tahunajaran, $trans, $siswa, $tglPer, $tglPerTo);
        break;
    default :
        include 'vRekapPemb.php';
        break;
}
