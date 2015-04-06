<?php

class main extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('mod_upaya_fisik');
    }

    public function index() {
        $this->Showupaya_fisik();
    }

    public function Showupaya_fisik() {
        $this->load->view('extjs');
        $this->load->view("upaya_fisik_entry");
    }


    public function DataListupaya_fisik() {
        $q = $this->input->get('query');
        $sort = $this->input->get('sort');
        $dir = $this->input->get('dir');
        $start = $this->input->get('start');
        $limit = $this->input->get('limit');

        $data = $this->mod_upaya_fisik->Masterupaya_fisik($q, $start, $limit);

        foreach ($data[1] as $r)
            $record_count = $r->recordcount;
        echo "{'totalCount':'$record_count', 'upaya_fisik_data':[";

        foreach ($data[0] as $row) {
            echo "{
                    'id':'$row->id',
                    'upaya_fisik':'$row->upaya_fisik'
                 },";
        }
        echo "]}";
    }

    public function Hapusupaya_fisik() {
        $id = $this->input->post('id');
        $this->mod_upaya_fisik->Hapusupaya_fisik($id);
    }
	
    public function Insertupaya_fisik() {
        $id = $this->input->post('id');
        $upaya_fisik = $this->input->post('upaya_fisik');
		
        $this->mod_upaya_fisik->Hapusupaya_fisik($id);
		$this->mod_upaya_fisik->Insertupaya_fisik($upaya_fisik);
        
    }
	
	public function Updateupaya_fisik() {
        $id = $this->input->post('id');
        $upaya_fisik = $this->input->post('upaya_fisik');
		
		$this->mod_upaya_fisik->Updateupaya_fisik($id, $upaya_fisik);
	}
	
}

?>