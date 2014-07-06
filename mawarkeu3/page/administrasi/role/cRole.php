<?php

require_once 'mRole.php';
$roleMdl = new mRole();

switch ($_REQUEST['act']) {
    case 'data':
        $page = isset($_POST['page']) ? $_POST['page'] : '1';
        $row = isset($_POST['rows']) ? $_POST['rows'] : '10';
        $offset = ($page - 1) * $row;
        $key = isset($_POST['keySearch']) ? $_POST['keySearch'] : '';
        echo json_encode($roleMdl->getRoleData($key, $offset, $row));
        break;
    case 'addNewRole':
        $roleid = $_REQUEST['rolex'];
        $roledesc = $_REQUEST['deskripsix'];
        echo json_encode($roleMdl->doSaveRole($roleid, $roledesc));
        break;
    case 'editRole':
        $roleid = $_REQUEST['rolex'];
        $oldroleid = $_REQUEST['oldrolex'];
        $roledesc = $_REQUEST['deskripsix'];
        echo json_encode($roleMdl->doEditRole($roleid, $oldroleid, $roledesc));
        break;
    case 'deleteRole':
        $roleid = $_REQUEST['rolex'];
        echo json_encode($roleMdl->doDeleteRole($roleid));
        break;
    default:
        include 'vRole.php';
        break;
}