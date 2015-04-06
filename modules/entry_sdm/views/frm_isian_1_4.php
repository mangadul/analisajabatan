<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Entry Rekap SDM</title>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>resources/ext4/examples/shared/example.css" />
<script type="text/javascript" src="<?=base_url()?>resources/ext4/examples/shared/include-ext.js"></script>
<link href="<?=$this->config->item('base_url');?>resources/ext4/resources/css/ext-all.css" rel="stylesheet" type="text/css" />
<!--
<link rel="stylesheet" href="<?=$this->config->item('base_url');?>assets/css/bootstrap.min.css"/>
-->
<style>
.icon-add { background-image:url(<?=base_url(); ?>assets/images/add.gif) !important; }
.tabs { background-image:url(<?=base_url(); ?>assets/images/tabs.gif ) !important; }
.icon-reload { background-image:url(<?=base_url(); ?>assets/images/reload.png ) !important; }
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

    var simple = Ext.widget({
        xtype: 'form',
        layout: 'form',
		//renderTo: 'render',
        id: 'simpleForm',
        url: '<?php echo base_url();?>index.php/entry_sdm/main/simpan_poin_1_4',
        frame: false,
		border: false,
        title: 'Isian Poin 1 - 5',
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
                xtype: 'fieldset',
                title: 'Data Umum / header',
                layout: 'anchor',
                defaults: {
                    anchor: '100%'
                },
                items: [
                /*               		
				{
					xtype: 'hiddenfield',
					name: 'id_jabatan',
					value: '<?php echo $data['id']?>'
				},
				*/
				{
					xtype: 'hiddenfield',
					name: 'id_instansi',
					value: '<?php echo $data['kode_instansi']?>'
				},						
				{
					xtype: 'textfield',			
					fieldLabel: '1.	Nama Jabatan',
					name: 'nama_jabatan',
					value: '<?php echo $data['nama']?>',
					readOnly: true,
					tooltip: 'Nama Jabatan'
				},
				{
					fieldLabel: '2.	Kode Jabatan',
					xtype: 'textfield',			
					value: '<?php echo $data['kode']?>',
					readOnly: true,
					name: 'kode_jabatan',
					allowBlank: false,
					tooltip: 'Kode Jabatan'
				},
				{
					xtype: 'textfield',			
					fieldLabel: '3.	Unit Organisasi',
					name: 'unit_organisasi',
					allowBlank: true,
					tooltip: "Unit Organisasi",
					value: '<?=$frm['unit_organisasi']?>'
				}, 
				{
					xtype: 'textfield',			
					fieldLabel: 'Eselon I',
					name: 'unit_org_eselon_1',
					tooltip: "Eselon I",
					value: '<?=$frm['unit_org_eselon_1']?>'					
				}, 
				{
					xtype: 'textfield',			
					fieldLabel: 'Eselon II',
					name: 'unit_org_eselon_2',
					tooltip: "Eselon II",
					value: '<?=$frm['unit_org_eselon_2']?>'
				}, 
				{
					xtype: 'textfield',			
					fieldLabel: 'Eselon III',
					name: 'unit_org_eselon_3',
					tooltip: "Eselon III",
					value: '<?=$frm['unit_org_eselon_3']?>'					
				}, 
				{
					xtype: 'textfield',			
					fieldLabel: 'Eselon IV',
					name: 'unit_org_eselon_4',
					tooltip: "Eselon IV",
					value: '<?=$frm['unit_org_eselon_4']?>'					
				}, 
				{
					xtype: 'textareafield',			
					fieldLabel: '5.	Ikhtisar Jabatan',
					name: 'ikhtisar_jabatan',
					tooltip: "Ikhtisar Jabatan",
					value: '<?=$frm['ikhtisar_jabatan']?>'					
				},				
			],
		},
		/*
		new Ext.Panel({
			applyTo:Ext.getBody(),
			//title:'Kedudukan Dalam struktur Organisasi',
			width:'100%',
			height:'100%',
			frame:false,
			autoLoad:{
			  url:'your.htm'
			}
		}),		
		*/
		{
                xtype: 'fieldset',
                title: 'Tanda Tangan / footer',
                layout: 'anchor',
                defaults: {
                    anchor: '100%'
                },				
                items: [               	
				{
					xtype: 'textfield',
					fieldLabel: 'Tempat Dibuat',
					name: 'tempat_dibuat',
					tooltip: "Tempat dibuat",
					value: '<?=$frm['tempat_dibuat']?>'
				},		
				{
					xtype: 'datefield',
					format: "Y-m-d",
					fieldLabel: 'Tanggal Dibuat',
					name: 'tgl_dibuat',
					tooltip: "Tanggal dibuat",
					value: '<?=$frm['tgl_dibuat']?>'
				},
				{
			            xtype: 'fieldset',
			            title: 'Atasan',
			            layout: 'anchor',
			            defaults: {
			                anchor: '100%'
			            },				
			            items: [               	

					{
						xtype: 'textfield',
						fieldLabel: 'Nama Atasan Langsung',
						name: 'atasan_langsung',
						tooltip: "Atasan Langsung",
						value: '<?=$frm['atasan_langsung']?>'						
					},		
					{
						xtype: 'textfield',
						fieldLabel: 'NIP Atasan',
						name: 'nip_atasan',
						tooltip: "NIP Atasan",
						value: '<?=$frm['nip_atasan']?>'						
					},
					{
						xtype: 'textfield',
						fieldLabel: 'Jabatan',
						name: 'jabatan_atasan_langsung',
						tooltip: "Jabatan Atasan",
						value: '<?=$frm['jabatan_atasan_langsung']?>'						
					},					
					]		
				},
				{
		                xtype: 'fieldset',
		                title: 'Pembuat',
		                layout: 'anchor',
		                defaults: {
		                    anchor: '100%'
		                },				
		                items: [               	

						{
							xtype: 'textfield',
							fieldLabel: 'Nama Yang Membuat',
							name: 'yg_membuat',
							tooltip: "Yang Membuat",
							value: '<?=$frm['yg_membuat']?>'
						},		
						{
							xtype: 'textfield',
							fieldLabel: 'NIP Pembuat',
							name: 'nip_pembuat',
							tooltip: "NIP Pembuat",
							value: '<?=$frm['nip_pembuat']?>'
						},
						{
							xtype: 'textfield',
							fieldLabel: 'Jabatan',
							name: 'jabatan_yg_membuat',
							tooltip: "Jabatan Yang Membuat",
							value: '<?=$frm['jabatan_yg_membuat']?>'
						},
						],
				},		
				{
					xtype: 'hiddenfield',
					name: 'tgl_entry',
					value: '<?=date("Y-m-d H:i:s")?>'
				},		
				]
		}		
        ],
        buttons: [{
            text: 'Save',
			handler: function() {
				var form = this.up('form').getForm();
				if (form.isValid()) {
					form.submit({
						success: function(form, action) {
						   Ext.MessageBox.alert('Success', action.result.message, function(btn, text){
							if (btn == 'ok'){
								//simple.getForm().reset();
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
        },{
            text: 'Cancel',
            handler: function() {
                this.up('form').getForm().reset();
            }
        }]
    });

	// kedudukan dalam sotk
    var store_sotk = Ext.create('Ext.data.TreeStore', {
        proxy: {
            type: 'ajax',
            reader: 'json',
            url: '<?php echo base_url();?>index.php/entry_sdm/isian_jabatan/get_data_sotk',
			node:'id',
        }
    });
		
	store_sotk.load();
	
    var frm_sotk = Ext.widget({
        xtype: 'form',
        layout: 'form',
        id: 'idfrmsotk',
        url: '<?php echo base_url();?>index.php/entry_sdm/isian_jabatan/save_sotk',
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
            value: '<?php echo $data["kode"];?>',
        },
		{
            xtype: 'hiddenfield',
            name: 'kode_instansi',
            value: '<?php echo $data["kode_instansi"];?>',
        },		
		{
            xtype: 'hiddenfield',
            name: 'parent_id',
        },
		{
            xtype: 'textfield',			
            fieldLabel: 'Nama Instansi / Jabatan',
            name: 'nama_jabatan',
            allowBlank: false,
			afterLabelTextTpl: required,			
            tooltip: 'Nama Instansi / Jabatan'
        },
		{
            xtype: 'checkboxfield',
            name: 'posisi_jabatan',
            fieldLabel: 'Posisi Jabatan',
            boxLabel: 'Posisi Jabatan yang mau diarsir',
 			//inputValue:1,
 			scope: this,
           handler: function (field, value) {
                scope: this,
                this.checkValue = field.getValue();
                console.log(this.checkValue);
                if (this.checkValue == true) {
                	this.inputValue =1;
                }
                else if (this.checkValue == false) {
                	this.inputValue =0;
                }
            }
        }, 			
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
								frm_sotk.getForm().reset();
								store_sotk.getRootNode().removeAll();
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
                this.up('form').getForm().reset();
            }
        }
		]
    });
	
	var win_sotk = Ext.create('Ext.window.Window',{
		layout: 'fit',
		items: [frm_sotk],
		closeAction: 'hide',
		closable: true,
		title: 'Form Struktur Organisasi',
		width: '50%',
		height: '20%',
		modal: true,
	});	
	
    //var pluginExpanded = true;	
    var tree_sotk = Ext.create('Ext.tree.Panel', {
		height: 400,
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
						url: '<?=base_url();?>index.php/entry_sdm/isian_jabatan/set_id_parent_sotk',
						method: 'POST',											
						params: {
							'id' : rec.get('id'),
						},								
						success: function(response) {
								win_sotk.on('show', function(win) {
									win_sotk.setTitle('Form Struktur Organisasi :: Sub :: '+rec.get('text'));
									var form = frm_sotk.getForm();
									//form.reset();
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
					win_sotk.on('show', function(win) {
						win_sotk.setTitle('Form Struktur Organisasi :: Sub :: '+rec.get('text'));
						var form = frm_sotk.getForm();
						var namjab = form.findField('nama_jabatan');
						var id = form.findField('id');
						var parent = form.findField('parent_id');
						namjab.setValue(rec.get('text'));
						id.setValue(rec.get('id'));
						parent.setValue(rec.get('parent_id'));
							Ext.Ajax.request({
								url: '<?=base_url();?>index.php/entry_sdm/isian_jabatan/get_posisi',
								method: 'POST',											
								params: {
									'id' : rec.get('id'),
								},								
								success: function(response) {
									if(response.responseText == '1') 
									{
										//console.log("posisi 1");
										form.findField('posisi_jabatan').setValue(true);
										//pos.checked=true;
									} else form.findField('posisi_jabatan').setValue(false);
								},
								failure: function(response) {
									Ext.MessageBox.alert('Failure', 'Insert Data Error due to connection problem!');
								}
							});				

					});
					win_sotk.doLayout();						
					win_sotk.show();
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
								url: '<?=base_url();?>index.php/entry_sdm/isian_jabatan/hapus_struktur_org',
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
		{
            text: 'ID',
            flex: 1,
            dataIndex: 'posisi_jabatan',
            sortable: true
        },
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
    });	
	// end kedudukan sotk

    var diagram_so = Ext.create('Ext.panel.Panel', {
        width: '100%',
        height: '100%',
        layout:'fit',
        constrain:true,
        items:[{
        xtype : "component",
        autoEl:{
            tag:'iframe',
            id: 'idso',
            src :'<?=$this->config->item('base_url');?>index.php/entry_sdm/isian_jabatan/diagram_sotk/'
        }
        }]
        ,
        dockedItems: [
            {
                xtype: 'toolbar',
                items: [
                    {
                        text: 'Refresh',
                        handler: function()
                        {
                        },
                    },
                  ]
            }
        ]
    });    

    var ddso = Ext.create('Ext.panel.Panel', {
        width: '100%',
        height: '100%',
        layout:'fit',
        //constrain:true,
        items:[
        {
			html: '<iframe height="500" width="100%" id="iddso" src="<?=$this->config->item('base_url');?>index.php/entry_sdm/isian_jabatan/diagram_sotk/"></iframe>'
        }
        ]
        ,
        dockedItems: [
            {
                xtype: 'toolbar',
                items: [
                    {
                        text: 'Refresh',
                        handler: function()
                        {
							document.getElementById('iddso').contentWindow.location.reload();
                        },
                    },
                  ]
            }
        ]
    });    

    // second tabs built from JS
    var tabs2 = Ext.widget('tabpanel', {
        renderTo: 'render',
        activeTab: 0,
        plain: true,
		layout: 'fit',
		constraint: true,
        defaults :{
            autoScroll: true,
            bodyPadding: 0,
        },
        items: [
        	{
                title: 'Poin 1,2,3,5',
                items: [simple],
            },
            {
                title: '4. Kedudukan Dalam Struktur Organisasi',
				items: [tree_sotk],
				layout: 'fit',
                listeners: {
                    activate: function(tab) {
						store_sotk.load();					
                    }
                }
            },
            {            	
                title: 'Diagram Struktur Organisasi <?php echo $data['kode'];?>',
                items: [ddso],
            },
        ]
    });

    var panel_diagram = Ext.create('Ext.panel.Panel', {
        layout:'fit',
        loader: {
            url: '<?=$this->config->item('base_url');?>index.php/entry_sdm/isian_jabatan/cetak_jabatan/', 
            contentType: 'scripts',
            autoLoad: true,
            autoShow: true,
            scripts : true,
            loadMask: true,
        },
        listeners: {
            activate: function(tab) {
				store_sotk.load();					
            }
        }
    });
	
});	

	</script>
</head>
<body>
    <div id="render"></div>	
</body>
</html>