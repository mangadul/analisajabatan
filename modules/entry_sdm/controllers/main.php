<?php

class main extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('mod_pemetaan');
    }

    public function index() {
        $this->load->view('main');
    }

    public function get_data_sdm() {
		/*
		http://localhost:82/anforjab/index.php/entry_sdm/main/get_data_sdm?_dc=1414961753190&page=1&start=0&limit=50&sort=id&dir=DESC&query=hapsah
		*/
		$start = $this->input->get('start') ? $this->input->get('start') : 0;
		$limit = $this->input->get('limit') ? $this->input->get('limit') : 100;
		$query = "
			select 
			sdm.*, m_instansi2.nama_panjang as nama_instansi, 
			m_instansi2.nama as nm_instansi,
			m_jabatan.nama as jabatan,
			m_pendidikan.pendidikan as pendidikan_txt,
			m_pangkat.pangkat as pangkat_txt,
			m_golongan.golongan as golongan_txt
			from m_rekap_sdm as sdm
			inner join m_instansi2 on (m_instansi2.kode = sdm.kode_instansi)
			INNER JOIN m_jabatan on (m_jabatan.id = sdm.id_jabatan)
			INNER JOIN m_pangkat on (m_pangkat.id = sdm.pangkat)
			INNER JOIN m_golongan on (m_golongan.id = sdm.golongan)
			INNER JOIN m_pendidikan on (m_pendidikan.id = sdm.pendidikan)
			WHERE 1
			";
		$total = $this->db->query($query)->num_rows();
		if($this->input->get('query'))
		{
			$query .= " and sdm.nama like '%".$this->input->get('query')."%' or sdm.nama like '%".$this->input->get('query')."%'";
		}
		$query .= " order by sdm.nama asc";
		$query .= sprintf(" limit %d,%d", $start, $limit);

		$rs = $this->db->query($query);
		$totdata = $rs->num_rows();
		if($totdata)
		{
			echo json_encode(array('total'=>$total, 'data'=>$rs->result_array()));
		} else return false;	
    }
	
	function upload_sdm()
	{
		$res = array('success'=>true, 'msg'=>'Data berhasil diupload.');
		echo json_encode($res);
	}

	function upload_db()
	{
		$res = array('success'=>true, 'msg'=>'Data berhasil diupload.');
		echo json_encode($res);
	}

	function backupdb()
	{
		$this->load->view('backupdb');
	}
	
	function download_sql()
	{
		$this->load->dbutil();
		$backup =& $this->dbutil->backup(); 
		$this->load->helper('file');
		$fn = sprintf("backup-anjab-%s.gz", date('dmYHis'));
		$file = sprintf('C:\\xampp\\tmp\\%s',$fn);
		write_file($file, $backup); 
		$this->load->helper('download');
		force_download($fn, $backup);	
	}
	
	function set_kode_instansi()
	{
		$this->session->userdata('kd_instansi', '');
		if($this->input->post('kode_instansi'))
		{
			$this->session->set_userdata('kode_instansi', $this->input->post('kode_instansi'));
			$this->session->set_userdata('nama_instansi', $this->input->post('nama_instansi'));
			echo "Kode instansi telah ditentukan. Kode: ",$this->input->post('kode_instansi'), " - ",$this->input->post('nama_instansi');
		}
	}

	function get_kode_instansi()
	{
		return $this->session->userdata('kd_instansi');
	}
	
	function get_instansi()
	{
		$query = "select kode, concat(kode,' | ',nama,' - ', nama_panjang) as nama from m_instansi2";
		if($this->input->get('query'))
		{
			$where = " where kode like '%".$this->input->get('query')."%' or nama_panjang like '%".$this->input->get('query')."%' OR nama like '%".$this->input->get('query')."%'";
			$query .= $where;
		}
		$rs = $this->db->query($query);
		$data = $rs->result_array();
		echo json_encode(array('total'=>count($data), 'data'=>$data));
	}

	function get_jabatan()
	{
		$query = "select id, concat(kode,' - ', nama) as jabatan from m_jabatan";
		if($this->input->get('query'))
		{
			$where = " where kode like '%".$this->input->get('query')."%' or nama like '%".$this->input->get('query')."%'";
			$query .= $where;
		}
		$rs = $this->db->query($query);
		$data = $rs->result_array();
		echo json_encode(array('total'=>count($data), 'data'=>$data));
	}
	
	function get_pangkat()
	{
		$query = "select * from m_pangkat";
		if($this->input->get('query'))
		{
			$where = " where pangkat like '%".$this->input->get('query')."%'";
			$query .= $where;
		}		
		$rs = $this->db->query($query);
		$data = $rs->result_array();
		echo json_encode(array('total'=>count($data), 'data'=>$data));
	}

	function cek_is_ada($table, $where)
	{
		$this->db->where($where);
		$this->db->from($table);
		$c = $this->db->count_all_results();
		if($c>0) return true;
			else return false;
	}
	
	/* poin 1,2,3,5 */
	function simpan_poin_1_4()
	{
		if($this->input->post('kode_jabatan') && $this->input->post('ikhtisar_jabatan') 
			&& $this->input->post('atasan_langsung') && $this->input->post('nip_atasan')
			&& $this->input->post('jabatan_atasan_langsung') && $this->input->post('nip_pembuat')
			&& $this->input->post('jabatan_yg_membuat') && $this->input->post('yg_membuat') 
			&& $this->input->post('id_instansi'))
		{
			$id_instansi = $this->input->post('id_instansi');
			$kode_jabatan = $this->input->post('kode_jabatan');
			$cond = array('id_instansi'=>$id_instansi, 'kode_jabatan'=>$kode_jabatan);
			if($this->cek_is_ada('frm_isian_1_5', $cond))
			{
				if($this->db->update('frm_isian_1_5', $this->input->post(),$cond))
				{
					echo json_encode(array('success'=>true, 'message'=>'Data berhasil disimpan.'));
				} else echo json_encode(array('success'=>false, 'message'=>'Data GAGAL disimpan.'));			
			} else
			{
				if($this->db->insert('frm_isian_1_5', $this->input->post()))
				{
					echo json_encode(array('success'=>true, 'message'=>'Data berhasil disimpan.'));
				} else echo json_encode(array('success'=>false, 'message'=>'Data GAGAL disimpan.'));			
			}
		} else echo json_encode(array('success'=>true, 'message'=>'Masukan semua field yang dibutuhkan'));
	}
	
	/* bahan kerja */
	function get_bahan_kerja()
	{
		$sj = $this->get_id_jabatan();
		$query = sprintf("select * from frm_isian_7_bahan_kerja where kode_jabatan='%s' and kode_instansi='%s'", $sj['kode'], $sj['kode_instansi']);
		if($this->input->get('query'))
		{
			$where = " and bahan_kerja like '%".$this->input->get('query')."%' or penggunaan like '%".$this->input->get('query')."%'";
			$query .= $where;
		}		
		$rs = $this->db->query($query);
		$data = $rs->result_array();
		echo json_encode(array('total'=>count($data), 'data'=>$data));
	}
	
	function simpan_bahan_kerja()
	{
		$ret = false;
		$sj = $this->get_id_jabatan();
		if($this->input->post('bahan_kerja') && $this->input->post('penggunaan'))
		{		
			$where = array("id"=>$this->input->post('id'), "kode_jabatan"=>$this->input->post('kode_jabatan'));
			$arr_insert = array_merge($this->input->post(), array('kode_instansi' => $sj['kode_instansi']));
			if($this->cek_is_ada('frm_isian_7_bahan_kerja',$where))
			{
				if($this->db->update('frm_isian_7_bahan_kerja',$arr_insert,$where))
				{
					$ret = true;
				} else $ret= false;
			} else 
			if($this->db->insert('frm_isian_7_bahan_kerja',$arr_insert))
			{
				$ret = true;
			} else $ret = false;
		}
		if($ret) echo "Data BERHASIL disimpan.";
			else echo "Data GAGAL disimpan.";
	}

	function hapus_bahan_kerja()
	{
		if($this->input->post('id'))
		{
			if($this->db->delete('frm_isian_7_bahan_kerja',sprintf('id in(%s)', $this->input->post('id'))))
			{
				echo "Data berhasil dihapus.";
			} else echo "Data GAGAL dihapus.";
		}
	}
	/* end bahan kerja */


	/* perangkat / alat kerja */
	function get_perangkat_kerja()
	{
		$sj = $this->get_id_jabatan();	
		$query = sprintf("select * from frm_isian_8_alat_kerja where kode_jabatan='%s' and kode_instansi='%s'", $sj['kode'], $sj['kode_instansi']);
		if($this->input->get('query'))
		{
			$where = " and perangkat_kerja like '%".$this->input->get('query')."%' or digunakan_untuk like '%".$this->input->get('query')."%'";
			$query .= $where;
		}		
		$rs = $this->db->query($query);
		$data = $rs->result_array();
		echo json_encode(array('total'=>count($data), 'data'=>$data));
	}
	
	function simpan_perangkat_kerja()
	{
		$ret = false;
		$sj = $this->get_id_jabatan();
		if($this->input->post('perangkat_kerja') && $this->input->post('digunakan_untuk'))
		{		
			$where = array("id"=>$this->input->post('id'), "kode_jabatan"=>$this->input->post('kode_jabatan'));
			$arr_data = array_merge($this->input->post(), array("kode_instansi" => $sj["kode_instansi"]));
			if($this->cek_is_ada('frm_isian_8_alat_kerja',$where))
			{
				if($this->db->update('frm_isian_8_alat_kerja', $arr_data, $where))
				{
					$ret = true;
				} else $ret= false;
			} else 
			if($this->db->insert('frm_isian_8_alat_kerja', $arr_data))
			{
				$ret = true;
			} else $ret = false;
		}
		if($ret) echo "Data BERHASIL disimpan.";
			else echo "Data GAGAL disimpan.";
	}

	function hapus_perangkat_kerja()
	{
		if($this->input->post('id'))
		{
			if($this->db->delete('frm_isian_8_alat_kerja',sprintf('id in(%s)', $this->input->post('id'))))
			{
				echo "Data berhasil dihapus.";
			} else echo "Data GAGAL dihapus.";
		}
	}	
	/* end perangkat / alat kerja */
	
	/* hasil kerja */
	function get_hasil_kerja()
	{
		$sj = $this->get_id_jabatan();
		$query = sprintf("select * from frm_isian_9_hasil_kerja where kode_jabatan='%s' and kode_instansi='%s'", $sj['kode'],$sj['kode_instansi']);
		if($this->input->get('query'))
		{
			$where = " and hasil_kerja like '%".$this->input->get('query')."%' or satuan_hasil like '%".$this->input->get('query')."%'";
			$query .= $where;
		}		
		$rs = $this->db->query($query);
		$data = $rs->result_array();
		echo json_encode(array('total'=>count($data), 'data'=>$data));
	}
	
	function simpan_hasil_kerja()
	{
		$ret = false;
		$sj = $this->get_id_jabatan();
		if($this->input->post('hasil_kerja') && $this->input->post('satuan_hasil'))
		{		
			$where = array("id"=>$this->input->post('id'), "kode_jabatan"=>$this->input->post('kode_jabatan'));
			$arr_data = array_merge($this->input->post(), array("kode_instansi" => $sj["kode_instansi"]));
			if($this->cek_is_ada('frm_isian_9_hasil_kerja',$where))
			{
				if($this->db->update('frm_isian_9_hasil_kerja',$arr_data,$where))
				{
					$ret = true;
				} else $ret= false;
			} else 
			if($this->db->insert('frm_isian_9_hasil_kerja',$arr_data))
			{
				$ret = true;
			} else $ret = false;
		}
		if($ret) echo "Data BERHASIL disimpan.";
			else echo "Data GAGAL disimpan.";
	}

	function hapus_hasil_kerja()
	{
		if($this->input->post('id'))
		{
			if($this->db->delete('frm_isian_9_hasil_kerja',sprintf('id in(%s)', $this->input->post('id'))))
			{
				echo "Data berhasil dihapus.";
			} else echo "Data GAGAL dihapus.";
		}
	}	
	/* end hasil kerja */
	
	/* tanggung jawab */
	function get_tanggungjawab()
	{
		$sj = $this->get_id_jabatan();
		$query = sprintf("select * from frm_isian_10_tanggungjawab where kode_jabatan='%s' and kode_instansi='%s'", $sj['kode'], $sj['kode_instansi']);
		if($this->input->get('query'))
		{
			$where = " and tanggungjawab like '%".$this->input->get('query')."%'";
			$query .= $where;
		}
		$query .= " order by tanggungjawab asc";
		$rs = $this->db->query($query);
		$data = $rs->result_array();
		echo json_encode(array('total'=>count($data), 'data'=>$data));
	}
	
	function simpan_tanggungjawab()
	{
		$ret = false;
		$sj = $this->get_id_jabatan();
		if($this->input->post('tanggungjawab'))
		{				
			$where = array("id"=>$this->input->post('id'), "kode_jabatan"=>$this->input->post('kode_jabatan'));
			$arr_data = array_merge($this->input->post(), array("kode_instansi" => $sj["kode_instansi"]));			
			if($this->cek_is_ada('frm_isian_10_tanggungjawab',$where))
			{
				if($this->db->update('frm_isian_10_tanggungjawab',$arr_data,$where))
				{
					$ret = true;
				} else $ret= false;
			} else 
			if($this->db->insert('frm_isian_10_tanggungjawab',$arr_data))
			{
				$ret = true;
			} else $ret = false;
		}
		if($ret) echo "Data BERHASIL disimpan.";
			else echo "Data GAGAL disimpan.";
	}

	function hapus_tanggungjawab()
	{
		if($this->input->post('id'))
		{
			if($this->db->delete('frm_isian_10_tanggungjawab',sprintf('id in(%s)', $this->input->post('id'))))
			{
				echo "Data berhasil dihapus.";
			} else echo "Data GAGAL dihapus.";
		}
	}	
	/* end tanggung jawab */
	
	/* wewenang */
	function get_wewenang()
	{
		$sj = $this->get_id_jabatan();	
		$query = sprintf("select * from frm_isian_11_wewenang where kode_jabatan='%s' and kode_instansi='%s'", $sj['kode'], $sj['kode_instansi']);
		if($this->input->get('query'))
		{
			$where = " and wewenang like '%".$this->input->get('query')."%'";
			$query .= $where;
		}		
		$query .= " order by wewenang asc";
		$rs = $this->db->query($query);
		$data = $rs->result_array();
		echo json_encode(array('total'=>count($data), 'data'=>$data));
	}
	
	function simpan_wewenang()
	{
		$ret = false;
		$sj = $this->get_id_jabatan();
		if($this->input->post('wewenang'))
		{		
			$where = array("id"=>$this->input->post('id'), "kode_jabatan"=>$this->input->post('kode_jabatan'));
			$arr_data = array_merge($this->input->post(), array("kode_instansi" => $sj["kode_instansi"]));
			if($this->cek_is_ada('frm_isian_11_wewenang',$where))
			{
				if($this->db->update('frm_isian_11_wewenang',$arr_data,$where))
				{
					$ret = true;
				} else $ret= false;
			} else 
			if($this->db->insert('frm_isian_11_wewenang',$arr_data))
			{
				$ret = true;
			} else $ret = false;
		}
		if($ret) echo "Data BERHASIL disimpan.";
			else echo "Data GAGAL disimpan.";
	}

	function hapus_wewenang()
	{
		if($this->input->post('id'))
		{
			if($this->db->delete('frm_isian_11_wewenang',sprintf('id in(%s)', $this->input->post('id'))))
			{
				echo "Data berhasil dihapus.";
			} else echo "Data GAGAL dihapus.";
		}
	}	
	/* end wewenang */
	
	/* 12 */
	function get_korelasi_jabatan()
	{
		$sj = $this->get_id_jabatan();	
		$query = sprintf("select * from frm_isian_12_korelasi_jabatan where kode_jabatan='%s' and kode_instansi='%s'", $sj['kode'], $sj['kode_instansi']);
		if($this->input->get('query'))
		{
			$where = " and jabatan like '%".$this->input->get('query')."%' OR unit_kerja_instansi like '%".$this->input->get('query')."%' OR dalam_hal like '%".$this->input->get('query')."%'";
			$query .= $where;
		}		
		$rs = $this->db->query($query);
		$data = $rs->result_array();
		echo json_encode(array('total'=>count($data), 'data'=>$data));
	}
	
	function simpan_korelasi_jabatan()
	{
		$ret = false;
		$sj = $this->get_id_jabatan();
		if($this->input->post('jabatan') && $this->input->post('kode_jabatan') && $this->input->post('unit_kerja_instansi') && $this->input->post('dalam_hal'))
		{		
			$where = array("id"=>$this->input->post('id'), "kode_jabatan"=>$this->input->post('kode_jabatan'));
			$arr_data = array_merge($this->input->post(), array("kode_instansi" => $sj["kode_instansi"]));			
			if($this->cek_is_ada('frm_isian_12_korelasi_jabatan',$where))
			{
				if($this->db->update('frm_isian_12_korelasi_jabatan',$arr_data,$where))
				{
					$ret = true;
				} else $ret= false;
			} else 
			if($this->db->insert('frm_isian_12_korelasi_jabatan',$arr_data))
			{
				$ret = true;
			} else $ret = false;
		}
		if($ret) echo "Data BERHASIL disimpan.";
			else echo "Data GAGAL disimpan.";
	}

	function hapus_korelasi_jabatan()
	{
		if($this->input->post('id'))
		{
			if($this->db->delete('frm_isian_12_korelasi_jabatan',sprintf('id in(%s)', $this->input->post('id'))))
			{
				echo "Data berhasil dihapus.";
			} else echo "Data GAGAL dihapus.";
		}
	}

	/* 13 */
	function get_lingkungan_kerja()
	{
		$sj = $this->get_id_jabatan();	
		$query = sprintf("select * from frm_isian_13_kondisi_kerja where kode_jabatan='%s' and kode_instansi='%s'", $sj['kode'], $sj['kode_instansi']);
		if($this->input->get('query'))
		{
			$where = " and aspek like '%".$this->input->get('query')."%' OR faktor like '%".$this->input->get('query')."%'";
			$query .= $where;
		}		
		$rs = $this->db->query($query);
		$data = $rs->result_array();
		echo json_encode(array('total'=>count($data), 'data'=>$data));
	}
	
	function simpan_lingkungan_kerja()
	{
		$ret = false;
		$sj = $this->get_id_jabatan();
		if($this->input->post('aspek') && $this->input->post('faktor'))
		{		
			$where = array("id"=>$this->input->post('id'), "kode_jabatan"=>$this->input->post('kode_jabatan'));
			$arr_data = array_merge($this->input->post(), array("kode_instansi" => $sj["kode_instansi"]));
			if($this->cek_is_ada('frm_isian_13_kondisi_kerja',$where))
			{
				if($this->db->update('frm_isian_13_kondisi_kerja',$arr_data,$where))
				{
					$ret = true;
				} else $ret= false;
			} else 
			if($this->db->insert('frm_isian_13_kondisi_kerja',$arr_data))
			{
				$ret = true;
			} else $ret = false;
		}
		if($ret) echo "Data BERHASIL disimpan.";
			else echo "Data GAGAL disimpan.";
	}

	function hapus_lingkungan_kerja()
	{
		if($this->input->post('id'))
		{
			if($this->db->delete('frm_isian_13_kondisi_kerja',sprintf('id in(%s)', $this->input->post('id'))))
			{
				echo "Data berhasil dihapus.";
			} else echo "Data GAGAL dihapus.";
		}
	}	
	
	/* 14 */ 
	function get_resiko_bahaya()
	{
		$sj = $this->get_id_jabatan();
		$query = sprintf("select * from frm_isian_14_resiko_bahaya where kode_jabatan='%s' and kode_instansi='%s'", $sj['kode'], $sj['kode_instansi']);
		if($this->input->get('query'))
		{
			$where = " and fisik_mental like '%".$this->input->get('query')."%' OR penyebab like '%".$this->input->get('query')."%'";
			$query .= $where;
		}
		$rs = $this->db->query($query);
		$data = $rs->result_array();
		echo json_encode(array('total'=>count($data), 'data'=>$data));
	}
	
	function simpan_resiko_bahaya()
	{
		$ret = false;
		$sj = $this->get_id_jabatan();
		if($this->input->post('fisik_mental') && $this->input->post('penyebab'))
		{		
			$where = array("id"=>$this->input->post('id'), "kode_jabatan"=>$this->input->post('kode_jabatan'));
			$arr_data = array_merge($this->input->post(), array("kode_instansi" => $sj["kode_instansi"]));
			if($this->cek_is_ada('frm_isian_14_resiko_bahaya',$where))
			{
				if($this->db->update('frm_isian_14_resiko_bahaya',$arr_data,$where))
				{
					$ret = true;
				} else $ret= false;
			} else 
			if($this->db->insert('frm_isian_14_resiko_bahaya',$arr_data))
			{
				$ret = true;
			} else $ret = false;
		}
		if($ret) echo "Data BERHASIL disimpan.";
			else echo "Data GAGAL disimpan.";
	}

	function hapus_resiko_bahaya()
	{
		if($this->input->post('id'))
		{
			if($this->db->delete('frm_isian_14_resiko_bahaya',sprintf('id in(%s)', $this->input->post('id'))))
			{
				echo "Data berhasil dihapus.";
			} else echo "Data GAGAL dihapus.";
		}
	}
	
	/* 16 */
	function get_prestasi_kerja()
	{
		$sj = $this->get_id_jabatan();
		$query = sprintf("select * from frm_isian_16_standar_prestasi where kode_jabatan='%s' and kode_instansi='%s'", $sj['kode'], $sj['kode_instansi']);
		if($this->input->get('query'))
		{
			$where = " and hasil_kerja like '%".$this->input->get('query')."%'";
			$query .= $where;
		}		
		$rs = $this->db->query($query);
		$data = $rs->result_array();
		echo json_encode(array('total'=>count($data), 'data'=>$data));
	}
	
	function simpan_prestasi_kerja()
	{
		$ret = false;
		$sj = $this->get_id_jabatan();
		if($this->input->post('hasil_kerja') && $this->input->post('jml_hasil') && $this->input->post('waktu_selesai'))
		{		
			$where = array("id"=>$this->input->post('id'), "kode_jabatan"=>$this->input->post('kode_jabatan'));
			$arr_data = array_merge($this->input->post(), array("kode_instansi" => $sj["kode_instansi"]));
			if($this->cek_is_ada('frm_isian_16_standar_prestasi',$where))
			{
				if($this->db->update('frm_isian_16_standar_prestasi',$arr_data,$where))
				{
					$ret = true;
				} else $ret= false;
			} else 
			if($this->db->insert('frm_isian_16_standar_prestasi',$arr_data))
			{
				$ret = true;
			} else $ret = false;
		}
		if($ret) echo "Data BERHASIL disimpan.";
			else echo "Data GAGAL disimpan.";
	}

	function hapus_prestasi_kerja()
	{
		if($this->input->post('id'))
		{
			if($this->db->delete('frm_isian_16_standar_prestasi',sprintf('id in(%s)', $this->input->post('id'))))
			{
				echo "Data berhasil dihapus.";
			} else echo "Data GAGAL dihapus.";
		}
	}
	
	/* infomasi_lain */ 
	function simpan_infomasi_lain()
	{
		$ret = false;
		$sj = $this->get_id_jabatan();
		if($this->input->post('informasi_lain'))
		{		
			$where = array("id"=>$this->input->post('id'), "kode_jabatan"=>$this->input->post('kode_jabatan'));
			$arr_data = array_merge($this->input->post(), array("kode_instansi" => $sj["kode_instansi"]));
			if($this->cek_is_ada('frm_isian_17_informasi_lain',$where))
			{
				if($this->db->update('frm_isian_17_informasi_lain',$arr_data,$where))
				{
					$ret = true;
				} else $ret= false;
			} else 
			if($this->db->insert('frm_isian_17_informasi_lain',$arr_data))
			{
				$ret = true;
			} else $ret = false;
		}
		if($ret) echo json_encode(array("result"=>true, "message"=>"Data BERHASIL disimpan."));
			else echo json_encode(array("result"=>true, "message"=>"Data GAGAL disimpan."));
	}
	
	function form_jabatan()
	{
		//$this->_dump($this->session->userdata);
		$data = array('kode_instansi'=>$this->session->userdata('kode_instansi'), 'nama_instansi'=>$this->session->userdata('nama_instansi'));		
		$this->load->view("struktural", $data);
	}

	function form_isian_jabatan()
	{
		$this->load->view("form_isian_jabatan");
	}
	
	function edit_sdm()
	{
		echo "Under Construction";
	}
	
	function get_golongan()
	{
		$query = "select * from m_golongan";
		if($this->input->get('query'))
		{
			$where = " where golongan like '%".$this->input->get('query')."%'";
			$query .= $where;
		}		
		$rs = $this->db->query($query);
		$data = $rs->result_array();
		echo json_encode(array('total'=>count($data), 'data'=>$data));
	}

	function get_pendidikan()
	{
		$query = "select * from m_pendidikan";
		if($this->input->get('query'))
		{
			$where = " where pendidikan like '%".$this->input->get('query')."%'";
			$query .= $where;
		}
		$rs = $this->db->query($query);
		$data = $rs->result_array();
		echo json_encode(array('total'=>count($data), 'data'=>$data));
	}

	function import_db()
	{
		echo "Under Construction";
	}

	function export_xls()
	{
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
	
	function get_nama_nip()
	{
		if($this->input->post('nip'))
		{
			$sql = sprintf("select nama from m_pegawai where nip='%s'", $this->input->post('nip'));
			$ret = $this->db->query($sql)->row_array();
			if(isset($ret['nama'])) echo $ret['nama']; 
				else echo sprintf("Nama dg NIP '%s' tidak ditemukan!", $this->input->post('nip'));
		}
	}
	
	function optimize_tbl()
	{
		if($this->input->post('action'))
		{
			$this->load->dbutil();
			$result = $this->dbutil->optimize_database();
			if ($result !== FALSE)
			{
				//$this->load->view('backupdb', array('status'=>$result));
				echo "Data berhasil dioptimisasi.";
			} else echo $this->_dump($result);
		}
	}
	
	function hapus_sdm()
	{
		if($this->input->post('id'))
		{
			if($this->db->delete('m_rekap_sdm', array('id'=>$this->input->post('id'))))
			{
				echo "Data berhasil dihapus.";
			}
		}
	}

	function cetak()
	{
		if($this->input->post('id'))
		{
			echo "Under Construction";
		}
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

	function get_jabatan_all()
	{
		$query = "SELECT * from m_jabatan where 1";
		if($this->input->get('query'))
		{
			$where = " AND kode like '%".$this->input->get('query')."%' or nama like '%".$this->input->get('query')."%'";
			$query .= $where;
		}
		$rs = $this->db->query($query);
		$data = $rs->result_array();
		echo json_encode(array('total'=>count($data), 'data'=>$data));			
	}
		
	function get_jabatan_struktural()
	{
		$query = "
			SELECT * from m_jabatan where 
			LENGTH(SUBSTRING_INDEX(kode, '.', 1)) = 3		
			";
		if($this->input->get('query'))
		{
			$where = " AND kode like '%".$this->input->get('query')."%' or nama like '%".$this->input->get('query')."%'";
			$query .= $where;
		}
		$rs = $this->db->query($query);
		$data = $rs->result_array();
		echo json_encode(array('total'=>count($data), 'data'=>$data));			
	}
	
	# non_struktural	
	function get_jabatan_non_struktural()
	{
		//select * from m_jabatan where SUBSTRING_INDEX('012.1.2', '.', 1) <> kode REGEXP '[0-9]+';	
		$query = "
			SELECT * from m_jabatan where 
			LENGTH(SUBSTRING_INDEX(kode, '.', 1)) <> 3		
			";
		if($this->input->get('query'))
		{
			$where = " and kode like '%".$this->input->get('query')."%' or nama like '%".$this->input->get('query')."%'";
			$query .= $where;
		}
		$rs = $this->db->query($query);
		$data = $rs->result_array();
		echo json_encode(array('total'=>count($data), 'data'=>$data));		
	}
	
	function non_struktural()
	{
		$this->load->view("non_struktural");
	}
	
	function data_non_struktural()
	{
		$this->load->view("grid_data_entri");
		//echo "grid data jabatan non struktural";
	}
	
	function get_data_isian_jabatan()
	{
		/*id_instansi=009&page=1&start=0&limit=200&sort=id&dir=DESC	*/
		$start = $this->input->get('start') ? $this->input->get('start') : 0;
		$limit = $this->input->get('limit') ? $this->input->get('limit') : 200;
		$query = "
		select
			frm_isian_1_5.id_frm as id, 
			frm_isian_1_5.kode_jabatan,
			frm_isian_1_5.ikhtisar_jabatan,
			frm_isian_1_5.id_instansi,
			m_jabatan.nama as nama_jabatan,
			m_instansi2.nama_panjang as instansi
		from
			frm_isian_1_5
		inner join m_instansi2 on frm_isian_1_5.id_instansi = m_instansi2.kode
		inner join m_jabatan on m_jabatan.kode = frm_isian_1_5.kode_jabatan
		where 1
		";
		$total = $this->db->query($query)->num_rows();
		if($this->input->get('id_instansi'))
		{
			$query .= sprintf(" and frm_isian_1_5.id_instansi='%s'", $this->input->get('id_instansi'));
		}
		if($this->input->get('query'))
		{
			$query .= " and frm_isian_1_5.kode_jabatan like '%".$this->input->get('query')."%' 
						or frm_isian_1_5.id_instansi like '%".$this->input->get('query')."%' 
						or m_jabatan.nama like '%".$this->input->get('query')."%'";
		}
		$query .= " order by frm_isian_1_5.kode_jabatan";		
		$query .= sprintf(" LIMIT %d, %d", $start, $limit);
		$res = $this->db->query($query)->result_array();
		if(count($res))
		{
			echo json_encode(array('total'=>$total, 'data'=>$res));
		} else return false;
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
		
	function _gunzip($file)
	{
		$file_name = $file;
		$buffer_size = 4096;
		// filesize()
		$out_file_name = str_replace('.gz', '', $file_name);
		$file = gzopen($file_name, 'rb');
		$out_file = fopen($out_file_name, 'wb');
		while(!gzeof($file)) {
		    fwrite($out_file, gzread($file, $buffer_size));
		}
		fclose($out_file);
		gzclose($file);
	}

	function _dump($s)
	{
		print('<pre>');
		print_r($s);
		print('</pre>');
	}
	
}

?>