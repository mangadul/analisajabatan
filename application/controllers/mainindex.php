<?php
/*
 * Author : andika1000@yahoo.com
 * Date   : 02 FEB 2012
 */

 class mainindex extends CI_Controller {
        
    function __construct()
    {
        parent::__construct();
        $this->load->model('Mod_main');
    }
        
    public function index()
    {
	if($this->session->userdata('op_login')){ 
           $data['menu_root'] = $this->Mod_main->MenuRoot();
           $this->load->view('template', $data);
        }
        else { 
            $sessdata = array('op_login'   => '',
                              'lokasi'      => '',
                              'daop'        => '',
                              'jab'         => '',
                              'nama_lokasi' => '',
                              'nipp'        => '',
                              'nama'        => '',
                              'username'    => '');
           $this->session->unset_userdata($sessdata);
           $this->load->view('login');
        }
    }
	
	
    public function login()
    {
        $sessdata = array('op_login'   => '',
                          'lokasi'      => '',
                          'daop'        => '',
                          'jab'         => '',
                          'nama_lokasi' => '',
                          'nipp'        => '',
                          'nama'        => '',
                          'username'    => '');
        $this->session->unset_userdata($sessdata);
        $this->load->view('login');
    }
	
    
    public function logout()
    {
            $sessdata = array('op_login'    => '',
                              'lokasi'      => '',
                              'jab'         => '',
                              'daop'        => '',
                              'nama_lokasi' => '',
                              'nipp'        => '',
                              'nama'        => '',
                              'username'    => '');
            $this->session->set_userdata($sessdata);
            $this->session->sess_destroy();
            echo "<script language = 'JavaScript'>
                    window.location = '".base_url()."index.php/mainindex/login';
                  </script>";
    }
    
    public function process_login($tag)
    {
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $login    = $this->Mod_main->ProcessLogin($username,$password);
        if($login['count']){
            $lokasi = $login['lokasi'];  
            $result = @mysql_query("SELECT nama_stn, daop FROM tabstn WHERE singk = '$lokasi'");
            $row = @mysql_fetch_array($result);
            
            $sessdata = array('op_login'    => '1',
                              'lokasi'      => $lokasi,
                              'daop'        => $row['daop'],
                              'jab'         => $login['jab'],
                              'nama_lokasi' => $row['nama_stn'],
                              'nipp'        => $login['nipp'],
                              'nama'        => $login['nama'],
                              'username'    => $username);
            $this->session->set_userdata($sessdata);
            
            if($tag) echo "1";  
            else {
                if(!$login['tag_pwd']) 
                    echo "<script> window.location = '".base_url()."index.php/mainindex/FormChangePassword'; </script>";
                else echo "<script> window.location = '".base_url()."index.php'; </script>";
            }
           
        } else {
             $sessdata = array('op_login'   => '',
                              'lokasi'      => '',
                              'daop'        => '',
                              'jab'         => '',
                              'nama_lokasi' => '',
                              'nipp'        => '',
                              'nama'        => '',
                              'username'    => '');
            $this->session->unset_userdata($sessdata);
            //$this->session->set_userdata($sessdata);
            if($tag) echo "0"; 
            else echo "<script language = 'JavaScript'>window.location = '".base_url()."index.php?errlog=1';</script>";
        }        
    }
    
    public function FormChangePassword(){
        $data['username'] = $this->session->userdata('username');
        $data['nipp']     = $this->session->userdata('nipp');
        $data['nama']     = $this->session->userdata('nama');
        if($this->session->userdata('op_login')=='' || $this->session->userdata('op_login')=='0')
            echo "<script language = 'JavaScript'>window.location = '".base_url()."index.php';</script>";
        else 
            $this->load->view('login_change_password', $data);
    }
    
    public function ChangePassword(){
        $username = $this->session->userdata('username');
        $password = $this->input->post('password_new');
        $nipp = $this->input->post('nipp');
        $nama = $this->input->post('nama');
        
       /*
$sql = "UPDATE `an_users` SET `password`=MD5('$password'), 
                       `nipp`='$nipp', `nama`='$nama',`tag_change_passwd`='1' 
                WHERE (`username`='$username')";
*/
$sql = "UPDATE `an_users` SET `password`=MD5('$password'),`tag_change_passwd`='1' 
                WHERE (`username`='$username')";

        mysql_query($sql);
        
        echo "<script language = 'JavaScript'>window.location = '".base_url()."index.php/mainindex/logout';</script>";
    }
    
    public function CekLogin(){
        if($this->session->userdata('lokasi'))echo 1;
        else echo 0;
    }
    
    public function JSONMenu($idmenu)
    {
       $menu = $this->Mod_main->MenuTree($idmenu);
       foreach ($menu as $mt) {
           $submenu = $this->Mod_main->MenuTree($mt->id);
           echo "{text:'$mt->title',";
           if($mt->url) echo "id:'$mt->id',";       
           if(count($submenu) > 0){
               echo "expanded: true, children: [";
               $this->JSONMenu($mt->id);
               echo "]";
           } else echo "leaf:true,'url':'URL $mt->title'";
           
           echo "},";        
       }
    }
    
    public function ShowMenu($idmenu)
    {
        echo "{text: '.', children: [";
         $this->JSONMenu($idmenu);
        echo "]}";  
    }
    
    public function ShowTab($idmenu)
    {
        echo "<script>window.location='".$this->Mod_main->GetMenuURL($idmenu)."?random=' + (new Date()).getTime() + Math.floor(Math.random() * 1000000);</script>"; 
    }
    
    public function HeadTemplate()
    {
        $this->load->view('template_head');
    }
    
    public function FooterTemplate()
    {
        $this->load->view('template_footer');
    }
    
    public function LoadDashboard()
    {
        $this->load->view('test'); 
    }
    
 }

?>