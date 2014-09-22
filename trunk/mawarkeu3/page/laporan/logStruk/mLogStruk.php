<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of mLogStruk
 *
 * @author mzainularifin
 */
class mLogStruk extends mDbConn{
    //put your code here
    public function getStrukData($strk = '', $nama = '', $dept = '', $tgl1 = '', $tgl2 = '', $offset, $row) {
        $where = array();
        
        $where[] = "struk_id<>0";
        $where[] = "pbyr_deletedate is null";
        if(!empty($strk)){
            $where[] = "struk_id=$strk";
        }
        if(!empty($nama)){
            $where[] = "ms_nama like '%$nama%'";
        }
        if(!empty($dept)){
            $where[] = "ms_departemen='$dept'";
        }
        if(!empty($tgl1) && !empty($tgl2)){
            $where[] = "date_format(pbyr_createdate, '%Y-%m-%d') between '$tgl1' and '$tgl2'";
        } elseif(!empty($tgl1) && empty($tgl2)){
            $where[] = "date_format(pbyr_createdate, '%Y-%m-%d') >= '$tgl1'";
        } elseif(empty($tgl1) && !empty($tgl2)){
            $where[] = "date_format(pbyr_createdate, '%Y-%m-%d') <= '$tgl2'";
        }
        
        if(count($where)>0){
            $wheres = "(" . implode(') AND (', $where) . ")";
        }
        
        $query = "
select p.struk_id, s.ms_nama, d.md_nama, max(p.pbyr_createdate) as pbyr_createdate
from pembayaran p
left join m_siswa s on s.ms_id = p.ms_id
left join m_departemen d on d.md_nama = s.ms_departemen
where $wheres
group by p.struk_id
order by p.struk_id asc, p.pbyr_createdate desc 
limit $offset, $row";
        $res = $this->fetchQuery($query);
        $querytot = "
select count(*) as jum
from (
select p.struk_id, s.ms_nama, d.md_nama, max(p.pbyr_createdate) as pbyr_createdate
from pembayaran p
left join m_siswa s on s.ms_id = p.ms_id
left join m_departemen d on d.md_nama = s.ms_departemen
where $wheres
group by p.struk_id
order by p.struk_id asc, p.pbyr_createdate desc 
) a";
        $restot = $this->fetchQuery($querytot);
        foreach($restot as $rest){
            $tot = $rest['jum'];
        }
        return array('rows' => $res, 'total' => $tot);
    }
    
    public function getDetailStrukData($struk) {
        $q = "select 
    p.pbyr_id, 
    s.ms_nama, 
    s.ms_departemen, 
    s.ms_jurusan, 
    s.ms_kelas, 
    t.mt_jenis, 
    p.pbyr_tahun, 
    p.pbyr_bulan, 
    format(p.pbyr_nominal,0,'id_ID') as pbyr_nominal, 
    p.pbyr_nominal as pbyr_nominal_fortotal, 
    p.pbyr_text,
    p.pbyr_createby,
    p.pbyr_createdate
from pembayaran p
left join m_transaksi t on t.mt_id = p.mt_id
left join m_siswa s on s.ms_id = p.ms_id
where p.struk_id=$struk and p.pbyr_deletedate is null
order by p.pbyr_createdate asc
";
        $namasiswa = '';
        $listTransaksi = array();
        $no = 1;
        foreach ($this->fetchQuery($q) as $data) {
            $namasiswa = $data['ms_nama'];
            $siswaDepartemen = $data['ms_departemen'];
            $siswaJurusan = $data['ms_jurusan'];
            $siswaKelas = $data['ms_kelas'];
            $teller = $data['pbyr_createby'];
            $strukdate = $data['pbyr_createdate'];
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

        $html[] = "<div id='lapLogStruk_printArea_$struk' style='font-family:arial; padding:10px;'>";
        $html[] = "<style>"
                . "@media print{"
                . "* {"
                . "font-size: 9px;"
                . "}"
                . "}"
                . "</style>";
        $html[] = "<div style='text-align:center; font-weight:bold'>KJKS MATHOLI'UL ANWAR<br>Jl. Raya Simo Sungelebak Karanggeneng Lamongan</div>";
        $html[] = "<table width='100%' style='border-bottom: 1px dashed #000000'>"
                . "<tr>"
                . "<td>Teller: $teller</td>"
                . "<td align='right'>Tgl: " . $strukdate . "</td>"
                . "</tr>"
                . "<tr>"
                . "<td colspan=2 align='center'>$namasiswa, ($siswaDepartemen - $siswaJurusan - $siswaKelas)</td>"
                . "</tr>"
                . "</table>";
        $html[] = "<table width='100%'>";
        foreach ($listTransaksi as $trans) {
            $html[] = "<tr>"
                    . "<td>" . $trans['mt_jenis'] . "</td>"
                    . "<td width='20%'>" . $trans['pbyr_tahun'] . ", " . $trans['pbyr_bulan'] . "</td>"
                    . "<td width='20%' align='right'>" . $trans['pbyr_nominal'] . "</td>"
                    . "</tr>";
            $html[] = "<tr><td colspan=3 style='padding-bottom: 10px;'>" . $trans['pbyr_text'] . "</td></tr>";
            $total = $total + $trans['pbyr_nominal_fortotal'];
        }
        setlocale(LC_MONETARY, 'id_ID.UTF-8');
        $totalll = number_format($total, 0, ',', '.');
        $html[] = "<tr>"
                . "<td align=center style='border-top: 1px dashed black;'>TOTAL</td>"
                . "<td colspan=2 align='right' style='border-top: 1px dashed black;'>$totalll</td>"
                . "</tr>";
        $html[] = "</table>";
        $html[] = "</div>";
        $htmls = implode('', $html);
        echo $htmls;
    }
    
}
