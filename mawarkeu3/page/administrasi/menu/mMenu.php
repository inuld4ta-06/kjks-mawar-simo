<?php

//include_once dirname(__FILE__) . '/../../../classes/mDbConn.php';

class mMenu extends mDbConn {

    public function getMenuData($searchKey = '', $offset, $row) {
        $q = $searchKey == '' ? '%%' : "%$searchKey%";
        $query = "select * from m_menu where (path like '$q' or name like '$q') limit $offset, $row";
        $res = $this->fetchQuery($query);
        $querytot = "select count(*) as jum from m_menu where (path like '$q' or name like '$q')";
        $restot = $this->fetchQuery($querytot);
        foreach($restot as $rest){
            $tot = $rest['jum'];
        }
        return array('rows' => $res, 'total' => $tot);
    }
    
    public function getListRole() {
        $q = "select * from m_role order by role_desc asc";
        return $this->fetchQuery($q);
    }
    
    public function getListParentMenu() {
        $q = "select menu_id, name from m_menu where type='MENU_HEADER' order by name asc";
        return $this->fetchQuery($q);
    }
    
    public function detailGrid($param) {
        $menuid = $param;
        $q = <<<q
                select * from m_menu_role where menu_id=$menuid
q;
        $data = array();
        foreach ($this->fetchQuery($q) as $row){
            array_push($data, $row);
        }
        return $data;
    }
    
    private function isMenuAvailable($path, $oldpath = '', $edit = false) {
        $con = $this->getConn();
        if($edit){
            $q = $con->query("select path from m_menu where path='$path' and path <> '$oldpath'");
        } else {
            $q = $con->query("select path from m_menu where path='$path'");
        }
        if($q->rowCount() > 0){
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    public function addNewMenu($path, $name, $prid, $type, $ordr, $enab) {
        if($this->isMenuAvailable($path)){
            return array('success' => false, 'msg' => 'Lokasi tersebut sudah digunakan.');
        } else {
            $con = $this->getConn();
            $q = "insert into m_menu (`path`, `name`, `created_date`, `enabled`, `parent_id`, `order`, `type`) "
                    . "values (:path, :name, now(), :enab, :prid, :ordr, :type)";
            $statmnt = $con->prepare($q);
            $statmnt->execute(array(
                ':path'=>$path,
                ':name'=>$name,
                ':enab'=>$enab,
                ':prid'=>$prid,
                ':ordr'=>$ordr,
                ':type'=>$type
                ));
            if($statmnt->rowCount() > 0){
                return array('success' => true);
            } else {
                return array('success' => false, 'msg' => 'terjadi kesalahan.');
            }
        }
    }
    
    public function addRoleToMenu($menuId, $roleid) {
        if($this->isMenuHasThisRole($menuId, $roleid)){
            return array('success' => false, 'msg' => 'Role tersebut sudah digunakan.');
        } else {
            $con = $this->getConn();
            $q = "insert into m_menu_role (`menu_id`, `role_id`)"
                    . "values (:menuid, :roleid)";
            $statmnt = $con->prepare($q);
            $statmnt->execute(array(
                ':menuid'=>$menuId,
                ':roleid'=>$roleid
                ));
            if($statmnt->rowCount() > 0){
                return array('success' => true);
            } else {
                return array('success' => false, 'msg' => 'terjadi kesalahan.');
            }
        }
    }
    
    private function isMenuHasThisRole($menuId, $roleid) {
        $con = $this->getConn();
        $q = $con->query("select menu_id from m_menu_role where menu_id='$menuId' and role_id='$roleid'");
        if($q->rowCount() > 0){
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    public function delRoleFromMenu($menuId, $roleid) {
        $con = $this->getConn();
        $q = "delete from m_menu_role where menu_id=:menuid and role_id=:roleid";
        $statmnt = $con->prepare($q);
        $statmnt->execute(array(
            ':menuid'=>$menuId,
            ':roleid'=>$roleid
            ));
        if($statmnt->rowCount() > 0){
            return array('success' => true);
        } else {
            return array('success' => false, 'msg' => 'terjadi kesalahan.');
        }
    }
    public function editMenu($path, $oldpath, $name, $prid, $type, $ordr, $enab) {
        if($this->isMenuAvailable($path, $oldpath, $edit=true)){
            return array('success' => false, 'msg' => 'Lokasi tersebut sudah digunakan.');
        } else {
            $con = $this->getConn();
            $q = "update m_menu set `path`=:path, `name`=:name, `enabled`=:enab, `parent_id`=:prid, `order`=:ordr, `type`=:type "
                    . "where `path`=:oldpath";
            $sttment = $con->prepare($q);
            $sttment->execute(array(
                ':path' =>$path,
                ':oldpath' => $oldpath,
                ':name' => $name,
                ':prid' => $prid,
                ':type' => $type,
                ':ordr' => $ordr,
                ':enab' => $enab
                ));
            if($sttment->rowCount() > 0){
                return array('success' => true);
            } else {
                return array('success' => false, 'msg' => 'terjadi kesalahn');
            }
        }

    }
    
    public function doDeleteMenu($menuid) {
        $db = $this->getConn();
        $affected = $db->exec("delete from m_menu where menu_id=$menuid");
        if ($affected > 0) {
            return array('success' => true);
        } else {
            return array('success' => false);
        }
        $db = null;
    }

}
