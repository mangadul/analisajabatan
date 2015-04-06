<?php

class main extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('mod_keterampilan');
    }

    public function index() {
        $this->Showketerampilan();
    }

    public function Showketerampilan() {
        $this->load->view('extjs');
        $this->load->view("keterampilan_entry");
    }


    public function DataListketerampilan() {
        $q = $this->input->get('query');
        $sort = $this->input->get('sort');
        $dir = $this->input->get('dir');
        $start = $this->input->get('start');
        $limit = $this->input->get('limit');

        $data = $this->mod_keterampilan->Masterketerampilan($q, $start, $limit);

        foreach ($data[1] as $r)
            $record_count = $r->recordcount;
        echo "{'totalCount':'$record_count', 'keterampilan_data':[";

        foreach ($data[0] as $row) {
            echo "{
                    'id':'$row->id',
                    'keterampilan':'$row->keterampilan'
                 },";
        }
        echo "]}";
    }

    public function Hapusketerampilan() {
        $id = $this->input->post('id');
        $this->mod_keterampilan->Hapusketerampilan($id);
    }
	
    public function Insertketerampilan() {
        $id = $this->input->post('id');
        $keterampilan = $this->input->post('keterampilan');
		
        $this->mod_keterampilan->Hapusketerampilan($id);
		$this->mod_keterampilan->Insertketerampilan($keterampilan);
        
    }
	
	public function Updateketerampilan() {
        $id = $this->input->post('id');
        $keterampilan = $this->input->post('keterampilan');
		
		$this->mod_keterampilan->Updateketerampilan($id, $keterampilan);
	}
	
}

?>