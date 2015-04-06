<?php

class main extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('mod_tugas_eselon');
    }

    public function index() {
        $this->ShowTugas_Eselon();
    }

    public function ShowTugas_Eselon() {
        $this->load->view('extjs');
        $this->load->view("tugas_eselon_entry");
    }


    public function DataListTugas_Eselon() {
        $q = $this->input->get('query');
        $sort = $this->input->get('sort');
        $dir = $this->input->get('dir');
        $start = $this->input->get('start');
        $limit = $this->input->get('limit');

        $data = $this->mod_tugas_eselon->MasterTugas_Eselon($q, $start, $limit);

        foreach ($data[1] as $r)
            $record_count = $r->recordcount;
        echo "{'totalCount':'$record_count', 'tugas_eselon_data':[";

        foreach ($data[0] as $row) {
            echo "{
                    'id':'$row->id',
					'id_eselon':'$row->id_eselon',
                    'tugas_eselon':'$row->tugas_eselon',
					'eselon': '$row->eselon'
                 },";
        }
        echo "]}";
    }

    
	
    public function LoadEselon() {
        $q = $this->input->get('query');

        $data = $this->mod_tugas_eselon->LoadEselon($q);
        echo "[";
        foreach ($data as $row) {
            echo "{
                    'value':'$row->id',
                    'name' :'$row->eselon'                    
                 },";
        }
        echo "]";
    }
	
	public function HapusTugas_Eselon() {
        $id = $this->input->post('id');
        $this->mod_tugas_eselon->Tugas_Eselon($id);
    }
	
    public function InsertTugas_Eselon() {
        $id = $this->input->post('id');
		$id_kecamatan = $this->input->post('id_eselon');
        $nama = $this->input->post('tugas_eselon');
        
		$this->mod_tugas_eselon->HapusTugas_Eselon($id);
		$this->mod_tugas_eselon->InsertTugas_Eselon($id_eselon, $tugas_eselon);
    }
	
	public function UpdateTugas_Eselon() {
        $id = $this->input->post('id');
        $id_eselon = $this->input->post('id_eselon');
		$nama = $this->input->post('tugas_eselon');
		
		$this->mod_tugas_eselon->UpdateTugas_eselon($id, $id_eselon, $tugas_eselon);
	}
	
}

?>