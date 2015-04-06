<?php

class main extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('mod_pengetahuan');
    }

    public function index() {
        $this->Showpengetahuan();
    }

    public function Showpengetahuan() {
        $this->load->view('extjs');
        $this->load->view("pengetahuan_entry");
    }


    public function DataListpengetahuan() {
        $q = $this->input->get('query');
        $sort = $this->input->get('sort');
        $dir = $this->input->get('dir');
        $start = $this->input->get('start');
        $limit = $this->input->get('limit');

        $data = $this->mod_pengetahuan->Masterpengetahuan($q, $start, $limit);

        foreach ($data[1] as $r)
            $record_count = $r->recordcount;
        echo "{'totalCount':'$record_count', 'pengetahuan_data':[";

        foreach ($data[0] as $row) {
            echo "{
                    'id':'$row->id',
                    'pengetahuan':'$row->pengetahuan'
                 },";
        }
        echo "]}";
    }

    public function Hapuspengetahuan() {
        $id = $this->input->post('id');
        $this->mod_pengetahuan->Hapuspengetahuan($id);
    }
	
    public function Insertpengetahuan() {
        $id = $this->input->post('id');
        $pengetahuan = $this->input->post('pengetahuan');
		
        $this->mod_pengetahuan->Hapuspengetahuan($id);
		$this->mod_pengetahuan->Insertpengetahuan($pengetahuan);
        
    }
	
	public function Updatepengetahuan() {
        $id = $this->input->post('id');
        $pengetahuan = $this->input->post('pengetahuan');
		
		$this->mod_pengetahuan->Updatepengetahuan($id, $pengetahuan);
	}
	
}

?>