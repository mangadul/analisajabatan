<?php

class main extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('mod_minat_kerja');
    }

    public function index() {
        $this->Showminat_kerja();
    }

    public function Showminat_kerja() {
        $this->load->view('extjs');
        $this->load->view("minat_kerja_entry");
    }


    public function DataListminat_kerja() {
        $q = $this->input->get('query');
        $sort = $this->input->get('sort');
        $dir = $this->input->get('dir');
        $start = $this->input->get('start');
        $limit = $this->input->get('limit');

        $data = $this->mod_minat_kerja->Masterminat_kerja($q, $start, $limit);

        foreach ($data[1] as $r)
            $record_count = $r->recordcount;
        echo "{'totalCount':'$record_count', 'minat_kerja_data':[";

        foreach ($data[0] as $row) {
            echo "{
                    'id':'$row->id',
                    'minat_kerja':'$row->minat_kerja'
                 },";
        }
        echo "]}";
    }

    public function Hapusminat_kerja() {
        $id = $this->input->post('id');
        $this->mod_minat_kerja->Hapusminat_kerja($id);
    }
	
    public function Insertminat_kerja() {
        $id = $this->input->post('id');
        $minat_kerja = $this->input->post('minat_kerja');
		
        $this->mod_minat_kerja->Hapusminat_kerja($id);
		$this->mod_minat_kerja->Insertminat_kerja($minat_kerja);
        
    }
	
	public function Updateminat_kerja() {
        $id = $this->input->post('id');
        $minat_kerja = $this->input->post('minat_kerja');
		
		$this->mod_minat_kerja->Updateminat_kerja($id, $minat_kerja);
	}
	
}

?>