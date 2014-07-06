<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of mDepartemen
 *
 * @author mzainularifin
 */
class mDepartemen extends mDbConn{
    //put your code here
    public function getDepartemenData($searchKey = '', $offset, $row) {
        $q = $searchKey == '' ? '%%' : "%$searchKey%";
        $query = "select md_id, md_nama "
                . "from m_departemen "
                . "where (md_id like '$q' or md_nama like '$q') "
                . "limit $offset, $row";
        $res = $this->fetchQuery($query);
        $querytot = "select count(*) as jum from m_departemen where (md_id like '$q' or md_nama like '$q')";
        $restot = $this->fetchQuery($querytot);
        foreach($restot as $rest){
            $tot = $rest['jum'];
        }
        return array('rows' => $res, 'total' => $tot);
    }
    
    public function doSaveDepartemen($departemendesc) {
        $db = $this->getConn();
        foreach ($db->query("select count(*) as jum from m_departemen where md_nama='$departemendesc'") as $row) {
            $jum = $row['jum'];
        }
        if ($jum > 0) {
            return array('success' => false, 'msg' => 'departemen tersebut sudah pernah dientrykan. silakan cek lagi.');
        } else {
            $affected = $db->exec("insert into m_departemen(md_nama) values('$departemendesc')");
            if ($affected > 0) {
                return array('success' => true);
            } else {
                return array('success' => false);
            }
        }
        $db = null;
    }

    public function doEditDepartemen($departemenid, $departemendesc) {
        $db = $this->getConn();
        $affected = $db->exec("update m_departemen set md_nama='$departemendesc' where md_id='$departemenid'");
        if ($affected > 0) {
            return array('success' => true);
        } else {
            return array('success' => false);
        }
        $db = null;
    }

    public function doDeleteDepartemen($departemenid) {
        $db = $this->getConn();
        $affected = $db->exec("delete from m_departemen where md_id='$departemenid'");
        if ($affected > 0) {
            return array('success' => true);
        } else {
            return array('success' => false);
        }
        $db = null;
    }
}
