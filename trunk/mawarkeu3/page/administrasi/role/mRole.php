<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of mRole
 *
 * @author mzainularifin
 */
//include_once '../../../classes/mDbConn.php';

class mRole extends mDbConn {

    //put your code here
    public function getRoleData($searchKey = '', $offset, $row) {
        $q = $searchKey == '' ? '%%' : "%$searchKey%";
        $query = "select * from m_role where (role_id like '$q' or role_desc like '$q') limit $offset, $row";
        $res = $this->fetchQuery($query);
        $querytot = "select count(*) as jum from m_role where (role_id like '$q' or role_desc like '$q')";
        $restot = $this->fetchQuery($querytot);
        foreach ($restot as $rest) {
            $tot = $rest['jum'];
        }
        return array('rows' => $res, 'total' => $tot);
    }

    public function doSaveRole($roleid, $roledesc) {
        $db = $this->getConn();
        foreach ($db->query("select count(*) as jum from m_role where role_id='$roleid'") as $row) {
            $jum = $row['jum'];
        }
        if ($jum > 0) {
            return array('success' => false, 'msg' => 'role tersebut sudah pernah dientrykan. silakan cek lagi.');
        } else {
            $affected = $db->exec("insert into m_role(role_id, role_desc) values('$roleid','$roledesc')");
            if ($affected > 0) {
                return array('success' => true);
            } else {
                return array('success' => false);
            }
        }
        $db = null;
    }

    public function doEditRole($roleid, $oldroleid, $roledesc) {
        $db = $this->getConn();
        foreach ($db->query("select count(*) as jum from m_role where role_id='$roleid' and role_id <> '$oldroleid'") as $row) {
            $jum = $row['jum'];
        }
        if ($jum > 0) {
            return array('success' => false, 'msg' => 'role tersebut sudah pernah dientrykan. silakan cek lagi.');
        } else {
            $affected = $db->exec("update m_role set role_id='$roleid', role_desc='$roledesc' where role_id='$oldroleid'");
            if ($affected > 0) {
                return array('success' => true);
            } else {
                return array('success' => false);
            }
        }
        $db = null;
    }

    public function doDeleteRole($roleid) {
        $db = $this->getConn();
        $affected = $db->exec("delete from m_role where role_id='$roleid'");
        if ($affected > 0) {
            return array('success' => true);
        } else {
            return array('success' => false);
        }
        $db = null;
    }

}
