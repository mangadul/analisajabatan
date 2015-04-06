<?php

class main extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('mod_jenis_jabatan');
    }

    public function index() {
        $this->Showjenis_jabatan();
    }

    public function Showjenis_jabatan() {
        $this->load->view('extjs');
        $this->load->view("jenis_jabatan_entry");
    }


    public function DataListjenis_jabatan() {
        $q = $this->input->get('query');
        $sort = $this->input->get('sort');
        $dir = $this->input->get('dir');
        $start = $this->input->get('start');
        $limit = $this->input->get('limit');

        $data = $this->mod_jenis_jabatan->Masterjenis_jabatan($q, $start, $limit);

        foreach ($data[1] as $r)
            $record_count = $r->recordcount;
        echo "{'totalCount':'$record_count', 'jenis_jabatan_data':[";

        foreach ($data[0] as $row) {
            echo "{
                    'id':'$row->id',
                    'jenis_jabatan':'$row->jenis_jabatan'
                 },";
        }
        echo "]}";
    }

    public function Hapusjenis_jabatan() {
        $id = $this->input->post('id');
        $this->mod_jenis_jabatan->Hapusjenis_jabatan($id);
    }
	
    public function Insertjenis_jabatan() {
        $id = $this->input->post('id');
        $jenis_jabatan = $this->input->post('jenis_jabatan');
		
        $this->mod_jenis_jabatan->Hapusjenis_jabatan($id);
		$this->mod_jenis_jabatan->Insertjenis_jabatan($jenis_jabatan);
        
    }
	
	public function Updatejenis_jabatan() {
        $id = $this->input->post('id');
        $jenis_jabatan = $this->input->post('jenis_jabatan');
		
		$this->mod_jenis_jabatan->Updatejenis_jabatan($id, $jenis_jabatan);
	}
	
}

?>