<?php

class Mod_global_data extends CI_Model {
    
    function __construct()
    {
        parent::__construct();
    }    
    
    function query_ka($q){
        $sql   = "SELECT ka_no AS ka_no, ka_nm AS ka_nm FROM ka 
                  WHERE  (ka_no LIKE '%$q%' OR ka_nm LIKE '%$q%') 
                  ORDER BY IF(SUBSTR(ka_no, 1, LENGTH('$q'))='$q',0,1), 
                           IF(SUBSTR(ka_nm, 1, LENGTH('$q'))='$q',0,1),
                           ka_no, ka_nm
                  LIMIT 30 ";
        return $this->db->query($sql)->result();
    }
    
    
    function query_penugasan_kru($jab){
        // Khusus kru yang Masa Persiapan Pensiun
        // stell = 96000846, set to masinis
        if ($jab == '96000846') $jab = '96000839';

        $sql = " SELECT DISTINCT kodejab kode, namajab nama
                 FROM kru_penugasan_jab_sap WHERE jab_sap = '$jab' 
                 ORDER BY kodejab";           
        $query = $this->db->query($sql);
        return $query->result();
    }
    
    function query_sap_pegawai($q){
        $sql = " SELECT A.pernr nipp, A.`name` nama, B.kode jab_sap, 
                        CONCAT('[',A.pernr,' - ',A.`name`,'] - [',B.nama,'] - [',UPPER(A.persa_text),']') display      
                 FROM `sap_v_pegawai` A INNER JOIN kru_jabatan_sap B ON A.stell = B.kode
                 HAVING (display LIKE '%$q%') 
                 ORDER BY IF(SUBSTR(A.name, 1, LENGTH('$q'))='$q',0,1),
                          IF(SUBSTR(A.pernr, 1, LENGTH('$q'))='$q',0,1),
                          IF(SUBSTR(B.nama, 1, LENGTH('$q'))='$q',0,1),
                          IF(SUBSTR(A.persa_text, 1, LENGTH('$q'))='$q',0,1)
                 LIMIT 0, 20 ";           
        $query = $this->db->query($sql); 
        $query = $query->result();
        return $query;
    }
    
    
}

?>
