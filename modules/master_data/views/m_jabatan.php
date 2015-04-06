<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Jabatan</title>

<link rel="stylesheet" type="text/css" href="<?=base_url()?>resources/ext4/examples/shared/example.css" />
<script type="text/javascript" src="<?=base_url()?>resources/ext4/examples/shared/include-ext.js"></script>

<link href="<?=$this->config->item('base_url');?>resources/ext4/resources/css/ext-all.css" rel="stylesheet" type="text/css" />
<!--
<link rel="stylesheet" href="<?=$this->config->item('base_url');?>assets/css/bootstrap.min.css"/>
-->

<style>
.icon-add { background-image:url(<?=base_url(); ?>assets/images/add.gif) !important; }
.icon-del { background-image:url(<?=base_url(); ?>assets/images/delete.png) !important; }
.icon-reload { background-image:url(<?=base_url(); ?>assets/images/reload.png) !important; }
.tabs { background-image:url(<?=base_url(); ?>assets/images/tabs.gif ) !important;}
.icon-import { background-image:url(<?=base_url(); ?>assets/images/csv.png) !important; }
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
    'Ext.tree.*',
    'Ext.tip.QuickTipManager',
]);

Ext.onReady(function(){
    Ext.tip.QuickTipManager.init();
	Ext.state.Manager.setProvider(Ext.create('Ext.state.CookieProvider'));
	
    var required = '<span style="color:red;font-weight:bold" data-qtip="Required">*</span>';
	
    Ext.define('mdl13', {
        extend: 'Ext.data.Model',
        fields: [ "id", "kode_rumpun", "kode","nama","id_jens_jabatan","jenis_jabatan","jenis_jabatan2"],
        idProperty: 'id'
    });

    var store13 = Ext.create('Ext.data.Store', {
        pageSize: 200,
        model: 'mdl13',
        remoteSort: true,
        proxy: {
            url: '<?=base_url()?>index.php/master_data/main/get_data_jabatan',
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
        }]
    });
	
	var APcellEditing13 = Ext.create('Ext.grid.plugin.RowEditing', {
		//clicksToEdit: 1,
		clicksToMoveEditor: 1,
		autoCancel: false,
		listeners : {
			'edit' : function() {						
				var editedRecords = grid13.getView().getSelectionModel().getSelection();
				Ext.Ajax.request({
					url: '<?=base_url();?>index.php/master_data/main/simpan_data_jabatan',
					method: 'POST',
					params: {
						'id' : editedRecords[0].data.id,
						'id_jens_jabatan' : editedRecords[0].data.id_jens_jabatan,
						'kode' : editedRecords[0].data.kode,
						'nama': editedRecords[0].data.nama,
					},								
					success: function(response) {
						var text = response.responseText;
						Ext.MessageBox.alert('Status', response.responseText, function(btn,txt){
							if(btn == 'ok')
							{
								store13.load();
							}
						}
						);
					},
					failure: function(response) {
						Ext.MessageBox.alert('Failure', 'Insert Data Error due to connection problem!');
					}
				});			   																																				
			}
		}
	});			
	
var grid13 = Ext.create('Ext.grid.Panel', {
	title: 'JABATAN',
	store: store13,
	disableSelection: false,
	loadMask: true,
	selModel: Ext.create('Ext.selection.CheckboxModel', {
		mode: 'MULTI', 
		multiSelect: true,
		keepExisting: true,
	}),
	viewConfig: {
		trackOver: true,
		stripeRows: true,
	},
	plugins: [APcellEditing13],	
	columns:[
	{
		xtype: 'rownumberer',
		width: 35,
		sortable: false
	},
	/*
	{
		text: "Kode Rumpun",
		dataIndex: 'kode_rumpun',
		flex: 1,
		sortable: false,
		editor: {
			xtype: 'textfield',
		}																	
	},
	*/
	{
		xtype: 'hiddenfield',
		//text: "Jenis Jabatan",
		dataIndex: 'id_jens_jabatan',
		id: 'id_jens_jabatan',
		sortable: false,
	},
	{
		text: "ID Jenis Jabatan",		
		dataIndex: 'id_jens_jabatan',
		flex: 1,
		sortable: false,
		renderer: function(value, metaData, record, rowIndex, colIndex, store, view) {
			var rec = store.getAt(rowIndex);
			return rec.get('jenis_jabatan2');
		},		
		editor: {
			id: 'cmb_jenis_jabatan',						
			xtype: 'combo',
			store: { 
				fields: ['id','nama','nama_panjang','jenis_jabatan'], 
				pageSize: 100, 
				proxy: { 
					type: 'ajax', 
					url: '<?=base_url();?>index.php/master_data/main/get_jenis_jabatan', 
					reader: { 
						root: 'data',
						type: 'json' 
					} 
				} 
			},
			triggerAction : 'all',					
			anchor: '100%',
			displayField: 'jenis_jabatan',
			valueField: 'id',
			listeners: {
				'select': function(combo, row, index) {
				}
			},																					
		}																	
	},				
	{
		text: "Kode",
		dataIndex: 'kode',
		flex: 1,
		sortable: false,
		editor: {
			xtype: 'textfield',
		}																	
	},				
	{
		text: "Nama",
		dataIndex: 'nama',
		flex: 3,
		sortable: false,
		editor: {
			xtype: 'textfield',
		}																	
	},
	],
	dockedItems: [
	{
		xtype: 'toolbar',
		dock: 'top',
		items: 
		[
		{
			text:'Tambah Data',
			iconCls: 'icon-add',
			handler: function(){          
				var r = Ext.create('mdl13', {
					//id: null,
					kode: '[KODE]',
					nama: '[NAMA JABATAN]',
					id_jens_jabatan: 1,
				});
				store13.insert(0, r);
				APcellEditing13.startEdit(0, 0);									
			}
		},
		{
			text:'Delete',
			iconCls: 'icon-del',
			handler: function() {          
				var records = grid13.getView().getSelectionModel().getSelection(), id=[];
				Ext.Array.each(records, function(rec){
					id.push(rec.get('id'));
				});
				if(id != '')
				{
				Ext.MessageBox.confirm('Hapus', 'Apakah anda akan menghapus item ini (' + id.join(',') + ') ?',
				function(resbtn){
					if(resbtn == 'yes')
					{
						Ext.Ajax.request({
							url: '<?=base_url();?>index.php/master_data/main/hapus_data_jabatan',
							method: 'POST',											
							params: {												
								'id' : id.join(','),
							},								
							success: function(response) {
								Ext.MessageBox.alert('OK', response.responseText, function()
								{
									store13.load();
								});
							},
							failure: function(response) {
								Ext.MessageBox.alert('Failure', 'Insert Data Error due to connection problem, or duplicate entries!');
							}
						});			   	
					} else 
					{
						Ext.MessageBox.alert('Error', 'Silahkan pilih item yang mau dihapus!');
					}																		
				});
				} else 
				{
					Ext.MessageBox.alert('Error', 'Silahkan pilih item yang mau dihapus!');
				}
			}
		},		
		{
			text:'Refresh',
			iconCls: 'icon-reload',
			handler: function(){          
				store13.load();
			}
		},'-',
		{
			text:'Import (CSV)',
			iconCls: 'icon-import',
			handler: function(){          
			}
		},
		{
			text:'Export (CSV)',
			iconCls: 'icon-import',
			handler: function(){          
			}
		},'-','Ket: Dobel klik pada item utk mengupdate data',
		'->',
		{
			id: 'pilih_jenis_jabatan',
			xtype: 'combo',
			flex:2,
			store: { 
				fields: ['id','nama','nama_panjang','jenis_jabatan'], 
				pageSize: 100, 
				proxy: { 
					type: 'ajax', 
					url: '<?=base_url();?>index.php/master_data/main/get_jenis_jabatan', 
					reader: { 
						root: 'data',
						type: 'json' 
					} 
				} 
			},
			triggerAction : 'all',					
			anchor: '100%',
			displayField: 'jenis_jabatan',
			valueField: 'id',
			listeners: {
				'select': function(combo, row, index) {
					var id = row[0].get('id');
					store13.load({params:{'id_jens_jabatan':id}});
				}
			},																							
		},
		{
			flex: 2,
			tooltip:'pencarian',
			emptyText: 'masukan pencarian',
			xtype: 'searchfield',
			name: 'cari13',
			store: store13,
			listeners: {
				keyup: function(e){ 
				}
			}
		},			
		]
	}],
   bbar: Ext.create('Ext.PagingToolbar',{
		store: store13,
		displayInfo: true,
		displayMsg: 'Displaying Data : {0} - {1} of {2}',
		emptyMsg: "No Display Data"
	}),
	listeners:{
		beforerender:function(){
			store13.load();
		}
	}			
	});
	
	
    Ext.create('Ext.container.Viewport', {
        layout: 'fit',
        items: [grid13]
    });
			
});

	</script>
</head>
<body>
    <div id="render13"></div>	
</body>
</html>