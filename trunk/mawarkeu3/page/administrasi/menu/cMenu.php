<?php

require_once 'mMenu.php';

$mdl = new mMenu();

switch ($_REQUEST['act']) {
    case 'data':
        $page = isset($_POST['page']) ? $_POST['page'] : '1';
        $row = isset($_POST['rows']) ? $_POST['rows'] : '10';
        $offset = ($page - 1) * $row;
        $key = isset($_POST['keySearch']) ? $_POST['keySearch'] : '';
        echo json_encode($mdl->getMenuData($key, $offset, $row));
        break;
    case 'detailgrid':
        $menuId = $_GET['menuID'];
        $indexdatagrid = $_GET['index'];
        echo <<<h
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" onclick="admMenu_openAddRole($menuId, $indexdatagrid);">Tambah Role</a>
        <ul>
h;
        foreach ($mdl->detailGrid($menuId) as $row) {
            $namaRole = $row['role_id'];
            echo <<<h
        <li>$namaRole <a href="javascript:void(0)" style="color: red" onclick="admMenu_delRole($menuId, '$namaRole', $indexdatagrid);">[Hapus]</a></li>
h;
        }
        echo <<<h
        </ul>
h;
        break;
    case 'addNewMenu' :
        $path = $_POST['pathx'];
        $name = $_POST['namex'];
        $prid = $_POST['pridx'];
        $type = $_POST['typex'];
        $ordr = $_POST['ordrx'];
        $enab = $_POST['enabx'];
        echo json_encode($mdl->addNewMenu($path, $name, $prid, $type, $ordr, $enab));
        break;
    case 'editMenu':
        $path = $_POST['pathx'];
        $oldpath = $_POST['oldpathx'];
        $name = $_POST['namex'];
        $prid = $_POST['pridx'];
        $type = $_POST['typex'];
        $ordr = $_POST['ordrx'];
        $enab = $_POST['enabx'];
        echo json_encode($mdl->editMenu($path, $oldpath, $name, $prid, $type, $ordr, $enab));
        break;
    case 'deleteMenu':
        $menuid = $_REQUEST['menuid'];
        echo json_encode($mdl->doDeleteMenu($menuid));
        break;
    case 'addRole':
        $menuid = $_POST['menuid'];
        $roleid = $_POST['roleid'];
        echo json_encode($mdl->addRoleToMenu($menuid, $roleid));
        break;
    case 'delrole':
        $menuid = $_POST['menuid'];
        $roleid = $_POST['roleid'];
        echo json_encode($mdl->delRoleFromMenu($menuid, $roleid));
        break;
    case 'comboListRole' :
        echo json_encode($mdl->getListRole());
        break;
    case 'comboListParentMenu':
        echo json_encode($mdl->getListParentMenu());
        break;
    default :
        include 'vMenu.php';
        break;
}