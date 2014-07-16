<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of mLapPembSiswa
 *
 * @author mzainularifin
 */
class mLapPembSiswa extends mDbConn{
    //put your code here
    public function getLapPembSiswaData($dept, $stts, $offset, $row) {
        $wheres = '';
        $arrFetch = array();
        if(!empty($dept)){
            $where[] = "s.ms_departemen=:dept";
            $arrFetch[':dept'] = $dept;
        }
        if(!empty($stts) && $stts == 'deleted'){
            $where[] = "p.pbyr_deletedate is not null";
        }
        if(!empty($stts) && $stts == 'notdeleted'){
            $where[] = "p.pbyr_deletedate is null";
        }
        if(count($where) > 0){
            $wheres = "where " . implode(" and ", $where);
        }
        
        $query = "
select 
    p.pbyr_id,
    p.struk_id, 
    p.pbyr_createdate, 
    p.pbyr_createby, 
    p.pbyr_tahun,
    p.pbyr_bulan,
    format(p.pbyr_nominal, 0, 'id_ID') as pbyr_nominal, 
    s.ms_nis, 
    s.ms_nama, 
    concat(s.ms_departemen, ' - ', s.ms_jurusan, ' - ', s.ms_kelas) as ms_kelas, 
    t.mt_jenis,
    p.pbyr_deletedate,
    p.pbyr_deleteby
from 
pembayaran p
left join m_siswa s on s.ms_id = p.ms_id
left join m_transaksi t on t.mt_id = p.mt_id
$wheres
";
        $queryLimit = "limit $offset, $row";
        
        $restot = $this->fetchQuery("select count(*) as jum from ($query) a", $arrFetch);
        foreach ($restot as $rest) {
            $tot = $rest['jum'];
        }
        $res = $this->fetchQuery($query . $queryLimit, $arrFetch);
        return array('rows' => $res, 'total' => $tot);
    }
    
    
    public function getListDepartemen() {
        return $this->fetchQuery("select distinct(ms_departemen) md_nama from m_siswa order by md_nama asc");
    }
    
    public function doDelPembayaran($pbyrid) {
        $db = $this->getConn();
        $username = $_SESSION['user_name'];
        $stmt = $db->query("update pembayaran set pbyr_deletedate=now(), pbyr_deleteby='$username' where pbyr_id=$pbyrid");
        if($stmt->rowCount() > 0){
            return array('success' => true);
        } else {
            return array('success' => false, 'msg' => 'Ada kesalahan');
        }
    }
    
    public function doUndoDelPembayaran($pbyrid) {
        $db = $this->getConn();
        $stmt = $db->query("update pembayaran set pbyr_deletedate=null, pbyr_deleteby=null where pbyr_id=$pbyrid");
        if($stmt->rowCount() > 0){
            return array('success' => true);
        } else {
            return array('success' => false, 'msg' => 'Ada kesalahan');
        }
    }
}
