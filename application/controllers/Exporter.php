<?php
/*
 * Author : andika1000@yahoo.com
 * Date   : 02 FEB 2012
 */

 class Exporter extends CI_Controller {
        
    function __construct()
    {
        parent::__construct();
        //$this->load->model('Mod_main');
    }
    
    //public $col;
        
    public function index()
    {
        $this->ExportExcel();
    }
    

    public function ExportExcel()
    {
        $data['judul_form']  = $this->input->post('judul_form');
        $data['params']  = $this->input->post('params');
        $data['url']     = $this->input->post('url');
        $data['idname']  = $this->input->post('idname');
        
        $data['sort']  = $this->input->post('sort');
        $data['dir']  = $this->input->post('dir');
        
        $data['lokasi']  = $this->session->userdata('lokasi');
        $data['url']     = substr($data['url'], 1,  (strlen($data['url'])-2) );
        
        $ColHeader = $this->CreateTable(json_decode($this->input->post('columns')),0,1,1);
        
        $data['tableHeader'] = $ColHeader[0];
        
        $index = $this->GetIndexId(json_decode($this->input->post('columns')));
        
        $data['indexId'] = substr($index, 0, (strlen($index)-1));
        $data['indexId'] = explode(',', $data['indexId']);
        
        $this->load->view('exporter/excel',$data);
    }

    
    public function PrintForm()
    {
        $data['judul_form'] = $this->input->post('judul_form');
        $data['params']     = $this->input->post('params');
        $data['url']        = $this->input->post('url');
        $data['columns']    = $this->input->post('columns');
        $data['sort']       = $this->input->post('sort');
        $data['dir']        = $this->input->post('dir');
        
        $this->load->view('extjs');
        $this->load->view('exporter/form_print',$data);
    }

    public function ExportPrintPreview()
    {
        $data['judul_form']  = $this->input->post('judul_form');
        $data['params']  = $this->input->post('params');
        $data['url']     = $this->input->post('url');
        
        $data['sort']  = $this->input->post('sort');
        $data['dir']  = $this->input->post('dir');
        
        $data['lokasi']  = $this->session->userdata('lokasi');
        $data['url']     = substr($data['url'], 1,  (strlen($data['url'])-2) );
        
        $ColHeader = $this->CreateTable(json_decode($this->input->post('columns')),0,1,1);
        
        $data['tableHeader'] = $ColHeader[0];
        
        $index = $this->GetIndexId(json_decode($this->input->post('columns')));
        
        $data['indexId'] = substr($index, 0, (strlen($index)-1));
        $data['indexId'] = explode(',', $data['indexId']);
        
        $this->load->view('extjs');
        $this->load->view('exporter/print_preview',$data);
        //echo 'OKEY';
    }

    

    
    
    // FUNGSI MEMBUAT COLUMN HEADER
    function GetIndexId($data){
        $outIndex = "";
        foreach ($data as $hname => $hvalue) {
            if($hname=='column'){
                for($i=0; $i<count($hvalue); $i++){
                    foreach ($hvalue[$i] as $dname=>$dvalue) {
                        if($dname=='column') $outIndex .= $this->GetIndexId($hvalue[$i]);
                        else if($dname=='id') $outIndex .= "$dvalue,";
                    }
                }
            }
        }
        return $outIndex;        
    } 

    
    function CekRowSpan($data,$level){
        $level = $level+1;
        foreach ($data as $hname => $hvalue) {
            if($hname=='column'){
                for($i=0; $i<count($hvalue); $i++){
                    foreach ($hvalue[$i] as $dname=>$dvalue) {
                        if($dname=='column'){
                            //echo ">>";
                            $level = $this->CekRowSpan($hvalue[$i],$level);
                        }
                    }
                }
            }
        }
        //echo $level;
        return $level;        
    } 

    
    
    function CreateTable($data,$level,$trBegin,$trEnd){
        $level++; $x=0; $subdata[0]='';
        $id = $text = $width = '';
        $txtPrint = '';
        $idPrint = '';
        $txtPrintOut[0] = '';
        $txtPrintOut[1] = '';
        
        if($trBegin) $txtPrint .= "<tr>";
        
        foreach ($data as $hname => $hvalue) {
            if($hname=='column'){
                for($i=0; $i<count($hvalue); $i++){
                    $tagCol = $tagRow = 0;
                    foreach ($hvalue[$i] as $dname=>$dvalue) {
                        if($dname=='column'){
                            $subdata[$x] = $hvalue[$i]; $x++;
                            $tagCol = count($dvalue);
                        }
                        else {
                            if($dname=='text') $text = $dvalue;
                            else if($dname=='id'){
                                $id = $dvalue;
                                $idPrint .= "$dvalue,";
                            }
                            else if($dname=='width') $width = $dvalue;
                            //$tagRow = $this->CekRowSpan($hvalue[$i],0);
                            //echo "<br>$dname : $dvalue";
                        }
                    }
                    if($tagCol > 0) $txtPrint .= "<td colspan='$tagCol'>$text</td>";
                    else $txtPrint .= "<td rowspan='???' style='width: $width px'>$text</td>";
                }
            }
        }
        if($tagRow < $this->CekRowSpan($data,0)){ 
            $tagRow = $this->CekRowSpan($data,0);
        }
        if($trEnd) $txtPrint .= "</tr>";
        $txtPrintOut[0] .= str_replace("rowspan='???'", "rowspan='$tagRow'", $txtPrint);
        $txtPrintOut[1] .= $idPrint;
        for($i=0; $i<count($subdata); $i++){
            $trB = $trS = 0;
            if($i==0)$trS=1; if($i==(count($subdata)-1))$trB=1;
            if($subdata[$i]){
                $out = $this->CreateTable($subdata[$i],$level,$trS,$trB);
                $txtPrintOut[0] .= $out[0];
                $txtPrintOut[1] .= $out[1];
            }
        }
        return $txtPrintOut;
    } 
    
    
 }

?>