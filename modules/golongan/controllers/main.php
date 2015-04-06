<?php

class main extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('mod_golongan');
    }

    public function index() {
        $this->Showgolongan();
    }

    public function Showgolongan() {
        $this->load->view('extjs');
        $this->load->view("golongan_entry");
    }


    public function DataListgolongan() {
        $q = $this->input->get('query');
        $sort = $this->input->get('sort');
        $dir = $this->input->get('dir');
        $start = $this->input->get('start');
        $limit = $this->input->get('limit');

        $data = $this->mod_golongan->Mastergolongan($q, $start, $limit);

        foreach ($data[1] as $r)
            $record_count = $r->recordcount;
        echo "{'totalCount':'$record_count', 'golongan_data':[";

        foreach ($data[0] as $row) {
            echo "{
                    'id':'$row->id',
                    'golongan':'$row->golongan'
                 },";
        }
        echo "]}";
    }

    public function Hapusgolongan() {
        $id = $this->input->post('id');
        $this->mod_golongan->Hapusgolongan($id);
    }
	
    public function Insertgolongan() {
        $id = $this->input->post('id');
        $golongan = $this->input->post('golongan');
		
        $this->mod_golongan->Hapusgolongan($id);
		$this->mod_golongan->Insertgolongan($golongan);
        
    }
	
	public function Updategolongan() {
        $id = $this->input->post('id');
        $golongan = $this->input->post('golongan');
		
		$this->mod_golongan->Updategolongan($id, $golongan);
	}
	
}

?>