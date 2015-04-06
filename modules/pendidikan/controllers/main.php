<?php

class main extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('mod_pendidikan');
    }

    public function index() {
        $this->Showpendidikan();
    }

    public function Showpendidikan() {
        $this->load->view('extjs');
        $this->load->view("pendidikan_entry");
    }


    public function DataListpendidikan() {
        $q = $this->input->get('query');
        $sort = $this->input->get('sort');
        $dir = $this->input->get('dir');
        $start = $this->input->get('start');
        $limit = $this->input->get('limit');

        $data = $this->mod_pendidikan->Masterpendidikan($q, $start, $limit);

        foreach ($data[1] as $r)
            $record_count = $r->recordcount;
        echo "{'totalCount':'$record_count', 'pendidikan_data':[";

        foreach ($data[0] as $row) {
            echo "{
                    'id':'$row->id',
                    'pendidikan':'$row->pendidikan'
                 },";
        }
        echo "]}";
    }

    public function Hapuspendidikan() {
        $id = $this->input->post('id');
        $this->mod_pendidikan->Hapuspendidikan($id);
    }
	
    public function Insertpendidikan() {
        $id = $this->input->post('id');
        $pendidikan = $this->input->post('pendidikan');
		
        $this->mod_pendidikan->Hapuspendidikan($id);
		$this->mod_pendidikan->Insertpendidikan($pendidikan);
        
    }
	
	public function Updatependidikan() {
        $id = $this->input->post('id');
        $pendidikan = $this->input->post('pendidikan');
		
		$this->mod_pendidikan->Updatependidikan($id, $pendidikan);
	}
	
}

?>