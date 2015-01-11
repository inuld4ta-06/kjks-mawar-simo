<?php

//include_once '../../classes/mDbConn.php';
class mPembayaranSiswa extends mDbConn {

    public function getStrukData($msid, $print = false) {
        $qry0 = <<<q
select 
    s.ms_nama, 
    sh.ms_departemen, 
    sh.ms_jurusan, 
    sh.ms_kelas, 
    p.pbyr_createby
from pembayaran p
left join m_siswa s on s.ms_id = p.ms_id
left join m_siswa_hist sh on sh.ms_id = s.ms_id and ((date_format(p.pbyr_createdate, "%Y") between year(sh.ms_begdate) and year(sh.ms_enddate)) and (date_format(p.pbyr_createdate, "%m") between date_format(sh.ms_begdate, "%m") and date_format(sh.ms_enddate, "%m")))
where p.ms_id=$msid and p.struk_id=0 and p.pbyr_deletedate is null
order by p.pbyr_createdate desc
limit 1
q;
        $namasiswa = '';
        foreach ($this->fetchQuery($qry0) as $data0) {
            $namasiswa = $data0['ms_nama'];
            $siswaDepartemen = $data0['ms_departemen'];
            $siswaJurusan = $data0['ms_jurusan'];
            $siswaKelas = $data0['ms_kelas'];
            $teller = $data0['pbyr_createby'];
        }
        
        $query = <<<q
select 
    p.pbyr_id, 
    s.ms_nama, 
    sh.ms_departemen, 
    sh.ms_jurusan, 
    sh.ms_kelas, 
    t.mt_jenis, 
    p.pbyr_tahun, 
    p.pbyr_bulan, 
    format(p.pbyr_nominal,0,'id_ID') as pbyr_nominal, 
    p.pbyr_nominal as pbyr_nominal_fortotal, 
    p.pbyr_text,
    p.pbyr_createby
from pembayaran p
left join m_transaksi t on t.mt_id = p.mt_id
left join m_siswa s on s.ms_id = p.ms_id
left join m_siswa_hist sh on sh.ms_id = s.ms_id and ((p.pbyr_tahun between year(sh.ms_begdate) and year(sh.ms_enddate)) and (p.pbyr_bulan between date_format(sh.ms_begdate, "%m") and date_format(sh.ms_enddate, "%m")))
where p.ms_id=$msid and p.struk_id=0 and p.pbyr_deletedate is null
order by p.mt_id asc, p.pbyr_tahun asc, p.pbyr_bulan asc
q;
        $listTransaksi = array();
        $no = 1;
        foreach ($this->fetchQuery($query) as $data) {
            $listTransaksi[$no]['pbyr_id'] = $data['pbyr_id'];
            $listTransaksi[$no]['mt_jenis'] = $data['mt_jenis'];
            $listTransaksi[$no]['pbyr_tahun'] = $data['pbyr_tahun'];
            $listTransaksi[$no]['pbyr_bulan'] = $data['pbyr_bulan'];
            $listTransaksi[$no]['pbyr_nominal'] = $data['pbyr_nominal'];
            $listTransaksi[$no]['pbyr_nominal_fortotal'] = $data['pbyr_nominal_fortotal'];
            $listTransaksi[$no]['pbyr_text'] = $data['pbyr_text'];
            $no++;
        }
        $total = 0;
        $html = array();
        
        if($print){
            $html[] = "<html><body onload='window.print()'>";
        }

        $html[] = "<div style='font-family:arial; padding:10px;'>";
        $html[] = "<style>"
                . "@media print{"
                . "* {"
                . "font-size: 9px;"
                . "}"
                . "}"
                . "</style>";
        $html[] = "<div style='text-align:center; font-weight:bold'>KJKS MATHOLI'UL ANWAR<br>Jl. Raya Simo Sungelebak Karanggeneng Lamongan</div>";
        $html[] = "<table width='100%' style='border-bottom: 1px dashed #000'>"
                . "<tr>"
                . "<td>Teller: $teller</td>"
                . "<td align='right'>Tgl: " . date("d/m/Y H:i:s") . "</td>"
                . "</tr>"
                . "<tr>"
                . "<td colspan=2 align='center'>$namasiswa, ($siswaDepartemen - $siswaJurusan - $siswaKelas)</td>"
                . "</tr>"
                . "</table>";
        $html[] = "<table width='100%'>";
        foreach ($listTransaksi as $trans) {
            $html[] = "<tr>"
                    . "<td width='60%'>" . $trans['mt_jenis'] . "</td>"
                    . "<td width='20%'>" . $trans['pbyr_tahun'] . ", " . $trans['pbyr_bulan'] . "</td>"
                    . "<td width='20%' align='right'>" . $trans['pbyr_nominal'] . "</td>"
                    . "</tr>";
            $html[] = "<tr><td colspan=3 style='padding-bottom: 10px;'>" . $trans['pbyr_text'] . "</td><td></td></tr>";
            $total = $total + $trans['pbyr_nominal_fortotal'];
        }
        setlocale(LC_MONETARY, 'id_ID.UTF-8');
        $totalll = number_format($total, 0, ',', '.');
        $html[] = "<tr>"
                . "<td style='border-top: 1px dashed #000' colspan=2 align=center>TOTAL</td>"
                . "<td style='border-top: 1px dashed #000' align='right'>$totalll</td>"
                . "</tr>";
        $html[] = "</table>";
        $html[] = "</div>";
        
        if($print){
            $html[] = "</body></html>";
        }
        
        $htmls = implode('', $html);

        if ($print) {
            $this->doSimpanStruk($msid);
        }

        return $htmls;
    }

    public function doSimpanStruk($msid) {
        $db = $this->getConn();
        $querycekNmrStruk = "select struk_id from pembayaran order by struk_id desc limit 1";
        foreach ($db->query($querycekNmrStruk) as $row) {
            $lastStrukNum = $row['struk_id'];
        }
        $lastStrukNum++;
        $db->exec("update pembayaran set struk_id=$lastStrukNum where ms_id=$msid and struk_id=0");
        $db = null;
    }

    public function getDataPembayaran($msid, $jenis, $bulan, $tahun, $offset, $rows) {
        if ($jenis == '') {
            $pbyrJenis = '';
        } else {
            $pbyrJenis = " and p.mt_id = $jenis ";
        }
        if ($bulan == 'all') {
            $pbyrBulan = "";
        } else {
            $pbyrBulan = " and p.pbyr_bulan = $bulan ";
        }
        $query = <<<q
                select 
                    p.pbyr_id,
                    t.mt_jenis,
                    p.ms_id,
                    p.pbyr_tahun,
                    p.pbyr_bulan,
                    format(p.pbyr_nominal,0,'id_ID') as pbyr_nominal,
                    p.pbyr_text,
                    p.pbyr_createdate,
                    p.pbyr_createby,
                    p.struk_id
                from pembayaran p
                left join m_transaksi t on t.mt_id = p.mt_id
                where p.ms_id = $msid
                    $pbyrJenis
                    and p.pbyr_tahun = $tahun
                    $pbyrBulan
                    and p.pbyr_deletedate is null
                order by p.pbyr_tahun asc, p.pbyr_bulan asc
                limit $offset, $rows
q;
        return $this->fetchQuery($query);
    }

    public function getDefaultPembayaran($msid, $jenis) {
        $query = "select nominal from default_pembayaran where ms_id=$msid and mt_id=$jenis";
        return $this->fetchQuery($query);
    }

    public function getComboDataSiswa($q) {
        $dateNow = date("Ymd");
        $query = <<<q
select 
    s.ms_id, s.ms_nis, s.ms_nisn, s.ms_nama, sh.ms_departemen, sh.ms_jurusan, sh.ms_kelas 
from 
    m_siswa s
    left join m_siswa_hist sh on sh.ms_id = s.ms_id and ('$dateNow' between sh.ms_begdate and sh.ms_enddate)
where 
    (s.ms_id like '%$q%' or s.ms_nis like '%$q%' or s.ms_nisn like '%$q%' or s.ms_nama like '%$q%') 
    and s.ms_active=1 
limit 20
q;
        $data = $this->fetchQuery($query);
        return $data;
    }

    public function loadJnsPmbyrn($ms_id) {
        $dateNow = date("Ymd");
        $query = <<<q
select 
    d.md_id
from 
    m_siswa_hist s
    left join m_departemen d on d.md_nama=s.ms_departemen
where 
    (s.ms_id='$ms_id')
    and ('$dateNow' between s.ms_begdate and s.ms_enddate)
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

    public function getNewStrukId($msid) {
        $username = $_SESSION['user_name'];
        $query = <<<q
                insert into t_struk (struk_msid, struk_date, struk_by)
                values ('$msid', now(), '$username')
q;
        if ($this->con->query($query)) {
            return array('success' => true, 'struk_id' => $this->con->insert_id);
        } else {
            return array('success' => false, 'msg' => $this->con->error);
        }
    }

    private function cekBayar($msid, $jenis, $tahun, $bulan) {
        $query = "select count(*) as jml "
                . "from pembayaran "
                . "where ms_id=$msid "
                . "and mt_id=$jenis "
                . "and pbyr_tahun=$tahun "
                . "and pbyr_bulan=$bulan "
                . "and pbyr_deletedate is null";
        foreach ($this->fetchQuery($query) as $data) {
            $jml = $data['jml'];
        }
        if ($jml > 0) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function doBayar($msid, $jenis, $bulan, $tahun, $nominal, $keterangan, $petugas) {
        $conn = $this->getConn();

        if ($this->cekBayar($msid, $jenis, $tahun, $bulan)) {
//            $q = <<<q
//INSERT INTO 
//    pembayaran (mt_id, ms_id, pbyr_tahun, pbyr_bulan, pbyr_nominal, pbyr_createdate, pbyr_createby, pbyr_text) 
//    VALUES ($jenis, $msid, $tahun, $bulan, $nominal, NOW(), '$petugas', '$keterangan');
//q;
            $q = <<<q
INSERT INTO 
pembayaran (mt_id, ms_id, pbyr_tahun, pbyr_bulan, pbyr_nominal, pbyr_createdate, pbyr_createby, pbyr_text) 
VALUES (:jenis, :msid, :tahun, :bulan, :nominal, NOW(), :petugas, :keterangan);
q;
            $statmnt = $conn->prepare($q);
            $statmnt->execute(
                    array(
                        ':jenis' => $jenis,
                        ':msid' => $msid,
                        ':tahun' => $tahun,
                        ':bulan' => $bulan,
                        ':nominal' => $nominal,
                        ':petugas' => $petugas,
                        ':keterangan' => $keterangan
                    )
            );
            if ($statmnt->rowCount() > 0) {
                return array('success' => true);
            } else {
                return array('success' => false, 'msg' => "###");
            }
        } else {
            return array('success' => false, 'msg' => 'Siswa sudah pernah membayar');
        }
    }

    public function doHapusPbyr($pbyrid, $petugas) {
        try {
            $db = $this->getConn();
            $affectedRows = $db->exec("update pembayaran set pbyr_deletedate=now(), pbyr_deleteby='$petugas' where pbyr_id=$pbyrid");
            if ($affectedRows > 0) {
                return array('success' => true);
            } else {
                return array('success' => false, 'msg' => $db->errorInfo());
            }
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

}
