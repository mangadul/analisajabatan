<script>
    function ExtractColumns(col){
        var jsonText='"column":[';
        for(var i=0; i<col.length; i++){
            if(i > 0) jsonText += ','; 
            jsonText += '{';
            jsonText += '"text":"'+col[i].text+'",';
            if(col[i].dataIndex){
                jsonText += '"id":"'+col[i].dataIndex+'",';
                jsonText += '"width":"'+col[i].width+'"';
            } else {
                jsonText += ExtractColumns(col[i].initialConfig.columns);
            }
            jsonText += '}';
        }
        jsonText += ']';
        return jsonText;
    }
    
    function ExportExcel(grid){
        //console.log(grid.store.sorters.items[0]);
        
        document.getElementById("params").value  = Ext.JSON.encode(grid.store.proxy.extraParams);
        document.getElementById("url").value     = Ext.JSON.encode(grid.store.proxy.url);
        document.getElementById("columns").value = "{"+ExtractColumns(grid.columns)+"}";
        document.getElementById("judul_form").value = grid.title;
        document.getElementById("sort").value = grid.store.sorters.items[0].property;
        document.getElementById("dir").value = grid.store.sorters.items[0].direction;
        
        document.getElementById("ExporterForm").action = '<?=base_url()?>index.php/Exporter';
        document.getElementById("ExporterForm").submit();
    }
    
    function ExportPrintPreview(grid){
        document.getElementById("params").value  = Ext.JSON.encode(grid.store.proxy.extraParams);
        document.getElementById("url").value     = Ext.JSON.encode(grid.store.proxy.url);
        document.getElementById("columns").value = "{"+ExtractColumns(grid.columns)+"}";
        document.getElementById("judul_form").value = grid.title;
        document.getElementById("sort").value = grid.store.sorters.items[0].property;
        document.getElementById("dir").value = grid.store.sorters.items[0].direction;
        
        document.getElementById("ExporterForm").action = '<?=base_url()?>index.php/Exporter/PrintForm';
        document.getElementById("ExporterForm").submit();
    }
</script>

<form method="POST" id="ExporterForm">
    <input type="hidden" name="params" id="params" />
    <input type="hidden" name="url" id="url" />
    <input type="hidden" name="judul_form" id="judul_form" />
    <input type="hidden" name="columns" id="columns" />
    <input type="hidden" name="sort" id="sort" />
    <input type="hidden" name="dir" id="dir" />
</form>