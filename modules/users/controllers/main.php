<?php

class main extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('mod_user');
    }

    public function index() {
        $this->ShowUser();
    }

    public function ShowUser() {
        $this->load->view('extjs');
        $this->load->view("_entry");
    }


    public function DataListUser() {
        $q = $this->input->get('query');
        $sort = $this->input->get('sort');
        $dir = $this->input->get('dir');
        $start = $this->input->get('start');
        $limit = $this->input->get('limit');

        $data = $this->mod_user->MasterUser($q, $start, $limit);

        foreach ($data[1] as $r)
            $record_count = $r->recordcount;
        echo "{'totalCount':'$record_count', 'user_data':[";

        foreach ($data[0] as $row) {
            echo "{
                    'username':'$row->username',
                    'password':'$row->password',
                    'kel_user':'$row->kel_user',
                    'nipp':'$row->nipp',
                    'nama':'$row->nama',
                    'lokasi':'$row->lokasi',
                    'keterangan':'$row->keterangan',
                    'ip':'$row->ip',
                    'active':'$row->active',
                    'job':'$row->job',
                    'tag_change_passwd':'$row->tag_change_passwd'                  
                 },";
        }
        echo "]}";
    }

    public function HapusUser() {
        $username = $this->input->post('username');
        $this->mod_user->HapusUser($username);
    }

    public function HapusUserForm() {
        $username = $this->input->post('username');
        $this->mod_user->HapusUser($username);
    }

    public function LoadUserForm() {
        $username = $this->input->get('username');
        $data = $this->mod_user->LoadUserForm($username);

        echo "{'user_data':[";

        foreach ($data as $row) {
            echo "{
                    'username':'$row->username',
                    'password':'$row->password',
                    'kel_user':'$row->kel_user',
                    'nipp':'$row->nipp',
                    'nama':'$row->nama',
                    'lokasi':'$row->lokasi',
                    'keterangan':'$row->keterangan',
                    'ip':'$row->ip',
                    'active':'$row->active',
                    'job':'$row->job',
                    'tag_change_passwd':'$row->tag_change_passwd'
                 },";
        }
        echo "]}";
    }

    public function LoadKA() {
        $q = $this->input->get('query');

        $data = $this->mod_o18->LoadKA($q);
        echo "[";
        foreach ($data as $row) {
            echo "{
                    'value':'$row->ka_no',
                    'name':'($row->ka_no) - $row->ka_nm'                    
                 },";
        }
        echo "]";
    }

    public function LoadKelSarana() {
        $q = $this->input->get('query');

        $data = $this->mod_o18->LoadKelSarana($q);
        echo "[";
        foreach ($data as $row) {
            echo "{
                    'value':'$row->kel_sarana',
                    'name' :'$row->kel_sarana'                    
                 },";
        }
        echo "]";
    }

    public function InsertUser() {
        $no_ka = $this->input->post('ka_no');
        $data = $this->input->post('data');

        $obj = json_decode($data);

        $this->mod_o18->HapusO18Form($no_ka);
        for ($j = 0; $j < count($obj); $j++) {
            $this->mod_o18->InsertO18($no_ka, $obj[$j]);
        }
    }
}

?>