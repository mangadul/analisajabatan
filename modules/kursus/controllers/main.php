<?php

class main extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('mod_kursus');
    }

    public function index() {
        $this->Showkursus();
    }

    public function Showkursus() {
        $this->load->view('extjs');
        $this->load->view("kursus_entry");
    }


    public function DataListkursus() {
        $q = $this->input->get('query');
        $sort = $this->input->get('sort');
        $dir = $this->input->get('dir');
        $start = $this->input->get('start');
        $limit = $this->input->get('limit');

        $data = $this->mod_kursus->Masterkursus($q, $start, $limit);

        foreach ($data[1] as $r)
            $record_count = $r->recordcount;
        echo "{'totalCount':'$record_count', 'kursus_data':[";

        foreach ($data[0] as $row) {
            echo "{
                    'id':'$row->id',
                    'kursus':'$row->kursus'
                 },";
        }
        echo "]}";
    }

    public function Hapuskursus() {
        $id = $this->input->post('id');
        $this->mod_kursus->Hapuskursus($id);
    }
	
    public function Insertkursus() {
        $id = $this->input->post('id');
        $kursus = $this->input->post('kursus');
		
        $this->mod_kursus->Hapuskursus($id);
		$this->mod_kursus->Insertkursus($kursus);
        
    }
	
	public function Updatekursus() {
        $id = $this->input->post('id');
        $kursus = $this->input->post('kursus');
		
		$this->mod_kursus->Updatekursus($id, $kursus);
	}
	
}

?>