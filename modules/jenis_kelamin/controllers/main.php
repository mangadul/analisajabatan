<?php

class main extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('mod_jenis_kelamin');
    }

    public function index() {
        $this->Showjenis_kelamin();
    }

    public function Showjenis_kelamin() {
        $this->load->view('extjs');
        $this->load->view("jenis_kelamin_entry");
    }


    public function DataListjenis_kelamin() {
        $q = $this->input->get('query');
        $sort = $this->input->get('sort');
        $dir = $this->input->get('dir');
        $start = $this->input->get('start');
        $limit = $this->input->get('limit');

        $data = $this->mod_jenis_kelamin->Masterjenis_kelamin($q, $start, $limit);

        foreach ($data[1] as $r)
            $record_count = $r->recordcount;
        echo "{'totalCount':'$record_count', 'jenis_kelamin_data':[";

        foreach ($data[0] as $row) {
            echo "{
                    'id':'$row->id',
                    'jenis_kelamin':'$row->jenis_kelamin'
                 },";
        }
        echo "]}";
    }

    public function Hapusjenis_kelamin() {
        $id = $this->input->post('id');
        $this->mod_jenis_kelamin->Hapusjenis_kelamin($id);
    }
	
    public function Insertjenis_kelamin() {
        $id = $this->input->post('id');
        $jenis_kelamin = $this->input->post('jenis_kelamin');
		
        $this->mod_jenis_kelamin->Hapusjenis_kelamin($id);
		$this->mod_jenis_kelamin->Insertjenis_kelamin($jenis_kelamin);
        
    }
	
	public function Updatejenis_kelamin() {
        $id = $this->input->post('id');
        $jenis_kelamin = $this->input->post('jenis_kelamin');
		
		$this->mod_jenis_kelamin->Updatejenis_kelamin($id, $jenis_kelamin);
	}
	
}

?>