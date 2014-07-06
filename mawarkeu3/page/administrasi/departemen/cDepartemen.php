<?php

require_once 'mDepartemen.php';
$mdlDepartemen = new mDepartemen();
switch ($_GET['act']){
    case 'data':
        $page = isset($_POST['page']) ? $_POST['page'] : '1';
        $row = isset($_POST['rows']) ? $_POST['rows'] : '10';
        $offset = ($page - 1) * $row;
        $key = isset($_POST['keySearch']) ? $_POST['keySearch'] : '';
        echo json_encode($mdlDepartemen->getDepartemenData($key, $offset, $row));
        break;
    case 'addNewDepartemen':
        $departemendesc = $_REQUEST['departemen'];
        echo json_encode($mdlDepartemen->doSaveDepartemen($departemendesc));
        break;
    case 'editDepartemen':
        $departemenid = $_REQUEST['departemenid'];
        $departemendesc = $_REQUEST['departemen'];
        echo json_encode($mdlDepartemen->doEditDepartemen($departemenid, $departemendesc));
        break;
    case 'deleteDepartemen':
        $departemenid = $_REQUEST['departemenid'];
        echo json_encode($mdlDepartemen->doDeleteDepartemen($departemenid));
        break;
    default :
        include 'vDepartemen.php';
        break;
}