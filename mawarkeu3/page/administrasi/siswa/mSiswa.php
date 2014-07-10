<?php

/**
 * Description of mSiswa
 *
 * @author mzainularifin
 */
class mSiswa extends mDbConn {

    //put your code here
    public function getSiswaData($dept, $jurs, $kels, $name, $order, $sort, $offset, $row) {
        $wheres = '';
        $arrFetch = array();
        if(!empty($dept)){
            $where[] = "d.md_id=:dept";
            $arrFetch[':dept'] = $dept;
        }
        if(!empty($jurs)){
            $where[] = "j.mj_id=:jurs";
            $arrFetch[':jurs'] = $jurs;
        }
        if(!empty($kels)){
            $where[] = "k.mkls_id=:kels";
            $arrFetch[':kels'] = $kels;
        }
        if(!empty($name)){
            $where[] = "(s.ms_nama like :name or s.ms_id like :name or s.ms_nis like :name or s.ms_nisn like :name)";
            $arrFetch[':name'] = '%' . $name . '%';
        }
        if(count($where) > 0){
            $wheres = "where " . implode(" and ", $where);
        }
        
        $querytot = "select count(*) as jum from m_siswa s "
                . "left join m_departemen d on d.md_nama = s.ms_departemen "
                . "left join m_jurusan j on j.mj_nama = s.ms_jurusan and j.mj_departemen = d.md_id "
                . "left join m_kelas k on k.mkls_nama = s.ms_kelas and k.mkls_jurusan = j.mj_id and k.mkls_departemen = d.md_id "
                . "$wheres";
        $restot = $this->fetchQuery($querytot, $arrFetch);
        foreach ($restot as $rest) {
            $tot = $rest['jum'];
        }
        
        $orders = '';
        if(!empty($order)){
            $orders = "order by $sort $order";
        } else {
            $orders = "order by ms_id asc";
        }
        
        $query = "select s.ms_id, s.ms_nis, s.ms_nisn, s.ms_nama, s.ms_jeniskelamin, s.ms_birthplace, date_format(s.ms_birthdate,'%Y-%m-%d') as ms_birthdate, d.md_id, s.ms_departemen, j.mj_id, s.ms_jurusan, k.mkls_id, s.ms_kelas "
                . "from m_siswa s "
                . "left join m_departemen d on d.md_nama = s.ms_departemen "
                . "left join m_jurusan j on j.mj_nama = s.ms_jurusan and j.mj_departemen = d.md_id "
                . "left join m_kelas k on k.mkls_nama = s.ms_kelas and k.mkls_jurusan = j.mj_id and k.mkls_departemen = d.md_id "
                . "$wheres "
                . "$orders "
                . "limit $offset , $row";
        $res = $this->fetchQuery($query, $arrFetch);
        return array('rows' => $res, 'total' => $tot, 'qry' => $query, 'qrytot' => $querytot);
    }

    public function doSaveSiswa($nis, $nisn, $nama, $gend, $tmpl, $tgll, $kels) {
        $db = $this->getConn();
        
        list($dept, $jurs, $klas) = $this->getNamaDeptJurKel($kels);
        
        foreach ($db->query("select count(*) as jum from m_siswa where ms_nis=$nis and ms_nisn=$nisn and ms_nama='$nama'") as $row) {
            $jum = $row['jum'];
        }
        if ($jum > 0) {
            return array('success' => false, 'msg' => 'siswa tersebut sudah pernah dientrykan. silakan cek lagi.');
        } else {
            $affected = $db->prepare("insert into "
                    . "m_siswa(ms_nis, ms_nisn, ms_nama, ms_jeniskelamin, ms_birthplace, ms_birthdate, ms_departemen, ms_jurusan, ms_kelas) "
                    . "values(:nis, :nisn, :nama, :gend, :tmpl, :tgll, :dept, :jurs, :klas)");
            $affected->execute(array(
                ':nis' => $nis,
                ':nisn' => $nisn,
                ':nama' => strtoupper($nama),
                ':gend' => $gend,
                ':tmpl' => $tmpl,
                ':tgll' => $tgll,
                ':dept' => $dept,
                ':jurs' => $jurs,
                ':klas' => $klas
            ));
            if ($affected->rowCount() > 0) {
                return array('success' => true);
            } else {
                return array('success' => false, 'msg' => 'gagal insert');
            }
        }
        $db = null;
    }

    public function doEditSiswa($msid, $nis, $nisn, $nama, $gend, $tmpl, $tgll, $kels) {
        $db = $this->getConn();
        
        list($dept, $jurs, $klas) = $this->getNamaDeptJurKel($kels);
        
        $whereNis = empty($nis) ? " ms_nis is null " : " ms_nis=$nis ";
        $whereNisn = empty($nisn) ? " ms_nisn is null " : " ms_nisn=$nisn ";
        
        foreach ($db->query("select count(*) as jum from m_siswa where $whereNis and $whereNisn and ms_nama='$nama' and ms_id <> $msid") as $row) {
            $jum = $row['jum'];
        }
        if ($jum > 0) {
            return array('success' => false, 'msg' => 'siswa tersebut sudah pernah dientrykan. silakan cek lagi.');
        } else {
            $affected = $db->prepare("update m_siswa set ms_nis=:nis, ms_nisn=:nisn, ms_nama=:nama, ms_jeniskelamin=:gend, ms_birthplace=:tmpl, ms_birthdate=:tgll, ms_departemen=:dept, ms_jurusan=:jurs, ms_kelas=:klas where ms_id=:msid");
            $affected->execute(array(
                ':msid' => $msid,
                ':nis' => $nis,
                ':nisn' => $nisn,
                ':nama' => strtoupper($nama),
                ':gend' => $gend,
                ':tmpl' => $tmpl,
                ':tgll' => $tgll,
                ':dept' => $dept,
                ':jurs' => $jurs,
                ':klas' => $klas
            ));
            if ($affected->rowCount() > 0) {
                return array('success' => true);
            } else {
                return array('success' => false, 'msg' => 'gagal update');
            }
        }
        $db = null;
    }

    public function doDeleteSiswa($msid) {
        $db = $this->getConn();
        $affected = $db->exec("delete from m_siswa where ms_id='$msid'");
        if ($affected > 0) {
            return array('success' => true);
        } else {
            return array('success' => false, 'msg' => 'gagal delete');
        }
        $db = null;
    }
    
    public function getListDept() {
        return $this->fetchQuery("select * from m_departemen order by md_nama");
    }
    
    public function getListJurs($mdid) {
        $where_mdid = empty($mdid) ? "" : " where mj_departemen = $mdid";
        return $this->fetchQuery("select * from m_jurusan $where_mdid order by mj_nama");
    }

    public function getListKels($mdid, $mjid) {
        $wheres = '';
        if(!empty($mdid)){
            $where[] =  " mkls_departemen = $mdid ";
        }
        if(!empty($mjid)){
            $where[] = " mkls_jurusan = $mjid ";
        }
        if(count($where) > 0){
            $wheres = "where " . implode(" and ", $where);
        }
        return $this->fetchQuery("select * from m_kelas $wheres order by mkls_nama");
    }

    public function getListKelas() {
        $query = "select k.mkls_id, concat(d.md_nama, ' - ', j.mj_nama, ' - ', k.mkls_nama) as mkls_nama "
                . "from m_kelas k "
                . "left join m_jurusan j on j.mj_id = k.mkls_jurusan "
                . "left join m_departemen d on d.md_id = k.mkls_departemen "
                . "order by d.md_nama asc, j.mj_nama asc, k.mkls_nama asc ";
        return $this->fetchQuery($query);
    }

    private function getNamaDeptJurKel($kels) {
        $db = $this->getConn();
        foreach ($db->query("
        select d.md_nama, j.mj_nama, k.mkls_nama
        from m_kelas k
        left join m_departemen d on d.md_id = k.mkls_departemen
        left join m_jurusan j on j.mj_id = k.mkls_jurusan
        where k.mkls_id=$kels
        ") as $row1) {
            $dept = $row1['md_nama'];
            $jurs = $row1['mj_nama'];
            $klas = $row1['mkls_nama'];
        }
        return array($dept, $jurs, $klas);
    }

}
