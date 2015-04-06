<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Uraian Tugas</title>

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
	
    Ext.define('mdl14', {
        extend: 'Ext.data.Model',
        fields: [ "id", "kode_jabatan", "fisik_mental", "penyebab"],
        idProperty: 'id'
    });

    var store14 = Ext.create('Ext.data.Store', {
        pageSize: 50,
        model: 'mdl14',
        remoteSort: true,
        proxy: {
            url: '<?=base_url()?>index.php/entry_sdm/main/get_resiko_bahaya',
            simpleSortMode: true,
			type: 'ajax',
			reader: {
				type: 'json',
				root: 'data'
			}			
        },
		baseParams: {
			limit: 100,
		},		
        sorters: [{
            property: 'id',
            direction: 'DESC'
        }]
    });
	
	var APcellEditing14 = Ext.create('Ext.grid.plugin.RowEditing', {
		clicksToMoveEditor: 1,
		autoCancel: false,
		listeners : {
			'edit' : function() {						
				var editedRecords = grid14.getView().getSelectionModel().getSelection();
				Ext.Ajax.request({
					url: '<?=base_url();?>index.php/entry_sdm/main/simpan_resiko_bahaya',
					method: 'POST',
					params: {
						'id' : editedRecords[0].data.id,
						'kode_jabatan': editedRecords[0].data.kode_jabatan,
						'fisik_mental': editedRecords[0].data.fisik_mental,
						'penyebab': editedRecords[0].data.penyebab,
					},								
					success: function(response) {
						var text = response.responseText;
						Ext.MessageBox.alert('Status', response.responseText, function(btn,txt){
							if(btn == 'ok')
							{
								store14.load();
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
	
var grid14 = Ext.create('Ext.grid.Panel', {
	title: '14. Resiko Bahaya',
	store: store14,
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
	plugins: [APcellEditing14],	
	columns:[
	{
		xtype: 'rownumberer',
		width: 35,
		sortable: false
	},
	{
		text: "Fisik / Mental",
		dataIndex: 'fisik_mental',
		flex: 1,
		sortable: false,
		editor: {
			xtype: 'textfield',
		}																	
	},				
	{
		text: "Penyebab",
		dataIndex: 'penyebab',
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
				var r = Ext.create('mdl14', {
					kode_jabatan: '<?=$data['kode']?>',
					fisik_mental: '[ EDITED - FISIK / MENTAL ]',
					penyebab: '[ EDITED - PENYEBAB ]',
				});
				store14.insert(0, r);
				APcellEditing14.startEdit(0, 0);									
			}
		},
		{
			text:'Delete',
			iconCls: 'icon-del',
			handler: function() {          
				var records = grid14.getView().getSelectionModel().getSelection(), id=[];
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
							url: '<?=base_url();?>index.php/entry_sdm/main/hapus_resiko_bahaya',
							method: 'POST',											
							params: {												
								'id' : id.join(','),
							},								
							success: function(response) {
								Ext.MessageBox.alert('OK', response.responseText, function()
								{
									store14.load();
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
				store14.load();
			}
		},
		'->',
		{
			flex: 1,
			tooltip:'pencarian',
			emptyText: 'masukan pencarian',
			xtype: 'searchfield',
			name: 'cari14',
			store: store14,
			listeners: {
				keyup: function(e){ 
				}
			}
		},			
		]
	}],
	listeners:{
		beforerender:function(){
			store14.load();
		}
	}			
	});
	
	
	Ext.create('Ext.panel.Panel', {
		id: 'panel14',
        layout:'fit',
        items: [grid14],
        renderTo: 'render14'
    });  
		
});

	</script>
</head>
<body>
    <div id="render14"></div>	
</body>
</html>