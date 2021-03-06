<?php

class mod_sts_pns extends CI_Model {
    function __construct()
    {
        parent::__construct();
    }
    
    function Mastersts_pns($q, $start, $limit){
        $sql = "SELECT SQL_CALC_FOUND_ROWS 
                *
                FROM m_sts_pns
                WHERE(id LIKE '%$q%' OR sts_pns LIKE '%$q%')
                ORDER by id
                LIMIT $start,$limit";    
        
        $query[0] = $this->db->query($sql); 
        $query[0] = $query[0]->result();
        
        $query[1] = $this->db->query("SELECT FOUND_ROWS() AS recordcount"); 
        $query[1] = $query[1]->result();
         
        return $query;        
    }
    
    function Hapussts_pns($id) {
        $this->db->delete('m_sts_pns',array('id'=>$id));
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
    
    
    
    function Insertsts_pns($sts_pns) {        
        $data = array(
            'sts_pns' => $sts_pns
        );
		
        $this->db->insert('m_sts_pns', $data);
    }
	
	function Updatests_pns($id, $sts_pns) {
		$sql = "UPDATE m_sts_pns SET sts_pns = '$sts_pns' WHERE id='$id'"; 
		$this->db->query($sql);
	}
}

?>