<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of mRekapPemb
 *
 * @author mzainularifin
 */
class mRekapPemb extends mDbConn {
    //put your code here
    
    public function getListJenisPembayaran($dept) {
        if(!empty($dept)){
            $queryDept = "select md_id from m_departemen where md_nama = '$dept'";
            foreach ($this->fetchQuery($queryDept) as $r){
                $idDept = $r['md_id'];
            }
            $query = "select mt_id, mt_jenis from m_transaksi where mt_departemen=$idDept";
            $listPemb = array();
            foreach ($this->fetchQuery($query) as $r2){
                array_push($listPemb, $r2);
            }
            return $listPemb;
        }
    }
    
    public function getListKelas($dept) {
        if(!empty($dept)){
            $queryDept = "select md_id from m_departemen where md_nama = '$dept'";
            foreach ($this->fetchQuery($queryDept) as $r){
                $idDept = $r['md_id'];
            }
            $query = "

select 
    concat(k.mkls_nama, '-', j.mj_nama) as kelas
from 
    m_kelas k
    left join m_jurusan j on j.mj_id = k.mkls_jurusan
where 
    k.mkls_departemen=$idDept 
order by 
    k.mkls_nama asc
    , j.mj_nama asc
    
";
            $listKels = array();
            foreach ($this->fetchQuery($query) as $r2){
                array_push($listKels, $r2);
            }
            return $listKels;
        }
    }
    
    public function getListSiswa($dept, $kls, $ta, $siswa) {
        if(!empty($dept)){
            $whereSiswa = array();
            $whereSiswa[] = "sh.ms_departemen = '$dept'";
            
            if(!empty($kls)){
                $klsExpl = explode("-", $kls);
                $jurs = $klsExpl[1];
                $kelas = $klsExpl[0];
                $whereSiswa[] = "sh.ms_jurusan = '$jurs' AND sh.ms_kelas = '$kelas'";
            }
            
            if(!empty($ta)){
                list($tahunSmtGanjil, $tahunSmtGenap) = explode('-', $ta);
                $tahunSmtGanjil07 = $tahunSmtGanjil . "07";
                $whereSiswa[] = <<<q
                        '$tahunSmtGanjil07' between date_format(sh.ms_begdate, "%Y%m") and date_format(sh.ms_enddate, "%Y%m")
q;
            }
            
            if(!empty($siswa)){
                $whereSiswa[] = "s.ms_nama like '%$siswa%' or s.ms_nis like '%$siswa%'";
            }
            
            if(count($whereSiswa) > 0){
                $whereImp = "where (". implode(') and (', $whereSiswa) . ")";
            }
            $query = <<<q
select 
    s.ms_id, 
    s.ms_nama, 
    s.ms_nis, 
    sh.ms_jurusan, 
    sh.ms_kelas 
from 
    m_siswa s
    left join m_siswa_hist sh on sh.ms_id = s.ms_id
$whereImp 
limit 20
q;
            $listPemb = array();
            foreach ($this->fetchQuery($query) as $r2){
                array_push($listPemb, $r2);
            }
            return $listPemb;
        }
    }
    
    public function getListTahunajaran() {
        $query = "select min(pbyr_tahun) as min_tahun, max(pbyr_tahun) as max_tahun from pembayaran";
        $res = $this->fetchQuery($query);
        $firstYear = $res[0]['min_tahun'];
        $lastYear = $res[0]['max_tahun'];
        $tahunajaran = array();
        for ($a = ($firstYear - 1); $a <= ($lastYear); $a++){
            $r['ta_nama'] = $a. "-" . ($a+1);
            array_push($tahunajaran, $r);
        }
        return $tahunajaran;
    }
    
    public function doSaveRekapPemb($dept, $kelas, $tahunajaran, $trans, $siswa, $tglPer, $tglPerTo){
        $typeTransaksi = "";
        $querygetTypeTrsns = "select mt_jenis from m_transaksi where mt_id=$trans";
        foreach ($this->fetchQuery($querygetTypeTrsns) as $r){
            $typeTransaksi = $r['mt_jenis'];
        }
        $lbl = date("d-M-Y_H:i:s");
        header("Content-type:application/vnd.ms-excel");
        header("Content-Disposition:attachment;filename=RekapPembayaran" . $typeTransaksi . "_" . $dept . "_" . $lbl . ".xls");
        header("Expires:0");
        header("Cache-Control:must-revalidate,post-check=0,pre-check=0");
        header("Pragma: public");
        $data = $this->doSearchRekapPemb($dept, $kelas, $tahunajaran, $trans, $siswa, $tglPer, $tglPerTo, 0, 0, true);
        echo "<table>";
        echo "<tr><td colspan=16>REKAP PEMBAYARAN " . strtoupper($typeTransaksi) . "</td></tr>";
        echo "<tr><td colspan=16>Departemen $dept Tahun Ajaran $tahunajaran</td></tr>";
        echo "<tr><td colspan=16>Per Tanggal $tglPer s/d $tglPerTo </td></tr>";
        echo "<tr>"
                . "<td rowspan=2>Jurusan</td>"
                . "<td rowspan=2>Kelas</td>"
                . "<td rowspan=2>No. Induk</td>"
                . "<td rowspan=2>Nama</td>"
                . "<td colspan=12>Bulan</td>"
            . "</tr>";
        echo "<tr>"
                . "<td>Juli</td>"
                . "<td>Agustus</td>"
                . "<td>September</td>"
                . "<td>Oktober</td>"
                . "<td>November</td>"
                . "<td>Desember</td>"
                . "<td>Januari</td>"
                . "<td>Februari</td>"
                . "<td>Maret</td>"
                . "<td>April</td>"
                . "<td>Mei</td>"
                . "<td>Juni</td>"
            . "</tr>";
        foreach ($data['rows'] as $row){
            echo "<tr>"
                    . "<td>" . $row['ms_jurusan'] . "</td>"
                    . "<td>" . $row['ms_kelas'] . "</td>"
                    . "<td>" . $row['ms_nis'] . "</td>"
                    . "<td>" . $row['ms_nama'] . "</td>"
                    . "<td>" . $row['pemb_07'] . "</td>"
                    . "<td>" . $row['pemb_08'] . "</td>"
                    . "<td>" . $row['pemb_09'] . "</td>"
                    . "<td>" . $row['pemb_10'] . "</td>"
                    . "<td>" . $row['pemb_11'] . "</td>"
                    . "<td>" . $row['pemb_12'] . "</td>"
                    . "<td>" . $row['pemb_01'] . "</td>"
                    . "<td>" . $row['pemb_02'] . "</td>"
                    . "<td>" . $row['pemb_03'] . "</td>"
                    . "<td>" . $row['pemb_04'] . "</td>"
                    . "<td>" . $row['pemb_05'] . "</td>"
                    . "<td>" . $row['pemb_06'] . "</td>"
                . "</tr>";
        }
        echo "</table>";
    }
    
    public function doSearchRekapPemb($dept, $kelas, $tahunajaran, $transaksi, $siswa, $tglPer, $tglPerTo, $offset, $rows, $save = false){
        $data = array();
        $wheresiswa = "";
        if(!empty($kelas)){
            $kelsExpl = explode("-", $kelas);
            $jurusan = $kelsExpl[1];
            $kls = $kelsExpl[0];
            $wheresiswa .= " and (sh.ms_jurusan='$jurusan' and sh.ms_kelas='$kls') ";
        }
        $wheresiswa .= !empty($siswa) ? " and (s.ms_id=$siswa) " : '';
        $limit = "";
        if (!$save){
            $limit = "limit $offset, $rows";
        }
        $query1 = <<<q
select 
    s.ms_id, 
    sh.ms_jurusan, 
    sh.ms_kelas, 
    s.ms_nis, 
    s.ms_nama,
    sh.ms_begdate,
    sh.ms_enddate
from 
    m_siswa s
    left join m_siswa_hist sh on sh.ms_id = s.ms_id
where 
    sh.ms_departemen='$dept' 
    and s.ms_active=1 
    and (
        ('$tglPer' between sh.ms_begdate and sh.ms_enddate)
        or
        ('$tglPerTo' between sh.ms_begdate and sh.ms_enddate)
    )
    $wheresiswa 
order by 
    ms_kelas asc, 
    ms_nis asc, 
    ms_nama asc 
$limit
q;
        list($tahunSmtGanjil, $tahunSmtGenap) = explode('-', $tahunajaran);
        foreach ($this->fetchQuery($query1) as $siswa){
            $msid = $siswa['ms_id'];
            $msbegdate = $siswa['ms_begdate'];
            $msenddate = $siswa['ms_enddate'];
            $tglPerNew = $tglPer > $msbegdate ? $tglPer : $msbegdate;
            $tglPerToNew = $tglPerTo < $msenddate ? $tglPerTo : $msenddate;
            $query2 = "select pbyr_tahun, pbyr_bulan, pbyr_nominal 
            from pembayaran 
            where 
            mt_id=$transaksi 
            and ms_id=$msid 
            and pbyr_tahun in ($tahunSmtGanjil, $tahunSmtGenap) 
            and pbyr_deletedate is null 
            and (pbyr_createdate >= '$tglPerNew' and pbyr_createdate <= '$tglPerToNew')";
            foreach ($this->fetchQuery($query2) as $r2){
                if ($r2['pbyr_tahun'] == $tahunSmtGanjil && $r2['pbyr_bulan'] == '7') {
                    $siswa['pemb_07'] = $r2['pbyr_nominal'];
                } elseif ($r2['pbyr_tahun'] == $tahunSmtGanjil && $r2['pbyr_bulan'] == '8') {
                    $siswa['pemb_08'] = $r2['pbyr_nominal'];
                } elseif ($r2['pbyr_tahun'] == $tahunSmtGanjil && $r2['pbyr_bulan'] == '9') {
                    $siswa['pemb_09'] = $r2['pbyr_nominal'];
                } elseif ($r2['pbyr_tahun'] == $tahunSmtGanjil && $r2['pbyr_bulan'] == '10') {
                    $siswa['pemb_10'] = $r2['pbyr_nominal'];
                } elseif ($r2['pbyr_tahun'] == $tahunSmtGanjil && $r2['pbyr_bulan'] == '11') {
                    $siswa['pemb_11'] = $r2['pbyr_nominal'];
                } elseif ($r2['pbyr_tahun'] == $tahunSmtGanjil && $r2['pbyr_bulan'] == '12') {
                    $siswa['pemb_12'] = $r2['pbyr_nominal'];
                } elseif ($r2['pbyr_tahun'] == $tahunSmtGenap && $r2['pbyr_bulan'] == '1') {
                    $siswa['pemb_01'] = $r2['pbyr_nominal'];
                } elseif ($r2['pbyr_tahun'] == $tahunSmtGenap && $r2['pbyr_bulan'] == '2') {
                    $siswa['pemb_02'] = $r2['pbyr_nominal'];
                } elseif ($r2['pbyr_tahun'] == $tahunSmtGenap && $r2['pbyr_bulan'] == '3') {
                    $siswa['pemb_03'] = $r2['pbyr_nominal'];
                } elseif ($r2['pbyr_tahun'] == $tahunSmtGenap && $r2['pbyr_bulan'] == '4') {
                    $siswa['pemb_04'] = $r2['pbyr_nominal'];
                } elseif ($r2['pbyr_tahun'] == $tahunSmtGenap && $r2['pbyr_bulan'] == '5') {
                    $siswa['pemb_05'] = $r2['pbyr_nominal'];
                } elseif ($r2['pbyr_tahun'] == $tahunSmtGenap && $r2['pbyr_bulan'] == '6') {
                    $siswa['pemb_06'] = $r2['pbyr_nominal'];
                }
            }
            array_push($data, $siswa);
        }
        $return = array();
        $return['rows'] = $data;
        $query3 = "
                select 
                    count(*) as jum 
                from m_siswa s 
                    left join m_siswa_hist sh on sh.ms_id = s.ms_id 
                where 
                    sh.ms_departemen='$dept' 
                    and s.ms_active=1 
                    and ('$tglPer' between sh.ms_begdate and sh.ms_enddate)
                    $wheresiswa";
        foreach ($this->fetchQuery($query3) as $r3){
            $return['total'] = $r3['jum'];
        }
        return $return;
    }
    
    private function getNominalSPP($msid, $mtid, $tahun, $bulan) {
        $query = "select pbyr_nominal from pembayaran where ms_id=$msid and mt_id=$mtid and pbyr_tahun=$tahun and pbyr_bulan=$bulan and pbyr_deletedate is null";
        $res = $this->fetchQuery($query);
        foreach ($res as $row){
            $nominal = $row['pbyr_nominal'];
        }
        return $nominal;
    }
    
    private function getSppMtid($namaDept) {
        if ($namaDept == "Pondok"){
            return 1;
        } elseif ($namaDept == "MA"){
            return 12;
        } 
    }
}
