<?php

class main extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('mod_status_marital');
    }

    public function index() {
        $this->Showstatus_marital();
    }

    public function Showstatus_marital() {
        $this->load->view('extjs');
        $this->load->view("status_marital_entry");
    }


    public function DataListstatus_marital() {
        $q = $this->input->get('query');
        $sort = $this->input->get('sort');
        $dir = $this->input->get('dir');
        $start = $this->input->get('start');
        $limit = $this->input->get('limit');

        $data = $this->mod_status_marital->Masterstatus_marital($q, $start, $limit);

        foreach ($data[1] as $r)
            $record_count = $r->recordcount;
        echo "{'totalCount':'$record_count', 'status_marital_data':[";

        foreach ($data[0] as $row) {
            echo "{
                    'id':'$row->id',
                    'status_marital':'$row->status_marital'
                 },";
        }
        echo "]}";
    }

    public function Hapusstatus_marital() {
        $id = $this->input->post('id');
        $this->mod_status_marital->Hapusstatus_marital($id);
    }
	
    public function Insertstatus_marital() {
        $id = $this->input->post('id');
        $status_marital = $this->input->post('status_marital');
		
        $this->mod_status_marital->Hapusstatus_marital($id);
		$this->mod_status_marital->Insertstatus_marital($status_marital);
        
    }
	
	public function Updatestatus_marital() {
        $id = $this->input->post('id');
        $status_marital = $this->input->post('status_marital');
		
		$this->mod_status_marital->Updatestatus_marital($id, $status_marital);
	}
	
}

?>