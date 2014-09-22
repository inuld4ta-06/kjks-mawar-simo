<?php

require_once 'mLogStruk.php';
$mdlLogStruk = new mLogStruk();
switch ($_GET['act']){
    case 'data':
        $page = isset($_POST['page']) ? $_POST['page'] : '1';
        $row = isset($_POST['rows']) ? $_POST['rows'] : '10';
        $offset = ($page - 1) * $row;
        $strk = isset($_POST['strk']) ? $_POST['strk'] : '';
        $nama = isset($_POST['nama']) ? $_POST['nama'] : '';
        $dept = isset($_POST['dept']) ? $_POST['dept'] : '';
        $tgl1 = isset($_POST['tgl1']) ? $_POST['tgl1'] : '';
        $tgl2 = isset($_POST['tgl2']) ? $_POST['tgl2'] : '';
        echo json_encode($mdlLogStruk->getStrukData($strk, $nama, $dept, $tgl1, $tgl2, $offset, $row));
        break;
    case 'detailgrid':
        $struk = $_GET['struk_id'];
        echo "<a ref='javascript:void(0)' class='easyui-linkbutton' iconCls='icon-print' onclick='lapLogStruk_printStruk($struk);'>Print Ulang</a>";
        echo $mdlLogStruk->getDetailStrukData($struk);
        break;
    case 'printStruk':
        $struk = $_GET['struk_id'];
        echo $mdlLogStruk->getDetailStrukData($struk);
        break;
    default :
        include 'vLogStruk.php';
        break;
}