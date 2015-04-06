<?php

class main extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('mod_pegawai');
    }

    public function index() {
        $this->ShowPegawai();
    }

    public function ShowPegawai() {
        $this->load->view('extjs');
        $this->load->view("pegawai_entry");
    }


    public function DataListPegawai() {
        $q = $this->input->get('query');
        $sort = $this->input->get('sort');
        $dir = $this->input->get('dir');
        $start = $this->input->get('start');
        $limit = $this->input->get('limit');

        $data = $this->mod_pegawai->MasterPegawai($q, $start, $limit, $sort, $dir);

        foreach ($data[1] as $r)
            $record_count = $r->recordcount;
        echo "{'totalCount':'$record_count', 'pegawai_data':[";

        foreach ($data[0] as $row) {
            echo "{
                    'id_pegawai':'$row->id_pegawai',
					'nip':'$row->nip',
                    'nama':'$row->nama',
					'nama_jabatan': '$row->nama_jabatan',
					'nama_golongan': '$row->nama_golongan',
					'alamat_asal': '$row->alamat_asal',
					'alamat_sekarang': '$row->alamat_sekarang',
					'kelurahan': '$row->kelurahan',
					'rt': '$row->rt',
					'rw': '$row->rw',
					'no_rmh': '$row->no_rmh',
					'no_telp': '$row->no_telp',
					'kode_pos': '$row->kode_pos',
					'email': '$row->email',
					'agama': '$row->agama',
					'jenis_kelamin': '$row->jenis_kelamin',
					'tempat_lahir': '$row->tempat_lahir',
					'tgl_lahir': '$row->tgl_lahir',
					'bulan_lahir': '$row->bulan_lahir',
					'tahun_lahir': '$row->tahun_lahir',
					'pendidikan_terakhir': '$row->pendidikan_terakhir',
					'pendidikan' : '$row->pendidikan',
					'status_marital': '$row->status_marital',
					'upload': '$row->upload',
					'status_pns': '$row->status_pns',
					'tgl_lahir_gab': '$row->tgl_lahir_gab'
                 },";
        }
        echo "]}";
    }

	
    public function LoadJabatan() {
        $q = $this->input->get('query');
        $data = $this->mod_pegawai->LoadJabatan($q);
        echo "[";
        foreach ($data as $row) {
            echo "{
                    'value':'$row->kode',
                    'name' :'[$row->kode] $row->nama'                    
                 },";
        }
        echo "]";
    }
	
	public function LoadGolongan() {
        $q = $this->input->get('query');
        $data = $this->mod_pegawai->LoadGolongan($q);
        echo "[";
        foreach ($data as $row) {
            echo "{
                    'value':'$row->id',
                    'name' :'$row->golongan'                    
                 },";
        }
        echo "]";
    }
	
	public function LoadPendidikan() {
        $q = $this->input->get('query');
        $data = $this->mod_pegawai->LoadPendidikan($q);
        echo "[";
        foreach ($data as $row) {
            echo "{
                    'value':'$row->id',
                    'name' :'$row->nama'                    
                 },";
        }
        echo "]";
    }
	
	
	public function LoadStatusPNS() {
        $q = $this->input->get('query');
        $data = $this->mod_pegawai->LoadStatusPNS($q);
        echo "[";
        foreach ($data as $row) {
            echo "{
                    'value':'$row->id',
                    'name' :'$row->status_pns'                    
                 },";
        }
        echo "]";
    }
	
	public function HapusPegawai() {
        $id = $this->input->post('id');
        $this->mod_kelurahan->HapusKelurahan($id);
    }
	
    public function InsertPegawai() {
        $id = $this->input->post('id');
		$id_kecamatan = $this->input->post('id_kecamatan');
        $nama = $this->input->post('nama');
        
		$this->mod_kelurahan->HapusKelurahan($id);
		$this->mod_kelurahan->InsertKelurahan($id_kecamatan, $nama);
    }
	
	public function UpdatePegawai() {
        $id = $this->input->post('id');
        $id_kecamatan = $this->input->post('id_kecamatan');
		$nama = $this->input->post('nama');
		
		$this->mod_kelurahan->UpdateKelurahan($id, $id_kecamatan, $nama);
	}
	
}

?>