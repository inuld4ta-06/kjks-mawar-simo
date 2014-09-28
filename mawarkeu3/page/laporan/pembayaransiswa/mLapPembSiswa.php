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
class mLapPembSiswa extends mDbConn {

    //put your code here
    public function getLapPembSiswaData($dept, $jurs, $kels, $nama, $jnsp, $stts, $tgfr, $tgto, $offset, $row) {
        $wheres = '';
        $arrFetch = array();
        if (!empty($dept)) {
            $where[] = "s.ms_departemen=:dept";
            $arrFetch[':dept'] = $dept;
        }
        if (!empty($jurs)) {
            $where[] = "s.ms_jurusan=:jurs";
            $arrFetch[':jurs'] = $jurs;
        }
        if (!empty($kels)) {
            $where[] = "s.ms_kelas=:kels";
            $arrFetch[':kels'] = $kels;
        }
        if (!empty($nama)) {
            $where[] = "s.ms_nama like :nama";
            $arrFetch[':nama'] = "%$nama%";
        }
        if (!empty($jnsp)) {
            $where[] = "t.mt_jenis=:jnsp";
            $arrFetch[':jnsp'] = $jnsp;
        }
        if (!empty($stts) && $stts == 'deleted') {
            $where[] = "p.pbyr_deletedate is not null";
        }
        if (!empty($stts) && $stts == 'notdeleted') {
            $where[] = "p.pbyr_deletedate is null";
        }
        if (!empty($tgfr) && !empty($tgto)) {
            $where[] = "(p.pbyr_createdate between '$tgfr' and ('$tgto' + INTERVAL 1 DAY))";
        } elseif (!empty($tgfr) && empty($tgto)) {
            $where[] = "(p.pbyr_createdate > '$tgfr')";
        } elseif (empty($tgfr) && !empty($tgto)) {
            $where[] = "(p.pbyr_createdate < '$tgto' + INTERVAL 1 DAY )";
        }
        if (count($where) > 0) {
            $wheres = "where (" . implode(") and (", $where) . ")";
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

    public function getListJurusan($dept) {
        $where = "where ms_departemen = 'MA'"; // defaultnya ke MA
        if(!empty($dept)){
            $where = "where ms_departemen = '$dept'";
        }
        return $this->fetchQuery("select distinct(ms_jurusan) as mj_nama from m_siswa $where order by mj_nama asc");
    }
    
    public function getListJnsPemby($dept) {
        $where = "where d.md_nama = 'MA'"; // defaultnya ke MA
        if(!empty($dept)){
            $where = "where d.md_nama = '$dept'";
        }
        return $this->fetchQuery("select t.mt_jenis
from m_transaksi t
left join m_departemen d on d.md_id = t.mt_departemen
$where 
order by t.mt_jenis asc");
    }
    
    public function getListKelas($dept, $jurs) {
        $wheres = ''; 
        $where = array();
        if(!empty($dept)){
            $where[] = "ms_departemen = '$dept'";
        }
        if(!empty($jurs)){
            $where[] = "ms_jurusan = '$jurs'";
        }
        if(count($where) > 0){
            $wheres = "where " . implode(" and ", $where);
        }
        return $this->fetchQuery("select distinct(ms_kelas) as mkls_nama from m_siswa $wheres order by mkls_nama asc");
    }
    
    public function doDelPembayaran($pbyrid) {
        $db = $this->getConn();
        $username = $_SESSION['user_name'];
        $stmt = $db->query("update pembayaran set pbyr_deletedate=now(), pbyr_deleteby='$username' where pbyr_id=$pbyrid");
        if ($stmt->rowCount() > 0) {
            return array('success' => true);
        } else {
            return array('success' => false, 'msg' => 'Ada kesalahan');
        }
    }

    public function doUndoDelPembayaran($pbyrid) {
        $db = $this->getConn();
        $stmt = $db->query("update pembayaran set pbyr_deletedate=null, pbyr_deleteby=null where pbyr_id=$pbyrid");
        if ($stmt->rowCount() > 0) {
            return array('success' => true);
        } else {
            return array('success' => false, 'msg' => 'Ada kesalahan');
        }
    }
    
    public function doSaveLapPemby($get){
        $lbl = date("d-M-YH:i:s");
        header("Content-type:application/vnd.ms-excel");
        header("Content-Disposition:attachment;filename=LogPembayaranSiswa_" . $get['dept'] . "_" . $get['jurs'] . "_" . $get['kels'] . "_" . $get['nama'] . "_" . $get['stts'] . "_" . $get['tgfr'] . "_" . $get['tgto'] . "_" . $lbl . ".xls");
        header("Expires:0");
        header("Cache-Control:must-revalidate,post-check=0,pre-check=0");
        header("Pragma: public");

        $wheres = '';
        $arrFetch = array();
        if (!empty($get['dept'])) {
            $where[] = "s.ms_departemen=:dept";
            $arrFetch[':dept'] = $get['dept'];
        }
        if (!empty($get['jurs'])) {
            $where[] = "s.ms_jurusan=:jurs";
            $arrFetch[':jurs'] = $get['jurs'];
        }
        if (!empty($get['kels'])) {
            $where[] = "s.ms_kelas=:kels";
            $arrFetch[':kels'] = $get['kels'];
        }
        if (!empty($get['nama'])) {
            $where[] = "s.ms_nama like :nama";
            $arrFetch[':nama'] = "%" . $get['nama'] . "%";
        }
        if (!empty($get['jnsp'])) {
            $where[] = "t.mt_jenis=:jnsp";
            $arrFetch[':jnsp'] = $get['jnsp'];
        }
        if (!empty($stts) && $stts == 'deleted') {
            $where[] = "p.pbyr_deletedate is not null";
        }
        if (!empty($stts) && $stts == 'notdeleted') {
            $where[] = "p.pbyr_deletedate is null";
        }
        if (!empty($tgfr) && !empty($tgto)) {
            $where[] = "(p.pbyr_createdate between '$tgfr' and ('$tgto' + INTERVAL 1 DAY))";
        } elseif (!empty($tgfr) && empty($tgto)) {
            $where[] = "(p.pbyr_createdate > '$tgfr')";
        } elseif (empty($tgfr) && !empty($tgto)) {
            $where[] = "(p.pbyr_createdate < '$tgto' + INTERVAL 1 DAY )";
        }
        if (count($where) > 0) {
            $wheres = "where (" . implode(") and (", $where) . ")";
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

        $res = $this->fetchQuery($query, $arrFetch);
        
        echo "<table>";
        echo "<tr>";
        echo "<td>NIS</td>";
        echo "<td>Nama</td>";
        echo "<td>Kelas</td>";
        echo "<td>Jenis Pembayaran</td>";
        echo "<td>Tahun</td>";
        echo "<td>Bulan</td>";
        echo "<td>Nominal</td>";
        echo "<td>Teller</td>";
        echo "<td>Tanggal Waktu</td>";
        echo "<td>Delete</td>";
        echo "<td>DeleteBy</td>";
        echo "</tr>";
        
        foreach ($res as $row){
            echo "<tr>";
            echo "<td>" . $row['ms_nis'] . "</td>";
            echo "<td>" . $row['ms_nama'] . "</td>";
            echo "<td>" . $row['ms_kelas'] . "</td>";
            echo "<td>" . $row['mt_jenis'] . "</td>";
            echo "<td>" . $row['pbyr_tahun'] . "</td>";
            echo "<td>" . $row['pbyr_bulan'] . "</td>";
            echo "<td>" . $row['pbyr_nominal'] . "</td>";
            echo "<td>" . $row['pbyr_createby'] . "</td>";
            echo "<td>" . $row['pbyr_createdate'] . "</td>";
            echo "<td>" . $row['pbyr_deletedate'] . "</td>";
            echo "<td>" . $row['pbyr_deleteby'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }

}
