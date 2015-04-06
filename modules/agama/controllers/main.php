<?php

class main extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('mod_agama');
    }

    public function index() {
        $this->ShowAgama();
    }

    public function ShowAgama() {
        $this->load->view('extjs');
        $this->load->view("agama_entry");
    }


    public function DataListAgama() {
        $q = $this->input->get('query');
        $sort = $this->input->get('sort');
        $dir = $this->input->get('dir');
        $start = $this->input->get('start');
        $limit = $this->input->get('limit');

        $data = $this->mod_agama->MasterAgama($q, $start, $limit);

        foreach ($data[1] as $r)
            $record_count = $r->recordcount;
        echo "{'totalCount':'$record_count', 'agama_data':[";

        foreach ($data[0] as $row) {
            echo "{
                    'id':'$row->id',
                    'agama':'$row->agama'
                 },";
        }
        echo "]}";
    }

    public function HapusAgama() {
        $id = $this->input->post('id');
        $this->mod_agama->HapusAgama($id);
    }
	
    public function InsertAgama() {
        $id = $this->input->post('id');
        $agama = $this->input->post('agama');
		
        $this->mod_agama->HapusAgama($id);
		$this->mod_agama->InsertAgama($agama);
        
    }
	
	public function UpdateAgama() {
        $id = $this->input->post('id');
        $agama = $this->input->post('agama');
		
		$this->mod_agama->UpdateAgama($id, $agama);
	}
	
}

?>