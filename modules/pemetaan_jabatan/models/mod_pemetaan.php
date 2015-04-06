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

	/*
	insert into m_tree_struktur(id_jenis_diagram, id_tree, parent, markup, id_jabatan)
	select 1, id, parentid, nama, id_jabatan
	from m_jabatan
	where m_jabatan.parentid is not null	
	*/
	
    function get_data_struktur($id) {
        $sql = sprintf("
			select id_tree as id,
			parent as parentId,
			concat('<a id=\"show_pilih\" href=\"#\" onclick=\"show_menu(',m_tree_struktur.id_tree,')\" style=\"text-decoration:none;\">',m_tree_struktur.markup,'</a>') as markup			
			from m_tree_struktur
			where id_tree = %d or parent = %d
			and id_jenis_diagram=1
		", $id, $id);
        $query = $this->db->query($sql); 
        $query = $query->result();
        return $query;
    }
	
    function get_data_jabatan($kode) {
        $sql = sprintf("
			select 
			m_jabatan.id, 
			concat('<a id=\"show_pilih\" href=\"#\" onclick=\"show_menu(',m_jabatan.id,')\" style=\"text-decoration:none;\">',m_jabatan.nama,'</a>') as markup,
			m_jabatan.parentid as parentId,
			m_jabatan.id as id_jabatan,
			m_jabatan.nama
			from m_jabatan
			where m_jabatan.parent in (select koint from m_jabatan where m_jabatan.kode='%s')
		", $kode);            
        $query = $this->db->query($sql); 
        $query = $query->result();
        return $query;
    }

	function get_data_sotk($instansi, $parent)
	{
		$query = sprintf("			
			select *, nama_jabatan as text 
			from 
			t_pemetaan_jabatan
			where 
				kode_instansi='%s' 
				and parent_id = '%d'
					", $instansi, $parent);
		$res = $this->db->query($query)->result_array();
		if(count($res)) return $res;
			else return false;
	}

	
}

?>