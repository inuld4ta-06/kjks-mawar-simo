<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of mJurusan
 *
 * @author mzainularifin
 */
class mJurusan extends mDbConn{
    //put your code here
    public function getJurusanData($searchKey = '', $offset, $row) {
        $q = $searchKey == '' ? '%%' : "%$searchKey%";
        $query = "select j.mj_id, d.md_nama, d.md_id, j.mj_nama "
                . "from m_jurusan j "
                . "left join m_departemen d on d.md_id = j.mj_departemen "
                . "where (j.mj_id like '$q' or j.mj_nama like '$q') "
                . "limit $offset, $row";
        $res = $this->fetchQuery($query);
        $querytot = "select count(*) as jum from m_jurusan where (mj_id like '$q' or mj_nama like '$q')";
        $restot = $this->fetchQuery($querytot);
        foreach($restot as $rest){
            $tot = $rest['jum'];
        }
        return array('rows' => $res, 'total' => $tot);
        
    }
    public function doSaveJurusan($dept, $jurs) {
        $db = $this->getConn();
        foreach ($db->query("select count(*) as jum from m_jurusan where mj_departemen='$dept' and mj_nama='$jurs'") as $row) {
            $jum = $row['jum'];
        }
        if ($jum > 0) {
            return array('success' => false, 'msg' => 'Jurusan tersebut sudah pernah dientrykan. silakan cek lagi.');
        } else {
            $affected = $db->exec("insert into m_jurusan(mj_departemen, mj_nama) values('$dept','$jurs')");
            if ($affected > 0) {
                return array('success' => true);
            } else {
                return array('success' => false);
            }
        }
        $db = null;
    }

    public function doEditJurusan($dept, $jursid, $jurs) {
        $db = $this->getConn();
        $affected = $db->exec("update m_jurusan set mj_departemen='$dept', mj_nama='$jurs' where mj_id='$jursid'");
        if ($affected > 0) {
            return array('success' => true);
        } else {
            return array('success' => false);
        }
        $db = null;
    }

    public function doDeleteJurusan($jursid) {
        $db = $this->getConn();
        $affected = $db->exec("delete from m_jurusan where mj_id='$jursid'");
        if ($affected > 0) {
            return array('success' => true);
        } else {
            return array('success' => false);
        }
        $db = null;
    }
    
    public function getListDepartemen() {
        return $this->fetchQuery("select * from m_departemen order by md_nama asc");
    }
}
