<?php

/**
 * Description of mSiswa
 *
 * @author mzainularifin
 */
class mSiswa extends mDbConn {

    //put your code here
    public function getSiswaData($searchKey = '', $offset, $row) {
        $q = $searchKey == '' ? '%%' : "%$searchKey%";
        $query = "select s.ms_id, s.ms_nis, s.ms_nisn, s.ms_nama, s.ms_jeniskelamin, s.ms_birthplace, date_format(s.ms_birthdate,'%Y-%m-%d') as ms_birthdate, d.md_id, s.ms_departemen, j.mj_id, s.ms_jurusan, k.mkls_id, s.ms_kelas "
                . "from m_siswa s "
                . "left join m_departemen d on d.md_nama = s.ms_departemen "
                . "left join m_jurusan j on j.mj_nama = s.ms_jurusan "
                . "left join m_kelas k on k.mkls_nama = s.ms_kelas "
                . "where (s.ms_id like '$q' or s.ms_nisn like '$q' or s.ms_nis like '$q' or s.ms_nama like '$q') "
                . "limit $offset, $row";
        $res = $this->fetchQuery($query);
        $querytot = "select count(*) as jum from m_siswa s where (s.ms_id like '$q' or s.ms_nisn like '$q' or s.ms_nis like '$q' or s.ms_nama like '$q')";
        $restot = $this->fetchQuery($querytot);
        foreach ($restot as $rest) {
            $tot = $rest['jum'];
        }
        return array('rows' => $res, 'total' => $tot);
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
