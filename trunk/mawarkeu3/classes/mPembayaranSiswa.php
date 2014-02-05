<?php

class mPembayaranSiswa {

    private function runQuery($query) {
        $db = new mysqli('localhost', 'root', 'z41nul', 'kjks_mawar_simo');
        return $db->query($query);
    }

    private function fetchQuery($query) {
        $db = new mysqli('localhost', 'root', 'z41nul', 'kjks_mawar_simo');
        $res = $db->query($query);
        $data = array();
        while ($row = $res->fetch_assoc()) {
            array_push($data, $row);
        }
        return $data;
    }

    public function getComboDataSiswa($q) {
        $query = "select ms_id, ms_nis, ms_nisn, ms_nama, ms_departemen, ms_jurusan, ms_kelas "
                . "from m_siswa "
                . "where ms_nis like '%$q%' or ms_nisn like '%$q%' or ms_nama like '%$q%'"
                . "limit 20";
        $data = $this->fetchQuery($query);
        return $data;
    }

    public function loadJnsPmbyrn($ms_id) {
        $query = "select d.md_id
            from m_siswa s
            left join m_departemen d on d.md_nama=s.ms_departemen
            where s.ms_id='$ms_id'";
        $getSiswaDetail = $this->runQuery($query);
        if ($getSiswaDetail->num_rows > 0) {
            $siswaDet = $getSiswaDetail->fetch_assoc();
            $dept = $siswaDet['md_id'];
            $query2 = "select * from m_transaksi where mt_departemen='$dept'";
            $html = array();
            foreach ($this->fetchQuery($query2) as $data) {
                $mtid = $data['mt_id'];
                $mtjenis = mysql_real_escape_string($data['mt_jenis']);
                $html[] = <<<q
<a href="javascript:void(0)" onclick="openJnsPmb('$mtjenis',$mtid);">$mtjenis</a><br>
q;
            }
            return implode('', $html);
        }
    }
    
    public function loadPmbyrn($mtid, $msid) {
        
    }

}
