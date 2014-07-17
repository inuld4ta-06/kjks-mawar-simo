<?php

/**
 * Description of mAdminUser
 *
 * @author mzainularifin
 */
//include_once '../../../classes/mDbConn.php';
class mAdminUser extends mDbConn {

    //put your code here
    public function getMainData($offset, $rows, $searchkey) {
        $q = "select user_id, user_name, user_password_hash, user_email, departemen, locked "
                . "from users "
                . "where (user_name like '%$searchkey%' or user_email like '%$searchkey%')"
                . "limit $offset, $rows";
        $rowz = $this->fetchQuery($q);

        $qtot = "select count(*) as jum "
                . "from users "
                . "where (user_name like '%$searchkey%' or user_email like '%$searchkey%')";
        foreach ($this->fetchQuery($qtot) as $r) {
            $rtot = $r['jum'];
        }
        $totz = $rtot;

        return array('rows' => $rowz, 'total' => $totz);
    }
    
    public function saveAddUser($user, $email, $dept, $password) {
        $db = $this->getConn();
        // cek dulu apakah sudah ada username tersebut
        $cek1 = $db->prepare("select * from users where user_name=:username");
        $cek1->execute(array(':username' => $user));
        if($cek1->rowCount() > 0){
            $db = null;
            return array('success' => false, 'msg' => 'Username tersebut sudah ada yang memakai. Silakan pilih username yang lain');
        } else {
            // masukkan ke database
            $ins1 = $db->prepare("INSERT INTO users (user_name, user_password_hash, user_email, departemen) VALUES(:username, :password, :email, :dept)");
            $ins1->execute(array(':username' => $user, ':password' => $password, ':email' => $email, ':dept' => $dept));
            if($ins1->rowCount() > 0){
                $db = null;
                return array('success' => true, 'msg' => 'User baru berhasil ditambahkan');
            } else {
                $db = null;
                return array('success' => false, 'msg' => 'Ada kesalahan. Silakan coba lagi.');
            }
        }
    }

    public function saveEditUser($userid, $user, $email, $dept) {
        $db = $this->getConn();
        // cek dulu apakah sudah ada username tersebut
        $cek1 = $db->prepare("select * from users where user_name=:username and user_id <> :userid");
        $cek1->execute(array(':username' => $user, ':userid' => $userid));
        if($cek1->rowCount() > 0){
            $db = null;
            return array('success' => false, 'msg' => 'Username tersebut sudah ada yang memakai. Silakan pilih username yang lain');
        } else {
            // update database
            $ins1 = $db->prepare("update users set user_name=:username, user_email=:email, departemen=:dept where user_id=:userid");
            $ins1->execute(array(':userid' => $userid, ':username' => $user, ':email' => $email, ':dept' => $dept));
            if($ins1->rowCount() > 0){
                $db = null;
                return array('success' => true, 'msg' => 'User berhasil diupdate');
            } else {
                $db = null;
                return array('success' => false, 'msg' => 'Ada kesalahan. Silakan coba lagi.');
            }
        }
    }
    
    public function resetPassword($userid, $password) {
        $db = $this->getConn();
        // update database
        $reset1 = $db->prepare("update users set user_password_hash=:password where user_id=:userid");
        $reset1->execute(array(':userid' => $userid, ':password' => $password));
        if($reset1->rowCount() > 0){
            $db = null;
            return array('success' => true, 'msg' => 'Password berhasil direset');
        } else {
            $db = null;
            return array('success' => false, 'msg' => 'Ada kesalahan. Silakan coba lagi.');
        }
    }

    public function doDelUser($user) {
        $db = $this->getConn();
        // cek dulu dia punya role ga?
        $cekrole = $db->prepare("select * from users_role where user_name=:user");
        $cekrole->execute(array(':user' => $user));
        if($cekrole->rowCount() > 0){
            // delete dulu role2 yg dia punya
            $stmt = $db->prepare("delete from users_role where user_name=:user");
            $stmt->execute(array(':user' => $user));
        }

        // kemudian baru delete usernya
        $stmt2 = $db->prepare("delete from users where user_name=:user");
        $stmt2->execute(array(':user' => $user));
        if ($stmt2->rowCount() > 0) {
            $db = null;
            return array('success' => true, 'msg' => "User $user telah berhasil dihapus");
        } else {
            $db = null;
            return array('success' => false, 'msg' => "Ada kesalahan");
        }
    }

    public function getUserRole($userid) {
        return $this->fetchQuery("select user_role from users_role where user_name='$userid'");
    }

    public function getListRole() {
        return $this->fetchQuery("select * from m_role order by role_id asc");
    }
    
    public function getListDept() {
        return $this->fetchQuery("select md_nama from m_departemen order by md_nama asc");
    }

    public function doAddRoleForUser($index, $user, $role) {
        // cek role sudah ada di user tersebut atau belum
        if (!$this->userHadRole($user, $role)) {
            $db = $this->getConn();
            $stmt = $db->prepare("insert into users_role(user_name, user_role) values(:user, :role)");
            $stmt->execute(array(':user' => $user, ':role' => $role));
            if ($stmt->rowCount() > 0) {
                $db = null;
                return array('success' => true, 'index' => $index, 'user_name' => $user);
            } else {
                $db = null;
                return array('success' => false, 'msg' => 'Ada kesalahan.');
            }
        } else {
            return array('success' => false, 'msg' => 'Role yang ingin ditambahkan sudah ada pada User tersebut.');
        }
    }

    private function userHadRole($user, $role) {
        $db = $this->getConn();
        $stmt = $db->prepare("select * from users_role where user_name=:user and user_role=:role ");
        $stmt->execute(array(':user' => $user, ':role' => $role));
        if ($stmt->rowCount() > 0) {
            $db = null;
            return true;
        } else {
            $db = null;
            return false;
        }
    }

    public function doDelRoleFromUser($user, $role) {
        $db = $this->getConn();
        $stmt = $db->prepare("delete from users_role where user_name=:user and user_role=:role");
        $stmt->execute(array(':user' => $user, ':role' => $role));
        if ($stmt->rowCount() > 0) {
            $db = null;
            return array('success' => true);
        } else {
            $db = null;
            return array('success' => false, 'msg' => 'Ada kesalahan.');
        }
    }

}
