<?php

class mod_upaya_fisik extends CI_Model {
    function __construct()
    {
        parent::__construct();
    }
    
    function Masterupaya_fisik($q, $start, $limit){
        $sql = "SELECT SQL_CALC_FOUND_ROWS 
                *
                FROM m_upaya_fisik
                WHERE(id LIKE '%$q%' OR upaya_fisik LIKE '%$q%')
                ORDER by id
                LIMIT $start,$limit";    
        
        $query[0] = $this->db->query($sql); 
        $query[0] = $query[0]->result();
        
        $query[1] = $this->db->query("SELECT FOUND_ROWS() AS recordcount"); 
        $query[1] = $query[1]->result();
         
        return $query;        
    }
    
    function Hapusupaya_fisik($id) {
        $this->db->delete('m_upaya_fisik',array('id'=>$id));
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
    
    
    
    function Insertupaya_fisik($upaya_fisik) {        
        $data = array(
            'upaya_fisik' => $upaya_fisik
        );
		
        $this->db->insert('m_upaya_fisik', $data);
    }
	
	function Updateupaya_fisik($id, $upaya_fisik) {
		$sql = "UPDATE m_upaya_fisik SET upaya_fisik = '$upaya_fisik' WHERE id='$id'"; 
		$this->db->query($sql);
	}
}

?>