<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of mJenisPembayaran
 *
 * @author mzainularifin
 */
class mJenisPembayaran extends mDbConn {
    //put your code here
    public function getJenisPembayaranData($searchKey = '', $offset, $row) {
        $q = $searchKey == '' ? '%%' : "%$searchKey%";
        $query = "select t.mt_id, d.md_nama, d.md_id, t.mt_jenis "
                . "from m_transaksi t "
                . "left join m_departemen d on d.md_id = t.mt_departemen "
                . "where (t.mt_id like '$q' or t.mt_jenis like '$q') "
                . "limit $offset, $row";
        $res = $this->fetchQuery($query);
        $querytot = "select count(*) as jum from m_transaksi where (mt_id like '$q' or mt_jenis like '$q')";
        $restot = $this->fetchQuery($querytot);
        foreach($restot as $rest){
            $tot = $rest['jum'];
        }
        return array('rows' => $res, 'total' => $tot);
        
    }
    public function doSaveJenisPembayaran($dept, $mtjenis) {
        $db = $this->getConn();
        foreach ($db->query("select count(*) as jum from m_transaksi where mt_departemen='$dept' and mt_jenis='$mtjenis'") as $row) {
            $jum = $row['jum'];
        }
        if ($jum > 0) {
            return array('success' => false, 'msg' => 'Jenis Pembayaran tersebut sudah pernah dientrykan. silakan cek lagi.');
        } else {
            $affected = $db->exec("insert into m_transaksi(mt_departemen, mt_jenis) values($dept,'$mtjenis')");
            if ($affected > 0) {
                return array('success' => true);
            } else {
                return array('success' => false);
            }
        }
        $db = null;
    }

    public function doEditJenisPembayaran($dept, $mtid, $mtjenis) {
        $db = $this->getConn();
        $affected = $db->exec("update m_transaksi set mt_departemen=$dept, mt_jenis='$mtjenis' where mt_id=$mtid");
        if ($affected > 0) {
            return array('success' => true);
        } else {
            return array('success' => false);
        }
        $db = null;
    }

    public function doDeleteJenisPembayaran($mtid) {
        $db = $this->getConn();
        $affected = $db->exec("delete from m_transaksi where mt_id=$mtid");
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
