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
    'Ext.tree.*',
    'Ext.tip.QuickTipManager',
    'Ext.container.ButtonGroup'
]);

Ext.onReady(function(){
    Ext.tip.QuickTipManager.init();
	
    var required = '<span style="color:red;font-weight:bold" data-qtip="Required">*</span>';
	
    var store6 = Ext.create('Ext.data.TreeStore', {
        proxy: {
            type: 'ajax',
            reader: 'json',
            url: '<?php echo base_url() ?>index.php/entry_sdm/isian_jabatan/get_data_uraian_tugas',
			node:'id',
        }
    });
		
	store6.load();
	
    var frm6 = Ext.widget({
        xtype: 'form',
        layout: 'form',
        id: 'simpleForm',
        url: '<?php echo base_url() ?>index.php/entry_sdm/isian_jabatan/save_uraian_tugas',
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
            value: '<?php echo $data['kode']?>',
        },		
		{
            xtype: 'hiddenfield',
            name: 'parent',
			//value: '0',
        },
		{
            xtype: 'textareafield',			
            fieldLabel: 'Uraian Tugas',
            name: 'uraian_tugas',
            allowBlank: false,
			afterLabelTextTpl: required,			
            tooltip: 'Uraian Tugas'
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
								//frm6.getForm().reset()
								frm6.getForm().findField('uraian_tugas').setValue('');
								store6.getRootNode().removeAll();
								store6.load();
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
	
	var win6 = Ext.create('Ext.window.Window',{
		layout: 'fit',
		items: [frm6],
		closeAction: 'hide',
		closable: true,
		title: 'Form Uraian Tugas',
		width: '50%',
		height: '30%',
		modal: true,
	});	
		
    //var pluginExpanded = true;	
    var treetugas = Ext.create('Ext.tree.Panel', {
        title: '6. Uraian Tugas',
        //renderTo: 'render6',
		height: 470,
        loadMask: true,
        useArrows: true,
        rootVisible: false,
		multiSelect: true,
		singleExpand: true,		
        store: store6,
        //animate: true,
        plugins: [{ptype: 'bufferedrenderer'}],
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
						url: '<?=base_url();?>index.php/entry_sdm/isian_jabatan/set_id_parent_tree',
						method: 'POST',											
						params: {
							'id' : rec.get('id'),
						},								
						success: function(response) {
								win6.on('show', function(win) {
									win6.setTitle('Form Uraian Tugas :: '+rec.get('text'));
									var form = frm6.getForm();
									form.reset();
									var parent = form.findField('parent');
									parent.setValue(rec.get('id'));
								});
								win6.doLayout();						
								win6.show();							
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
					win6.on('show', function(win) {
						win6.setTitle('Form Uraian Tugas :: '+rec.get('text'));
						var form = frm6.getForm();
						var urtag = form.findField('uraian_tugas');
						var id = form.findField('id');
						var parent = form.findField('parent');
						urtag.setValue(rec.get('text'));
						id.setValue(rec.get('id'));
						parent.setValue(rec.get('parent_id'));
					});
					win6.doLayout();						
					win6.show();
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
								url: '<?=base_url();?>index.php/entry_sdm/isian_jabatan/hapus_uraian_tugas',
								method: 'POST',											
								params: {
									'id' : rec.get('id'),
								},								
								success: function(response) {
									Ext.MessageBox.alert('Status', response.responseText, function(a,txt)
									{
										store6.load();
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
            text: 'Uraian Tugas',
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
		{
            text: 'Parent',
            flex: 1,
            dataIndex: 'parent_id',
            sortable: true
        }, 
		*/
		],
		dockedItems: [
			{
				xtype: 'toolbar',
				id: 'urtag',
				items: [
					{
						xtype: 'button',
						iconCls: 'icon-add',
						text: 'Tambah Data',
						handler: function()
						{
                			win6.on('show', function(win) {
									win6.setTitle('Form Uraian Tugas');
									var form = frm6.getForm();
									form.reset();
									var parent = form.findField('parent');
									parent.setValue(0);
							});
                			win6.doLayout();						
                			win6.show();
						}
					},
					{
						xtype: 'button',
						iconCls: 'icon-reload',
						text: 'Refresh',
						handler: function()
						{
							store6.load();
						}
					},
				]
			}
		],	
    });
		
	Ext.create('Ext.panel.Panel', {
		id: 'panel6',
        layout:'fit',
        items: treetugas,
        renderTo: 'render6'
    });  
	
});

	</script>
</head>
<body>
    <div id="render6"></div>	
</body>
</html>