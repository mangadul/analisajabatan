<?php

class main extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('mod_pangkat');
    }

    public function index() {
        $this->Showpangkat();
    }

    public function Showpangkat() {
        $this->load->view('extjs');
        $this->load->view("pangkat_entry");
    }


    public function DataListpangkat() {
        $q = $this->input->get('query');
        $sort = $this->input->get('sort');
        $dir = $this->input->get('dir');
        $start = $this->input->get('start');
        $limit = $this->input->get('limit');

        $data = $this->mod_pangkat->Masterpangkat($q, $start, $limit);

        foreach ($data[1] as $r)
            $record_count = $r->recordcount;
        echo "{'totalCount':'$record_count', 'pangkat_data':[";

        foreach ($data[0] as $row) {
            echo "{
                    'id':'$row->id',
                    'pangkat':'$row->pangkat'
                 },";
        }
        echo "]}";
    }

    public function Hapuspangkat() {
        $id = $this->input->post('id');
        $this->mod_pangkat->Hapuspangkat($id);
    }
	
    public function Insertpangkat() {
        $id = $this->input->post('id');
        $pangkat = $this->input->post('pangkat');
		
        $this->mod_pangkat->Hapuspangkat($id);
		$this->mod_pangkat->Insertpangkat($pangkat);
        
    }
	
	public function Updatepangkat() {
        $id = $this->input->post('id');
        $pangkat = $this->input->post('pangkat');
		
		$this->mod_pangkat->Updatepangkat($id, $pangkat);
	}
	
}

?>