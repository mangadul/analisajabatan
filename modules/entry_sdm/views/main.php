<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Entry Rekap SDM</title>
<!--
<link rel="stylesheet" type="text/css" href="<?=base_url()?>resources/ext4/examples/shared/example.css" />
-->
<script type="text/javascript" src="<?=base_url()?>resources/ext4/examples/shared/include-ext.js"></script>
<link href="<?=$this->config->item('base_url');?>resources/ext4/resources/css/ext-all.css" rel="stylesheet" type="text/css" />

<script type="text/javascript">
Ext.Loader.setConfig({enabled: true});

Ext.Loader.setPath('Ext.ux', '<?=base_url()?>resources/ext4/examples/ux/');
Ext.require([
    'Ext.grid.*',
    'Ext.data.*',
    'Ext.util.*',
    'Ext.toolbar.Paging',
    'Ext.ux.PreviewPlugin',
    'Ext.ModelManager',
    'Ext.ux.DataTip',	
	'Ext.ux.form.SearchField',
    'Ext.tip.QuickTipManager'
]);

Ext.onReady(function(){
    Ext.tip.QuickTipManager.init();
	
    var required = '<span style="color:red;font-weight:bold" data-qtip="Required">*</span>';
	
    Ext.define('mdl_sdm', {
        extend: 'Ext.data.Model',
        fields: [ "id", "kode_instansi", "nip", "struktural", "fungsional", "periode", "jabatan_terakhir", "nama",
					"pangkat", "golongan", "pendidikan", "nama_jabatan", "nama_kota","id_jabatan","nama_instansi", "jabatan",
					"pangkat_txt", "golongan_txt", "pendidikan_txt", "nm_instansi"
        ],
        idProperty: 'id'
    });

    // create the Data Store
    var store = Ext.create('Ext.data.Store', {
        pageSize: 100,
        model: 'mdl_sdm',
        remoteSort: true,
        proxy: {
            url: '<?=base_url()?>index.php/entry_sdm/main/get_data_sdm',
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

    // pluggable renders
    function renderTopic(value, p, record) {
        return Ext.String.format(
            '<b><a href="http://sencha.com/forum/showthread.php?t={2}" target="_blank">{0}</a></b><a href="http://sencha.com/forum/forumdisplay.php?f={3}" target="_blank">{1} Forum</a>',
            value,
            record.data.forumtitle,
            record.getId(),
            record.data.forumid
        );
    }

    function renderLast(value, p, r) {
        return Ext.String.format('{0}<br/>by {1}', Ext.Date.dateFormat(value, 'M j, Y, g:i a'), r.get('lastposter'));
    }

    var frmUpload = Ext.create('Ext.form.Panel', {
        width: 500,
        frame: false,
        bodyPadding: '10 10 0',
        defaults: {
            anchor: '100%',
            allowBlank: false,
            msgTarget: 'side',
            labelWidth: 50
        },
        items: [
		{
            xtype: 'filefield',
            id: 'uploadfile',
            emptyText: 'Pilih File',
            fieldLabel: 'Upload File',
            name: 'file',
            buttonText: '',
            buttonConfig: {
                iconCls: 'upload-icon'
            }
        }],

        buttons: [{
            text: 'Save',
            handler: function(){
                var form = this.up('form').getForm();
                if(form.isValid()){
                    form.submit({
                        url: '<?=base_url();?>index.php/entry_sdm/main/upload_sdm',
                        waitMsg: 'Upload data sdm...',
                        success: function(fp, o) {
                        	//console.log(o.result.msg);
                        	Ext.MessageBox('Success', o.result.msg);
                        }
                    });
                }
            }
        },
		{
            text: 'Reset',
            handler: function() {
                this.up('form').getForm().reset();
            }
        },
		]
    });
		
	var winfrmupl = Ext.create('Ext.window.Window',{
		layout: 'fit',
		items: [frmUpload],
		closeAction: 'hide',
		closable: true,
		title: 'Upload File',
		width: 400,
		height: 150,
		modal: true,
	});	
	
   var simple = Ext.widget({
        xtype: 'form',
        layout: 'form',
        id: 'simpleForm',
        url: '<?=base_url();?>index.php/entry_sdm/main/simpan_sdm',
        frame: false,
        bodyPadding: '5 5 0',
        width: '100%',
        height: '100%',
		autoscroll:true,
        fieldDefaults: {
            msgTarget: 'side',
            labelWidth: 75
        },
        plugins: {
            ptype: 'datatip'
        },
        defaultType: 'textfield',
        items: [
		{
			xtype: 'fieldset',
			title: 'Jabatan',
			id:'data_jabatan',
			layout: 'anchor',
			defaults: {
				anchor: '100%'
			},				
			items: [       		
			{
				fieldLabel: 'Kota',
				name: 'nama_kota',
				value: 'CILEGON',
				readOnly: true,
				allowBlank: false,
			},
			Ext.create('Ext.form.ComboBox', {
				fieldLabel: 'Instansi',
				id: 'frm_instansi',
				afterLabelTextTpl: required,
				allowBlank: false,
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
				value :'',							
				emptyText: 'Pilih Instansi...',
				name: 'kode_instansi',
				triggerAction: 'all',
				queryMode: 'remote',
				minChars: 2,
				enableKeyEvents:true,							
				selectOnFocus:true,																												
				typeAhead: true,
				pageSize: true,
				displayField: 'nama',
				valueField: 'kode',
				listeners: {
					'select': function(combo, row, index) {
					}
				},		
			}),		
			{
				fieldLabel: 'NIP',
				afterLabelTextTpl: required,
				name: 'nip',
				id:'nip',
				xtype: 'textfield',			
				emptyText: 'Masukan NIP (angka)...',
				allowBlank: false,
				tooltip: 'NIP',
				listeners: {
					'change': function() 
					{
					  console.log('you changed the text of this input field');
					  var value = Ext.getCmp('nip').getValue().toString();
					  if(value!='')
					  {
							Ext.Ajax.request({
								url: '<?=base_url();?>index.php/entry_sdm/main/get_nama_nip',
								method: 'POST',
								params: {
									'nip' : value.toString(),
								},								
								success: function(response) {
									//Ext.MessageBox.alert('Status', response.responseText);
									Ext.getCmp('nama').setValue(response.responseText);
								},
								failure: function(response) {
									Ext.MessageBox.alert('Failure', 'Error due to connection problem!');
								}
							});				  
					  }
					}
				}			
			},		
			{
				fieldLabel: 'Nama',
				afterLabelTextTpl: required,
				name: 'nama',
				id: 'nama',
				emptyText: 'Masukan nama lengkap...',			
				xtype: 'textfield',			
				allowBlank: false,
				tooltip: 'Masukan nama lengkap'
			},
			Ext.create('Ext.form.ComboBox', {
				fieldLabel: 'Pangkat',
				id: 'pangkat',
				afterLabelTextTpl: required,
				allowBlank: false,
				store: { 
					fields: ['id','pangkat'], 
					pageSize: 200, 
					proxy: { 
						type: 'ajax', 
						url: '<?=base_url();?>index.php/entry_sdm/main/get_pangkat', 
						reader: { 
							root: 'data',
							type: 'json' 
						} 
					} 
				},
				value :'',							
				emptyText: 'Pilih Pangkat...',
				name: 'pangkat',
				triggerAction: 'all',
				queryMode: 'remote',
				minChars: 3,
				enableKeyEvents:true,							
				selectOnFocus:true,																												
				typeAhead: true,
				pageSize: true,
				displayField: 'pangkat',
				valueField: 'id',
				listeners: {
					'select': function(combo, row, index) {
					}
				},		
			}),				
			Ext.create('Ext.form.ComboBox', {
				fieldLabel: 'Golongan',
				id: 'golongan',
				afterLabelTextTpl: required,
				allowBlank: false,
				store: { 
					fields: ['id','golongan'], 
					pageSize: 200, 
					proxy: { 
						type: 'ajax', 
						url: '<?=base_url();?>index.php/entry_sdm/main/get_golongan', 
						reader: { 
							root: 'data',
							type: 'json' 
						} 
					} 
				},
				value :'',							
				emptyText: 'Pilih Golongan...',
				name: 'golongan',
				triggerAction: 'all',
				queryMode: 'remote',
				minChars: 3,
				enableKeyEvents:true,							
				selectOnFocus:true,																												
				typeAhead: true,
				pageSize: true,
				displayField: 'golongan',
				valueField: 'id',
				listeners: {
					'select': function(combo, row, index) {
					}
				},		
			}),
			Ext.create('Ext.form.ComboBox', {
				fieldLabel: 'Pendidikan',
				id: 'pendidikan',
				afterLabelTextTpl: required,
				allowBlank: false,
				store: { 
					fields: ['id','pendidikan'], 
					pageSize: 200, 
					proxy: { 
						type: 'ajax', 
						url: '<?=base_url();?>index.php/entry_sdm/main/get_pendidikan', 
						reader: { 
							root: 'data',
							type: 'json' 
						} 
					} 
				},
				value :'',							
				emptyText: 'Pilih Pendidikan...',
				name: 'pendidikan',
				triggerAction: 'all',
				queryMode: 'remote',
				minChars: 3,
				enableKeyEvents:true,							
				selectOnFocus:true,																												
				typeAhead: true,
				pageSize: true,
				displayField: 'pendidikan',
				valueField: 'id',
				listeners: {
					'select': function(combo, row, index) {
					}
				},		
			}),
			Ext.create('Ext.form.ComboBox', {
				fieldLabel: 'Jabatan',
				id: 'jabatan',
				afterLabelTextTpl: required,
				allowBlank: false,
				store: { 
					fields: ['id','jabatan'], 
					pageSize: 200, 
					proxy: { 
						type: 'ajax', 
						url: '<?=base_url();?>index.php/entry_sdm/main/get_jabatan', 
						reader: { 
							root: 'data',
							type: 'json' 
						} 
					} 
				},
				value :'',							
				emptyText: 'Pilih Jabatan...',
				name: 'id_jabatan',
				triggerAction: 'all',
				queryMode: 'remote',
				minChars: 3,
				enableKeyEvents:true,							
				selectOnFocus:true,																												
				typeAhead: true,
				pageSize: true,
				displayField: 'jabatan',
				valueField: 'id',
				listeners: {
					'select': function(combo, row, index) {
					}
				},		
			}),	
		]
		},
		{
			fieldLabel: 'Struktural',
			name: 'struktural',
			id: 'struktural',
		},	
		{
			fieldLabel: 'Fungsional',
			name: 'fungsional',
			id: 'fungsional,'
		},
		{
			fieldLabel: 'Periode',
			name: 'periode',
			id: 'periode',
		},			
		{
			fieldLabel: 'Jabatan Terakhir',
			name: 'jabatan_terakhir',
			id: 'jabatan_terakhir',
		},		
     ],

        buttons: [{
            text: 'Save',
            handler: function() {
				var form = this.up('form').getForm();
				if (form.isValid()) {
					form.submit({
						success: function(form, action) {
							Ext.MessageBox.alert('Success', action.result.message, function(btn){
								if(btn == 'ok')
								{
									store.load();
									simple.getForm().reset();
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
            }
        },{
            text: 'Cancel',
            handler: function() {
                this.up('form').getForm().reset();
            }
        }]
    });
	
    var pluginExpanded = true;
    var grid = Ext.create('Ext.grid.Panel', {
        title: 'Data SDM',
        store: store,
		//autoScroll: true,
        disableSelection: true,
        loadMask: true,
        viewConfig: {
            trackOver: false,
            stripeRows: false
        },
        // grid columns
        columns:[
		{
			xtype:'actioncolumn',
			items: [
			{
				icon   : '<?=base_url();?>assets/images/edit.png',  
				tooltip: 'Edit item',
				handler: function(grid, rowIndex, colIndex) {
					var rec = store.getAt(rowIndex);
					var id = rec.get('id');
					Ext.MessageBox.confirm('Edit item', 'Apakah anda akan menyunting item ini ('+rec.get('nama')+') ?',function(resbtn){
						if(resbtn == 'yes')
						{
                			win.on('show', function(win) {	   
								simple.getForm().findField('kode_instansi').setValue(rec.get('kode_instansi'));
								simple.getForm().findField('nip').setValue(rec.get('nip'));
								simple.getForm().findField('nama').setValue(rec.get('nama'));
								simple.getForm().findField('pangkat').setValue(rec.get('pangkat'));
								simple.getForm().findField('golongan').setValue(rec.get('golongan'));
								simple.getForm().findField('pendidikan').setValue(rec.get('pendidikan'));
								simple.getForm().findField('jabatan').setValue(rec.get('id_jabatan'));
								simple.getForm().findField('struktural').setValue(rec.get('struktural'));
								simple.getForm().findField('fungsional').setValue(rec.get('fungsional'));
								simple.getForm().findField('periode').setValue(rec.get('periode'));
								simple.getForm().findField('jabatan_terakhir').setValue(rec.get('jabatan_terakhir'));
                				//console.log('instansi: '+rec.get('kode_instansi'));
								//simple.getForm().findField('pangkat').setValue(rec.get('pangkat'));

                			});
                			win.doLayout();
                			win.show();
						}
					})
				}				
			},'-',
			{
				icon   : '<?=base_url();?>assets/images/delete.gif',  
				tooltip: 'Hapus item',
				handler: function(grid, rowIndex, colIndex) {
					var rec = store.getAt(rowIndex);
					var id = rec.get('id');
					Ext.MessageBox.confirm('Delete item', 'Apakah anda akan menghapus item ini ('+rec.get('nama')+') ?',function(resbtn){
						if(resbtn == 'yes')
						{
							Ext.Ajax.request({
								url: '<?=base_url();?>index.php/entry_sdm/main/hapus_sdm',
								method: 'POST',
								params: {
									'id' : id
								},								
								success: function(response) {
									var text = response.responseText;
									Ext.MessageBox.alert( "Status", text, function(){
										store.load();
									});											
								},
								failure: function() {
									Ext.MessageBox.alert( "Error", "Data GAGAL dihapus!");											
								}
							});			   																			
						}
					})
				}				
			},'-',
			{
				icon   : '<?=base_url();?>assets/images/print.png',  
				tooltip: 'Cetak',
				handler: function(grid, rowIndex, colIndex) {
					var rec = store.getAt(rowIndex);
					var id = rec.get('id');
					Ext.MessageBox.confirm('Cetak item', 'Apakah anda akan mencetak item ini ('+rec.get('nama')+') ?',function(resbtn){
						if(resbtn == 'yes')
						{
							/*
							Ext.Ajax.request({
								url: '<?=base_url();?>index.php/entry_sdm/main/cetak',
								method: 'POST',
								params: {
									'id' : id
								},								
								success: function(response) {
									var text = response.responseText;
									Ext.MessageBox.alert( "Status", text, function(){
										store.load();
									});											
								},
								failure: function() {
									Ext.MessageBox.alert( "Error", "Data GAGAL dicetak!");											
								}
							});			   																			
							*/
						}
					})
				}				
			},			
			]
		},		
		/*
		{
            id: 'ID',
            text: "ID",
            dataIndex: 'id',
            sortable: false
        },
        */
		{
            text: "Nama Kota",
            dataIndex: 'nama_kota',
            width: 100,
            hidden: true,
            sortable: true
        },
		{
            text: "Instansi",
            dataIndex: 'nm_instansi',
            width: 150,
            align: 'left',
            sortable: true
        },
		{
            text: "NIP",
            dataIndex: 'nip',
            width: 150,
            sortable: true
        },
		{
            text: "Nama",
            dataIndex: 'nama',
            width: 200,
            sortable: true
        },
		{
            text: "Pangkat",
            dataIndex: 'pangkat_txt',
            width: 150,
            sortable: true
        },				
		{
            text: "Golongan",
            dataIndex: 'golongan_txt',
            width: 150,
            sortable: true
        },				
		{
            text: "Struktural",
            dataIndex: 'struktural',
            width: 150,
            sortable: true
        },
		{
            text: "Pendidikan",
            dataIndex: 'pendidikan_txt',
            width: 150,
            sortable: true
        },
		{
            text: "Nama Jabatan",
            dataIndex: 'jabatan',
            width: 150,
            sortable: true
        },
		{
            text: "Jabatan Fungsional",
            dataIndex: 'fungsional',
            width: 150,
            sortable: true
        },
		{
            text: "Jabatan Terakhir",
            dataIndex: 'jabatan_terakhir',
            width: 150,
            sortable: true
        },
		{
            text: "Periode",
            dataIndex: 'periode',
            width: 150,
            sortable: true
        },
		],
        plugins: [{
            ptype: 'preview',
            bodyField: 'excerpt',
            expanded: true,
            pluginId: 'preview'
        }],
        // paging bar on the bottom
        dockedItems: [
			{
				xtype: 'toolbar',
				items: [					
					Ext.create('Ext.Action', {
						text: 'Tambah Data',
						id: 'tambahdata',
						handler: function(){
                			win.on('show', function(win) {	   
								simple.getForm().reset();
								simple.getForm().findField('nama').setValue('');
								/*
                				store.load({
                					params:{'subbidang_kode':'505'},
                					scope: this,
                				});
                				*/
                			});
                			win.doLayout();						
                			win.show();												
						}
					}),			
					Ext.create('Ext.Action', {
						text: 'Import (csv)',
						id: 'import',
						handler: function() {
                			winfrmupl.on('show', function(win) {	   
                				/*
                				store.load({
                					params:{'subbidang_kode':'505'},
                					scope: this,
                				});
								*/
                			});
                			winfrmupl.doLayout();						
                			winfrmupl.show();						
						}
					}),			
					Ext.create('Ext.Action', {
						text: 'Export (xls)',
						handler: function() {
							Ext.MessageBox.confirm('Konfirmasi Export', 'Export data ke excel akan dilakukan', function(btn){
								if(btn=='yes')
								{
								}
							});
						}
					}),
					Ext.create('Ext.Action', {
						text: 'Cetak',
						id: 'cetak',
						handler: function() {
							Ext.MessageBox.confirm('Konfirmasi Cetak', 'Pencetakan akan dilakukan', function(btn){
								if(btn=='yes')
								{
								}
							});
						}
					}),
					'->',
					{
                        xtype: 'searchfield',
						remoteFilter: true,
						store: store,
                        //height: 30,
                        id: 'searchField',
                        //styleHtmlContent: true,
                        width: 320,
                        fieldLabel: 'Pencarian',
                        emptyText: 'masukan nama atau nip...',
                    },				
				]
			},
		],		
        bbar: Ext.create('Ext.PagingToolbar', {
            store: store,
            displayInfo: true,
            displayMsg: 'Displaying data {0} - {1} of {2}',
            emptyMsg: "No data to display",
            inputItemWidth: 35,
        }),
        //renderTo: 'topic-grid'
    });
	
    Ext.create('Ext.container.Viewport', {
        title: 'Data SDM',
        layout: 'fit',
        //padding: '5',
        items: [grid],		
		//renderTo: Ext.getBody()
    });
	
	var win = Ext.create('widget.window', {
		title: 'Tambah Data Jabatan Pegawai',
		modal: true,
		header: {
			titlePosition: 2,
			titleAlign: 'center'
		},
		closable: true,
		closeAction: 'hide',
		width: '50%',
		minWidth: 500,
		height: 500,
		tools: [{type: 'pin'}],
		layout: {
			type: 'border',
			padding: 5
		},
		items: [
		{
			region: 'center',
			xtype: 'tabpanel',
			items: [
				{
					title: 'Data Jabatan',
					layout: 'fit',
					items: [simple]
				}, 
			]
		}]
	});
		
    // trigger the data store load
    store.loadPage(1);
});	
	</script>
</head>
<body>
    <div id="topic-grid"></div>
</body>
</html>
