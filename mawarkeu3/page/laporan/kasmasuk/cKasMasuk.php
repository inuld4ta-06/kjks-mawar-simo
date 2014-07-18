<?php

require_once 'mKasMasuk.php';
$mdlKasMasuk = new mKasMasuk();
$act = filter_input(INPUT_GET, 'act');
$post = filter_input_array(INPUT_POST);
switch ($act){
    case 'data':
        $dept = isset($post['dept']) ? $post['dept'] : 'MA';
        $jurs = isset($post['jurs']) ? $post['jurs'] : '';
        $kels = isset($post['kels']) ? $post['kels'] : '';
        $tglfr = isset($post['tglfr']) ? $post['tglfr'] : date("Y-m-d");
        $tglto = isset($post['tglto']) ? $post['tglto'] : date("Y-m-d");
        echo json_encode($mdlKasMasuk->getKasMasukData($dept, $jurs, $kels, $tglfr, $tglto));
        break;
    case 'comboSearchDepartemen':
        foreach ($mdlKasMasuk->getListDepartemen() as $row){
            if($row['md_nama'] == 'MA'){
                $row['selected'] = true;
            }
            $data[] = $row;
        }
        echo json_encode($data);
        break;
    case 'comboSearchJurusan':
        $dept = filter_input(INPUT_GET, 'dept');
        echo json_encode($mdlKasMasuk->getListJurusan($dept));
        break;
    case 'comboSearchKelas':
        $dept = filter_input(INPUT_GET, 'dept');
        $jurs = filter_input(INPUT_GET, 'jurs');
        echo json_encode($mdlKasMasuk->getListKelas($dept, $jurs));
        break;
    default :
        include 'vKasMasuk.php';
        break;
}