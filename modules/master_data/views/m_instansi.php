<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Instansi</title>

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
.icon-export { background-image:url(<?=base_url(); ?>assets/images/csv.png) !important; }
.tabs { background-image:url(<?=base_url(); ?>assets/images/tabs.gif ) !important;}
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
        fields: [ "kode", "nama", "nama_panjang", "alamat", "no_telp", "fax", "email", "web"],
        idProperty: 'id'
    });

    var store13 = Ext.create('Ext.data.Store', {
        pageSize: 200,
        model: 'mdl13',
        remoteSort: true,
        proxy: {
            url: '<?=base_url()?>index.php/master_data/main/get_data_instansi',
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
					url: '<?=base_url();?>index.php/master_data/main/simpan_data_instansi',
					method: 'POST',
					params: {
						'kode' : editedRecords[0].data.kode,
						'nama' : editedRecords[0].data.nama,
						'nama_panjang': editedRecords[0].data.nama_panjang,
						'alamat': editedRecords[0].data.alamat,
						'no_telp': editedRecords[0].data.no_telp,
						'fax': editedRecords[0].data.fax,
						'email': editedRecords[0].data.email,
						'web': editedRecords[0].data.web,
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
	title: 'INSTANSI',
	store: store13,
	autoScroll: true,
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
	{
		text: "Kode",
		dataIndex: 'kode',
		width: 50,
		sortable: false,
		editor: {
			xtype: 'textfield',
		}																			
	},				
	{
		text: "Singkatan",
		dataIndex: 'nama',
		flex: 1,
		sortable: false,
		editor: {
			xtype: 'textfield',
		}																	
	},		
	{
		text: "Nama",
		dataIndex: 'nama_panjang',
		flex: 3,
		sortable: false,
		editor: {
			xtype: 'textfield',
		}																	
	},				
	{
		text: "Alamat",
		dataIndex: 'alamat',
		flex: 3,
		sortable: false,
		editor: {
			xtype: 'textfield',
		}																	
	},				
	{
		text: "No Telp",
		dataIndex: 'no_telp',
		flex: 1,
		sortable: false,
		editor: {
			xtype: 'textfield',
		}																	
	},				
	{
		text: "Fax",
		dataIndex: 'fax',
		flex: 1,
		sortable: false,
		editor: {
			xtype: 'textfield',
		}																	
	},				
	{
		text: "Email",
		dataIndex: 'email',
		flex: 1,
		sortable: false,
		editor: {
			xtype: 'textfield',
		}																	
	},				
	{
		text: "Web",
		dataIndex: 'web',
		flex: 1,
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
					kode : '[KODE]',
					nama : '[SINGKATAN]',
					nama_panjang: '[NAMA PANJANG]',
					alamat : '[ALAMAT]',
					no_telp: '[NO TELP]',
					fax: '[FAX]',
					email: '[EMAIL]',
					web: '[WEB]',
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
					id.push(rec.get('kode'));
				});
				if(id != '')
				{
				Ext.MessageBox.confirm('Hapus', 'Apakah anda akan menghapus item ini (' + id.join(',') + ') ?',
				function(resbtn){
					if(resbtn == 'yes')
					{
						Ext.Ajax.request({
							url: '<?=base_url();?>index.php/master_data/main/hapus_data_instansi',
							method: 'POST',											
							params: {												
								'kode' : id.join(','),
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
		},
		{
			text:'Export (CSV)',
			iconCls: 'icon-export',
			handler: function(){          
			}
		},'-',
		'Ket: Dobel klik pada item untuk mengedit data',
		'->',
		{
			flex: 1,
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