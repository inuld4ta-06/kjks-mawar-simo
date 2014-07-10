<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of mKelas
 *
 * @author mzainularifin
 */
class mKelas extends mDbConn {
    //put your code here
    public function getKelasData($searchKey = '', $offset, $row) {
        $q = $searchKey == '' ? "" : "where k.mkls_jurusan = '$searchKey'";
        $query = "select k.mkls_id, d.md_nama, j.mj_id, j.mj_nama, k.mkls_nama "
                . "from m_kelas k "
                . "left join m_departemen d on d.md_id = k.mkls_departemen "
                . "left join m_jurusan j on j.mj_id = k.mkls_jurusan "
                . "$q "
                . "order by md_nama asc, mkls_nama asc, mj_nama asc "
                . "limit $offset, $row";
        $res = $this->fetchQuery($query);
        $querytot = "select count(*) as jum from m_kelas k $q";
        $restot = $this->fetchQuery($querytot);
        foreach($restot as $rest){
            $tot = $rest['jum'];
        }
        return array('rows' => $res, 'total' => $tot);
        
    }
    
    public function doSaveKelas($jurs, $kels) {
        $db = $this->getConn();
        foreach ($db->query("select mj_departemen from m_jurusan where mj_id=$jurs") as $row1) {
            $dept = $row1['mj_departemen'];
        }
        foreach ($db->query("select count(*) as jum from m_kelas where mkls_jurusan=$jurs and mkls_nama='$kels'") as $row) {
            $jum = $row['jum'];
        }
        if ($jum > 0) {
            return array('success' => false, 'msg' => 'Kelas tersebut sudah pernah dientrykan. silakan cek lagi.');
        } else {
            $affected = $db->exec("insert into m_kelas(mkls_departemen, mkls_jurusan, mkls_nama) values($dept,$jurs,'$kels')");
            if ($affected > 0) {
                return array('success' => true);
            } else {
                return array('success' => false);
            }
        }
        $db = null;
    }

    public function doEditKelas($jurs, $kelsid, $kels) {
        $db = $this->getConn();
        foreach ($db->query("select mj_departemen from m_jurusan where mj_id=$jurs") as $row1) {
            $dept = $row1['mj_departemen'];
        }
        foreach ($db->query("select count(*) as jum from m_kelas where mkls_jurusan=$jurs and mkls_nama='$kels' and mkls_id <> $kelsid") as $row) {
            $jum = $row['jum'];
        }
        if ($jum > 0) {
            return array('success' => false, 'msg' => 'Kelas tersebut sudah pernah dientrykan. silakan cek lagi.');
        } else {
            $affected = $db->exec("update m_kelas set mkls_departemen=$dept, mkls_jurusan=$jurs, mkls_nama='$kels' where mkls_id=$kelsid");
            if ($affected > 0) {
                return array('success' => true);
            } else {
                return array('success' => false);
            }
            $db = null;
        }
    }

    public function doDeleteKelas($kelsid) {
        $db = $this->getConn();
        $affected = $db->exec("delete from m_kelas where mkls_id=$kelsid");
        if ($affected > 0) {
            return array('success' => true);
        } else {
            return array('success' => false);
        }
        $db = null;
    }
    
    public function getListJurusan() {
        return $this->fetchQuery("
            select j.mj_id, concat(d.md_nama, ' - ', j.mj_nama) as mj_nama
            from m_jurusan j
            left join m_departemen d on d.md_id = j.mj_departemen
            order by j.mj_departemen
        ");
    }
    
    public function doSave($param) {
        
    }
}
