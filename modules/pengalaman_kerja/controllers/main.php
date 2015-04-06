<?php

class main extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('mod_pengalaman_kerja');
    }

    public function index() {
        $this->Showpengalaman_kerja();
    }

    public function Showpengalaman_kerja() {
        $this->load->view('extjs');
        $this->load->view("pengalaman_kerja_entry");
    }


    public function DataListpengalaman_kerja() {
        $q = $this->input->get('query');
        $sort = $this->input->get('sort');
        $dir = $this->input->get('dir');
        $start = $this->input->get('start');
        $limit = $this->input->get('limit');

        $data = $this->mod_pengalaman_kerja->Masterpengalaman_kerja($q, $start, $limit);

        foreach ($data[1] as $r)
            $record_count = $r->recordcount;
        echo "{'totalCount':'$record_count', 'pengalaman_kerja_data':[";

        foreach ($data[0] as $row) {
            echo "{
                    'id':'$row->id',
                    'pengalaman_kerja':'$row->pengalaman_kerja'
                 },";
        }
        echo "]}";
    }

    public function Hapuspengalaman_kerja() {
        $id = $this->input->post('id');
        $this->mod_pengalaman_kerja->Hapuspengalaman_kerja($id);
    }
	
    public function Insertpengalaman_kerja() {
        $id = $this->input->post('id');
        $pengalaman_kerja = $this->input->post('pengalaman_kerja');
		
        $this->mod_pengalaman_kerja->Hapuspengalaman_kerja($id);
		$this->mod_pengalaman_kerja->Insertpengalaman_kerja($pengalaman_kerja);
        
    }
	
	public function Updatepengalaman_kerja() {
        $id = $this->input->post('id');
        $pengalaman_kerja = $this->input->post('pengalaman_kerja');
		
		$this->mod_pengalaman_kerja->Updatepengalaman_kerja($id, $pengalaman_kerja);
	}
	
}

?>