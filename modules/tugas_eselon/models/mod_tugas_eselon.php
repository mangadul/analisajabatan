<?php

class mod_tugas_eselon extends CI_Model {
    function __construct()
    {
        parent::__construct();
    }
    
    function MasterTugas_Eselon($q, $start, $limit){
        $sql = "SELECT SQL_CALC_FOUND_ROWS 
                A.id, B.id AS id_eselon, A.tugas_eselon AS tugas_eselon, B.eselon AS eselon
                FROM m_eselon A, m_tugas_eselon B
                WHERE A.id_eselon = B.id AND (A.eselon LIKE '%$q%' OR B.tugas_eselom LIKE '%$q%')
                ORDER by A.tugas_eselon
                LIMIT $start,$limit";    
        
        $query[0] = $this->db->query($sql); 
        $query[0] = $query[0]->result();
        
        $query[1] = $this->db->query("SELECT FOUND_ROWS() AS recordcount"); 
        $query[1] = $query[1]->result();
         
        return $query;        
    }
    
    function HapusTugas_Eselon($id) {
        $this->db->delete('m_tugas_eselon',array('id'=>$id));
    }
        
    
    function LoadEselon($q) {
        $length = strlen($q);
        $sql = "SELECT id, eselon FROM m_eselon
                WHERE eselon LIKE '%$q%'
                ORDER BY eselon
                LIMIT 10"; 
		
        $query = $this->db->query($sql); 
        $query = $query->result();
        return $query;
    }
    
    function InsertTugas_Eselon($id_eselon, $eselon) {        
        $data = array(
            'id_eselon' => $id_eselon,
			'eselon' => $eselon
        );
		
        $this->db->insert('m_tugas_eselon', $data);
    }
	
	function UpdateTugas_eselon($id, $id_eselon, $tugas_eselon) {
		$sql = "UPDATE m_tugas_eselon SET id_eselon = '$id_eselon', tugas_eselon = '$tugas_eselon' WHERE id='$id'"; 
		$this->db->query($sql);
	}
}

?>