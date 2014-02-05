<?php

$act = isset($_REQUEST['p']) ? $_REQUEST['p'] : 'ui';

switch ($act) {
    case 'ui':
        include 'views/template.php';
    case 'pembayaransiswa':
        include 'controller/cPembayaranSiswa.php';
        break;
    default :
        break;
}

