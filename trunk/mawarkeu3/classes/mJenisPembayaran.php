<?php
require_once 'mDbConn.php';

class mJenisPembayaran extends mDbConn{
    
    public function getListJnsPbyr($offset, $rows) {
        $query = <<<q
select 
    t.*, 
    d.md_nama as departemen 
from m_transaksi t 
left join m_departemen d on d.md_id=t.mt_departemen
limit $offset, $rows
q;
        $res = $this->fetchQuery($query);
        
        $queryTot = <<<q
select count(*) as jum from m_transaksi
q;
        $resTot = $this->fetchQuery($queryTot);
        $tot = $resTot[0]['jum'];
        return array('rows'=>$res,'total'=>$tot);
    }
    
    public function getListDepartemen(){
        $query = 'select * from m_departemen';
        $res = $this->fetchQuery($query);
        return $res;
    }
    
    public function saveJnsPbyrn($dept, $namaTrans) {
        $query = "insert into m_transaksi(mt_departemen, mt_jenis) values('$dept', '$namaTrans')";
        if($this->runQuery($query)){
            return array('success' => true);
        } else {
            return array('success' => false, 'msg' => mDbConn::getError());
        }
    }
    
    public function updateJnsPbyrn($mtid, $namaTrans) {
        $namaTrans = mysql_real_escape_string($namaTrans);
        $query = "UPDATE `kjks_mawar_simo`.`m_transaksi` SET `mt_jenis`='$namaTrans' WHERE  `mt_id`=$mtid";
        if($this->runQuery($query)){
            return array('success' => true);
        } else {
            return array('success' => false, 'msg' => mDbConn::getError());
        }
    }
    
    public function deleteJnsPbyrn($mtid) {
        $query = "DELETE FROM `kjks_mawar_simo`.`m_transaksi` WHERE  `mt_id`=$mtid";
        if($this->runQuery($query)){
            return array('success' => true);
        } else {
            return array('success' => false, 'msg' => mDbConn::getError());
        }
    }
    
}
