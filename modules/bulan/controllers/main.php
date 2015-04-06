<?php

class main extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('mod_bulan');
    }

    public function index() {
        $this->Showbulan();
    }

    public function Showbulan() {
        $this->load->view('extjs');
        $this->load->view("bulan_entry");
    }


    public function DataListbulan() {
        $q = $this->input->get('query');
        $sort = $this->input->get('sort');
        $dir = $this->input->get('dir');
        $start = $this->input->get('start');
        $limit = $this->input->get('limit');

        $data = $this->mod_bulan->Masterbulan($q, $start, $limit);

        foreach ($data[1] as $r)
            $record_count = $r->recordcount;
        echo "{'totalCount':'$record_count', 'bulan_data':[";

        foreach ($data[0] as $row) {
            echo "{
                    'id':'$row->id',
                    'bulan':'$row->bulan'
                 },";
        }
        echo "]}";
    }

    public function Hapusbulan() {
        $id = $this->input->post('id');
        $this->mod_bulan->Hapusbulan($id);
    }
	
    public function Insertbulan() {
        $id = $this->input->post('id');
        $bulan = $this->input->post('bulan');
		
        $this->mod_bulan->Hapusbulan($id);
		$this->mod_bulan->Insertbulan($bulan);
        
    }
	
	public function Updatebulan() {
        $id = $this->input->post('id');
        $bulan = $this->input->post('bulan');
		
		$this->mod_bulan->Updatebulan($id, $bulan);
	}
	
}

?>