<?php

class mod_kelurahan extends CI_Model {
    function __construct()
    {
        parent::__construct();
    }
    
    function MasterKelurahan($q, $start, $limit){
        $sql = "SELECT SQL_CALC_FOUND_ROWS 
                A.id, B.id AS id_kecamatan, A.nama AS nama, B.nama AS nama_kecamatan
                FROM m_kelurahan A, m_kecamatan B
                WHERE A.id_kecamatan = B.id AND (A.nama LIKE '%$q%' OR B.nama LIKE '%$q%')
                ORDER by A.nama
                LIMIT $start,$limit";    
        
        $query[0] = $this->db->query($sql); 
        $query[0] = $query[0]->result();
        
        $query[1] = $this->db->query("SELECT FOUND_ROWS() AS recordcount"); 
        $query[1] = $query[1]->result();
         
        return $query;        
    }
    
    function HapusKelurahan($id) {
        $this->db->delete('m_kelurahan',array('id'=>$id));
    }
        
    
    function LoadKecamatan($q) {
        $length = strlen($q);
        $sql = "SELECT id, nama FROM m_kecamatan
                WHERE nama LIKE '%$q%'
                ORDER BY nama
                LIMIT 10"; 
		
        $query = $this->db->query($sql); 
        $query = $query->result();
        return $query;
    }
    
    function InsertKelurahan($id_kecamatan, $nama) {        
        $data = array(
            'id_kecamatan' => $id_kecamatan,
			'nama' => $nama
        );
		
        $this->db->insert('m_kelurahan', $data);
    }
	
	function UpdateKelurahan($id, $id_kecamatan, $nama) {
		$sql = "UPDATE m_kelurahan SET id_kecamatan = '$id_kecamatan', nama = '$nama' WHERE id='$id'"; 
		$this->db->query($sql);
	}
}

?>