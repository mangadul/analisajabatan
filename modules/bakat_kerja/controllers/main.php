<?php

class main extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('mod_bakat_kerja');
    }

    public function index() {
        $this->Showbakat_kerja();
    }

    public function Showbakat_kerja() {
        $this->load->view('extjs');
        $this->load->view("bakat_kerja_entry");
    }


    public function DataListbakat_kerja() {
        $q = $this->input->get('query');
        $sort = $this->input->get('sort');
        $dir = $this->input->get('dir');
        $start = $this->input->get('start');
        $limit = $this->input->get('limit');

        $data = $this->mod_bakat_kerja->Masterbakat_kerja($q, $start, $limit);

        foreach ($data[1] as $r)
            $record_count = $r->recordcount;
        echo "{'totalCount':'$record_count', 'bakat_kerja_data':[";

        foreach ($data[0] as $row) {
            echo "{
                    'id':'$row->id',
                    'bakat_kerja':'$row->bakat_kerja'
					'kode_bakat':'$row->kode_bakat'
                 },";
        }
        echo "]}";
    }

    public function Hapusbakat_kerja() {
        $id = $this->input->post('id');
        $this->mod_bakat_kerja->Hapusbakat_kerja($id);
    }
	
    public function Insertbakat_kerja() {
        $id = $this->input->post('id');
        $bakat_kerja = $this->input->post('bakat_kerja');
		$kode_bakat = $this->input->post('kode_bakat');
		
        $this->mod_bakat_kerja->Hapusbakat_kerja($id);
		$this->mod_bakat_kerja->Insertbakat_kerja($bakat_kerja, $kode_bakat);
		//$this->mod_bakat_kerja->Insertbakat_kerja($kode_bakat);
        
    }
	
	public function Updatebakat_kerja() {
        $id = $this->input->post('id');
        $bakat_kerja = $this->input->post('bakat_kerja');
		$kode_bakat = $this->input->post('kode_bakat');
		
		$this->mod_bakat_kerja->Updatebakat_kerja($id, $bakat_kerja, $kode_bakat);
	}
	
}

?>