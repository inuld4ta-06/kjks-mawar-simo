<?php

require_once 'mLapPembSiswa.php';
$mdlLapPembSiswa = new mLapPembSiswa();
$act = filter_input(INPUT_GET, 'act');
$post = filter_input_array(INPUT_POST);
switch ($act){
    case 'data':
        $page = isset($post['page']) ? $post['page'] : '1';
        $row = isset($post['rows']) ? $post['rows'] : '10';
        $offset = ($page - 1) * $row;
        $dept = isset($post['dept']) ? $post['dept'] : '';
        $stts = isset($post['stts']) ? $post['stts'] : 'all';
        $tgfr = isset($post['tgfr']) ? $post['tgfr'] : '';
        $tgto = isset($post['tgto']) ? $post['tgto'] : '';
        echo json_encode($mdlLapPembSiswa->getLapPembSiswaData($dept, $stts, $tgfr, $tgto, $offset, $row));
        break;
    case 'comboSearchDepartemen':
        echo json_encode($mdlLapPembSiswa->getListDepartemen());
        break;
    case 'delPembayaran':
        $pbyrid = $post['pbyrid'];
        echo json_encode($mdlLapPembSiswa->doDelPembayaran($pbyrid));
        break;
    case 'undoDelPembayaran':
        $pbyrid = $post['pbyrid'];
        echo json_encode($mdlLapPembSiswa->doUndoDelPembayaran($pbyrid));
        break;
    default :
        include 'vLapPembSiswa.php';
        break;
}