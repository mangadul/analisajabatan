<?php

class main extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('mod_kecamatan');
    }

    public function index() {
        $this->ShowKecamatan();
    }

    public function ShowKecamatan() {
        $this->load->view('extjs');
        $this->load->view("kecamatan_entry");
    }


    public function DataListKecamatan() {
        $q = $this->input->get('query');
        $sort = $this->input->get('sort');
        $dir = $this->input->get('dir');
        $start = $this->input->get('start');
        $limit = $this->input->get('limit');

        $data = $this->mod_kecamatan->MasterKecamatan($q, $start, $limit);

        foreach ($data[1] as $r)
            $record_count = $r->recordcount;
        echo "{'totalCount':'$record_count', 'nama_data':[";

        foreach ($data[0] as $row) {
            echo "{
                    'id':'$row->id',
                    'nama':'$row->nama'
                 },";
        }
        echo "]}";
    }

    public function HapusKecamatan() {
        $id = $this->input->post('id');
        $this->mod_kecamatan->HapusKecamatan($id);
    }
	
    public function InsertKecamatan() {
        $id = $this->input->post('id');
        $nama = $this->input->post('nama');
		
        $this->mod_kecamatan->HapusKecamatan($id);
		$this->mod_kecamatan->InsertKecamatan($nama);
        
    }
	
	public function UpdateKecamatan() {
        $id = $this->input->post('id');
        $nama = $this->input->post('nama');
		
		$this->mod_kecamatan->UpdateKecamatan($id, $nama);
	}
	
}

?>