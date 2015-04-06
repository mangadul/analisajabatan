<?php
/*
 * Author : andika1000@yahoo.com
 * Date   : 02 FEB 2012
 */

 class global_data extends CI_Controller {
        
    function __construct()
    {
        parent::__construct();
        $this->load->model('mod_global_data');
    }
        
    public function index(){
       
    }
    
    public function json_ka(){
        $q = $this->input->get('query');
        $data = $this->mod_global_data->query_ka($q);
        echo '[';
        foreach ($data as $row){
        echo "{
              'value': '$row->ka_no', 
              'name':  '$row->ka_nm',
              'display':  '$row->ka_nm ($row->ka_no)'
            },";
        }
        echo ']';    
    }
    
    public function json_penugasan_kru(){
       $jab   = $this->input->get('jabsap');
       $data  = $this->mod_global_data->query_penugasan_kru($jab);
       echo json_encode($data);
    }    
    
    public function json_sap_pegawai()
    {
       $q      = $this->input->get('query');
	   $q 	   = mysql_real_escape_string($q);
       $data   = $this->mod_global_data->query_sap_pegawai($q);
       echo json_encode($data);
    }
    
    
    
    
 }

?>