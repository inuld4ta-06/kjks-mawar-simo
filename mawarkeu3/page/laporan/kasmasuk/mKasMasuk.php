<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of mKasMasuk
 *
 * @author mzainularifin
 */
class mKasMasuk extends mDbConn{
    //put your code here
    public function getKasMasukData($dept, $jurs, $kels, $tglfr, $tglto) {
        $wheres = '';
        $arrFetch = array();
        if(!empty($tglfr) || !empty($tglto)){
            if(!empty($tglfr) && !empty($tglto)){
                if($tglfr == $tglto) {
                    $where[] = "date_format(p.pbyr_createdate,'%Y-%m-%d') between :tglfr and :tglto";
                    $arrFetch[':tglfr'] = $tglfr;
                    $arrFetch[':tglto'] = $tglfr;
                } elseif ($tglfr > $tglto) {
                    $where[] = "date_format(p.pbyr_createdate,'%Y-%m-%d') between :tglto and :tglfr";
                    $arrFetch[':tglfr'] = $tglfr;
                    $arrFetch[':tglto'] = $tglto;
                } elseif ($tglfr < $tglto) {
                    $where[] = "date_format(p.pbyr_createdate,'%Y-%m-%d') between :tglfr and :tglto";
                    $arrFetch[':tglfr'] = $tglfr;
                    $arrFetch[':tglto'] = $tglto;
                }
            } elseif(!empty($tglfr) && empty($tglto)){
                $where[] = "date_format(p.pbyr_createdate,'%Y-%m-%d') = :tglfr";
                $arrFetch[':tglfr'] = $tglfr;
            } elseif(empty($tglfr) && !empty($tglto)){
                $where[] = "date_format(p.pbyr_createdate,'%Y-%m-%d') = :tglto";
                $arrFetch[':tglto'] = $tglto;
            }
        }
        if(!empty($dept)){
            $where[] = "s.ms_departemen=:dept";
            $arrFetch[':dept'] = $dept;
        }
        if(!empty($jurs)){
            $where[] = "s.ms_jurusan=:jurs";
            $arrFetch[':jurs'] = $jurs;
        }
        if(!empty($kels)){
            $where[] = "s.ms_kelas=:kels";
            $arrFetch[':kels'] = $kels;
        }
        
        $where[] = "p.pbyr_deletedate is null";
        
        if(count($where) > 0){
            $wheres = "where " . implode(" and ", $where);
        }
        
        $querySelect = "
select 
    date_format(p.pbyr_createdate, '%Y-%m-%d') as pbyr_createdate, 
    t.mt_jenis, 
    sum(p.pbyr_nominal_debet) as debet, 
    sum(p.pbyr_nominal) as kredit 
from pembayaran p
left join m_transaksi t on t.mt_id = p.mt_id
left join m_siswa s on s.ms_id = p.ms_id
$wheres
group by p.mt_id 
";
        $res = $this->fetchQuery($querySelect, $arrFetch);
        $saldo = 0;
        if(count($res) > 0){
            foreach($res as $ress){
                $saldo = $saldo + $ress['debet'] + $ress['kredit'];
                $ress['saldo'] = number_format($saldo, 0, ',','.');
                $ress['kredit'] = number_format($ress['kredit'], 0, ',', '.');
                $rowz[] = $ress;
            }
            $rowz[] = array(
                'pbyr_createdate' => date("Y-m-d"),
                'mt_jenis' => 'Masuk Tab KJKS Mawar',
                'debet' => number_format($saldo, 0, ',','.'),
                'kredit' => '',
                'saldo' => 0
            );
            return array('rows' => $rowz);
        } else {
            return array('rows' => array(array('pbyr_createdate' => "no data")));
        }
    }
    
    
    public function getListDepartemen() {
        return $this->fetchQuery("select distinct(ms_departemen) as md_nama from m_siswa order by md_nama asc");
    }
    
    public function getListJurusan($dept) {
        $where = "where ms_departemen = 'MA'"; // defaultnya ke MA
        if(!empty($dept)){
            $where = "where ms_departemen = '$dept'";
        }
        return $this->fetchQuery("select distinct(ms_jurusan) as mj_nama from m_siswa $where order by mj_nama asc");
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
}
