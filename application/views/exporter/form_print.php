<style>
.icon-back { background-image: url('<?=base_url()?>resources/images/toolbar/back-icon.png') !important;}
.icon-excel { background-image: url('<?=base_url()?>resources/images/toolbar/excel.gif') !important;}
.icon-print { background-image: url('<?=base_url()?>resources/images/toolbar/print.png') !important;}
</style>


<form method='POST' id='ExporterForm' target='previewFrame'>
    <input type='hidden' name='params' id='params' value='<?=$params?>'/>
    <input type='hidden' name='url' id='url' value='<?=$url?>'/>
    <input type='hidden' name='judul_form' id='judul_form' value='<?=$judul_form?>'/>
    <input type='hidden' name='columns' id='columns' value='<?=$columns?>'/>
    <input type='hidden' name='sort' id='sort' value='<?=$sort?>'/>
    <input type='hidden' name='dir' id='dir' value='<?=$dir?>'/>
</form>



<script>

function ExportPrintPreview(){
    document.getElementById("ExporterForm").action = '<?=base_url()?>index.php/Exporter/ExportPrintPreview';
    document.getElementById("ExporterForm").submit();  
}

function ExportExcel(){
    document.getElementById("ExporterForm").action = '<?=base_url()?>index.php/Exporter';
    document.getElementById("ExporterForm").submit();
}
    
    
Ext.require([
    'Ext.panel.*',
    'Ext.toolbar.*',
    'Ext.button.*',
    'Ext.container.ButtonGroup',
    'Ext.layout.container.Table'
]);

Ext.onReady(function() {
    var fakeHTML = '<iframe name="previewFrame" id="previewFrame" style="border: 0px solid; background:#ffffff"  width="100%" height="100%"></iftame>';
    var SamplePanel = Ext.create('Ext.Panel', {
        region: 'center',
        title: 'PREVIEW [ <?= str_replace('<br>', ' ', $judul_form)?> ] ',
        xtype: 'panel',
        html:fakeHTML,
        dockedItems: [{
            dock: 'top',
            xtype: 'toolbar',
            items: [{
                text: 'Back',
                iconCls: 'icon-back',
                handler: function() {window.history.back()}
            },'->',{
                text: 'Download Excel',
                iconCls: 'icon-excel',
                handler: function() {ExportExcel()}
            },'-',{
                text: 'Print',
                iconCls: 'icon-print',
                handler: function(){
                    frames["previewFrame"].focus();
                    frames["previewFrame"].print(); 
                }
            }]
        }]
    });
    
    

    Ext.create('Ext.container.Viewport', {
        layout: 'border',
        padding: '5',
        items: [SamplePanel]
    }); 
    ExportPrintPreview();
});



</script>



