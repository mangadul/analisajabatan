<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Data Isian Jabatan</title>

<link rel="stylesheet" type="text/css" href="<?=base_url()?>resources/ext4/examples/shared/example.css" />
<script type="text/javascript" src="<?=base_url()?>resources/ext4/examples/shared/include-ext.js"></script>

<link href="<?=$this->config->item('base_url');?>resources/ext4/resources/css/ext-all.css" rel="stylesheet" type="text/css" />
<!--
<link rel="stylesheet" href="<?=$this->config->item('base_url');?>assets/css/ExtJSOrgChart.css"/>
<script type="text/javascript" src="<?=$this->config->item('base_url');?>assets/js/ExtJSOrgChart.js"></script>
<link rel="stylesheet" href="<?=$this->config->item('base_url');?>assets/css/bootstrap.min.css"/>
-->
<!--
<script type="text/javascript" src="<?=base_url()?>resources/ext4/examples/shared/options-toolbar.js"></script>
-->

<style>
.icon-print-preview { background-image:url(<?=base_url(); ?>assets/images/txt.png) !important; }
.icon-print { background-image:url(<?=base_url(); ?>assets/images/print.png) !important; }
.icon-reload { background-image:url(<?=base_url(); ?>assets/images/reload.png) !important; }
.icon-print-pdf { background-image:url(<?=base_url(); ?>assets/images/pdf.png) !important; }
.icon-print-xls { background-image:url(<?=base_url(); ?>assets/images/xls.png) !important; }
.tabs { background-image:url(<?=base_url(); ?>assets/images/tabs.gif ) !important; }
</style>

<script type="text/javascript">
Ext.Loader.setConfig({enabled: true});

Ext.Loader.setPath('Ext.ux', '<?=base_url()?>resources/ext4/examples/ux/');
Ext.require([
    '*',
    'Ext.grid.*',
    'Ext.data.*',
    'Ext.util.*',
    'Ext.toolbar.Paging',
    'Ext.ux.PreviewPlugin',
    'Ext.ux.DataTip',   
    'Ext.ModelManager', 
    'Ext.ux.form.SearchField',
    'Ext.menu.*',
    'Ext.tip.QuickTipManager',
    'Ext.container.ButtonGroup'
]);

Ext.onReady(function(){
    Ext.tip.QuickTipManager.init();
    

    Ext.define('mdl_strc', {
        extend: 'Ext.data.Model',
        fields: [ 'id', 'kode_jabatan','nama_jabatan','ikhtisar_jabatan','id_instansi','instansi'],
        idProperty: 'id'
    });

    // create the Data Store
    var storestruk = Ext.create('Ext.data.Store', {
        pageSize: 200,
        model: 'mdl_strc',
        remoteSort: true,
        proxy: {
            //url: '<?=base_url()?>index.php/entry_sdm/main/get_jabatan_struktural',
            url: '<?=base_url()?>index.php/entry_sdm/main/get_data_isian_jabatan',
            simpleSortMode: true,
			type: 'ajax',
			reader: {
				type: 'json',
				root: 'data'
			}			
        },
		baseParams: {
			limit: 200,
		},		
        sorters: [{
            property: 'id',
            direction: 'DESC'
        }],
		autoLoad: true
    });
	
    var pluginExpanded = true;
		
    // create the grid
    var grid = Ext.create('Ext.grid.Panel', {
        store: storestruk,
        title: 'Data Isian Jabatan',
		disableSelection: false,		
        columns: [
            {
                xtype: 'rownumberer',
                width: 35,
                sortable: false
            },        
            {
                xtype:'actioncolumn',
                width: 20,
                items: [
                {
                    icon   : '<?=base_url();?>assets/images/txt.png', 
                    tooltip: 'Download',
                    handler: function(grid, rowIndex, colIndex) {
                        var rec = storestruk.getAt(rowIndex);
                        Ext.MessageBox.confirm('Unduh', 'Apakah anda akan mengunduh item ini ('+rec.get('nama_jabatan')+'-'+rec.get('instansi')+') ?',function(resbtn){
                            if(resbtn == 'yes')
                            {
                                window.location = '<?=base_url();?>index.php/entry_sdm/isian_jabatan/download_odt/'+rec.get('kode_jabatan')+'/'+rec.get('id_instansi');
                            }
                        })
                    }               
                }
                ],
            },            
            {text: "Kode", width: 80, dataIndex: 'kode_jabatan'},
            {text: "Nama Jabatan", width: 200, dataIndex: 'nama_jabatan'},
            {text: "Instansi", flex: 2, dataIndex: 'instansi'},
            {text: "Ikhtisar Jabatan", flex: 2, dataIndex: 'ikhtisar_jabatan'},
        ],
        width: 800,
        height: 450,
        //constrain:true,        
        //layout: 'fit',
        dockedItems: [
    			{
    				xtype: 'toolbar',
    				items: [
                    {
                        id: 'cmb_pilih_instansi',                       
                        xtype: 'combo',
                        name: 'kd_instansi',
                        fieldLabel: 'Pilih Instansi',
                        flex:2,
                        store: { 
                            fields: ['kode','nama'], 
                            pageSize: 200, 
                            proxy: { 
                                type: 'ajax', 
                                url: '<?=base_url();?>index.php/entry_sdm/main/get_instansi', 
                                reader: { 
                                    root: 'data',
                                    type: 'json' 
                                } 
                            } 
                        },
                        minChars: 2,
                        triggerAction : 'all',                  
                        anchor: '100%',
                        displayField: 'nama',
                        valueField: 'kode',
                        listeners: {
                            'select': function(combo, row, index) {
                                storestruk.load({params: {id_instansi: row[0].get('kode')}});
                                }
                            }
                    },'',
                    {
                        xtype: 'button',
                        iconCls: 'icon-reload',                        
                        text: 'Reload',
                        handler: function(){
                                storestruk.load({params: {id_instansi: '', query:'', page:1, start:0, limit:200}});
                        }                        
                    },
                    '->',                                                               
					{
                        xtype: 'searchfield',
						remoteFilter: true,
						store: storestruk,
                        //height: 30,
                        flex: 2,
                        id: 'searchField',
                        //styleHtmlContent: true,
						emptyText: 'masukan kode atau nama jabatan',
                    },
				]
			}
		],		
		listeners: {
			cellclick: function(view, td, cellIndex, record, tr, rowIndex, e, eOpts) {
			}
		},		
        bbar: Ext.create('Ext.PagingToolbar', {
            store: storestruk,
            displayInfo: true,
            displayMsg: 'Displaying data {0} - {1} of {2}',
            emptyMsg: "No data to display",
            inputItemWidth: 35,
        }),        
    });

    Ext.create('Ext.container.Viewport', {
        title: 'Data Entri Jabatan',
        layout: 'fit',
        //padding: '5',
        items: [grid],
        renderTo: Ext.getBody()
    });

}); 

    </script>
</head>
<body>
    <div id="topic-grid"></div> 
</body>
</html>
	