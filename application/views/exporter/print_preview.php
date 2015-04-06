<?php
    $r = new HttpRequest($url, HttpRequest::METH_GET);
    
    foreach (json_decode($params) as $name => $value) {
       $r->addQueryData(array($name=>$value));
    }  
    $r->addQueryData(array('lokasi'=>$lokasi));
    $r->addQueryData(array('sort'=>$sort));
    $r->addQueryData(array('dir'=>$dir));
    try {
        $data = $r->send()->getBody();
    } 
    catch (HttpException $ex) {
        echo $ex;
    }
    
 
?>
<Style type='text/css' media='print'>

/* body{width : 200px} */

</Style>
<style>

    body{
        padding: 10px;
    }
    table{
        border: 1px solid #CCCCCC;
        border-top: 0px solid #CCCCCC;
        border-right: 0px solid #CCCCCC;
    }
    td{
        border-top: 1px solid #CCCCCC;
        border-right: 1px solid #CCCCCC;
        font-family: Arial,calibri,sans-serif;
        font-size: 12px;
        padding: 4px
    }
    table thead {
        text-align: center;
        font-weight: bold;
        background: rgb(122,188,255); /* Old browsers */
        background: -moz-linear-gradient(top,  rgba(122,188,255,1) 0%, rgba(96,171,248,1) 44%, rgba(64,150,238,1) 100%); /* FF3.6+ */
        background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(122,188,255,1)), color-stop(44%,rgba(96,171,248,1)), color-stop(100%,rgba(64,150,238,1))); /* Chrome,Safari4+ */
        background: -webkit-linear-gradient(top,  rgba(122,188,255,1) 0%,rgba(96,171,248,1) 44%,rgba(64,150,238,1) 100%); /* Chrome10+,Safari5.1+ */
        background: -o-linear-gradient(top,  rgba(122,188,255,1) 0%,rgba(96,171,248,1) 44%,rgba(64,150,238,1) 100%); /* Opera 11.10+ */
        background: -ms-linear-gradient(top,  rgba(122,188,255,1) 0%,rgba(96,171,248,1) 44%,rgba(64,150,238,1) 100%); /* IE10+ */
        background: linear-gradient(to bottom,  rgba(122,188,255,1) 0%,rgba(96,171,248,1) 44%,rgba(64,150,238,1) 100%); /* W3C */
        filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#7abcff', endColorstr='#4096ee',GradientType=0 ); /* IE6-9 */

    }
</style>

<center><?="<h1>$judul_form</h1>"?></center>

<table width="100%" cellspacing="0" cellpadding="0">
    <thead>
    <?= $tableHeader ?>
    </thead>
    <tbody>
    <?php
         function LoadData($data,$indexId){
             foreach ($data as $name=>$value) {
                if($name=='data'){ 
                    for($i=0; $i<count($value); $i++){
                        echo "<tr>";
                        for($j=0;$j<count($indexId);$j++){
                            echo "<td>".$value[$i]->$indexId[$j]."</td>";
                        }  
                        echo "</tr>";
                    }
                }
             }
                 
        } 

        LoadData(json_decode($data),$indexId);
    ?>
    </tbody>
</table>

