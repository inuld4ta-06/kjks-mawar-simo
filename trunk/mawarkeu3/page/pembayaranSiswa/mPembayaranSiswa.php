<?php
include_once '../../classes/mDbConn.php';
class mPembayaranSiswa extends mDbConn {

    public function getComboDataSiswa($q) {
        $query = "select ms_id, ms_nis, ms_nisn, ms_nama, ms_departemen, ms_jurusan, ms_kelas "
                . "from m_siswa "
                . "where ms_id like '%$q%' or ms_nis like '%$q%' or ms_nisn like '%$q%' or ms_nama like '%$q%'"
                . "limit 20";
        $data = $this->fetchQuery($query);
        return $data;
    }

    public function loadJnsPmbyrn($ms_id) {
        $query = <<<q
                select d.md_id
            from m_siswa s
            left join m_departemen d on d.md_nama=s.ms_departemen
            where s.ms_id='$ms_id'
q;
        $getSiswaDetail = $this->fetchQuery($query);
        if (count($getSiswaDetail) > 0) {
            $siswaDet = $getSiswaDetail[0];
            $dept = $siswaDet['md_id'];
            $query2 = "select * from m_transaksi where mt_departemen='$dept'";
            $datas = array();
            foreach ($this->fetchQuery($query2) as $data) {
                array_push($datas, $data);
            }
            return $datas;
        } else {
            return "tidak ditemukan jenis pembayarannya, query : " . $query . ", numrows : " . $this->runQuery($query)->num_rows . " con : " . $this->con->error;
        }
    }
    
    public function loadStrukPmbyrn($ms_id) {
        echo <<<t
t;
    }
    
    public function loadPmbyrn($mtid, $msid) {
        
    }

    public function getDetailSyahriyah($msid) {
        $q = <<<q
SELECT 
    pbyr_tahun, 
    CASE pbyr_bulan 
        WHEN 1 THEN 'Januari' 
        WHEN 2 THEN 'Februari' 
        WHEN 3 THEN 'Maret' 
        WHEN 4 THEN 'April' 
        WHEN 5 THEN 'Mei' 
        WHEN 6 THEN 'Juni' 
        WHEN 7 THEN 'Juli' 
        WHEN 8 THEN 'Agustus' 
        WHEN 9 THEN 'September' 
        WHEN 10 THEN 'Oktober' 
        WHEN 11 THEN 'Nopember' 
        WHEN 12 THEN 'Desember' 
    END AS pbyr_bulan_v,
    pbyr_nominal,
    pbyr_createdate
FROM pembayaran p
WHERE ms_id=$msid
order by pbyr_tahun desc, pbyr_bulan desc
q;
        return $this->fetchQuery($q);
        
    }

    public function getFooterSyahriyah($msid) {
        $q = <<<q
SELECT SUM(p.pbyr_nominal) AS pbyr_nominal, 'Total' AS pbyr_bulan_v
FROM pembayaran p
WHERE ms_id=$msid
q;
        return $this->fetchQuery($q);
    }
    
    public function getNewStrukId($msid) {
        $username = $_SESSION['user_name'];
        $query = <<<q
                insert into t_struk (struk_msid, struk_date, struk_by)
                values ('$msid', now(), '$username')
q;
        if($this->con->query($query)){
            return array('success' => true, 'struk_id' => $this->con->insert_id);
        } else {
            return array('success' => false, 'msg' => $this->con->error);
        }
    }
    
    public function getSyahriyahSiswaDefaultNominal($msid) {
        $q = <<<q
SELECT ms_defaultSyahriyahNominal
FROM m_siswa
WHERE ms_id=$msid
q;
        return $this->fetchQuery($q);
    }
    
    public function cekSyahriyahBayar($msid, $tahun, $bulan) {
        $q = <<<q
select pbyr_id from pembayaran where ms_id=$msid and pbyr_tahun=$tahun and pbyr_bulan=$bulan
q;
        $r = $this->runQuery($q);
        if($r->num_rows > 0){
            return false;
        } else {
            return true;
        }
    }
    
    public function doSyahriyahBayar($msid, $tahun, $bulan, $nominal) {
        if($this->cekSyahriyahBayar($msid, $tahun, $bulan)) {
            $q = <<<q
INSERT INTO 
    pembayaran (mt_id, ms_id, pbyr_tahun, pbyr_bulan, pbyr_nominal, pbyr_createdate) 
    VALUES (1, $msid, $tahun, $bulan, $nominal, NOW());
q;
            $r = $this->runQuery($q);
            if($r){
                return array('success' => true);
            } else {
                return array('success' => false, 'msg' => "###");
            }
        } else {
            return array('success' => false, 'msg' => 'Siswa sudah pernah membayar untuk bulan dan tahun tersebut.');
        }
    }
    
}
