<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Pemetaan Jabatan</title>

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
.icon-add { background-image:url(<?=base_url(); ?>assets/images/add.png) !important; }
.icon-del { background-image:url(<?=base_url(); ?>assets/images/delete.png) !important; }
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
	
    var required = '<span style="color:red;font-weight:bold" data-qtip="Required">*</span>';
	
    Ext.define('mdl_strc', {
        extend: 'Ext.data.Model',
        fields: [ "kode","nama","nama_panjang"],
        idProperty: 'kode'
    });

    // create the Data Store
    var storestruk = Ext.create('Ext.data.Store', {
        pageSize: 2000,
        model: 'mdl_strc',
        remoteSort: true,
        proxy: {
            url: '<?=base_url()?>index.php/pemetaan_jabatan/main/get_instansi',
            simpleSortMode: true,
			type: 'ajax',
			reader: {
				type: 'json',
				root: 'data'
			}			
        },
		baseParams: {
			limit: 2000,
		},		
        sorters: [{
            property: 'kode',
            direction: 'DESC'
        }],
		autoLoad: true
    });
	
    var pluginExpanded = true;
	var kode='';
    // create the grid
    var grid = Ext.create('Ext.grid.Panel', {
        store: storestruk,
		disableSelection: false,		
        columns: [
            {text: "Kode", width: 50, dataIndex: 'kode'},
            {text: "Nama", width: 100, dataIndex: 'nama'},
            {text: "Nama Panjang", flex: 1, dataIndex: 'nama_panjang'},
        ],
        width: 540,
        height: 200,
		listeners: {
			cellclick: function(view, td, cellIndex, record, tr, rowIndex, e, eOpts) {
				//diagram_panel(record.get('id'),record.get('kode'));
				//Ext.MessageBox.alert(record.get('kode'), record.get('nama'));
				Ext.Ajax.request({
					url : '<?=$this->config->item('base_url');?>index.php/pemetaan_jabatan/main/set_instansi_id/',
					method : "POST",
					params: {kode_instansi: record.get('kode'), nama_instansi: record.get('nama')},
					success : function(response, opts) {
						var panel = Ext.getCmp('diagram');
						panel.setDisabled(false);
						panel.body.update();
						panel.doLayout();						
						store_sotk.load();
						store_pelaksana.load();						
						Ext.getCmp('bar-instansi').update('Instansi: '+record.get('kode')+' - '+record.get('nama')+' :: '+record.get('nama_panjang'));
						Ext.Ajax.request({
							url : '<?=$this->config->item('base_url');?>index.php/pemetaan_jabatan/main/clear_parent_id/',
							method : "POST",
							success : function(response, opts) {
							},
							failure : function(response, opts) {          
								alert("Error while loading data : "+response.responseText);                  
							}
						});

						//var detailPanel = Ext.getCmp('ipanel');
						//detailPanel.body.update('<iframe width="730" height="430" id="ipanel" name="ipanel" frameborder="0" src="<?=$this->config->item('base_url');?>index.php/pemetaan_jabatan/main/get_diagram_struktur"></iframe>');			
					} , 
					failure : function(response, opts) {          
						alert("Error while loading data : "+response.responseText);                  
					}
				});				
				//kode = record.get('kode');
				//var iframe = document.getElementById('ipanel');
				//iframe.src = iframe.src;				
			}
		},		
	dockedItems: [
	{
		xtype: 'toolbar',
		dock: 'top',
		items: 
		[
			{
				flex: 1,
				tooltip:'masukan pencarian',
				emptyText: 'masukan kode atau nama Instansi...',
				xtype: 'searchfield',
				name: 'cari_instansi',
				store: storestruk,
				fieldLabel:'Pencarian:',
				listeners: {
					keyup: function(e){ 
					}
				}
			}			
		]
	}]

    });
	
    grid.getSelectionModel().on('selectionchange', function(sm, selectedRecord) {
        if (selectedRecord.length) {
            var detailPanel = Ext.getCmp('detailPanel');
			//Ext.MessageBox.alert(selectedRecord[0].data);
            //detailPanel.update(selectedRecord[0].data);
        }
    });	

		
	var ipanel= new Ext.Panel({
		html: '<iframe width="100%" height="100%" id="ipanel" name="ipanel" frameborder="0" src="<?=$this->config->item('base_url');?>index.php/pemetaan_jabatan/main/get_diagram_struktur"></iframe>',
		autoScroll: true,
		id: 'ipanel',
        dockedItems: {
            itemId: 'toolbar',
            xtype: 'toolbar',
            items: [
				{
					text: 'Tambah Data Diagram',
					handler: function(){
						Ext.MessageBox.alert("Status", "Tambah Data");
					}			
				}
            ]
        },
	});
	
	function diagram_panel(id,kode)
	{
		Ext.Ajax.request({
			url : '<?=$this->config->item('base_url');?>index.php/pemetaan_jabatan/main/get_data_str/'+id,
			method : "GET",
			success : function(response, opts) {
				var detailPanel = Ext.getCmp('ipanel');
				detailPanel.body.update('<iframe width="730" height="430" id="ipanel" name="ipanel" frameborder="0" src="<?=$this->config->item('base_url');?>index.php/pemetaan_jabatan/main/get_diagram_struktur"></iframe>');			
			} , 
			failure : function(response, opts) {          
				alert("Error while loading data : "+response.responseText);                  
			}
		});
	}

	// kedudukan dalam sotk
    var store_sotk = Ext.create('Ext.data.TreeStore', {
        proxy: {
            type: 'ajax',
            reader: 'json',
            url: '<?php echo base_url();?>index.php/pemetaan_jabatan/main/get_data_sotk',
			node:'id',
        },
		//autoLoad: true        
    });
		
    var frm_sotk = Ext.widget({
        xtype: 'form',
        layout: 'form',
        id: 'idfrmsotk',
        url: '<?php echo base_url();?>index.php/pemetaan_jabatan/main/save_sotk',
        frame: false,
		border: false,
        fieldDefaults: {
            msgTarget: 'side',
            labelWidth: 150
        },
        plugins: {
            ptype: 'datatip'
        },
        defaultType: 'textfield',
        items: [
		{
            xtype: 'hiddenfield',
			id: 'id',
            name: 'id',
        },
		{
            xtype: 'hiddenfield',
            name: 'kode_jabatan',
            id: 'id_kode_jabatan',
        },
		{
            xtype: 'hiddenfield',
            name: 'parent_id',
        },
		{
            xtype: 'textfield',
            name: 'kode_instansi',
            value: '<?=$data['kode_instansi'];?>',
            fieldLabel: 'Kode Instansi',
            readOnly: true,
            tooltip: 'Nama Instansi: <?=$data['nama_instansi'];?>',
        },
        {
			xtype: 'combo',
			name: 'nama_jabatan',
            fieldLabel: 'Nama Instansi Jabatan',
			store: { 
				fields: ['id','kode_jabatan','nama_jabatan'], 
				pageSize: 100, 
				proxy: { 
					type: 'ajax', 
					url: '<?=base_url();?>index.php/pemetaan_jabatan/main/get_jabatan_by_kode',
					reader: { 
						root: 'data',
						type: 'json' 
					},
					extraParams: {kode_instansi : '<?=$data['kode_instansi'];?>'},
				} 
			},
			triggerAction : 'all',					
			anchor: '100%',
			displayField: 'nama_jabatan',
			valueField: 'nama_jabatan',
			listeners: {
				'select': function(combo, row, index) {
					Ext.getCmp('id_kode_jabatan').setValue(row[0].get('kode_jabatan'));
				}
			},																					        	
        }
        /*
		{
            xtype: 'textfield',			
            fieldLabel: 'Nama di Pemetaan Jabatan',
            name: 'nama_jabatan',
            allowBlank: false,
			afterLabelTextTpl: required,			
            tooltip: 'Nama di Pemetaan Jabatan'
        },
        */
        ],		
        buttons: [
		{
            text: 'Save',
			handler: function() {
				var form = this.up('form').getForm();
				if (form.isValid()) {
					form.submit({
						success: function(form, action) {
						   Ext.MessageBox.alert('Success', action.result.message, function(btn, text){
							if (btn == 'ok'){
								//frm_sotk.getForm().reset();
								//store_sotk.getRootNode().removeAll();
								store_sotk.load();
							}						   
						   });
						},
						failure: function(form, action) {
							Ext.MessageBox.alert('Failed', action.result ? action.result.message : 'No response');
						}
					});
				} else {
					Ext.MessageBox.alert( "Error!", "Silahkan isi form dg benar!" );
				}
			},
		},
		{
            text: 'Cancel',
            handler: function() {
                //this.up('form').getForm().reset();
                win_sotk.hide();
            }
        }
		]
    });

    Ext.define('mdl_pelaksana', {
        extend: 'Ext.data.Model',
        fields: [ "id","kode_jabatan","kode_instansi","nama_jabatan","id_pemetaan","tahun_anjab","jml_tersedia"],
        idProperty: 'id'
    });

    var store_pelaksana = Ext.create('Ext.data.Store', {
        pageSize: 50,
        model: 'mdl_pelaksana',
        remoteSort: true,
        proxy: {
            url: '<?=base_url()?>index.php/pemetaan_jabatan/main/get_pelaksana',
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
	
	var ce_pelaksana = Ext.create('Ext.grid.plugin.RowEditing', {
		clicksToMoveEditor: 1,
		autoCancel: false,
		listeners : {
			'edit' : function() {						
				var editedRecords = grid_pelaksana.getView().getSelectionModel().getSelection();
				Ext.Ajax.request({
					url: '<?=base_url();?>index.php/pemetaan_jabatan/main/simpan_pelaksana',
					method: 'POST',
					params: {
						'id' : editedRecords[0].data.id,
						'kode_jabatan': editedRecords[0].data.kode_jabatan,
						'kode_instansi': editedRecords[0].data.kode_instansi,
						'nama_jabatan': editedRecords[0].data.nama_jabatan,
						//'id_pemetaan': editedRecords[0].data.id_pemetaan,
						'tahun_anjab': editedRecords[0].data.tahun_anjab,						
						'jml_tersedia': editedRecords[0].data.jml_tersedia,
					},								
					success: function(response) {
						var text = response.responseText;
						Ext.MessageBox.alert('Status', response.responseText, function(btn,txt){
							if(btn == 'ok')
							{
								store_pelaksana.load();
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
	
var grid_pelaksana = Ext.create('Ext.grid.Panel', {
	store: store_pelaksana,
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
	plugins: [ce_pelaksana],	
	columns:[
	{
		xtype: 'rownumberer',
		width: 35,
		sortable: false
	},
	{
		text: "Jabatan",
		dataIndex: 'kode_jabatan',
		flex: 1,
		sortable: false,
		renderer: function(value, metaData, record, rowIndex, colIndex, store, view) {
			var rec = store.getAt(rowIndex);
			return rec.get('nama_jabatan');
		},		
		editor: {
			id: 'cmb_temperamen',						
			xtype: 'combo',
			store: { 
				fields: ['id','kode_jabatan','nama_jabatan'], 
				pageSize: 100, 
				proxy: { 
					type: 'ajax', 
					url: '<?=base_url();?>index.php/pemetaan_jabatan/main/get_jabatan_by_kode',
					reader: { 
						root: 'data',
						type: 'json' 
					},
					extraParams: {kode_instansi : '<?=$data['kode_instansi'];?>'},
				} 
			},
			triggerAction : 'all',					
			anchor: '100%',
			displayField: 'nama_jabatan',
			valueField: 'kode_jabatan',
			listeners: {
				'select': function(combo, row, index) {
					Ext.getCmp('idnamajabatan').setValue(row[0].get('nama_jabatan'));
				}
			},																					
		}																			
	},		
	{
		text: "Nama Jabatan",		
		flex: 1,
		sortable: false,		
		dataIndex: 'nama_jabatan',
		id: 'idnamajabatan',
	},
	{
		text: "Tahun Anjab",		
		flex: 1,
		sortable: false,		
		dataIndex: 'tahun_anjab',
		editor: { xtype: 'numberfield'}
	},
	{
		text: "Jumlah Tersedia",		
		flex: 1,
		sortable: false,		
		dataIndex: 'jml_tersedia',
		editor: { xtype: 'numberfield'}
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
				var r = Ext.create('mdl_pelaksana', {
					kode_jabatan: '[KODE JABATAN]',
					tahun_anjab: '[<?=date('Y');?>]',
					jml_tersedia: '1',
				});
				store_pelaksana.insert(0, r);
				ce_pelaksana.startEdit(0, 0);									
			}
		},
		{
			text:'Delete',
			iconCls: 'icon-del',
			handler: function() {          
				var records = grid_pelaksana.getView().getSelectionModel().getSelection(), id=[];
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
							url: '<?=base_url();?>index.php/pemetaan_jabatan/main/hapus_pelaksana',
							method: 'POST',											
							params: {												
								'id' : id.join(','),
							},								
							success: function(response) {
								Ext.MessageBox.alert('OK', response.responseText, function()
								{
									store_pelaksana.load();
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
				store_pelaksana.load();
			}
		},
		'->',
		{
			flex: 1,
			tooltip:'masukan pencarian',
			emptyText: 'masukan pencarian',
			xtype: 'searchfield',
			name: 'cari_pelaksana',
			store: store_pelaksana,
			listeners: {
				keyup: function(e){ 
				}
			}
		},			
		]
	}],
	listeners:{
		beforerender:function(){
			store_pelaksana.load();
		}
	}			
	});
	
    var tab_pemetaan = Ext.widget('tabpanel', {
        activeTab: 0,
        plain: true,
		layout: 'fit',
        defaults :{
            autoScroll: true,
            bodyPadding: 0,
        },
        items: [
            {
                title: 'Inputan Pemetaan Jabatan',
				items: [frm_sotk],
				layout: 'fit',
                listeners: {
                    activate: function(tab) {
						//store_sotk.load();
                    }
                }
            },
            /*
            {
                title: 'Pelaksana / Staf',
                id: 'id-pelaksana',
                items: [grid_pelaksana],
                layout: 'fit',
                listeners: {
                    activate: function(tab) {
                        store_pelaksana.load();
                    }
                }
            },
            */
        ]
    });


	var win_sotk = Ext.create('Ext.window.Window',{
		layout: 'fit',
		items: [tab_pemetaan],
		closeAction: 'hide',
		closable: true,
		title: 'Form Struktur Organisasi',
		width: '50%',
		height: '50%',
		modal: true,
	});	

    //var pluginExpanded = true;	
    var tree_sotk = Ext.create('Ext.tree.Panel', {
		height: 300,
		layout: 'fit',
        loadMask: true,
        useArrows: true,
        rootVisible: false,
		multiSelect: true,
		singleExpand: true,		
        store: store_sotk,
        //animate: true,
        plugins: [{
            ptype: 'bufferedrenderer'
        }],
        columns: [
        {
            xtype:'actioncolumn',
            width:60,
            items: [
			{
                icon: '<?=base_url();?>assets/images/add.png',
                tooltip: 'Tambah',
                handler: function(grid, rowIndex, colIndex) {				
                    var rec = grid.getStore().getAt(rowIndex);
					Ext.Ajax.request({
						url: '<?=base_url();?>index.php/pemetaan_jabatan/main/set_id_parent_sotk',
						method: 'POST',											
						params: {
							'id' : rec.get('id'),
						},								
						success: function(response) {
								win_sotk.on('show', function(win) {
									win_sotk.setTitle('Form Struktur Organisasi :: Sub :: '+rec.get('text'));
									var form = frm_sotk.getForm();
									form.reset();
									var parent = form.findField('parent_id');
									parent.setValue(rec.get('id'));
								});
								win_sotk.doLayout();						
								win_sotk.show();							
						},
						failure: function(response) {
							Ext.MessageBox.alert('Failure', 'Insert Data Error due to connection problem!');
						}
					});
                }
            },
			{
                icon: '<?=base_url();?>assets/images/edit.png',
                tooltip: 'Edit',
                handler: function(grid, rowIndex, colIndex) {	
                    var rec = grid.getStore().getAt(rowIndex);
					Ext.Ajax.request({
						url: '<?=base_url();?>index.php/pemetaan_jabatan/main/set_id_parent_sotk',
						method: 'POST',											
						params: {
							'id' : rec.get('id'),
						},								
						success: function(response) {
								win_sotk.on('show', function(win) {
									win_sotk.setTitle('Form Struktur Organisasi :: Sub :: '+rec.get('text'));
									var form = frm_sotk.getForm();
									form.reset();
									var namjab = form.findField('nama_jabatan');
									var id = form.findField('id');
									var parent = form.findField('parent_id');
									namjab.setValue(rec.get('text'));
									id.setValue(rec.get('id'));
									parent.setValue(rec.get('parent_id'));		
									store_pelaksana.load();							
								});
								win_sotk.doLayout();						
								win_sotk.show();							
						},
						failure: function(response) {
							Ext.MessageBox.alert('Failure', 'Insert Data Error due to connection problem!');
						}
					});                	
                	/*			
                    var rec = grid.getStore().getAt(rowIndex);
					win_sotk.on('show', function(win) {
						win_sotk.setTitle('Form Struktur Organisasi :: Sub :: '+rec.get('text'));
						var form = frm_sotk.getForm();
						var namjab = form.findField('nama_jabatan');
						var id = form.findField('id');
						var parent = form.findField('parent_id');
						namjab.setValue(rec.get('text'));
						id.setValue(rec.get('id'));
						parent.setValue(rec.get('parent_id'));
					});
					win_sotk.doLayout();						
					win_sotk.show();
					*/
                }
            },
			{
                icon: '<?=base_url();?>assets/images/delete.png',
                tooltip: 'Delete',
                handler: function(grid, rowIndex, colIndex) {
					var rec = grid.getStore().getAt(rowIndex);
					Ext.MessageBox.confirm('Hapus', 'Apakah anda akan menghapus item ini (' +rec.get('text')+ ') ?', function(resbtn){
						if(resbtn == 'yes')
						{				
							Ext.Ajax.request({
								url: '<?=base_url();?>index.php/pemetaan_jabatan/main/hapus_struktur_org',
								method: 'POST',											
								params: {
									'id' : rec.get('id'),
								},								
								success: function(response) {
									Ext.MessageBox.alert('Status', response.responseText, function(a,txt)
									{
										store_sotk.load();
									});
								},
								failure: function(response) {
									Ext.MessageBox.alert('Failure', 'Insert Data Error due to connection problem!');
								}
							});				
						}
					});
                }
            }
			]
        },		
		{
            xtype: 'treecolumn', 
            text: 'Jabatan / Instansi',
            flex: 4,
            sortable: true,
            dataIndex: 'text'
        },
		/*
		{
            text: 'ID',
            flex: 1,
            dataIndex: 'id',
            sortable: true
        },
		*/		
		],
		dockedItems: [
			{
				xtype: 'toolbar',
				id: 'tree_jabatan',
				items: [
					{
						xtype: 'button',
						iconCls: 'icon-add',
						text: 'Tambah Data',
						handler: function()
						{
                			win_sotk.on('show', function(win) {
									win_sotk.setTitle('Form Struktur Organisasi');
									var form = frm_sotk.getForm();
									form.reset();
									var parent = form.findField('parent_id');
									parent.setValue(0);
							});
                			win_sotk.doLayout();						
                			win_sotk.show();
						}
					},
					{
						xtype: 'button',
						iconCls: 'icon-reload',
						text: 'Refresh',
						handler: function()
						{
							store_sotk.load();
						}
					},
				]
			}
		],	
		listeners: {
			itemclick : function(view,rec,item,index,eventObj) {
					Ext.Ajax.request({
						url: '<?=base_url();?>index.php/pemetaan_jabatan/main/set_id_parent_sotk',
						method: 'POST',											
						params: {
							'id' : rec.get('id'),
						},								
						success: function(response) {
							grid_pelaksana.setTitle('Data Pelaksana :: ' +rec.get('text'));
							store_pelaksana.load();
						},
						failure: function(response) {
							Ext.MessageBox.alert('Failure', 'Insert Data Error due to connection problem!');
						}
					});
            }				
		}			
    });	
	// end kedudukan sotk
	
	
	var panel_diagram = Ext.create('Ext.panel.Panel', {
		id: 'panel_diagram',
		autoScroll: true,
        // /layout:'fit',
        title: 'Diagram Pemetaan Jabatan',
        items: [
            {
                id: 'id-panel-diagram-sotk',
                html: '<iframe id="if-diagram" height="800" frameborder="0" width="1024" src="<?=$this->config->item('base_url');?>index.php/pemetaan_jabatan/main/diagram_sotk/"></iframe>',
                autoScroll: true,
            },        
        ],
        dockedItems: {
            itemId: 'toolbar',
            xtype: 'toolbar',
            items: [
				{
					text: 'Refresh',
					iconCls: 'icon-reload',
					id: 'refresh-diagram',
					handler: function()
					{
						var iframe = document.getElementById('if-diagram');
						iframe.src = iframe.src;
					}
				},'-',
				{
					text: 'Print',
					iconCls: 'icon-print',
					id: 'print-diagram',
				}
            ]
        },        
    });  

	var panel_jabatan = Ext.create('Ext.panel.Panel', {
		id: 'panel_jabatan',
        layout:'fit',
        items: [
        		{
					layout: 'border',
					items: [
					{
						region: 'north',
						//stateId: 'navigation-panel',
						//id: 'struktur', 
						title: 'Data Pemetaan',
						split: true,
						height: '50%',
						margins: '1 0 0 0',
						layout: 'fit',
						items: [tree_sotk]
					},
					{
						region: 'south',
						//stateId: 'navigation-panel',
						//id: 'diagram', 
						title: 'Data Pelaksana',
						height: '50%',
						margins: '1 0 0 0',
						layout: 'fit',
						autoScroll: true,
						items: [grid_pelaksana],
					}
				]
			}
        ],
    });  

	// rekap data
    Ext.define('mdl_rekap', {
        extend: 'Ext.data.Model',
        fields: [ "tahun","jenis_jabatan","jml"],
        idProperty: 'id'
    });

    var store_rekap = Ext.create('Ext.data.Store', {
        pageSize: 50,
        model: 'mdl_rekap',
        remoteSort: true,
        proxy: {
            url: '<?=base_url()?>index.php/pemetaan_jabatan/main/get_rekap',
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
	

var grid_rekap = Ext.create('Ext.grid.Panel', {
	store: store_rekap,
	disableSelection: false,
	loadMask: true,
	viewConfig: {
		trackOver: true,
		stripeRows: true,
	},
	columns:[
	{
		xtype: 'rownumberer',
		width: 35,
		sortable: false
	},
	{
		text: "Kode Jenis Jabatan",
		dataIndex: 'jenis_jabatan',
		flex: 1,
		sortable: false,
	},		
	{
		text: "Jumlah Tersedia",		
		flex: 1,
		sortable: false,		
		dataIndex: 'jml',
	},
	{
		text: "Tahun Anjab",		
		flex: 1,
		sortable: false,		
		dataIndex: 'tahun',
	},
	],
	dockedItems: [
	{
		xtype: 'toolbar',
		dock: 'top',
		items: 
		[
        {
			xtype: 'combo',
			name: 'tahun_anjab',
            fieldLabel: 'Tahun',
			store: { 
				fields: ['tahun'], 
				pageSize: 100, 
				proxy: { 
					type: 'ajax', 
					url: '<?=base_url();?>index.php/pemetaan_jabatan/main/get_tahun_rekap',
					reader: { 
						root: 'data',
						type: 'json' 
					},
					extraParams: {kode_instansi : '<?=$data['kode_instansi'];?>'},
				} 
			},
			triggerAction : 'all',					
			anchor: '100%',
			displayField: 'tahun',
			valueField: 'tahun',
			listeners: {
				'select': function(combo, row, index) {
					store_rekap.getProxy().extraParams = {tahun: row[0].get('tahun')};
					store_rekap.load();
				}
			},																					        	
        },
		]
	}],
	listeners:{
		beforerender:function(){
			store_rekap.load();
		}
	}			
	});

    // second tabs built from JS
    var tabs2 = Ext.widget('tabpanel', {
        //renderTo: 'render',
        activeTab: 0,
        autoScroll: true,
        plain: true,
		//layout: 'fit',
        defaults :{
            autoScroll: true,
            bodyPadding: 0,
        },
        items: [
            {
                title: 'Data Pemetaan Jabatan',
				//items: [tree_sotk],
				items: [panel_jabatan],
				layout: 'fit',
                listeners: {
                    activate: function(tab) {
						//store_sotk.load();
                    }
                }
            }, 
            {
                title: 'Rekapitulasi Data',
				items: [grid_rekap],
				layout: 'fit',
                listeners: {
                    activate: function(tab) {
						//store_rekap.load();
                    }
                }
            }, 
           	panel_diagram
        ]
    });

	var viewport = Ext.create('Ext.Viewport', {
		id: 'border-example',
		layout: 'border',
		items: [
		{
			region: 'west',
			stateId: 'navigation-panel',
			id: 'struktur', 
			title: 'INSTANSI',
			split: true,
			width: '40%',
			//minWidth: 175,
			//maxWidth: 400,
			//collapsible: true,
			//animCollapse: true,
			margins: '1 0 0 0',
			layout: 'fit',
			items: [grid]
		},
		{
			region: 'east',
			stateId: 'navigation-panel',
			id: 'diagram', 
			title: 'DIAGRAM',
			width: '60%',
			disabled: true,
			//minWidth: 600,
			//maxWidth: 700,
			//split: true,
			//collapsible: true,
			//animCollapse: true,
			margins: '1 0 0 0',
			layout: 'fit',
			items: [tabs2],
	        dockedItems: {
	            itemId: 'toolbar',
	            xtype: 'toolbar',
	            items: [
					{
						text: 'Instansi: ',
						id: 'bar-instansi',
					}
	            ]
	        },
		}
		]
	});
});	

	</script>
</head>
<body>
    <div id="topic-grid"></div>	
</body>
</html>
