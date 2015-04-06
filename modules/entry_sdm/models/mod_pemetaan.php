<?php

class mod_pemetaan extends CI_Model {

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
            
}

?>