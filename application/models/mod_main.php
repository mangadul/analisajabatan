<?php

class Mod_main extends CI_Model {
    
    function __construct()
    {
        parent::__construct();
        $this->username = $this->session->userdata('username');
    }    
    
    function MenuRoot(){
        $sql   = "  SELECT A.*
                    FROM   an_modules A , an_group_module B, an_group_user C
                    WHERE  A.id = B.idmodule AND B.idgroup = C.idgroup AND
                           C.iduser = '".$this->username."' AND A.is_root = 1 ORDER BY A.id";
        
        $query = $this->db->query($sql);
        return $query->result();
    }

    function MenuTree($idmenu){
        $this->db->where('parent', $idmenu);
        $this->db->order_by('id');
        $query = $this->db->get('an_modules');

        $sql   = "  SELECT A.*
                    FROM   an_modules A , an_group_module B, an_group_user C
                    WHERE  A.id = B.idmodule AND B.idgroup = C.idgroup AND
                           C.iduser = '".$this->username."' AND A.parent = '$idmenu' ORDER BY A.id";
        
        $query = $this->db->query($sql);
        
        return $query->result();
    }

    function GetMenuURL($idmenu){
        $this->db->where('id', $idmenu);
        $query = $this->db->get('an_modules');
        $query = $query->result();
        
        foreach ($query as $row){ 
            $url = $row->url;
            $tag = $row->tag; 
        }
        
        if($tag)$url = base_url().$url;
            
        return $url; 
    }

    function CekLogin(){
            /*$lokasi = $this->session->userdata('lokasi');
            $uname  = $this->session->userdata('username');
            
            $hostURL = base_url()."index.php/";
            $thisURL = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
            $dbURL   = "index.php/".str_replace($hostURL, '', $thisURL);
            
            $sql   = " SELECT count(*) AS count
                       FROM an_modules AS A
                       INNER JOIN an_group_module AS B ON A.id = B.idmodule
                       INNER JOIN an_group_user AS C ON C.idgroup = B.idgroup
                       WHERE A.tag = '1' AND C.iduser = '$uname' AND A.url = '$dbURL' ";
            $query = $this->db->query($sql)->result();
            $count = 0;
            foreach ($query as $r){
                $count = $r->count;
            }
            if(!$count){
                echo "<script language = 'JavaScript'>
                    window.location = '".base_url()."index.php/login';
                  </script>";
            }*/
            /*ListLapkaKA echo "<script language = 'JavaScript'>
                    window.location = '".base_url()."index.php/login';
                  </script> ";*/
        
    }

    function ProcessLogin($uname,$pass){
        $uname = mysql_real_escape_string($uname);
        $pass = mysql_real_escape_string($pass);
        
        $sql = " SELECT COUNT(*)as count, nipp, nama, lokasi, job, tag_change_passwd tag_pwd FROM an_users
                 WHERE username = '$uname' AND password = md5('$pass') AND active='1'
                 GROUP BY username";
        $query = $this->db->query($sql)->result();
        $data['count'] = 0;
        foreach ($query as $r) {
            $data['count']   =  $r->count;
            $data['nipp']    =  $r->nipp;
            $data['nama']    =  $r->nama;
            $data['lokasi']  =  $r->lokasi;
            $data['jab']     =  $r->job;
            $data['tag_pwd'] =  $r->tag_pwd;
        }
        
        return $data;
    }
}

?>
