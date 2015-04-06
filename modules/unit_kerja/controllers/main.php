<?php

class main extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('mod_unit_kerja');
    }

    public function index() {
        $this->Showunit_kerja();
    }

    public function Showunit_kerja() {
        $this->load->view('extjs');
        $this->load->view("unit_kerja_entry");
    }


    public function DataListunit_kerja() {
        $q = $this->input->get('query');
        $sort = $this->input->get('sort');
        $dir = $this->input->get('dir');
        $start = $this->input->get('start');
        $limit = $this->input->get('limit');

        $data = $this->mod_unit_kerja->Masterunit_kerja($q, $start, $limit);

        foreach ($data[1] as $r)
            $record_count = $r->recordcount;
        echo "{'totalCount':'$record_count', 'unit_kerja_data':[";

        foreach ($data[0] as $row) {
            echo "{
                    'id':'$row->id',
                    'unit_kerja':'$row->unit_kerja'
                 },";
        }
        echo "]}";
    }

    public function Hapusunit_kerja() {
        $id = $this->input->post('id');
        $this->mod_unit_kerja->Hapusunit_kerja($id);
    }
	
    public function Insertunit_kerja() {
        $id = $this->input->post('id');
        $unit_kerja = $this->input->post('unit_kerja');
		
        $this->mod_unit_kerja->Hapusunit_kerja($id);
		$this->mod_unit_kerja->Insertunit_kerja($unit_kerja);
        
    }
	
	public function Updateunit_kerja() {
        $id = $this->input->post('id');
        $unit_kerja = $this->input->post('unit_kerja');
		
		$this->mod_unit_kerja->Updateunit_kerja($id, $unit_kerja);
	}
	
}

?>