<?php

class Isian_Jabatan extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('mod_pemetaan');
        $this->load->model('model_jabatan');		
    }

    public function index() {
        //$this->load->view('main');
    }
	
	function tata_cara_pengisian()
	{
		echo "<h1>Tata Cara Pengisian Formulir Isian Jabatan</h1><p>Pilih jabatan terlebih dahulu yang ada di tabel sebelah kiri.</p><p>Silahkan pilih menu Tab yang ada di sebelah kiri untuk memilih masing-masing poin isian. Scoll ke atas atau ke bawah untuk memilih poin isian yang dikehendaki.<br\>";
		echo "&nbsp;Jangan lupa untuk menyimpan data dengan menekan tombol 'Simpan' atau 'Save' setelah isian untuk masing-masing poin terisi semua.</p>";
		echo sprintf("<p>Contoh Pengisisan Uraian Tugas dengan format penulisan poin utama diawali alphabet karakter hurup besar (A,B,C,D dst), dan poin dibawahnya dengan hurup kecil (a,b,c,d,e dst), gambar:
			<div align='center'><img src='%s'></div>
		</p>", base_url().'assets/images/frmuraiantugas.jpg');
		echo sprintf("<p>Untuk jenis isian grid dengan item yang bisa diedit pada grid tersebut, untuk menambahkan item baru klik tombol 
			'Tambah' kemudian isi di isian grid, setelah selesai klik tombol 'Update'. Untuk mengedit item yang sudah dientri, dobel klik pada item grid yang diinginkan,
			gunakan tab untuk berpindah isian, setelah selesai tekan 'Enter' pada keyboard. Lihat gambar di bawah ini:
			<div align='center'><img src='%s'><br /><img src='%s'></div>
		</p>", base_url().'assets/images/frmbahankerja.jpg',  base_url().'assets/images/frmbahankerja-entri.jpg');
		echo sprintf("<p>Untuk menghapus item, silahkan pilih / ceklis item pada grid kemudian tekan tombol 'Delete / Hapus', pilih 'Yes' pada konfirmasi penghapusan data, lihat gambar :
			<div align='center'><img src='%s'></div>
		</p>", base_url().'assets/images/hapusitem.jpg');		
	}

	function get_posisi()
	{
		if($this->input->post('id'))
		{
			$res = $this->model_jabatan->get_data_sotk_id($this->input->post('id'));
			echo $res['posisi_jabatan'];		
		}
	}

	function get_data_sotk()
	{
		$parid = $this->input->get('node');
		$parid = ($parid == 'root') ? 0 : $parid;
		$parent_id = isset($parid) ? $parid : 0;
		$res = $this->model_jabatan->get_data_sotk($this->session->userdata('kode_jabatan_isian'),$this->session->userdata('kode_instansi'), $parent_id);
		$tree = array('text'=>'.', 'children'=>$res);
		echo json_encode($tree);		
	}
	
	function save_sotk()
	{
		$kj = $this->get_id_jabatan();
		$pid = $this->input->post('id');
		$posjab = ($this->input->post('posisi_jabatan')=='on') ? 1 : 0;
		if($this->input->post('id'))
		{
			$idParent = $this->db->query(sprintf("select parent_id from frm_isian_1_5_struktur where id='%d'", $pid))->row_array();
			$parent_id = array('parent_id'=>$idParent['parent_id']);
			$post_array = array_merge($this->input->post(), $parent_id, array('posisi_jabatan'=>$posjab), array('kode_instansi'=>$kj['kode_instansi']));
		}
		$cond = array('kode_instansi'=>$kj['kode_instansi'],'kode_jabatan'=>$kj['kode'],'id'=>$pid);
		if($this->cek_is_ada('frm_isian_1_5_struktur', $cond))
		{
			if($this->db->update('frm_isian_1_5_struktur', $post_array,$cond))
			{
				echo json_encode(array('success'=>true, 'message'=>'Data berhasil disimpan.'));
			} else echo json_encode(array('success'=>false, 'message'=>'Data GAGAL disimpan.'));			
		} else
		{
			$arr_input = array_merge($this->input->post(), array('kode_instansi'=>$kj['kode_instansi']));
			if($this->db->insert('frm_isian_1_5_struktur', array_merge($this->input->post(),array('posisi_jabatan'=>$posjab))))
			{
				echo json_encode(array('success'=>true, 'message'=>'Data berhasil disimpan.'));
			} else echo json_encode(array('success'=>false, 'message'=>'Data GAGAL disimpan.'));			
		}
		/*		
		if($this->db->insert('frm_isian_1_5_struktur', $this->input->post())){
			echo json_encode(array("success"=>true, "message"=>"Data sudah disimpan"));
		} else echo json_encode(array("success"=>false, "message"=>"Data GAGAL disimpan"));	
		*/
	}
	
	function get_parentid_sotk()
	{
		return $this->userdata('id_tree_sotk');
	}
	
	function set_id_parent_sotk()
	{
		$this->session->userdata('id_tree_sotk', '');
		if($this->input->post('id'))
		{
			$this->session->set_userdata('id_tree_sotk', $this->input->post('id'));
		}	
	}
	
	function point_1_4()
	{
		$data = $this->get_id_jabatan();
		$res = $this->model_jabatan->get_data_1_5($this->session->userdata('kode_jabatan_isian'),$this->session->userdata('kode_instansi'));
		$this->load->view("frm_isian_1_4", array("data"=>$data, 'frm'=>$res));
	}

	function poin_6_uraian_tugas()
	{
		$data = $this->get_id_jabatan();		
		$this->load->view("frm_isian_6_uraian_tugas", array("data"=>$data));
	}

	function get_tree_parentid()
	{
		return $this->userdata('treeparentid');
	}

	function set_id_parent_tree()
	{
		$this->session->userdata('treeparentid', '');
		if($this->input->post('id'))
		{
			$this->session->set_userdata('treeparentid', $this->input->post('id'));
		}
	}

	function get_data_uraian_tugas_byid()
	{
		if($this->input->post('id'))
		{
			$query = sprintf("select * from  frm_isian_6_uraian_tugas where id='%d'",$this->input->post('id'));
			$res = $this->db->query($query)->row_array();
			if(count($res)) return $res;
				else return false;
		}
	}
	
	function get_data_uraian_tugas()
	{
		$kj = $this->get_id_jabatan();		
		$parid = $this->input->get('node');
		$parid = ($parid == 'root') ? 0 : $parid;
		$parent_id = isset($parid) ? $parid : 0;
		$query = sprintf("
			select 
					id, kode_jabatan, parent,
					uraian_tugas as text, 
					parent as parent_id 
				from 
					frm_isian_6_uraian_tugas 
				where 
					parent='%d' and kode_jabatan='%s'
				order by uraian_tugas asc
				",
			$parent_id, $kj['kode']);
		//echo $query;
		$res = $this->db->query($query)->result_array();		
		$tree = array('text'=>'.', 'children'=>$res);
		echo json_encode($tree);
	}
	
	function hapus_uraian_tugas()
	{
		if($this->input->post('id')) {
			if($this->db->delete('frm_isian_6_uraian_tugas', array('id'=>$this->input->post('id'))))
			{
				echo "Data berhasil dihapus";
			} else echo "Data GAGAL dihapus";
		}		
	}
	
	function hapus_struktur_org()
	{
		if($this->input->post('id')) {
			if($this->db->delete('frm_isian_1_5_struktur', array('id'=>$this->input->post('id'))))
			{
				echo "Data berhasil dihapus";
			} else echo "Data GAGAL dihapus";
		}		
	}
	
	function save_uraian_tugas()
	{
		$kj = $this->get_id_jabatan();
		$pid = $this->input->post('id');
		$p = $this->input->post('parent');
		$id = isset($pid) ?  $pid : "";
		$cond = array('id'=>$id);
		if($this->input->post('id'))
		{
			$idParent = $this->db->query(sprintf("select parent from frm_isian_6_uraian_tugas where id='%d'", $pid))->row_array();
			$parent_id = array('parent'=>$idParent['parent']);
			$post_array = array_merge($this->input->post(), $parent_id, array('kode_instansi'=>$kj['kode_instansi']));
		}
		if($this->cek_is_ada('frm_isian_6_uraian_tugas', $cond))
		{
			if($this->db->update('frm_isian_6_uraian_tugas', $post_array,$cond))
			{
				echo json_encode(array('success'=>true, 'message'=>'Data berhasil disimpan.'));
			} else echo json_encode(array('success'=>false, 'message'=>'Data GAGAL disimpan.'));			
		} else
		{
			$arr_input = array_merge($this->input->post(), array('kode_instansi'=>$kj['kode_instansi']));
			if($this->db->insert('frm_isian_6_uraian_tugas', $arr_input))
			{
				echo json_encode(array('success'=>true, 'message'=>'Data berhasil disimpan.'));
			} else echo json_encode(array('success'=>false, 'message'=>'Data GAGAL disimpan.'));			
		}
	}

	function simpan_sj_jenjang()
	{
		$ret = false;
		$sj = $this->get_id_jabatan();
		if($this->input->post('penjenjangan'))
		{		
			$where = array("id"=>$this->input->post('id'));
			$arr_data = array_merge($this->input->post(), array("kode_instansi" => $sj["kode_instansi"]));
			if($this->cek_is_ada('frm_sj_c_penjenjangan',$where))
			{
				if($this->db->update('frm_sj_c_penjenjangan',$arr_data,$where))
				{
					$ret = true;
				} else $ret= false;
			} else 
			if($this->db->insert('frm_sj_c_penjenjangan',$arr_data))
			{
				$ret = true;
			} else $ret = false;
		}
		if($ret) echo json_encode(array("success"=>true, "message"=>"Data BERHASIL disimpan."));
			else echo json_encode(array("success"=>true, "message"=>"Data GAGAL disimpan."));
	}
	
	function poin_7_bahan_kerja()
	{
		$data = $this->get_id_jabatan();		
		$this->load->view("frm_isian_7_bahan_kerja", array("data"=>$data));
	}

	function poin_8_perangkat_alat_kerja()
	{
		$data = $this->get_id_jabatan();		
		$this->load->view("frm_isian_8_perangkat_alat_kerja", array("data"=>$data));
	}
	
	function poin_9_hasil_kerja()
	{
		$data = $this->get_id_jabatan();		
		$this->load->view("frm_isian_9_hasil_kerja", array("data"=>$data));
	}

	function poin_10_tanggungjawab()
	{
		$data = $this->get_id_jabatan();		
		$this->load->view("frm_isian_10_tanggungjawab", array("data"=>$data));
	}

	function poin_11_wewenang()
	{
		$data = $this->get_id_jabatan();		
		$this->load->view("frm_isian_11_wewenang", array("data"=>$data));
	}

	function poin_12_korelasi_jabatan()
	{
		$data = $this->get_id_jabatan();		
		$this->load->view("frm_isian_12_korelasi_jabatan", array("data"=>$data));
	}

	function poin_13_kondisi_lingkungan_kerja()
	{
		$data = $this->get_id_jabatan();		
		$this->load->view("frm_isian_13_kondisi_lingkungan_kerja", array("data"=>$data));
	}

	function poin_14_resiko_bahaya()
	{
		$data = $this->get_id_jabatan();		
		$this->load->view("frm_isian_14_resiko_bahaya", array("data"=>$data));
	}

	function poin_15_syarat_jabatan()
	{
		$data = $this->get_id_jabatan();
		$pangkat = $this->db->query(sprintf('select  * from frm_sj_a_pangkatgol where kode_instansi="%s" and kode_jabatan="%s"', $data['kode_instansi'], $data['kode']))->row_array();
		$pengker = $this->db->query(sprintf('select  * from frm_sj_d_pengalaman where kode_instansi="%s" and kode_jabatan="%s"', $data['kode_instansi'], $data['kode']))->row_array();
		$fisik = $this->db->query(sprintf('select  * from frm_sj_k_kondisi_fisik where kode_instansi="%s" and kode_jabatan="%s"', $data['kode_instansi'], $data['kode']))->row_array();
		$data_fisik = array(
			'id' => isset($fisik['id']) ? $fisik['id'] : '',
			'jenis_kelamin' => isset($fisik['jenis_kelamin']) ? $fisik['jenis_kelamin'] : 1,
			'umur' => isset($fisik['umur']) ? $fisik['umur'] : '',
			'berat_badan' => isset($fisik['berat_badan']) ? $fisik['berat_badan'] : '',
			'postur_badan' => isset($fisik['postur_badan']) ? $fisik['postur_badan'] : '',
			'tinggi_badan' => isset($fisik['tinggi_badan']) ? $fisik['tinggi_badan'] : '',
			'penampilan' => isset($fisik['penampilan']) ? $fisik['penampilan'] : ''
			);
		$dp = array(
			'id' => isset($pengker['id']) ? $pengker['id'] : '',
			'pengalaman' => isset($pengker['pengalaman']) ? $pengker['pengalaman'] : ''
			);
		$dgol = array(
			'id'=>isset($pangkat['id']) ? $pangkat['id'] : '',
			'pangkat'=>isset($pangkat['pangkat']) ? $pangkat['pangkat'] : '',
			'golongan'=>isset($pangkat['golongan']) ? $pangkat['golongan'] : ''
			); 
		$this->load->view("frm_isian_15_syarat_jabatan", array("data"=>$data,'dt'=>$dgol,'pengker'=>$dp,'df'=>$data_fisik));
	}
	
	function poin_16_standar_prestasi_kerja()
	{
		$data = $this->get_id_jabatan();		
		$this->load->view("frm_isian_16_standar_prestasi_kerja", array("data"=>$data));
	}

	function poin_17_informasi_lain()
	{
		$data = $this->get_id_jabatan();		
		$query = sprintf("select * from frm_isian_17_informasi_lain where kode_jabatan = '%s'", $data['kode']);
		$res = $this->db->query($query)->row_array();
		$il = isset($res['informasi_lain']) ? $res['informasi_lain'] : '';
		$out = array("data"=>$data, 'info_lain'=>$il);
		$this->load->view("frm_isian_17_informasi_lain", $out);
	}

	function syarat_jabatan($id)
	{
		$data = $this->get_id_jabatan();		
		switch($id)
		{
			case 1: echo "Form isian Pangkat"; break;
			case 2: echo "Pendidikan"; break;
			case 3: echo "Kursus / Diklat"; break;
			case 4: echo "Pengalaman Kerja"; break;
			case 5: echo "Pengetahuan Kerja"; break;
			case 6: echo "Keterampilan Kerja"; break;
			case 7: echo "Bakat Kerja"; break;
			case 8: echo "Temperamen Kerja"; break;
			case 9: echo "Minat Kerja"; break;
			case 10: echo "Upaya fisik"; break;
			case 11: echo "Kondisi fisik"; break;
			case 12: echo "Fungsi Pekerjaan"; break;			
		}
		//echo "Isian formulir syarat Jabatan";
		//$this->load->view("frm_isian_17_informasi_lain", array("data"=>$data));
	}
	
	function _unset_id()
	{
		$this->session->set_userdata('id_jabatan_isian', '');
		$this->session->set_userdata('kode_jabatan_isian', '');
		$this->session->set_userdata('nama_jabatan_isian', '');			
		$this->session->set_userdata('kode_instansi', '');			
		$this->session->set_userdata('nama_instansi', '');			
	}
	
	function set_id_jabatan()
	{
		if($this->input->post('id'))
		{
			$this->_unset_id();			
			$kd = explode('.', $this->input->post('kode'));
			$res = $this->_get_kode_jabatan($kd[0]);	
			/*
			/^[1-9][0-9]*$/
			if (!preg_match('/^[0-9]*$/', $id)) {				
			*/
			$this->session->set_userdata('id_jabatan_isian', $this->input->post('id'));
			$this->session->set_userdata('kode_jabatan_isian', $this->input->post('kode'));
			$this->session->set_userdata('nama_jabatan_isian', $this->input->post('nama'));
			if(isset($res['kode']))
			{
				$this->session->set_userdata('kode_instansi', $res['kode']);
				$this->session->set_userdata('nama_instansi', $res['instansi']);
				echo json_encode(array('success'=>true,'msg'=>'ok','kode_instansi'=>$res['kode'], 'nama_instansi'=>$res['instansi']));				
			} else echo json_encode(array('success'=>true,'msg'=>'pilih_instansi')); 
		}
	}

	function pilih_instansi()
	{
		$this->session->userdata('kode_instansi', '');
		$this->session->userdata('nama_instansi', '');
		if($this->input->post('kode_instansi'))
		{
			// oke
			$this->session->set_userdata('kode_instansi', $this->input->post('kode_instansi'));
			$this->session->set_userdata('nama_instansi', $this->input->post('nama_instansi'));
			echo "Kode instansi telah ditentukan. Kode: ",$this->input->post('kode_instansi'), " - ",$this->input->post('nama_instansi');
		}		
	}

	function set_id_jabatan_non_struk()
	{
		if($this->input->post('id'))
		{
			$this->_unset_id();			
			$this->session->set_userdata('id_jabatan_isian', $this->input->post('id'));
			$this->session->set_userdata('kode_jabatan_isian', $this->input->post('kode'));
			$this->session->set_userdata('nama_jabatan_isian', $this->input->post('nama'));
			echo "ok";
		}
	}
	
	function _get_kode_jabatan($kode)
	{
		$instansi = sprintf("select kode, concat(kode,' | ',nama,' - ',nama_panjang) as instansi from m_instansi2 where kode='%s'", $kode);
		$res = $this->db->query($instansi)->row_array();
		return $res;
	}
	
	function get_id_jabatan()
	{
		$kd = explode('.',$this->session->userdata('kode_jabatan_isian'));
		$res = $this->_get_kode_jabatan($kd[0]);
		if(isset($res[0])) {
			$nmi = $res['instansi'];
			$kdi = $kd[0];
		} else
		{
			$nmi = $this->session->userdata('nama_instansi');
			$kdi = $this->session->userdata('kode_instansi');
		}
		return array(
			'id' => $this->session->userdata('id_jabatan_isian'),
			'kode' => $this->session->userdata('kode_jabatan_isian'),
			'nama' => $this->session->userdata('nama_jabatan_isian'),
			'nama_instansi' => $nmi,
			'kode_instansi' => $kdi
		);
	}
	
	function get_pangkat()
	{
		$query = "select * from m_pangkat";
		$rs = $this->db->query($query);
		$data = $rs->result_array();
		echo json_encode(array('total'=>count($data), 'data'=>$data));
	}
	
	function simpan_sdm()
	{
		if($this->input->post('kode_instansi') &&
		$this->input->post('nip') &&
		$this->input->post('nama') &&
		$this->input->post('pangkat') &&
		$this->input->post('golongan') &&
		$this->input->post('pendidikan') &&
		$this->input->post('id_jabatan') 		
		)
		{
			if($this->db->insert('m_rekap_sdm', $this->input->post()))
			{
				echo json_encode(array('success' => true, 'message' => 'Data BERHASIL disimpan.'));	
			} else echo json_encode(array('success' => true, 'message' => 'Data GAGAL disimpan!.'));
		}
	}

	# syarat jabatan pendidikan
	function sj_pendidikan()
	{
		$data = $this->get_id_jabatan();		
		$this->load->view("frm_sj_pendidikan", array("data"=>$data));
	}
	
	function get_sj_pendidikan()
	{
		$sj = $this->get_id_jabatan();
		$query = sprintf("select * from frm_sj_b_pendidikan where kode_jabatan='%s' and kode_instansi='%s'", $sj['kode'], $sj['kode_instansi']);
		if($this->input->get('query'))
		{
			$where = " and pendidikan like '%".$this->input->get('query')."%'";
			$query .= $where;
		}		
		$rs = $this->db->query($query);
		$data = $rs->result_array();
		echo json_encode(array('total'=>count($data), 'data'=>$data));
	}

	function simpan_sj_kondisifisik()
	{
		$ret = false;
		$sj = $this->get_id_jabatan();
		if($this->input->post('jenis_kelamin') || $this->input->post('umur'))
		{		
			$where = array("id"=>$this->input->post('id'), "kode_jabatan"=>$this->input->post('kode_jabatan'));
			$arr_data = array_merge($this->input->post(), array("kode_instansi" => $sj["kode_instansi"]));
			if($this->cek_is_ada('frm_sj_k_kondisi_fisik',$where))
			{
				if($this->db->update('frm_sj_k_kondisi_fisik',$arr_data,$where))
				{
					$ret = true;
				} else $ret= false;
			} else 
			if($this->db->insert('frm_sj_k_kondisi_fisik',$arr_data))
			{
				$ret = true;
			} else $ret = false;
		}
		if($ret) echo json_encode(array('success'=>true, 'message'=>'Data BERHASIL disimpan.'));
			else echo  json_encode(array('success'=>true, 'message'=>'Data GAGAL disimpan.'));
	}	

	function simpan_sj_pendidikan()
	{
		$ret = false;
		$sj = $this->get_id_jabatan();
		if($this->input->post('pendidikan'))
		{		
			$where = array("id"=>$this->input->post('id'), "kode_jabatan"=>$this->input->post('kode_jabatan'));
			$arr_data = array_merge($this->input->post(), array("kode_instansi" => $sj["kode_instansi"]));
			if($this->cek_is_ada('frm_sj_b_pendidikan',$where))
			{
				if($this->db->update('frm_sj_b_pendidikan',$arr_data,$where))
				{
					$ret = true;
				} else $ret= false;
			} else 
			if($this->db->insert('frm_sj_b_pendidikan',$arr_data))
			{
				$ret = true;
			} else $ret = false;
		}
		if($ret) echo "Data BERHASIL disimpan.";
			else echo "Data GAGAL disimpan.";
	}

	function hapus_sj_pendidikan()
	{
		if($this->input->post('id'))
		{
			if($this->db->delete('frm_sj_b_pendidikan',sprintf('id in(%s)', $this->input->post('id'))))
			{
				echo "Data berhasil dihapus.";
			} else echo "Data GAGAL dihapus.";
		}
	}

	# syarat jabatan pendidikan
	function sj_kursus_diklat()
	{
		$sj = $this->get_id_jabatan();		
		$res = $this->db->query(sprintf("select * from frm_sj_c_penjenjangan where kode_jabatan='%s' and kode_instansi='%s'", $sj['kode'], $sj['kode_instansi']))->row_array();
		$penj = array(
			'id'=> isset($res['id']) ? $res['id'] : '',
			'penjenjangan' => isset($res['penjenjangan']) ? $res['penjenjangan'] : ''
			);
		$this->load->view("frm_sj_kursus_diklat", array("data"=>$sj,"dt"=>$penj));
	}

	function get_sj_diklat()
	{
		$sj = $this->get_id_jabatan();
		$query = sprintf("select * from frm_sj_c_kurdik where kode_jabatan='%s' and kode_instansi='%s'", $sj['kode'], $sj['kode_instansi']);
		if($this->input->get('query'))
		{
			$where = " and teknis like '%".$this->input->get('query')."%'";
			$query .= $where;
		}		
		$rs = $this->db->query($query);
		$data = $rs->result_array();
		echo json_encode(array('total'=>count($data), 'data'=>$data));
	}

	function simpan_sj_diklat()
	{
		$ret = false;
		$sj = $this->get_id_jabatan();
		if($this->input->post('teknis'))
		{		
			$where = array("id"=>$this->input->post('id'), "kode_jabatan"=>$this->input->post('kode_jabatan'));
			$arr_data = array_merge($this->input->post(), array("kode_instansi" => $sj["kode_instansi"]));
			if($this->cek_is_ada('frm_sj_c_kurdik',$where))
			{
				if($this->db->update('frm_sj_c_kurdik',$arr_data,$where))
				{
					$ret = true;
				} else $ret= false;
			} else 
			if($this->db->insert('frm_sj_c_kurdik',$arr_data))
			{
				$ret = true;
			} else $ret = false;
		}
		if($ret) echo "Data BERHASIL disimpan.";
			else echo "Data GAGAL disimpan.";
	}
	
	function hapus_sj_diklat()
	{
		if($this->input->post('id'))
		{
			if($this->db->delete('frm_sj_c_kurdik',sprintf('id in(%s)', $this->input->post('id'))))
			{
				echo "Data berhasil dihapus.";
			} else echo "Data GAGAL dihapus.";
		}
	}
	
	function simpan_sj_kursus()
	{
		$ret = false;
		$sj = $this->get_id_jabatan();
		if($this->input->post('penjenjangan'))
		{		
			$where = array("id"=>$this->input->post('id'), "kode_jabatan"=>$this->input->post('kode_jabatan'));
			$arr_data = array_merge($this->input->post(), array("kode_instansi" => $sj["kode_instansi"]));
			if($this->cek_is_ada('frm_sj_c_kursus',$where))
			{
				if($this->db->update('frm_sj_c_kursus',$arr_data,$where))
				{
					$ret = true;
				} else $ret= false;
			} else 
			if($this->db->insert('frm_sj_c_kursus',$arr_data))
			{
				$ret = true;
			} else $ret = false;
		}
		if($ret) echo json_encode(array("success"=>true,"message"=>"Data BERHASIL disimpan"));
			else echo json_encode(array("success"=>true,"message"=>"Data GAGAL disimpan"));
	}

	function simpan_sj_pengker()
	{
		$ret = false;
		$sj = $this->get_id_jabatan();
		if($this->input->post('pengalaman'))
		{		
			$where = array("id"=>$this->input->post('id'));
			$arr_data = array_merge($this->input->post(), array("kode_instansi" => $sj["kode_instansi"]));
			if($this->cek_is_ada('frm_sj_d_pengalaman',$where))
			{
				if($this->db->update('frm_sj_d_pengalaman',$arr_data,$where))
				{
					$ret = true;
				} else $ret= false;
			} else 
			if($this->db->insert('frm_sj_d_pengalaman',$arr_data))
			{
				$ret = true;
			} else $ret = false;
		}
		if($ret) echo json_encode(array("success"=>true,"message"=>"Data BERHASIL disimpan"));
			else echo json_encode(array("success"=>true,"message"=>"Data GAGAL disimpan"));
	}
	
	function simpan_sj_pangkat_golongan()
	{
		$ret = false;
		$sj = $this->get_id_jabatan();
		if($this->input->post('pangkat') && $this->input->post('golongan'))
		{		
			$where = array("id"=>$this->input->post('id'), "kode_jabatan"=>$this->input->post('kode_jabatan'));
			$arr_data = array_merge($this->input->post(), array("kode_instansi" => $sj["kode_instansi"]));
			if($this->cek_is_ada('frm_sj_a_pangkatgol',$where))
			{
				if($this->db->update('frm_sj_a_pangkatgol',$arr_data,$where))
				{
					$ret = true;
				} else $ret= false;
			} else 
			if($this->db->insert('frm_sj_a_pangkatgol',$arr_data))
			{
				$ret = true;
			} else $ret = false;
		}
		if($ret) echo json_encode(array("success"=>true,"message"=>"Data BERHASIL disimpan"));
			else echo json_encode(array("success"=>true,"message"=>"Data GAGAL disimpan"));
	}
	
	function sj_pengetahuan()
	{
		$data = $this->get_id_jabatan();		
		$this->load->view("frm_sj_pengetahuan", array("data"=>$data));
	}
	
	function get_sj_pengetahuan()
	{
		$sj = $this->get_id_jabatan();
		$query = sprintf("select * from frm_sj_e_pengetahuan where kode_jabatan='%s' and kode_instansi='%s'", $sj['kode'], $sj['kode_instansi']);
		if($this->input->get('query'))
		{
			$where = " and pengetahuan_kerja like '%".$this->input->get('query')."%'";
			$query .= $where;
		}		
		$rs = $this->db->query($query);
		$data = $rs->result_array();
		echo json_encode(array('total'=>count($data), 'data'=>$data));	
	}

	function simpan_sj_pengetahuan()
	{
		$ret = false;
		$sj = $this->get_id_jabatan();
		if($this->input->post('pengetahuan_kerja'))
		{		
			$where = array("id"=>$this->input->post('id'), "kode_jabatan"=>$this->input->post('kode_jabatan'));
			$arr_data = array_merge($this->input->post(), array("kode_instansi" => $sj["kode_instansi"]));
			if($this->cek_is_ada('frm_sj_e_pengetahuan',$where))
			{
				if($this->db->update('frm_sj_e_pengetahuan',$arr_data,$where))
				{
					$ret = true;
				} else $ret= false;
			} else 
			if($this->db->insert('frm_sj_e_pengetahuan',$arr_data))
			{
				$ret = true;
			} else $ret = false;
		}
		if($ret) echo "Data BERHASIL disimpan";
			else echo "Data GAGAL disimpan";
	}

	function hapus_sj_pengetahuan()
	{
		if($this->input->post('id'))
		{
			if($this->db->delete('frm_sj_e_pengetahuan',sprintf('id in(%s)', $this->input->post('id'))))
			{
				echo "Data berhasil dihapus.";
			} else echo "Data GAGAL dihapus.";
		}
	}
	
	// keterampilan kerja
	function sj_keterampilan()
	{
		$data = $this->get_id_jabatan();		
		$this->load->view("frm_sj_keterampilan", array("data"=>$data));
	}
	
	function get_sj_keterampilan()
	{
		$sj = $this->get_id_jabatan();
		$query = sprintf("select * from frm_sj_f_keterampilan where kode_jabatan='%s' and kode_instansi='%s'", $sj['kode'], $sj['kode_instansi']);
		if($this->input->get('query'))
		{
			$where = " and keterampilan like '%".$this->input->get('query')."%'";
			$query .= $where;
		}		
		$rs = $this->db->query($query);
		$data = $rs->result_array();
		echo json_encode(array('total'=>count($data), 'data'=>$data));	
	}

	function simpan_sj_keterampilan()
	{
		$ret = false;
		$sj = $this->get_id_jabatan();
		if($this->input->post('keterampilan'))
		{		
			$where = array("id"=>$this->input->post('id'), "kode_jabatan"=>$this->input->post('kode_jabatan'));
			$arr_data = array_merge($this->input->post(), array("kode_instansi" => $sj["kode_instansi"]));
			if($this->cek_is_ada('frm_sj_f_keterampilan',$where))
			{
				if($this->db->update('frm_sj_f_keterampilan',$arr_data,$where))
				{
					$ret = true;
				} else $ret= false;
			} else 
			if($this->db->insert('frm_sj_f_keterampilan',$arr_data))
			{
				$ret = true;
			} else $ret = false;
		}
		if($ret) echo "Data BERHASIL disimpan";
			else echo "Data GAGAL disimpan";
	}

	function hapus_sj_keterampilan()
	{
		if($this->input->post('id'))
		{
			if($this->db->delete('frm_sj_f_keterampilan',sprintf('id in(%s)', $this->input->post('id'))))
			{
				echo "Data berhasil dihapus.";
			} else echo "Data GAGAL dihapus.";
		}
	}

	// bakat kerja
	function sj_bakat_kerja()
	{
		$data = $this->get_id_jabatan();		
		$this->load->view("frm_sj_bakat_kerja", array("data"=>$data));
	}
	
	function get_sj_bakat_kerja()
	{
		$sj = $this->get_id_jabatan();
		$query = sprintf("
			select frm_sj_g_bakat.*, m_bakat_kerja.kode_bakat_kerja,
			m_bakat_kerja.arti,m_bakat_kerja.bakat_kerja as ket,
			concat(m_bakat_kerja.kode_bakat_kerja,' - ', m_bakat_kerja.arti) as kode_arti
			from frm_sj_g_bakat 
			inner join m_bakat_kerja on m_bakat_kerja.id = frm_sj_g_bakat.bakat_kerja
			where frm_sj_g_bakat.kode_jabatan='%s' and frm_sj_g_bakat.kode_instansi='%s'", $sj['kode'], $sj['kode_instansi']);
		if($this->input->get('query'))
		{
			$where = " and m_bakat_kerja.arti like '%".$this->input->get('query')."%' OR m_bakat_kerja.bakat_kerja like '%".$this->input->get('query')."%'";
			$query .= $where;
		}		
		$rs = $this->db->query($query);
		$data = $rs->result_array();
		echo json_encode(array('total'=>count($data), 'data'=>$data));	
	}

	function simpan_sj_bakat_kerja()
	{
		$ret = false;
		$sj = $this->get_id_jabatan();
		if($this->input->post('bakat_kerja'))
		{		
			$where = array("id"=>$this->input->post('id'), "kode_jabatan"=>$this->input->post('kode_jabatan'));
			$arr_data = array_merge($this->input->post(), array("kode_instansi" => $sj["kode_instansi"]));
			if($this->cek_is_ada('frm_sj_g_bakat',$where))
			{
				if($this->db->update('frm_sj_g_bakat',$arr_data,$where))
				{
					$ret = true;
				} else $ret= false;
			} else 
			if($this->db->insert('frm_sj_g_bakat',$arr_data))
			{
				$ret = true;
			} else $ret = false;
		}
		if($ret) echo "Data BERHASIL disimpan";
			else echo "Data GAGAL disimpan";
	}

	function hapus_sj_bakat_kerja()
	{
		if($this->input->post('id'))
		{
			if($this->db->delete('frm_sj_g_bakat',sprintf('id in(%s)', $this->input->post('id'))))
			{
				echo "Data berhasil dihapus.";
			} else echo "Data GAGAL dihapus.";
		}
	}
	
	// temperamen
	function sj_temperamen()
	{
		$data = $this->get_id_jabatan();		
		$this->load->view("frm_sj_temperamen", array("data"=>$data));
	}
	
	function get_sj_temperamen()
	{
		$sj = $this->get_id_jabatan();
		$query = sprintf("
			select frm_sj_h_temperamen.*,
			m_tempramen_kerja.kode_tempramen,
			m_tempramen_kerja.arti,
			m_tempramen_kerja.tempramen as ket,
			concat(m_tempramen_kerja.kode_tempramen, ' - ',m_tempramen_kerja.arti) as kode_arti
			from frm_sj_h_temperamen 
			inner join m_tempramen_kerja on m_tempramen_kerja.id = frm_sj_h_temperamen.temperamen
			where frm_sj_h_temperamen.kode_jabatan='%s' and frm_sj_h_temperamen.kode_instansi='%s'", $sj['kode'], $sj['kode_instansi']);
		if($this->input->get('query'))
		{
			$where = " and m_tempramen_kerja.tempramen like '%".$this->input->get('query')."%' or m_tempramen_kerja.arti like '%".$this->input->get('query')."%'";
			$query .= $where;
		}		
		$rs = $this->db->query($query);
		$data = $rs->result_array();
		echo json_encode(array('total'=>count($data), 'data'=>$data));	
	}

	function simpan_sj_temperamen()
	{
		$ret = false;
		$sj = $this->get_id_jabatan();
		if($this->input->post('temperamen'))
		{		
			$where = array("id"=>$this->input->post('id'), "kode_jabatan"=>$this->input->post('kode_jabatan'));
			$arr_data = array_merge($this->input->post(), array("kode_instansi" => $sj["kode_instansi"]));
			if($this->cek_is_ada('frm_sj_h_temperamen',$where))
			{
				if($this->db->update('frm_sj_h_temperamen',$arr_data,$where))
				{
					$ret = true;
				} else $ret= false;
			} else 
			if($this->db->insert('frm_sj_h_temperamen',$arr_data))
			{
				$ret = true;
			} else $ret = false;
		}
		if($ret) echo "Data BERHASIL disimpan";
			else echo "Data GAGAL disimpan";
	}

	function hapus_sj_temperamen()
	{
		if($this->input->post('id'))
		{
			if($this->db->delete('frm_sj_h_temperamen',sprintf('id in(%s)', $this->input->post('id'))))
			{
				echo "Data berhasil dihapus.";
			} else echo "Data GAGAL dihapus.";
		}
	}
	
	// minat kerja
	function sj_minat_kerja()
	{
		$data = $this->get_id_jabatan();		
		$this->load->view("frm_sj_minat_kerja", array("data"=>$data));
	}
	
	function get_sj_minat_kerja()
	{
		$sj = $this->get_id_jabatan();
		$query = sprintf("
			select frm_sj_i_minat_kerja.*,
			m_jns_minat_kerja.kode_minat,
			m_jns_minat_kerja.arti,
			concat(m_jns_minat_kerja.kode_minat,' - ', m_jns_minat_kerja.arti) as kode_arti,
			m_minat_kerja.minat_kerja as ket
			from frm_sj_i_minat_kerja 
			inner join m_minat_kerja on m_minat_kerja.id = frm_sj_i_minat_kerja.minat_kerja
			inner join m_jns_minat_kerja on m_jns_minat_kerja.id = m_minat_kerja.id_jns_minat_kerja			
			where frm_sj_i_minat_kerja.kode_jabatan='%s' and frm_sj_i_minat_kerja.kode_instansi='%s'", $sj['kode'], $sj['kode_instansi']);
		if($this->input->get('query'))
		{
			$where = " and m_minat_kerja.minat_kerja like '%".$this->input->get('query')."%' OR m_jns_minat_kerja.arti like '%".$this->input->get('query')."%'";
			$query .= $where;
		}		
		$rs = $this->db->query($query);
		$data = $rs->result_array();
		echo json_encode(array('total'=>count($data), 'data'=>$data));	
	}

	function simpan_sj_minat_kerja()
	{
		$ret = false;
		$sj = $this->get_id_jabatan();
		if($this->input->post('minat_kerja'))
		{		
			$where = array("id"=>$this->input->post('id'), "kode_jabatan"=>$this->input->post('kode_jabatan'));
			$arr_data = array_merge($this->input->post(), array("kode_instansi" => $sj["kode_instansi"]));
			if($this->cek_is_ada('frm_sj_i_minat_kerja',$where))
			{
				if($this->db->update('frm_sj_i_minat_kerja',$arr_data,$where))
				{
					$ret = true;
				} else $ret= false;
			} else 
			if($this->db->insert('frm_sj_i_minat_kerja',$arr_data))
			{
				$ret = true;
			} else $ret = false;
		}
		if($ret) echo "Data BERHASIL disimpan";
			else echo "Data GAGAL disimpan";
	}

	function hapus_sj_minat_kerja()
	{
		if($this->input->post('id'))
		{
			if($this->db->delete('frm_sj_i_minat_kerja',sprintf('id in(%s)', $this->input->post('id'))))
			{
				echo "Data berhasil dihapus.";
			} else echo "Data GAGAL dihapus.";
		}
	}
	
	// upaya fisik
	function sj_upaya_fisik()
	{
		$data = $this->get_id_jabatan();		
		$this->load->view("frm_sj_upaya_fisik", array("data"=>$data));
	}
	
	function get_sj_upaya_fisik()
	{
		$sj = $this->get_id_jabatan();
		$query = sprintf("
			select 
				frm_sj_j_upaya_fisik.*, 
				m_upaya_fisik.kode, m_upaya_fisik.arti,
				m_upaya_fisik.arti as ket,
				concat(m_upaya_fisik.kode,' - ',m_upaya_fisik.arti) as kode_arti
			from frm_sj_j_upaya_fisik
			inner join m_upaya_fisik on m_upaya_fisik.id = frm_sj_j_upaya_fisik.upaya_fisik
			where frm_sj_j_upaya_fisik.kode_jabatan='%s' and frm_sj_j_upaya_fisik.kode_instansi='%s'", $sj['kode'], $sj['kode_instansi']);
		if($this->input->get('query'))
		{
			$where = " and m_upaya_fisik.kode like '%".$this->input->get('query')."%' OR m_upaya_fisik.arti like '%".$this->input->get('query')."%'";
			$query .= $where;
		}		
		$rs = $this->db->query($query);
		$data = $rs->result_array();
		echo json_encode(array('total'=>count($data), 'data'=>$data));	
	}

	function simpan_sj_upaya_fisik()
	{
		$ret = false;
		$sj = $this->get_id_jabatan();
		if($this->input->post('upaya_fisik'))
		{		
			$where = array("id"=>$this->input->post('id'), "kode_jabatan"=>$this->input->post('kode_jabatan'));
			$arr_data = array_merge($this->input->post(), array("kode_instansi" => $sj["kode_instansi"]));
			if($this->cek_is_ada('frm_sj_j_upaya_fisik',$where))
			{
				if($this->db->update('frm_sj_j_upaya_fisik',$arr_data,$where))
				{
					$ret = true;
				} else $ret= false;
			} else 
			if($this->db->insert('frm_sj_j_upaya_fisik',$arr_data))
			{
				$ret = true;
			} else $ret = false;
		}
		if($ret) echo "Data BERHASIL disimpan";
			else echo "Data GAGAL disimpan";
	}

	function hapus_sj_upaya_fisik()
	{
		if($this->input->post('id'))
		{
			if($this->db->delete('frm_sj_j_upaya_fisik',sprintf('id in(%s)', $this->input->post('id'))))
			{
				echo "Data berhasil dihapus.";
			} else echo "Data GAGAL dihapus.";
		}
	}

	// fungsi pekerjaan
	function sj_fungsi_pekerjaan()
	{
		$data = $this->get_id_jabatan();		
		$this->load->view("frm_sj_fungsi_pekerjaan", array("data"=>$data));
	}
	
	function get_sj_fungsi_pekerjaan()
	{
		$sj = $this->get_id_jabatan();
		$query = sprintf("
			select frm_sj_l_fungsi_pekerjaan.*, 
			m_fungsi_kerja.kode, m_fungsi_kerja.arti,
			m_fungsi_kerja.uraian,
			concat(m_fungsi_kerja.kode,' - ',m_fungsi_kerja.arti) as kode_arti
			from frm_sj_l_fungsi_pekerjaan
			inner join m_fungsi_kerja on m_fungsi_kerja.kode = frm_sj_l_fungsi_pekerjaan.fungsi_kerja
			where frm_sj_l_fungsi_pekerjaan.kode_jabatan='%s' and frm_sj_l_fungsi_pekerjaan.kode_instansi='%s'", $sj['kode'], $sj['kode_instansi']);
		if($this->input->get('query'))
		{
			$where = " and m_fungsi_kerja.kode like '%".$this->input->get('query')."%' OR m_fungsi_kerja.arti like '%".$this->input->get('query')."%' OR m_fungsi_kerja.uraian like '%".$this->input->get('query')."%'";
			$query .= $where;
		}		
		$rs = $this->db->query($query);
		$data = $rs->result_array();
		echo json_encode(array('total'=>count($data), 'data'=>$data));	
	}

	function simpan_sj_fungsi_pekerjaan()
	{
		$ret = false;
		$sj = $this->get_id_jabatan();
		if($this->input->post('fungsi_kerja'))
		{		
			$where = array("id"=>$this->input->post('id'), "kode_jabatan"=>$this->input->post('kode_jabatan'), "fungsi_kerja"=>$this->input->post('fumgsi_kerja'));
			$arr_data = array_merge($this->input->post(), array("kode_instansi" => $sj["kode_instansi"]));
			if($this->cek_is_ada('frm_sj_l_fungsi_pekerjaan',$where))
			{
				if($this->db->update('frm_sj_l_fungsi_pekerjaan',$arr_data,$where))
				{
					$ret = true;
				} else $ret= false;
			} else 
			if($this->db->insert('frm_sj_l_fungsi_pekerjaan',$arr_data))
			{
				$ret = true;
			} else $ret = false;
		}
		if($ret) echo "Data BERHASIL disimpan";
			else echo "Data GAGAL disimpan";
	}

	function hapus_sj_fungsi_pekerjaan()
	{
		if($this->input->post('id'))
		{
			if($this->db->delete('frm_sj_l_fungsi_pekerjaan',sprintf('id in(%s)', $this->input->post('id'))))
			{
				echo "Data berhasil dihapus.";
			} else echo "Data GAGAL dihapus.";
		}
	}


	function genqr($fn='tes')
	{
		$this->load->library('qrcode');
		$params['data'] = $fn;
		$params['level'] = 'H';
		$params['size'] = 2;
		$params['savename'] = FCPATH.'report/qrcode/'.$fn.'.png';
		$this->qrcode->generate($params);
		return 'report/qrcode/'.$fn.'.png';
	}

	function odt_to_pdf()
	{
		$this->load->helper('download');
		$sj = $this->get_id_jabatan();
		$fname = $sj['kode'].$sj['kode_instansi'];
		$outfile = FCPATH.'report\\pdf\\';
		$filename = FCPATH.'report\\odt\\'.$fname.'.odt';
		if (file_exists($filename)) {
			$command = sprintf("\"C:\\Program Files\\OpenOffice 4\\program\\python.exe\" %s --format pdf --output %s %s ", FCPATH.'bin\\unoconv', $outfile ,$filename);
			echo $command;
			system($command, $ret);
			$data = file_get_contents($outfile.$fname.'.pdf');
			force_download($fname.'.pdf', $data);			
		} else {
		    echo "The file $filename does not exist";
		}
	}

	function download_odt($kode_jabatan=false, $kode_instansi=false)
	{
		if($kode_instansi && $kode_jabatan)
		{
			$sj['kode'] = $kode_jabatan;
			$sj['kode_instansi'] = $kode_instansi;
			$data = $this->data_jabatan($kode_jabatan, $kode_instansi);
		} else 
		{

			$sj = $this->get_id_jabatan();
			$data = $this->data_jabatan();
		}
		$this->save_to_odt($data);		
		$this->load->helper('download');
		$fname = $sj['kode'].$sj['kode_instansi'];
		$loc = FCPATH.'report/odt/'.$fname.'.odt';		
		$data = file_get_contents($loc);
		force_download($fname.'.odt',$data);
	}

    function save_to_odt($data)
    {
    	// http://blog.loftdigital.com/blog/pdf-doc-xls-odf-from-php
		//$sj = $this->get_id_jabatan();
		$sj = $data['jabatan'];
		$this->load->helper('text');
		define('TMP_PATH', 'C:\\xampp\\tmp');
		//$fn = $sj['kode'].$sj['kode_instansi'].'-'.$sj['nama'];
		$fn = $sj['kode'].$sj['kode_instansi'];
		$filename = ellipsize($fn,100,.5);
		$this->load->library('odf');
		$this->load->helper('download');

		$this->create_diagram($sj['kode'], $sj['kode_instansi']);
		$file_in = $filename . ".odt";
		$file_out = $filename . ".pdf";
		$qr = $this->genqr($sj['kode'].'-'.$sj['kode_instansi']);
		$inst = explode('-',$sj['nama_instansi']);
		$this->odf->cfile('report/tpl/formulir_jabatan.odt');
		$this->odf->setImage('qrcode', $qr);
		$this->odf->setVars('gendate', date("d-m-Y H:i:s"));
		$this->odf->setVars('nama_jabatan', $sj['nama']);
		$this->odf->setVars('kode_jabatan', $sj['kode']);
		$this->odf->setVars('unit_organisasi', trim($inst[1]));
		$this->odf->setImage('logo', 'report/tpl/logo.png');
		$this->odf->setVars('eselon_1', $data['dt1']['unit_org_eselon_1']);
		$this->odf->setVars('eselon_2', $data['dt1']['unit_org_eselon_2']);
		$this->odf->setVars('eselon_3', $data['dt1']['unit_org_eselon_3']);
		$this->odf->setVars('eselon_4', $data['dt1']['unit_org_eselon_4']);
		$this->odf->setVars('ikhtisar_jabatan', $data['dt1']['ikhtisar_jabatan']);
		$this->odf->setImage('sotk', FCPATH.'report/diagram/'.$sj['kode'].$sj['kode_instansi'].".png");

		// uraian tugas
		if(is_array($data['urtag']))
		{
			$ut = $this->odf->setSegment('urtag');
			$chr = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Z');
			$a=0;
			foreach($data['urtag'] as $uraian)
			{
				$patterns = array('/(^[a-zA-Z]+)\./');
				$txt = preg_replace($patterns, '', $uraian['uraian_tugas']);
				$ut->kode($chr[$a]);
				$ut->utug(trim($txt));
				$q_get_6 = sprintf("select * from frm_isian_6_uraian_tugas where kode_jabatan='%s' and kode_instansi='%s' and parent='%d' order by uraian_tugas asc", $sj['kode'], $sj['kode_instansi'], $uraian['id']);
				//echo $q_get_6;
				$data_q6 = $this->db->query($q_get_6)->result_array();	
				$i=1;
				$uraian = $this->odf->setSegment('uraian');
				foreach ($data_q6 as $c) {
					$ptrn = array('/(^[a-zA-Z]+)\./');
					$child = preg_replace($patterns, '', $c['uraian_tugas']);
					$ut->uraian->no($i);
					$ut->uraian->ur(trim($child)."\r\n");
					$ut->uraian->merge();
					$i++;
				}				
				$this->odf->mergeSegment($uraian);
				$ut->merge();
				$a++;
			}
			$this->odf->mergeSegment($ut);
		} 

		// bahan kerja
		if(is_array($data['bahan_kerja']))
		{
			$article = $this->odf->setSegment('bk');
			$i = 1;
			foreach($data['bahan_kerja'] as $elbahan) {
				$article->no($i);
				$article->bahan_kerja($elbahan['bahan_kerja']);
				$article->penggunaan($elbahan['penggunaan']);
				$article->merge();
				$i++;
			}
			$this->odf->mergeSegment($article);	
		}

		// alat kerja
		if(is_array($data['alat_kerja']))
		{
			$al = $this->odf->setSegment('alat');
			$i = 1;
			foreach($data['alat_kerja'] as $element) {
				$al->no($i);
				$al->perangkat($element['perangkat_kerja']);
				$al->digunakan($element['digunakan_untuk']);
				$al->merge();
				$i++;
			}
			$this->odf->mergeSegment($al);	
		}

		// hasil kerja
		if(is_array($data['hasil_kerja']))
		{
			$shasil = $this->odf->setSegment('hasil');
			$i = 1;
			foreach($data['hasil_kerja'] as $element) {
				$shasil->no($i);
				$shasil->hasil_kerja($element['hasil_kerja']);
				$shasil->satuan_hasil($element['satuan_hasil']);
				$shasil->merge();
				$i++;
			}
			$this->odf->mergeSegment($shasil);	
		}

		// tanggung jawab
		if(is_array($data['tanggungjawab']))
		{
			$stj = $this->odf->setSegment('tj');
			$i=1;
			foreach($data['tanggungjawab'] as $element) {
				$patterns = array('/(^[a-zA-Z]+)\./');
				$tanggungjawab = preg_replace($patterns, '', $element['tanggungjawab']);								
				$stj->no($i);
				$stj->tanggungjawab($tanggungjawab);
				$stj->merge();
				$i++;
			}
			$this->odf->mergeSegment($stj);				
		}

		// tanggung jawab
		if(is_array($data['wewenang']))
		{
			$sww = $this->odf->setSegment('wewenang');
			$i=1;
			foreach($data['wewenang'] as $element) {
				$patterns = array('/(^[a-zA-Z]+)\./');
				$wewenang = preg_replace($patterns, '', $element['wewenang']);				
				$sww->no($i);
				$sww->ww($wewenang);
				$sww->merge();
				$i++;
			}
			$this->odf->mergeSegment($sww);	
		}
		
		// korelasi jabatan 
		if(is_array($data['korelasi_jabatan']))
		{
			$srelasi = $this->odf->setSegment('relasi');
			$i = 1;
			foreach($data['korelasi_jabatan'] as $element) {
				$srelasi->no($i);
				$srelasi->jabatan($element['jabatan']);
				$srelasi->unit_kerja($element['unit_kerja_instansi']);
				$srelasi->dalam_hal($element['dalam_hal']);
				$srelasi->merge();
				$i++;
			}
			$this->odf->mergeSegment($srelasi);	
		}

		// lingkungan kerja
		if(is_array($data['kondisi_kerja']))
		{
			$slingkungan = $this->odf->setSegment('lingkungan');
			$i = 1;
			foreach($data['kondisi_kerja'] as $element) {
				$slingkungan->no($i);
				$slingkungan->aspek($element['aspek']);
				$slingkungan->faktor($element['faktor']);
				$slingkungan->merge();
				$i++;
			}
			$this->odf->mergeSegment($slingkungan);	
		}

		// resiko / bahaya
		if(is_array($data['resiko']))
		{
			$sresiko = $this->odf->setSegment('resiko');
			$i = 1;
			foreach($data['resiko'] as $element) {
				$sresiko->no($i);
				$sresiko->fisik($element['fisik_mental']);
				$sresiko->penyebab($element['penyebab']);
				$sresiko->merge();
				$i++;
			}
			$this->odf->mergeSegment($sresiko);	
		}
		// syarat jabatan
		if(is_array($data['sj']))
		{
			$this->odf->setVars('sj_pangkat', $data['sj']['pangkat']);			
			$this->odf->setVars('sj_golongan', $data['sj']['golongan']);			
			$this->odf->setVars('sj_pendidikan', $data['sj']['pendidikan']);			
			$this->odf->setVars('sj_penjenjangan', $data['penjenjangan']);			
			if(is_array($data['sj_teknis'])){  
				$i=0;
				foreach ($data['sj_teknis'] as $sjt) {
					$teknis[] = $data['sj_teknis'][$i]['teknis'];
				}								
				//$this->odf->setVars('sj_teknis', implode(',', $teknis)); 
				$this->odf->setVars('sj_teknis', ''); 
			}
			$this->odf->setVars('sj_pengalaman', $data['sj']['pengalaman']);			
			if(is_array($data['sj_pengetahuan'])){ 
				$i=0;
				foreach ($data['sj_pengetahuan'] as $sjt) {
					$pengetahuan[] = $data['sj_pengetahuan'][$i]['pengetahuan_kerja'];
				}								
				//$this->odf->setVars('sj_pengetahuan', implode(',', $pengetahuan)); 
				$this->odf->setVars('sj_pengetahuan', ''); 
			}
			if(is_array($data['sj_keterampilan'])){ 
				$i=0;
				foreach ($data['sj_keterampilan'] as $sjt) {
					$keterampilan[] = $data['sj_keterampilan'][$i]['keterampilan'];
				}								
				//$this->odf->setVars('sj_keterampilan', implode(',', $keterampilan)); 
				$this->odf->setVars('sj_keterampilan', ''); 
			}
		}
		
		if(is_array($data['sj_bakat_kerja']))
		{
			$sbakat = $this->odf->setSegment('sj_bakat');
			$i = 1;
			foreach($data['sj_bakat_kerja'] as $element) {
				$sbakat->no($i);
				$sbakat->kode($element['kode_bakat_kerja']);
				$sbakat->arti($element['arti']);
				$sbakat->ket('');
				$sbakat->merge();
				$i++;
			}
			$this->odf->mergeSegment($sbakat);	
		}

		if(is_array($data['sj_temperamen']))
		{
			$stemperamen = $this->odf->setSegment('temperamen');
			foreach($data['sj_temperamen'] as $element) {
				$stemperamen->kode($element['kode_tempramen']);
				$stemperamen->arti($element['arti']);
				$stemperamen->merge();
				$i++;
			}
			$this->odf->mergeSegment($stemperamen);	
		}

		if(is_array($data['sj_minat_kerja']))
		{
			$sminat = $this->odf->setSegment('minat');
			foreach($data['sj_minat_kerja'] as $element) {
				$sminat->kode($element['kode_minat']);
				$sminat->arti($element['arti']);
				$sminat->uraian($element['uraian']);
				$sminat->merge();
				$i++;
			}
			$this->odf->mergeSegment($sminat);	
		}

		if(is_array($data['sj_upaya_fisik']))
		{
			$suf = $this->odf->setSegment('uf');
			$i = 1;
			foreach($data['sj_upaya_fisik'] as $element) {
				$suf->no($i);				
				$suf->upaya_fisik($element['kode']);
				$suf->merge();
				$i++;
			}
			$this->odf->mergeSegment($suf);	
		}

		// kondisi fisik
		$this->odf->setVars('fisik_jk', $data['sj']['jk']);			
		$this->odf->setVars('fisik_umur', $data['sj']['umur']);			
		$this->odf->setVars('fisik_postur', $data['sj']['postur_badan']);			
		$this->odf->setVars('fisik_berat', $data['sj']['berat_badan']);			
		$this->odf->setVars('fisik_tinggi', $data['sj']['tinggi_badan']);			
		$this->odf->setVars('fisik_penampilan', $data['sj']['penampilan']);			

		if(is_array($data['sj_fungsi_kerja']))
		{
			$sfungsi = $this->odf->setSegment('fungsi');
			$i = 1;
			foreach($data['sj_fungsi_kerja'] as $element) {
				$sfungsi->no($i);				
				$sfungsi->kode($element['kode']);
				$sfungsi->arti($element['arti']);
				$sfungsi->merge();
				$i++;
			}
			$this->odf->mergeSegment($sfungsi);	
		}


		if(is_array($data['prestasi']))
		{
			$sprestasi = $this->odf->setSegment('prestasi');
			$i = 1;
			foreach($data['prestasi'] as $element) {
				$sprestasi->no($i);				
				$sprestasi->hasil_kerja($element['hasil_kerja']);
				$sprestasi->jumlah($element['jml_hasil']);
				$sprestasi->waktu_selesai($element['waktu_selesai']);
				$sprestasi->merge();
				$i++;
			}
			$this->odf->mergeSegment($sprestasi);	
		}

		if(!empty($data['sj_infolain']['informasi_lain']))
		{
			$this->odf->setVars('informasi_lain', $data['sj_infolain']['informasi_lain']);			
		} else $this->odf->setVars('informasi_lain', '-');

		// footer
		$this->odf->setVars('tgl_dibuat', $data['tgl_dibuat']);			
		$this->odf->setVars('atasan_langsung', $data['dt1']['atasan_langsung']);			
		$this->odf->setVars('nip_atasan', $data['dt1']['nip_atasan']);			
		$this->odf->setVars('pembuat', $data['dt1']['yg_membuat']);		
		$this->odf->setVars('nip_pembuat', $data['dt1']['nip_pembuat']);			

		//$this->odf->exportAsAttachedFile($filename); 
		//$this->_dump($this->odf);
		$this->odf->saveToDisk("report/odt/". $file_in); 
		$inputFile = "report/odt/". $file_in;
		$inputType = "application/vnd.oasis.opendocument.text";
		$outputFile = "report/pdf/". $file_out;
		$outputType = "application/pdf";
		//force_download($name, $data);
    }

    function save_to_pdf()
    {

    }

    function _get_nama_instansi($kode)
    {
    	$res = $this->db->query(sprintf("select * from m_instansi2 where kode='%s'", trim($kode)))->row_array();
    	return $res['nama_panjang'];
    }

    function _get_nama_jabatan($kode)
    {
    	$res = $this->db->query(sprintf("select * from m_jabatan where kode='%s'", trim($kode)))->row_array();
    	return $res['nama'];
    }

    function data_jabatan($kd_jabatan=false,$kd_instansi=false)
    {
    	if($kd_jabatan && $kd_instansi)
    		{
    			$sj['kode'] = $kd_jabatan;
    			$sj['nama'] = $this->_get_nama_jabatan($kd_jabatan);
    			$sj['kode_instansi'] = $kd_instansi;
    			$sj['nama_instansi'] = $kd_instansi .' - '.$this->_get_nama_instansi($kd_instansi);
    		} else $sj = $this->get_id_jabatan();
		// isian 1-5
		$q_get_15 = sprintf("select * from frm_isian_1_5 where kode_jabatan='%s' and id_instansi='%s'", $sj['kode'], $sj['kode_instansi']);
		$data_q15 = $this->_get_data($q_get_15);
		// uraian tugas
		$q_get_6 = sprintf("select * from frm_isian_6_uraian_tugas where kode_jabatan='%s' and kode_instansi='%s' and parent=0 order by uraian_tugas asc", $sj['kode'], $sj['kode_instansi']);
		$data_q6 = $this->_get_datas($q_get_6);
		// bahan kerja
		$q_get_7 = sprintf("select * from frm_isian_7_bahan_kerja where kode_jabatan='%s' and kode_instansi='%s'", $sj['kode'], $sj['kode_instansi']);
		$data_q7 = $this->_get_datas($q_get_7);
		// alat kerja 
		$q_get_8 = sprintf("select * from frm_isian_8_alat_kerja where kode_jabatan='%s' and kode_instansi='%s'", $sj['kode'], $sj['kode_instansi']);
		$data_q8 = $this->_get_datas($q_get_8);
		// alat kerja 
		$q_get_9 = sprintf("select * from frm_isian_9_hasil_kerja where kode_jabatan='%s' and kode_instansi='%s'", $sj['kode'], $sj['kode_instansi']);
		$data_q9 = $this->_get_datas($q_get_9);
		// tanggung 
		$q_get_10 = sprintf("select * from frm_isian_10_tanggungjawab where kode_jabatan='%s' and kode_instansi='%s'", $sj['kode'], $sj['kode_instansi']);
		$data_q10 = $this->_get_datas($q_get_10);
		// wewenang 
		$q_get_11 = sprintf("select * from frm_isian_11_wewenang where kode_jabatan='%s' and kode_instansi='%s' order by wewenang asc", $sj['kode'], $sj['kode_instansi']);
		$data_q11 = $this->_get_datas($q_get_11);
		// korelasi_jabatan
		$q_get_12 = sprintf("select * from frm_isian_12_korelasi_jabatan where kode_jabatan='%s' and kode_instansi='%s'", $sj['kode'], $sj['kode_instansi']);
		$data_q12 = $this->_get_datas($q_get_12);
		// kondisi_kerja
		$q_get_13 = sprintf("select * from frm_isian_13_kondisi_kerja where kode_jabatan='%s' and kode_instansi='%s'", $sj['kode'], $sj['kode_instansi']);
		$data_q13 = $this->_get_datas($q_get_13);
		// resiko bahaya
		$q_get_14 = sprintf("select * from frm_isian_14_resiko_bahaya where kode_jabatan='%s' and kode_instansi='%s'", $sj['kode'], $sj['kode_instansi']);
		$data_q14 = $this->_get_datas($q_get_14);
		//$this->_dump($data_q10);
		// penjenjangan
		$q_get_penjj = sprintf("select * from frm_sj_c_penjenjangan where kode_jabatan='%s' and kode_instansi='%s'", $sj['kode'], $sj['kode_instansi']);
		$data_jenjang = $this->_get_data($q_get_penjj);		
		// prestasi 
		$q_get_16 = sprintf("select * from frm_isian_16_standar_prestasi where kode_jabatan='%s' and kode_instansi='%s'", $sj['kode'], $sj['kode_instansi']);
		$data_q16 = $this->_get_datas($q_get_16);		
		/* syarat jabatan */
		$q_sj = sprintf("
			select 
				frm_sj_a_pangkatgol.*, 
				frm_sj_d_pengalaman.pengalaman,
				frm_sj_k_kondisi_fisik.jenis_kelamin,
				frm_sj_k_kondisi_fisik.umur,
				frm_sj_k_kondisi_fisik.tinggi_badan,
				frm_sj_k_kondisi_fisik.berat_badan,
				frm_sj_k_kondisi_fisik.postur_badan,
				frm_sj_k_kondisi_fisik.penampilan,
				m_jenis_kelamin.jenis_kelamin as jk,
				group_concat(frm_sj_b_pendidikan.pendidikan) as pendidikan
			from
				frm_sj_a_pangkatgol
				left join frm_sj_d_pengalaman on frm_sj_d_pengalaman.kode_jabatan = frm_sj_a_pangkatgol.kode_jabatan  and  frm_sj_d_pengalaman.kode_instansi = frm_sj_a_pangkatgol.kode_instansi
				left join frm_sj_k_kondisi_fisik on frm_sj_k_kondisi_fisik.kode_jabatan = frm_sj_a_pangkatgol.kode_jabatan  and  frm_sj_k_kondisi_fisik.kode_instansi = frm_sj_a_pangkatgol.kode_instansi
				left join m_jenis_kelamin on m_jenis_kelamin.id = frm_sj_k_kondisi_fisik.jenis_kelamin
				left join frm_sj_b_pendidikan on frm_sj_b_pendidikan.kode_jabatan=frm_sj_a_pangkatgol.kode_jabatan and frm_sj_b_pendidikan.kode_instansi=frm_sj_a_pangkatgol.kode_instansi
			where 
				frm_sj_a_pangkatgol.kode_jabatan='%s' and frm_sj_a_pangkatgol.kode_instansi='%s'", $sj['kode'], $sj['kode_instansi']);
		$data_sj = $this->_get_data($q_sj);		
		$q_sj_fk = sprintf("
			select 
			frm_sj_l_fungsi_pekerjaan.*,
			m_fungsi_kerja.kode, 
			m_fungsi_kerja.arti, 
			m_fungsi_kerja.uraian
			from frm_sj_l_fungsi_pekerjaan
			inner join m_fungsi_kerja on frm_sj_l_fungsi_pekerjaan.fungsi_kerja= m_fungsi_kerja.kode
			where 
			frm_sj_l_fungsi_pekerjaan.kode_jabatan='%s' and frm_sj_l_fungsi_pekerjaan.kode_instansi='%s'

			", $sj['kode'], $sj['kode_instansi']);
		$data_sj_fk = $this->_get_datas($q_sj_fk);		

		// upaya fisik
		$q_sj_uf = sprintf("
			select 
				frm_sj_j_upaya_fisik.*,
				m_upaya_fisik.kode, 
				m_upaya_fisik.arti
			from
				frm_sj_j_upaya_fisik
				inner join m_upaya_fisik on m_upaya_fisik.id = frm_sj_j_upaya_fisik.upaya_fisik
			where 
			frm_sj_j_upaya_fisik.kode_jabatan='%s' and frm_sj_j_upaya_fisik.kode_instansi='%s'
			", $sj['kode'], $sj['kode_instansi']);
		$data_sj_uf = $this->_get_datas($q_sj_uf);		

		// sarjab minat kerja
		$q_sj_mk = sprintf("
			select 
				m_minat_kerja.id_jns_minat_kerja,
				m_jns_minat_kerja.kode_minat,
				m_jns_minat_kerja.arti,
				group_concat(m_minat_kerja.minat_kerja) as uraian
			from
				frm_sj_i_minat_kerja
				inner join m_minat_kerja on m_minat_kerja.id = frm_sj_i_minat_kerja.minat_kerja
				inner join m_jns_minat_kerja on m_jns_minat_kerja.id =  m_minat_kerja.id_jns_minat_kerja
			where
				frm_sj_i_minat_kerja.kode_jabatan='%s' and frm_sj_i_minat_kerja.kode_instansi='%s'
			group by
				m_minat_kerja.id_jns_minat_kerja				
			", $sj['kode'], $sj['kode_instansi']);
		$data_sj_mk = $this->_get_datas($q_sj_mk);		

		// temperamen
		$q_sj_tmpr = sprintf("
			select
				frm_sj_h_temperamen.*,
				m_tempramen_kerja.kode_tempramen,
				m_tempramen_kerja.arti
			from
				frm_sj_h_temperamen
			inner join m_tempramen_kerja on m_tempramen_kerja.id = frm_sj_h_temperamen.temperamen
			where
				frm_sj_h_temperamen.kode_jabatan='%s' and frm_sj_h_temperamen.kode_instansi='%s'
			", $sj['kode'], $sj['kode_instansi']);
		$data_sj_tmpr = $this->_get_datas($q_sj_tmpr);		

		// bakat kerja
		$q_sj_bakat = sprintf("
			select 
				frm_sj_g_bakat.* ,
				m_bakat_kerja.kode_bakat_kerja,
				m_bakat_kerja.arti
			from
				frm_sj_g_bakat
			inner join m_bakat_kerja on m_bakat_kerja.id = frm_sj_g_bakat.bakat_kerja		
			where
				frm_sj_g_bakat.kode_jabatan='%s' and frm_sj_g_bakat.kode_instansi='%s'
			", $sj['kode'], $sj['kode_instansi']);
		$data_sj_bakat = $this->_get_datas($q_sj_bakat);	
		// keterampilan
		$q_sj_keter = sprintf("
			select * from frm_sj_f_keterampilan
			where frm_sj_f_keterampilan.kode_jabatan='%s' and frm_sj_f_keterampilan.kode_instansi='%s'
			", $sj['kode'], $sj['kode_instansi']);
		$data_sj_keter = $this->_get_datas($q_sj_keter);		

		// pengetahuan
		$q_sj_tahu = sprintf("
			select * from frm_sj_e_pengetahuan where  kode_jabatan='%s' and kode_instansi='%s'
			", $sj['kode'], $sj['kode_instansi']);
		$data_sj_tahu = $this->_get_datas($q_sj_tahu);		

		// teknis
		$q_sj_teknis = sprintf("select * from frm_sj_c_kurdik where kode_jabatan='%s' and kode_instansi='%s'
			", $sj['kode'], $sj['kode_instansi']);
		$data_sj_teknis = $this->_get_datas($q_sj_teknis);		
		
		// informasi lain
		$q_sj_info = sprintf("select * from frm_isian_17_informasi_lain where kode_jabatan='%s' and  kode_instansi='%s'
			", $sj['kode'], $sj['kode_instansi']);
		$data_sj_info = $this->_get_data($q_sj_info);		
		//$data_diagram = $this->diagram_sotk();
		$iscetak = false;
		if(isset($data_q15['tgl_dibuat']))
		{
			$tgl_cetak_dibuat = $this->_bulan_tahun($data_q15['tgl_dibuat']);		
			$tgl_dibuat = isset($tgl_cetak_dibuat) ? $tgl_cetak_dibuat : date('d-m-Y');
			$arr_tgl = explode('-', $data_q15['tgl_dibuat']);
			$tgl_dibuat = $arr_tgl[2].' '.$tgl_dibuat;			
		} else $tgl_dibuat = date('Y-m-d');
		// end to odt 
		$inst = explode('-',$sj['nama_instansi']);
		$nama_ins = trim($inst[1]);
		$data = array(
				'jabatan'=>$sj, 
				'instansi'=>$nama_ins,
				'dt1'=>$data_q15, 
				'tgl_dibuat'=> $tgl_dibuat,
				'urtag'=>$data_q6,
				'bahan_kerja'=>$data_q7,
				'alat_kerja'=>$data_q8,
				'hasil_kerja'=>$data_q9,
				'tanggungjawab'=>$data_q10,
				'wewenang'=>$data_q11,
				'korelasi_jabatan'=>$data_q12,
				'kondisi_kerja'=>$data_q13,
				'resiko'=>$data_q14,
				'prestasi'=>$data_q16,
				'sj'=>$data_sj,
				'sj_fungsi_kerja'=>$data_sj_fk,
				'sj_upaya_fisik'=>$data_sj_uf,
				'sj_minat_kerja'=>$data_sj_mk,
				'sj_temperamen'=>$data_sj_tmpr,
				'sj_bakat_kerja'=>$data_sj_bakat,
				'sj_keterampilan'=>$data_sj_keter,
				'sj_pengetahuan'=>$data_sj_tahu,
				'sj_teknis'=>$data_sj_teknis,
				'sj_infolain'=>$data_sj_info,
				'iscetak'=>$iscetak,
				'penjenjangan' => isset($data_jenjang['penjenjangan']) ? $data_jenjang['penjenjangan'] : ''
			);
		return $data;
    }

	function cetak_jabatan($print=false)
	{
		$data = $this->data_jabatan();
		if($print) $iscetak = true;
		$this->load->view('cetak_jabatan', $data);
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

	function recurse($categories, $parent = null, $level = 0)
	{
	    $ret = '<ul>';
	    foreach($categories as $index => $category)
	    {
	        if($category['parent'] == $parent)
	        {
	            $ret .= '<li><a href="#"><p class="Tier' . $level . '">' . $category['name'] . '</p></a>';
	            $sub = $this->recurse($categories, $category['id'], $level+1);
	            if($sub != '<ul></ul>')
	                $ret .= $sub;
	            $ret .= '</li>';
	        }
	    }
	    return $ret . '</ul>';
	}

	// http://stackoverflow.com/questions/12285694/creating-a-recursive-category-tree-function
	function nested2ul($data) {
	  $result = array();
	  if (sizeof($data) > 0) {
	    $result[] = '<ul>';
	    foreach ($data as $entry) {
	    	if($entry['posisi_jabatan']==1)
	    	{
		      $result[] = sprintf(
		        '<li><a href="#" style="background:#ccc;">%s</a> %s</li>',
		        $entry['name'],
		        $this->nested2ul($entry['children'])
		      );	    		
	    	} else 
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

	function create_diagram($jabatan=false,$instansi=false)
	{
		$phantomjs = FCPATH."bin\phantomjs\phantomjs.exe";
		$render = sprintf("%sreport\\diagram\\%s.png",FCPATH,$jabatan.$instansi);
		$render = str_replace("\\", "\\/", $render);
		if($jabatan && $instansi)
		{
			$data = sprintf("
			var page = require('webpage').create();
			page.open('%sindex.php/entry_sdm/isian_jabatan/diagram_sotk/%s/%s', function() {
				page.viewportSize = {
				    width: 600,
				    height: 250
				};				
			  page.render('%s');
			  phantom.exit();
			});
			", base_url(), $jabatan, $instansi, $render);
			$filename = FCPATH."report\\diagram\\".$jabatan.$instansi.".js";
	    	$f = @fopen($filename, 'w');
	        if (!$f) {
	            echo "Gagal menulis file.";
	        } else {
	            $bytes = fwrite($f, $data);
	            fclose($f);
	        }	
	        $cmd = $phantomjs.' '.$filename;
	        system($cmd, $output);	
    	}
	}

	function diagram_sotk($jabatan=false,$instansi=false)
	{
		if($jabatan && $instansi)
		{
			$sj['kode'] = $jabatan;
			$sj['kode_instansi'] = $instansi;
		} else $sj = $this->get_id_jabatan();		
		$sql = sprintf("select id, nama_jabatan as name, posisi_jabatan, parent_id as parent from frm_isian_1_5_struktur where kode_jabatan='%s' and kode_instansi='%s'", $sj['kode'], $sj['kode_instansi']);
		$res = $this->db->query($sql)->result_array();
		if(count($res))
		{
			$data = $this->nested2ul(array($this->makeRecursive($res)));
			$this->load->view('diagram_sotk', array('data' => $data));
		} else echo "-- Data masih kosong --";
	}

	public function makeTree($parent, $array)
	{
	  if (!is_array($array) OR empty($array)) return FALSE;
	  $output = '<ul>';
	  foreach($array as $key => $value):
	    if ($value['parent'] == $parent):
	        $output .= '<li>';

	        if ($value['parent'] == NULL):
	            $output .= $value['name'];

	            $matches = array();

	            foreach($array as $subkey => $subvalue):
	                if ($subvalue['parent'] == $value['id']):
	                    $matches[$subkey] = $subvalue;
	                endif;
	            endforeach;

	            $output .= $this->makeTree($value['id'], $matches);
	        else:
	            $output .= $value['name'];
	            $output .= '</li>';
	        endif;
	    endif;
	  endforeach;
	  $output .= '</ul>';
	  return $output;
	}

	function _bulan_tahun($tgl)
	{
		$tgl = isset($tgl) ? $tgl : date('Y-m-d');
		$tanggal = explode('-',$tgl);
		$bulan = $this->_bulan($tanggal[1]);
			return $bulan .' '.  $tanggal[0];
	}

	function _bulan($bulan)
	{
		$nama_bulan = '';
		switch ($bulan) {
			case '01': $nama_bulan = 'Januari'; break;
			case '02': $nama_bulan = 'Februari'; break;
			case '03': $nama_bulan = 'Maret'; break;
			case '04': $nama_bulan = 'April'; break;
			case '05': $nama_bulan = 'Mei'; break;
			case '06': $nama_bulan = 'Juni'; break;
			case '07': $nama_bulan = 'Juli'; break;
			case '08': $nama_bulan = 'Agustus'; break;
			case '09': $nama_bulan = 'September'; break;
			case '10': $nama_bulan = 'Oktober'; break;
			case '11': $nama_bulan = 'November'; break;
			case '12': $nama_bulan = 'Desember'; break;
		}
		return $nama_bulan;
	}

	function _get_datas($q)
	{
		$res = $this->db->query($q)->result_array();
		if(count($res)) return $res;
			else return false;
	}	

	function _get_data($q)
	{
		$res = $this->db->query($q)->row_array();
		if(count($res)) return $res;
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
	
	function cek_is_ada($table, $where)
	{
		$this->db->where($where);
		$this->db->from($table);
		$c = $this->db->count_all_results();
		if($c>0) return true;
			else return false;
	}
	
	function _dump($s)
	{
		print('<pre>');
		print_r($s);
		print('</pre>');
	}
	
}

?>