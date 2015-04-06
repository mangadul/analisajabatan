<?php

class main extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('mod_eselon');
    }

    public function index() {
        $this->Showeselon();
    }

    public function Showeselon() {
        $this->load->view('extjs');
        $this->load->view("eselon_entry");
    }


    public function DataListeselon() {
        $q = $this->input->get('query');
        $sort = $this->input->get('sort');
        $dir = $this->input->get('dir');
        $start = $this->input->get('start');
        $limit = $this->input->get('limit');

        $data = $this->mod_eselon->Mastereselon($q, $start, $limit);

        foreach ($data[1] as $r)
            $record_count = $r->recordcount;
        echo "{'totalCount':'$record_count', 'eselon_data':[";

        foreach ($data[0] as $row) {
            echo "{
                    'id':'$row->id',
                    'eselon':'$row->eselon'
                 },";
        }
        echo "]}";
    }

    public function Hapuseselon() {
        $id = $this->input->post('id');
        $this->mod_eselon->Hapuseselon($id);
    }
	
    public function Inserteselon() {
        $id = $this->input->post('id');
        $eselon = $this->input->post('eselon');
		
        $this->mod_eselon->Hapuseselon($id);
		$this->mod_eselon->Inserteselon($eselon);
        
    }
	
	public function Updateeselon() {
        $id = $this->input->post('id');
        $eselon = $this->input->post('eselon');
		
		$this->mod_eselon->Updateeselon($id, $eselon);
	}
	
}

?>