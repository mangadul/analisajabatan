<?php

class model_jabatan extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }
    
    
    function get_data($id) {
        $sql = sprintf("
			select 
			m_tree.id_tree as id, 
			concat('<a id=\"show_pilih\" href=\"#\" onclick=\"show_menu(',m_tree.id_tree,')\" style=\"text-decoration:none;\">',m_jabatan.nama,'</a>') as markup,
			m_tree.parent as parentId,
			m_tree.id_jabatan,
			m_jabatan.nama
			from m_tree
			inner join m_jabatan on m_jabatan.id = m_tree.id_jabatan
			where m_tree.id_jenis_diagram = '%d'	
		", $id);            
        $query = $this->db->query($sql); 
        $query = $query->result();
        return $query;
    }
          
	function get_data_1_5($kode, $instansi)
	{
		$query = sprintf("select * from frm_isian_1_5 where id_instansi='%s' and kode_jabatan='%s'", $instansi, $kode);
		$res = $this->db->query($query)->row_array();
		if(count($res)) return $res;
			else return false;
	}

	function get_data_sotk($jabatan, $instansi, $parent)
	{
		$query = sprintf("
			select *, nama_jabatan as text 
			from 
			frm_isian_1_5_struktur 
			where 
				kode_instansi='%s' 
				and kode_jabatan='%s'
				and parent_id = '%d'
					", $instansi, $jabatan, $parent);
		$res = $this->db->query($query)->result_array();
		if(count($res)) return $res;
			else return false;
	}

	function get_data_sotk_id($id)
	{
		$query = sprintf("
			select * 
			from 
			frm_isian_1_5_struktur 
			where id = '%d'", $id);
		$res = $this->db->query($query)->row_array();
		if(count($res)) return $res;
			else return false;
	}
	
}

?>