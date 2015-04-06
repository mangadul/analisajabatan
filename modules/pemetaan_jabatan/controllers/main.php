<?php

class main extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('mod_pemetaan');
    }

    public function index() {
        $this->load->view('main');
		//{"id":1,"markup":"Home Page","parentId":0}
    }
	
	function get_data_pemetaan($id=1)
	{
		//$id = $this->input->get('id');
		$res = $this->mod_pemetaan->get_data($id);
		echo json_encode($res);
	}

	function get_data_str($id=1)
	{
		$this->session->set_userdata('id_str','');
		$this->session->set_userdata('id_str',$id);	
		$res = $this->mod_pemetaan->get_data_struktur($id);
		echo json_encode($res);
	}

	public function get_data_struktur($kode='001.1')
	{
		$this->session->set_userdata('kode_jab','');
		$this->session->set_userdata('kode_jab',$kode);
		$res = $this->mod_pemetaan->get_data_jabatan($kode);
		echo json_encode($res);
	}

	
	function get_diagram_struktur()
	{
		$data = array(
			'kode'=>$this->session->userdata('kode_jab'),
			'id'=>$this->session->userdata('id_str')
			);
		$this->load->view('diagram_struktur', $data);
	}
	
	function get_parentid()
	{
		return $this->session->userdata('parentid');
	}
	
	function struktur_organisasi()
	{
		$inst = $this->get_instansi_id();
		$data = array('data'=>$inst);
		$this->load->view("struktur_organisasi", $data);
	}

	function get_instansi_id()
	{
		$kode = ($this->session->userdata('kode_instansi')) ? $this->session->userdata('kode_instansi') : '001' ;
		$nama = ($this->session->userdata('nama_instansi')) ? $this->session->userdata('nama_instansi') : 'SETDA';
		$jabatan = '';
		return array(
			'kode_instansi' => $kode,
			'nama_instansi' => $nama,
			'kode_jabatan' => $jabatan
			);
	}
	
	function get_parentid_sotk()
	{
		return $this->session->userdata('id_parent_sotk');
	}

	function set_id_parent_sotk()
	{
		if($this->input->post('id'))
		{
			$this->session->userdata('id_parent_sotk','');
			$this->session->set_userdata('id_parent_sotk',$this->input->post('id'));
		}	
	}

	function set_instansi_id()
	{
		$this->session->userdata('kode_instansi','');
		$this->session->userdata('nama_instansi','');
		if($this->input->post('kode_instansi'))
		{			
			$this->session->set_userdata('kode_instansi', $this->input->post('kode_instansi'));
			$this->session->set_userdata('nama_instansi', $this->input->post('nama_instansi'));
		}
	}

	function get_jabatan()
	{
		$sql = "select id, kode, nama from m_jabatan";
		if($this->input->get('query'))
		{
			$where = " where kode like '%".$this->input->get('query')."%' or nama like '%".$this->input->get('query')."%'";
			$sql .= $where;
		}		
		$res = $this->db->query($sql)->result_array();
		echo json_encode(array("total"=>count($res),"data"=>$res));
	}

	function get_instansi()
	{
		$sql = "select kode, nama, nama_panjang from m_instansi2";
		if($this->input->get('query'))
		{
			$where = " where kode like '%".$this->input->get('query')."%' or nama like '%".$this->input->get('query')."%' or nama_panjang like '%".$this->input->get('query')."%'";
			$sql .= $where;
		}		
		$res = $this->db->query($sql)->result_array();
		echo json_encode(array("total"=>count($res),"data"=>$res));
	}
	
	function hapus_tree()
	{
		if($this->input->post('id')) 
		{
			$this->db->query(sprintf("delete from m_tree where id_tree='%d'", $this->input->post('id')));
		}	
	}
	
	function get_rec($id)
	{
		$dmp=array();
		$lastid=$id;
		while($getid = $this->rec_parentid($id)){
			echo $getid;
			$lastid = $getid;
			$id = $lastid;
			$dmp[] = $lastid;
		}
		//$this->_dump($dmp);
	}
	
	function rec_parentid($id)
	{
		//$ids[] = array();
		$sql = sprintf("select id_tree from m_tree where parent='%d'",$id);
		$res = $this->db->query($sql)->result_array();
		echo "top:", $this->_dump($res);
		if(count($res) > 1)
		{
			$i = count(count($res));
			while($i==0)
			{
				$val = $res[$i]['id_tree'];
				if($this->rec_parentid($val));
				$i--;
			}
		} else if(count($res)==1) return $res[0]['id_tree'];
			else return false;
	}
	
	function tambah_tree()
	{
		if($this->input->post('markup') 
		&& $this->input->post('id_jabatan') // && $this->input->post('id_jenis_diagram')
		)
		{
					
			$data = array_merge($this->input->post(), array('parent'=>$this->get_parentid(), 'id_jenis_diagram'=>1));
			if($this->db->insert('m_tree', $data)) echo "Data berhasil ditambah!";
		}
	}
	
	function set_tree_parent()
	{
		if($this->input->post('id'))
		{
			$this->session->set_userdata('parentid', $this->input->post('id'));		
		}
	}

	function makeRecursive($d, $r = 0, $pk = 'parent', $k = 'id', $c = 'children') {
	  $m = array();
	  foreach ($d as $e) {
	    isset($m[$e[$pk]]) ?: $m[$e[$pk]] = array();
	    isset($m[$e[$k]]) ?: $m[$e[$k]] = array();
	    $m[$e[$pk]][] = array_merge($e, array($c => &$m[$e[$k]]));
	  }
	  return $m[$r][0]; // remove [0] if there could be more than one root nodes
	}

	// http://stackoverflow.com/questions/12285694/creating-a-recursive-category-tree-function
	function nested2ul($data) {
	  $result = array();
	  if (sizeof($data) > 0) {
	    $result[] = '<ul>';
	    foreach ($data as $entry) {
	      $result[] = sprintf(
	        '<li><a href="#">%s</a> %s</li>',
	        $entry['name'],
	        $this->nested2ul($entry['children'])
	      );
	    }
	    $result[] = '</ul>';
	  }
	  return implode($result);
	}


	function get_jabatan_by_kode()
	{		
		if($this->input->get('kode_instansi'))
		{
			$sql = sprintf("select id, kode, kode as kode_jabatan, nama, concat(kode,' | ',nama) as nama_jabatan from m_jabatan where 1");
			if($this->input->get('query'))
			{
				$where = " and kode like '%".$this->input->get('query')."%' or nama like '%".$this->input->get('query')."%'";
				$sql .= $where;
			}					
			$res = $this->db->query($sql)->result_array();
			echo json_encode(array('data'=>$res, 'total'=>count($res)));
		}
	}

	function hapus_pelaksana()
	{
		if($this->input->post('id'))
		{
			$cond = array('id'=>$this->input->post('id'));
			if($this->db->delete('t_pemetaan_jabatan_pelaku', $cond))
				echo "Data berhasil dihapus.";
				else echo "Data GAGAL dihapus!";
		}
	}

	function get_pelaksana()
	{
		$id_pemetaan = $this->get_parentid_sotk();
		$sql = sprintf("
			select 
			t_pemetaan_jabatan_pelaku.*, 
			m_jabatan.kode as kode_jabatan,
			m_jabatan.nama as nama_jabatan
			from t_pemetaan_jabatan_pelaku
			inner join t_pemetaan_jabatan on t_pemetaan_jabatan.id = t_pemetaan_jabatan_pelaku.id_pemetaan
			inner join m_jabatan on m_jabatan.kode = t_pemetaan_jabatan_pelaku.kode_jabatan
		 	where t_pemetaan_jabatan_pelaku.id_pemetaan='%s'", $id_pemetaan);
		if($this->input->get('query'))
		{
			$where = " and m_jabatan.kode like '%".$this->input->get('query')."%' or m_jabatan.nama like '%".$this->input->get('query')."%'";
			$sql .= $where;
		}		
		$res = $this->db->query($sql)->result_array();
		echo json_encode(array('data'=>$res, 'total'=>count($res)));
	}

	function clear_parent_id()
	{
		$this->session->set_userdata('id_parent_sotk','');
	}

	function simpan_pelaksana()
	{
		$instansi = $this->get_instansi_id();
		$id_pemetaan = $this->get_parentid_sotk();
		if($this->input->post('kode_jabatan'))
		{
			$cond = array('id'=>$this->input->post('id'));
			$data = array_merge($this->input->post(), array('kode_jabatan'=>$this->input->post('kode_jabatan')), array('id_pemetaan'=>$id_pemetaan,'kode_instansi'=>$instansi['kode_instansi']));
			if($this->cek_is_ada('t_pemetaan_jabatan_pelaku', $cond))
			{
				if($this->db->update('t_pemetaan_jabatan_pelaku', $data, $cond)) echo "Data berhasil diupdate.";
					else echo "Data GAGAL diupdate.";
			} else
			{
				if($this->db->insert('t_pemetaan_jabatan_pelaku', $data)) echo "Data berhasil ditambah.";
					else echo "Data GAGAL ditambah.";
			}
		} else echo "Silahkan Masukan data!";
	}

	function hapus_struktur_org()
	{
		if($this->input->post('id'))
		{
			$cond = array('id'=>$this->input->post('id'));
			if($this->db->delete('t_pemetaan_jabatan',$cond)) echo "Data berhasil dihapus.";
				else echo "Data GAGAL dihapus.";
		}
	}

	function save_sotk()
	{
		$kj = $this->get_instansi_id();
		$pid = $this->input->post('id');
		if($this->input->post('id'))
		{
			$idParent = $this->db->query(sprintf("select parent_id from t_pemetaan_jabatan where id='%d'", $pid))->row_array();
			$parent_id = array('parent_id'=>$idParent['parent_id']);
			$post_array = array_merge($this->input->post(), $parent_id, array('kode_instansi'=>$kj['kode_instansi']));
		}
		$cond = array('id'=>$pid);
		if($this->cek_is_ada('t_pemetaan_jabatan', $cond))
		{
			if($this->db->update('t_pemetaan_jabatan', $post_array,$cond))
			{
				echo json_encode(array('success'=>true, 'message'=>'Data berhasil disimpan.'));
			} else echo json_encode(array('success'=>false, 'message'=>'Data GAGAL disimpan.'));			
		} else
		{
			$arr_input = array_merge($this->input->post(), array('kode_instansi'=>$kj['kode_instansi']));
			if($this->db->insert('t_pemetaan_jabatan', $this->input->post()))
			{
				echo json_encode(array('success'=>true, 'message'=>'Data berhasil disimpan.'));
			} else echo json_encode(array('success'=>false, 'message'=>'Data GAGAL disimpan.'));			
		}
	}

	function diagram_sotk()
	{
		$sj = $this->get_instansi_id();		
		$sql = sprintf("select id, nama_jabatan as name, parent_id as parent from t_pemetaan_jabatan where kode_instansi='%s'", $sj['kode_instansi']);
		$res = $this->db->query($sql)->result_array();
		if(count($res))
		{
			$data = $this->nested2ul(array($this->makeRecursive($res)));
			//return $data;
			$this->load->view('diagram_sotk', array('data' => $data));			
		} else echo "-- Data masih kosong --";
	}

	function get_tahun_rekap()
	{
		$sql = "select distinct(tahun_anjab) as tahun from t_pemetaan_jabatan_pelaku";
		$res = $this->db->query($sql)->result_array();
		echo json_encode(array('total'=>count($res),'data'=>$res));
	}

	function get_rekap()
	{
		$sj = $this->get_instansi_id();
		$tahun = ($this->input->get('tahun')) ? $this->input->get('tahun') : date('Y');
		$sql = sprintf("
			select 
				sum(t_pemetaan_jabatan_pelaku.jml_tersedia) as jml,
				tahun_anjab as tahun,
				m_jenis_jabatan.nama_panjang as jenis_jabatan
			from
				t_pemetaan_jabatan_pelaku
				left join m_jabatan on m_jabatan.kode = t_pemetaan_jabatan_pelaku.kode_jabatan
				left join m_jenis_jabatan on m_jenis_jabatan.id = m_jabatan.id_jenis_jabatan
			where
				t_pemetaan_jabatan_pelaku.kode_instansi = '%s'
				and t_pemetaan_jabatan_pelaku.tahun_anjab = '%s'
			group by
				m_jabatan.id_jenis_jabatan, t_pemetaan_jabatan_pelaku.tahun_anjab
		", $sj['kode_instansi'], $tahun);
		$res = $this->db->query($sql)->result_array();
		echo json_encode(array('total', 'data'=>$res));
	}

	function get_data_sotk()
	{
		$parid = $this->input->get('node');
		$parid = ($parid == 'root') ? 0 : $parid;
		$parent_id = isset($parid) ? $parid : 0;
		$res = $this->mod_pemetaan->get_data_sotk($this->session->userdata('kode_instansi'), $parent_id);
		$tree = array('text'=>'.', 'children'=>$res);
		echo json_encode($tree);		
	}

	function update_parent_kode()
	{
		$sql = "select kode from m_jabatan where trim(kode) <> ''";
		$res = $this->db->query($sql)->result_array();
		foreach($res as $v)
		{
			$s = explode('.',$v['kode']);
			for($a=0;$a<count($s);$a++){ $sa[$a] = (int) $s[$a];}
			$ab = implode('.',$sa);
			$this->db->query(sprintf("update m_jabatan set koint='%s' where kode='%s'", $ab,$v['kode']));
			array_pop($s);
			for($a=0;$a<count($s);$a++){ $ss[$a] = (int) $s[$a];}
			$parent = implode('.',$ss);
			$this->db->query(sprintf("update m_jabatan set parent='%s' where kode='%s'", $parent,$v['kode']));
		}
		$q2 = "
			update m_jabatan mj 
			left join m_jabatan ma
			on mj.parent = ma.koint
			set mj.parentid = ma.id";
		$this->db->query($q2);
		$this->db->query("truncate table m_tree_struktur");
		$q3 = "
			insert into m_tree_struktur(id_jenis_diagram, id_tree, parent, markup, id_jabatan)
			select 1, id, parentid, nama, id_jabatan
			from m_jabatan
			where m_jabatan.parentid is not null		
		";
		$this->db->query($q3);
	}
	
	function form_tree()
	{
		$m_jabatan = "select id, concat(kode,' :: ' ,nama) as nama_jabatan from m_jabatan";
		$m_jab = $this->db->query($m_jabatan)->result_array();
		$cmb_jabatan = $this->combo($m_jab);
		$q_jj = "select * from m_tree_jenis";
		$m_jj = $this->db->query($q_jj)->result_array();
		$cmb_jj = $this->combo($m_jj);
		$data = array(
			'm_jabatan' => $cmb_jabatan,
			'm_jj' => $cmb_jj,
			'parentid' => $this->session->userdata('parentid')
		);
		$this->load->view('form_tree', $data);
	}
	
	function cek_is_ada($table, $where)
	{
		$this->db->where($where);
		$this->db->from($table);
		$c = $this->db->count_all_results();
		if($c>0) return true;
			else return false;
	}

	function combo($data)
	{
		$i = 0;
		foreach($data as $d)
		{
			$k = array_keys($d);
			$v = array_values($d);
			$cmb[$v[0]] = $v[1];
			$i++;
		}
		if(is_array($cmb)) return $cmb;
	}
	
	function _dump($s)
	{
		print('<pre>');
		print_r($s);
		print('</pre>');
	}
	
}

?>