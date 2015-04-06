<?php

class main extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('mod_pangkat');
    }

    public function index() {
        $this->Showpangkat();
    }

    public function Showpangkat() {
        $this->load->view('extjs');
        $this->load->view("pangkat_entry");
    }


    public function DataListpangkat() {
        $q = $this->input->get('query');
        $sort = $this->input->get('sort');
        $dir = $this->input->get('dir');
        $start = $this->input->get('start');
        $limit = $this->input->get('limit');

        $data = $this->mod_pangkat->Masterpangkat($q, $start, $limit);

        foreach ($data[1] as $r)
            $record_count = $r->recordcount;
        echo "{'totalCount':'$record_count', 'pangkat_data':[";

        foreach ($data[0] as $row) {
            echo "{
                    'id':'$row->id',
                    'pangkat':'$row->pangkat'
                 },";
        }
        echo "]}";
    }

    public function Hapuspangkat() {
        $id = $this->input->post('id');
        $this->mod_pangkat->Hapuspangkat($id);
    }
	
    public function Insertpangkat() {
        $id = $this->input->post('id');
        $pangkat = $this->input->post('pangkat');		
        $this->mod_pangkat->Hapuspangkat($id);
		$this->mod_pangkat->Insertpangkat($pangkat);
        
    }
	
	public function Updatepangkat() {
        $id = $this->input->post('id');
        $pangkat = $this->input->post('pangkat');
		
		$this->mod_pangkat->Updatepangkat($id, $pangkat);
	}

	# kelurahan
	function kelurahan()
	{
		$this->load->view("m_kelurahan");
	}
	
	function get_data_kelurahan()
	{
		$query = "SELECT m_kelurahan.*, m_kecamatan.kecamatan 
				FROM m_kelurahan 
				INNER JOIN m_kecamatan ON m_kecamatan.id = m_kelurahan.id_kecamatan";
		if($this->input->get('query'))
		{
			$where = " where m_kecamatan.kecamatan like '%".$this->input->get('query')."%' OR m_kelurahan.kelurahan like '%".$this->input->get('query')."%'";
			$query .= $where;
		}		
		$rs = $this->db->query($query);
		$data = $rs->result_array();
		echo json_encode(array('total'=>count($data), 'data'=>$data));		
	}

		
	function get_fungsi_pekerjaan()
	{
		$query = "
			select m_fungsi_kerja.*,
			concat(m_fungsi_kerja.kode,' - ',m_fungsi_kerja.arti) as kode_arti,
			m_jenis_fungsi_kerja.jenis_fungsi
			from m_fungsi_kerja
			inner join m_jenis_fungsi_kerja on m_jenis_fungsi_kerja.id = m_fungsi_kerja.id_jenis_fungsi
		";
		if($this->input->get('query'))
		{
			$where = " where m_fungsi_kerja.kode like '%".$this->input->get('query')."%' OR m_fungsi_kerja.arti like '%".$this->input->get('query')."%' OR m_fungsi_kerja.uraian like '%".$this->input->get('query')."%'";
			$query .= $where;
		}		
		$rs = $this->db->query($query);
		$data = $rs->result_array();
		echo json_encode(array('total'=>count($data), 'data'=>$data));	
	}
	
	function get_upaya_fisik()
	{
		$query = "
			select 
				m_upaya_fisik.id,
				m_upaya_fisik.kode,
				m_upaya_fisik.arti,
				concat(m_upaya_fisik.kode,' - ',m_upaya_fisik.arti) as kode_arti,
				m_upaya_fisik.arti as ket
			from m_upaya_fisik
		";
		if($this->input->get('query'))
		{
			$where = " where kode like '%".$this->input->get('query')."%' OR arti like '%".$this->input->get('query')."%'";
			$query .= $where;
		}		
		$rs = $this->db->query($query);
		$data = $rs->result_array();
		echo json_encode(array('total'=>count($data), 'data'=>$data));	
	}
	
	function get_minat_kerja()
	{
		$query = "
			select 
				m_minat_kerja.id,
				m_jns_minat_kerja.kode_minat,
				m_jns_minat_kerja.arti,
				concat(m_jns_minat_kerja.kode_minat,' - ',m_jns_minat_kerja.arti, ' | ', m_minat_kerja.minat_kerja) as kode_arti,
				m_minat_kerja.minat_kerja as ket
			from m_minat_kerja 
				inner join m_jns_minat_kerja on m_jns_minat_kerja.id = m_minat_kerja.id_jns_minat_kerja		
		";
		if($this->input->get('query'))
		{
			$where = " where m_minat_kerja.minat_kerja like '%".$this->input->get('query')."%' OR m_jns_minat_kerja.arti like '%".$this->input->get('query')."%'";
			$query .= $where;
		}		
		$rs = $this->db->query($query);
		$data = $rs->result_array();
		echo json_encode(array('total'=>count($data), 'data'=>$data));	
	}
	
	function get_bakat_kerja()
	{
		$query = "SELECT *, concat(kode_bakat_kerja, ' - ', arti) kode_arti FROM m_bakat_kerja";
		if($this->input->get('query'))
		{
			$where = " where arti like '%".$this->input->get('query')."%' OR bakat_kerja like '%".$this->input->get('query')."%'";
			$query .= $where;
		}		
		$rs = $this->db->query($query);
		$data = $rs->result_array();
		echo json_encode(array('total'=>count($data), 'data'=>$data));		
	}

	function get_temperamen()
	{
		$query = "SELECT *, concat(kode_tempramen, ' - ', arti) kode_arti FROM m_tempramen_kerja";
		if($this->input->get('query'))
		{
			$where = " where arti like '%".$this->input->get('query')."%' OR tempramen like '%".$this->input->get('query')."%'";
			$query .= $where;
		}		
		$rs = $this->db->query($query);
		$data = $rs->result_array();
		echo json_encode(array('total'=>count($data), 'data'=>$data));		
	}
		
	function simpan_data_kelurahan()
	{
		$ret = false;
		if($this->input->post('kecamatan'))
		{		
			$where = array("id"=>$this->input->post('id'));
			if($this->cek_is_ada('m_kecamatan',$where))
			{
				if($this->db->update('m_kecamatan',$this->input->post(),$where))
				{
					$ret = true;
				} else $ret= false;
			} else 
			if($this->db->insert('m_kecamatan',$this->input->post()))
			{
				$ret = true;
			} else $ret = false;
		}
		if($ret) echo "Data BERHASIL disimpan.";
			else echo "Data GAGAL disimpan.";		
	}
	
	function hapus_data_kelurahan()
	{
		if($this->input->post('id'))
		{
			if($this->db->delete('m_kecamatan',sprintf('id in(%s)', $this->input->post('id'))))
			{
				echo "Data berhasil dihapus.";
			} else echo "Data GAGAL dihapus.";
		}
	}
	
	function cek_is_ada($table, $where)
	{
		$this->db->where($where);
		$this->db->from($table);
		$c = $this->db->count_all_results();
		if($c>0) return true;
			else return false;
	}
	
	#kecamatan
	function kecamatan()
	{
		$this->load->view("m_kecamatan");
	}
	
	function get_data_kecamatan()
	{
		$query = "select * from m_kecamatan";
		if($this->input->get('query'))
		{
			$where = " where kecamatan like '%".$this->input->get('query')."%'";
			$query .= $where;
		}		
		$rs = $this->db->query($query);
		$data = $rs->result_array();
		echo json_encode(array('total'=>count($data), 'data'=>$data));	
	}
	
	function simpan_data_kecamatan()
	{
		$ret = false;
		if($this->input->post('kecamatan'))
		{		
			$where = array("id"=>$this->input->post('id'));
			if($this->cek_is_ada('m_kecamatan',$where))
			{
				if($this->db->update('m_kecamatan',$this->input->post(),$where))
				{
					$ret = true;
				} else $ret= false;
			} else 
			if($this->db->insert('m_kecamatan',$this->input->post()))
			{
				$ret = true;
			} else $ret = false;
		}
		if($ret) echo "Data BERHASIL disimpan.";
			else echo "Data GAGAL disimpan.";	
	}
	
	function hapus_data_kecamatan()
	{
		if($this->input->post('id'))
		{
			if($this->db->delete('m_kecamatan',sprintf('id in(%s)', $this->input->post('id'))))
			{
				echo "Data berhasil dihapus.";
			} else echo "Data GAGAL dihapus.";
		}	
	}

	# m_jabatan
	# kelurahan
	function jabatan()
	{
		$this->load->view("m_jabatan");
	}
	
	function get_data_jabatan()
	{
        $q = $this->input->get('query');
        $sort = $this->input->get('sort');
        $dir = $this->input->get('dir');
        $start = $this->input->get('start');
        $limit = $this->input->get('limit');		

		$query = "
				SELECT
				m_jabatan.id, m_jabatan.kode_rumpun, m_jabatan.kode, m_jabatan.nama, 
				id_jens_jabatan, 
				concat(m_jenis_jabatan.nama,' - ',m_jenis_jabatan.nama_panjang) as jenis_jabatan2 
				FROM 
				m_jabatan 
				LEFT JOIN m_jenis_jabatan ON m_jabatan.id_jens_jabatan = m_jenis_jabatan.id		
		";
		$total = $this->db->query($query)->num_rows();
		if($this->input->get('id_jens_jabatan'))
		{
			$where = sprintf(" WHERE id_jens_jabatan='%d'", $this->input->get('id_jens_jabatan'));
			$query .= $where;
			$total = $this->db->query($query)->num_rows();		
		}
		
		if($this->input->get('query'))
		{
			$where = " WHERE m_jabatan.kode like '%".$this->input->get('query')."%' OR m_jabatan.nama like '%".$this->input->get('query')."%'";
			$query .= $where;
			$total = $this->db->query($query)->num_rows();
		}		
		$query = $query . " LIMIT $start, $limit";
		$rs = $this->db->query($query);
		$data = $rs->result_array();
		echo json_encode(array('total'=>$total, 'data'=>$data));		
	}
	
	function simpan_data_jabatan()
	{
		$ret = false;
		if($this->input->post('kode') && $this->input->post('nama'))
		{		
			$id = $this->input->post('id');
			$where = array("id"=>$id);
			if($this->cek_is_ada('m_jabatan',$where))
			{
				if($this->db->update('m_jabatan',$this->input->post(),$where))
				{
					$ret = true;
				} else $ret= false;
			} else 
			if($this->db->insert('m_jabatan',$this->input->post()))
			{
				$ret = true;
			} else $ret = false;
		}
		if($ret) echo "Data BERHASIL disimpan.";
			else echo "Data GAGAL disimpan.";		
	}
	
	function hapus_data_jabatan()
	{
		if($this->input->post('id'))
		{
			if($this->db->delete('m_jabatan',sprintf('id in(%s)', $this->input->post('id'))))
			{
				echo "Data berhasil dihapus.";
			} else echo "Data GAGAL dihapus.";
		}
	}
	
	function get_jenis_jabatan()
	{
		$query = "SELECT id, nama, nama_panjang, concat(nama,' - ', nama_panjang) jenis_jabatan FROM m_jenis_jabatan";
		if($this->input->get('query'))
		{
			$where = " where nama like '%".$this->input->get('query')."%' OR nama_panjang like '%".$this->input->get('query')."%'";
			$query .= $where;
		}		
		$rs = $this->db->query($query);
		$data = $rs->result_array();
		echo json_encode(array('total'=>count($data), 'data'=>$data));
	}
	

	function instansi()
	{
		$this->load->view("m_instansi");
	}
	
	function get_data_instansi()
	{
		$query = "select * from m_instansi2";
		if($this->input->get('query'))
		{
			$where = " where kode like '%".$this->input->get('query')."%' OR nama like '%".$this->input->get('query')."%' OR nama_panjang like '%".$this->input->get('query')."%'";
			$query .= $where;
		}		
		$rs = $this->db->query($query);
		$data = $rs->result_array();
		echo json_encode(array('total'=>count($data), 'data'=>$data));	
	}
	
	function simpan_data_instansi()
	{
		$ret = false;
		if($this->input->post('kode') && $this->input->post('nama') && $this->input->post('nama_panjang'))
		{		
			$where = array("kode"=>$this->input->post('kode'));
			if($this->cek_is_ada('m_instansi2',$where))
			{
				if($this->db->update('m_instansi2',$this->input->post(),$where))
				{
					$ret = true;
				} else $ret= false;
			} else 
			if($this->db->insert('m_instansi2',$this->input->post()))
			{
				$ret = true;
			} else $ret = false;
		}
		if($ret) echo "Data BERHASIL disimpan.";
			else echo "Data GAGAL disimpan.";	
	}

	function hapus_data_instansi()
	{
		if($this->input->post('kode'))
		{
			$kd = explode(',', $this->input->post('kode'));
			foreach($kd as $k=>$v)
			{
				$ne[] = "'$v'";
			}
			$kode = implode(',', $ne);
			if($this->db->delete('m_instansi2',sprintf('kode in(%s)', $kode)))
			{
				echo "Data berhasil dihapus.";
			} else echo "Data GAGAL dihapus.";
		}	
	}
	
	
}

?>