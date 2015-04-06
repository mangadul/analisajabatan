<?php

class main extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('mod_sts_pns');
    }

    public function index() {
        $this->Showsts_pns();
    }

    public function Showsts_pns() {
        $this->load->view('extjs');
        $this->load->view("sts_pns_entry");
    }


    public function DataListsts_pns() {
        $q = $this->input->get('query');
        $sort = $this->input->get('sort');
        $dir = $this->input->get('dir');
        $start = $this->input->get('start');
        $limit = $this->input->get('limit');

        $data = $this->mod_sts_pns->Mastersts_pns($q, $start, $limit);

        foreach ($data[1] as $r)
            $record_count = $r->recordcount;
        echo "{'totalCount':'$record_count', 'sts_pns_data':[";

        foreach ($data[0] as $row) {
            echo "{
                    'id':'$row->id',
                    'sts_pns':'$row->sts_pns'
                 },";
        }
        echo "]}";
    }

    public function Hapussts_pns() {
        $id = $this->input->post('id');
        $this->mod_sts_pns->Hapussts_pns($id);
    }
	
    public function Insertsts_pns() {
        $id = $this->input->post('id');
        $sts_pns = $this->input->post('sts_pns');
		
        $this->mod_sts_pns->Hapussts_pns($id);
		$this->mod_sts_pns->Insertsts_pns($sts_pns);
        
    }
	
	public function Updatests_pns() {
        $id = $this->input->post('id');
        $sts_pns = $this->input->post('sts_pns');
		
		$this->mod_sts_pns->Updatests_pns($id, $sts_pns);
	}
	
}

?>