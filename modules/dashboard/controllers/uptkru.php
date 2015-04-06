<?php
/*
 * Author : andika1000@yahoo.com
 * Date   : 29 AGUSTUS 2012
 */

 class uptkru extends CI_Controller {
        
    function __construct()
    {
        parent::__construct();
    }
        
    public function index(){        
           
        $this->load->view("uptkru");
    }
    
 }

?>
