<?php

class mod_o18 extends CI_Model {
    function __construct()
    {
        parent::__construct();
    }
    
    function MasterO18($q, $start, $limit){
        $sql = "SELECT SQL_CALC_FOUND_ROWS 
                A.*, B.ka_nm
                FROM or_o18 A, ka B
                WHERE A.ka_no=B.ka_no
                AND (A.ka_no LIKE '%$q%' OR B.ka_nm LIKE '%$q%' OR A.kel_sarana LIKE '%$q%')
                ORDER by A.ka_no, A.urut, A.kel_sarana
                LIMIT $start,$limit";    
        
        $query[0] = $this->db->query($sql); 
        $query[0] = $query[0]->result();
        
        $query[1] = $this->db->query("SELECT FOUND_ROWS() AS recordcount"); 
        $query[1] = $query[1]->result();
         
        return $query;        
    }
    
    function HapusO18($no_ka, $kel_sarana) {
        $this->db->delete('or_o18',array('ka_no'=>$no_ka,'kel_sarana'=>$kel_sarana));
    }
    
    function HapusO18Form($no_ka) {
        $this->db->delete('or_o18',array('ka_no'=>$no_ka));
    }
    
    function LoadO18Form($no_ka){
        $sql = "SELECT * FROM or_o18 WHERE ka_no='$no_ka' ORDER BY ka_no, urut, kel_sarana";    
        
        $query = $this->db->query($sql); 
        $query = $query->result();
        return $query;         
    }
    
    function LoadKA($q) {
        $length = strlen($q);
        $sql = "SELECT ka_no, UPPER(ka_nm) ka_nm FROM ka
                WHERE  (ka_no LIKE '%$q%' OR ka_nm LIKE '%$q%')
                ORDER BY IF(SUBSTR(ka_no, 1, $length)='$q',0,1),
                         IF(SUBSTR(ka_nm, 1, $length)='$q',0,1),
                         ka_no, ka_nm
                LIMIT 10";    
        
        $query = $this->db->query($sql); 
        $query = $query->result();
        return $query;
    }
    
    function LoadKelSarana($q) {
        $length = strlen($q);
        $sql = "SELECT DISTINCT kel_sarana
                FROM mstsarana
                WHERE kel_sarana LIKE '%$q%'
                LIMIT 10";    
        
        $query = $this->db->query($sql); 
        $query = $query->result();
        return $query;
    }
    
    
    function InsertO18($no_ka, $obj) {        
       
        $data = array(
            'ka_no' => $no_ka,
            'kel_sarana' => $obj->kel_sarana,
            'jumlah' => $obj->jumlah,
            'urut' => $obj->urut,
        );
        
        $this->db->insert('or_o18', $data);
    }
}

?>