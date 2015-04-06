<?php

class main extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('mod_kelurahan');
    }

    public function index() {
        $this->ShowKelurahan();
    }

    public function ShowKelurahan() {
        $this->load->view('extjs');
        $this->load->view("kelurahan_entry");
    }


    public function DataListKelurahan() {
        $q = $this->input->get('query');
        $sort = $this->input->get('sort');
        $dir = $this->input->get('dir');
        $start = $this->input->get('start');
        $limit = $this->input->get('limit');

        $data = $this->mod_kelurahan->MasterKelurahan($q, $start, $limit);

        foreach ($data[1] as $r)
            $record_count = $r->recordcount;
        echo "{'totalCount':'$record_count', 'kelurahan_data':[";

        foreach ($data[0] as $row) {
            echo "{
                    'id':'$row->id',
					'id_kecamatan':'$row->id_kecamatan',
                    'nama':'$row->nama',
					'nama_kecamatan': '$row->nama_kecamatan'
                 },";
        }
        echo "]}";
    }

    
	
    public function LoadKecamatan() {
        $q = $this->input->get('query');

        $data = $this->mod_kelurahan->LoadKecamatan($q);
        echo "[";
        foreach ($data as $row) {
            echo "{
                    'value':'$row->id',
                    'name' :'$row->nama'                    
                 },";
        }
        echo "]";
    }
	
	public function HapusKelurahan() {
        $id = $this->input->post('id');
        $this->mod_kelurahan->HapusKelurahan($id);
    }
	
    public function InsertKelurahan() {
        $id = $this->input->post('id');
		$id_kecamatan = $this->input->post('id_kecamatan');
        $nama = $this->input->post('nama');
        
		$this->mod_kelurahan->HapusKelurahan($id);
		$this->mod_kelurahan->InsertKelurahan($id_kecamatan, $nama);
    }
	
	public function UpdateKelurahan() {
        $id = $this->input->post('id');
        $id_kecamatan = $this->input->post('id_kecamatan');
		$nama = $this->input->post('nama');
		
		$this->mod_kelurahan->UpdateKelurahan($id, $id_kecamatan, $nama);
	}
	
}

?>