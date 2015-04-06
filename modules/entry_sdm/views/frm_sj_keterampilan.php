<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Pengetahuan</title>

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
	
    Ext.define('mdl_sj_keterampilan', {
        extend: 'Ext.data.Model',
        fields: [ "id", "kode_jabatan", "kode_instansi", "keterampilan"],
        idProperty: 'id'
    });

    var store_sj_keterampilan = Ext.create('Ext.data.Store', {
        pageSize: 50,
        model: 'mdl_sj_keterampilan',
        remoteSort: true,
        proxy: {
            url: '<?=base_url()?>index.php/entry_sdm/isian_jabatan/get_sj_keterampilan',
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
	
	var ce_sj_keterampilan = Ext.create('Ext.grid.plugin.RowEditing', {
		clicksToMoveEditor: 1,
		autoCancel: false,
		listeners : {
			'edit' : function() {						
				var editedRecords = grid_sj_keterampilan.getView().getSelectionModel().getSelection();
				Ext.Ajax.request({
					url: '<?=base_url();?>index.php/entry_sdm/isian_jabatan/simpan_sj_keterampilan',
					method: 'POST',
					params: {
						'id' : editedRecords[0].data.id,
						'kode_jabatan': editedRecords[0].data.kode_jabatan,
						'kode_instansi': editedRecords[0].data.kode_instansi,
						'keterampilan': editedRecords[0].data.keterampilan,
					},								
					success: function(response) {
						var text = response.responseText;
						Ext.MessageBox.alert('Status', response.responseText, function(btn,txt){
							if(btn == 'ok')
							{
								store_sj_keterampilan.load();
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
	
var grid_sj_keterampilan = Ext.create('Ext.grid.Panel', {
	store: store_sj_keterampilan,
	disableSelection: false,
	//height: 300,
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
	plugins: [ce_sj_keterampilan],	
	columns:[
	{
		xtype: 'rownumberer',
		width: 35,
		sortable: false
	},
	{
		text: "Keterampilan Kerja",
		dataIndex: 'keterampilan',
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
				var r = Ext.create('mdl_sj_keterampilan', {
					kode_jabatan: '<?=$data['kode']?>',
					kode_instansi: '<?=$data['kode_instansi']?>',
					keterampilan: '[KETERAMPILAN KERJA]',
				});
				store_sj_keterampilan.insert(0, r);
				ce_sj_keterampilan.startEdit(0, 0);									
			}
		},
		{
			text:'Delete',
			iconCls: 'icon-del',
			handler: function() {          
				var records = grid_sj_keterampilan.getView().getSelectionModel().getSelection(), id=[];
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
							url: '<?=base_url();?>index.php/entry_sdm/isian_jabatan/hapus_sj_keterampilan',
							method: 'POST',											
							params: {												
								'id' : id.join(','),
							},								
							success: function(response) {
								Ext.MessageBox.alert('OK', response.responseText, function()
								{
									store_sj_keterampilan.load();
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
				store_sj_keterampilan.load();
			}
		},
		'->',
		{
			flex: 1,
			tooltip:'masukan pencarian',
			emptyText: 'masukan pencarian',
			xtype: 'searchfield',
			name: 'cari_sj_keterampilan',
			store: store_sj_keterampilan,
			listeners: {
				keyup: function(e){ 
				}
			}
		},			
		]
	}],
	listeners:{
		beforerender:function(){
			store_sj_keterampilan.load();
		}
	}			
	});
	
	
	Ext.create('Ext.panel.Panel', {
		id: 'panel_sj_keterampilan',
        layout:'fit',
        items: [grid_sj_keterampilan],
        renderTo: 'ren_sj_keterampilan'
    });  
		
});

	</script>
</head>
<body>
    <div id="ren_sj_keterampilan"></div>	
</body>
</html>