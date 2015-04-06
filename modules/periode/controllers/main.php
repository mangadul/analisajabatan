<?php

class main extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('mod_periode');
    }

    public function index() {
        $this->Showperiode();
    }

    public function Showperiode() {
        $this->load->view('extjs');
        $this->load->view("periode_entry");
    }


    public function DataListperiode() {
        $q = $this->input->get('query');
        $sort = $this->input->get('sort');
        $dir = $this->input->get('dir');
        $start = $this->input->get('start');
        $limit = $this->input->get('limit');

        $data = $this->mod_periode->Masterperiode($q, $start, $limit);

        foreach ($data[1] as $r)
            $record_count = $r->recordcount;
        echo "{'totalCount':'$record_count', 'periode_data':[";

        foreach ($data[0] as $row) {
            echo "{
                    'id':'$row->id',
                    'periode':'$row->periode'
                 },";
        }
        echo "]}";
    }

    public function Hapusperiode() {
        $id = $this->input->post('id');
        $this->mod_periode->Hapusperiode($id);
    }
	
    public function Insertperiode() {
        $id = $this->input->post('id');
        $periode = $this->input->post('periode');
		
        $this->mod_periode->Hapusperiode($id);
		$this->mod_periode->Insertperiode($periode);
        
    }
	
	public function Updateperiode() {
        $id = $this->input->post('id');
        $periode = $this->input->post('periode');
		
		$this->mod_periode->Updateperiode($id, $periode);
	}
	
}

?>