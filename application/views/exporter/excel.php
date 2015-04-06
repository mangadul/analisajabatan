<?php
    
    $filename ="excelreport.xls";
    $contents = "testdata1 \t testdata2 \t testdata3 \t \n";
    header('Content-type: application/ms-excel');
    header('Content-Disposition: attachment; filename='.$filename);
    

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

<?="<h3>$judul_form</h3>"?>

<table width="100%" border="1">
    <?= $tableHeader ?>
    
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
    
</table>

