<?php

/**
 * Description of cAdminUser
 *
 * @author mzainularifin
 */
require_once 'mAdminUser.php';
$madminuser = new mAdminUser();
$post = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
switch (filter_input(INPUT_GET, 'act')) {
    case 'data';
        $page = isset($post['page']) ? $post['page'] : '1';
        $row = isset($post['rows']) ? $post['rows'] : '10';
        $offset = ($page - 1) * $row;
        $search = isset($post['key']) ? $post['key'] : '';
        echo json_encode($madminuser->getMainData($offset, $row, $search));
        break;
    case 'detailgrid':
        $username = filter_input(INPUT_GET, 'username');
        $index = filter_input(INPUT_GET, 'index');
        echo <<<h
<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" onclick="admUser_openAddRole($index,'$username')">Tambah Role</a>
<ul> 
h;
        foreach ($madminuser->getUserRole($username) as $row) {
            $namarole = $row['user_role'];
            echo <<<h
            <li>$namarole <a href="javascript:void(0)" style="color: red" onclick="admUser_doDeleteRoleFromUser($index,'$username','$namarole')">[hapus]</a></li>
h;
        }
        echo <<<h
</ul>
h;
        break;
    case 'addNewUser':
        if (empty($post['user_name'])) {
            echo json_encode(array('success' => false, 'msg' => "Empty Username"));
        } elseif (empty($post['user_password_new']) || empty($post['user_password_repeat'])) {
            echo json_encode(array('success' => false, 'msg' => "Empty Password"));
        } elseif ($post['user_password_new'] !== $post['user_password_repeat']) {
            echo json_encode(array('success' => false, 'msg' => "Password and password repeat are not the same"));
        } elseif (strlen($post['user_password_new']) < 6) {
            echo json_encode(array('success' => false, 'msg' => "Password has a minimum length of 6 characters"));
        } elseif (strlen($post['user_name']) > 64 || strlen($post['user_name']) < 2) {
            echo json_encode(array('success' => false, 'msg' => "Username cannot be shorter than 2 or longer than 64 characters"));
        } elseif (!preg_match('/^[a-z\d]{2,64}$/i', $post['user_name'])) {
            echo json_encode(array('success' => false, 'msg' => "Username does not fit the name scheme: only a-Z and numbers are allowed, 2 to 64 characters"));
        } elseif (empty($post['user_email'])) {
            echo json_encode(array('success' => false, 'msg' => "Email cannot be empty"));
        } elseif (strlen($post['user_email']) > 64) {
            echo json_encode(array('success' => false, 'msg' => "Email cannot be longer than 64 characters"));
        } elseif (!filter_var($post['user_email'], FILTER_VALIDATE_EMAIL)) {
            echo json_encode(array('success' => false, 'msg' => "Your email address is not in a valid email format"));
        } elseif (!empty($post['user_name'])
            && strlen($post['user_name']) <= 64
            && strlen($post['user_name']) >= 2
            && preg_match('/^[a-z\d]{2,64}$/i', $post['user_name'])
            && !empty($post['user_email'])
            && strlen($post['user_email']) <= 64
            && filter_var($post['user_email'], FILTER_VALIDATE_EMAIL)
            && !empty($post['user_password_new'])
            && !empty($post['user_password_repeat'])
            && ($post['user_password_new'] === $post['user_password_repeat'])
        ) {
            require_once("libraries/password_compatibility_library.php");
            $user = $post['user_name'];
            $email = $post['user_email'];
            $dept = $post['dept'];
            $password = password_hash($post['user_password_new'], PASSWORD_DEFAULT);
            echo json_encode($madminuser->saveAddUser($user, $email, $dept, $password));
        }        
        break;
    case 'editUser':
        if (empty($post['user_name'])) {
            echo json_encode(array('success' => false, 'msg' => "Empty Username"));
        } elseif (strlen($post['user_name']) > 64 || strlen($post['user_name']) < 2) {
            echo json_encode(array('success' => false, 'msg' => "Username cannot be shorter than 2 or longer than 64 characters"));
        } elseif (!preg_match('/^[a-z\d]{2,64}$/i', $post['user_name'])) {
            echo json_encode(array('success' => false, 'msg' => "Username does not fit the name scheme: only a-Z and numbers are allowed, 2 to 64 characters"));
        } elseif (empty($post['user_email'])) {
            echo json_encode(array('success' => false, 'msg' => "Email cannot be empty"));
        } elseif (strlen($post['user_email']) > 64) {
            echo json_encode(array('success' => false, 'msg' => "Email cannot be longer than 64 characters"));
        } elseif (!filter_var($post['user_email'], FILTER_VALIDATE_EMAIL)) {
            echo json_encode(array('success' => false, 'msg' => "Your email address is not in a valid email format"));
        } elseif (!empty($post['user_name'])
            && strlen($post['user_name']) <= 64
            && strlen($post['user_name']) >= 2
            && preg_match('/^[a-z\d]{2,64}$/i', $post['user_name'])
            && !empty($post['user_email'])
            && strlen($post['user_email']) <= 64
            && filter_var($post['user_email'], FILTER_VALIDATE_EMAIL)
        ) {
            $userid = $post['userid'];
            $user = $post['user_name'];
            $email = $post['user_email'];
            $dept = $post['dept'];
            echo json_encode($madminuser->saveEditUser($userid, $user, $email, $dept));
        }        
        break;
    case 'resetPassword':
        if (empty($post['user_password_new']) || empty($post['user_password_repeat'])) {
            echo json_encode(array('success' => false, 'msg' => "Empty Password"));
        } elseif ($post['user_password_new'] !== $post['user_password_repeat']) {
            echo json_encode(array('success' => false, 'msg' => "Password and password repeat are not the same"));
        } elseif (strlen($post['user_password_new']) < 6) {
            echo json_encode(array('success' => false, 'msg' => "Password has a minimum length of 6 characters"));
        } elseif (
            !empty($post['user_password_new'])
            && !empty($post['user_password_repeat'])
            && ($post['user_password_new'] === $post['user_password_repeat'])
        ) {
            require_once("libraries/password_compatibility_library.php");
            $userid = $post['userid'];
            $password = password_hash($post['user_password_new'], PASSWORD_DEFAULT);
            echo json_encode($madminuser->resetPassword($userid, $password));
        }        
        break;
    case 'delUser':
        $user = filter_input(INPUT_POST, 'user');
        echo json_encode($madminuser->doDelUser($user));
        break;
    case 'addRole':
        $index = filter_input(INPUT_GET, 'index');
        $user = filter_input(INPUT_GET, 'user');
        $role = filter_input(INPUT_POST, 'role');
        echo json_encode($madminuser->doAddRoleForUser($index, $user, $role));
        break;
    case 'doDelRole':
        $user = filter_input(INPUT_POST, 'user');
        $role = filter_input(INPUT_POST, 'role');
        echo json_encode($madminuser->doDelRoleFromUser($user, $role));
        break;
    case 'comboRole':
        echo json_encode($madminuser->getListRole());
        break;
    case 'comboDepartemen':
        $data = array();
        array_push($data, array('md_nama' => 'all'));
        foreach ($madminuser->getListDept() as $row){
            array_push($data, $row);
        }
        echo json_encode($data);
        break;
    default :
        include 'vAdminUser.php';
        break;
}