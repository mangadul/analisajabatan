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
.icon-add {
    background-image:url(<?=base_url(); ?>assets/images/add.gif) !important;
}

.tabs {
    background-image:url(<?=base_url(); ?>assets/images/tabs.gif ) !important;
}
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

    var frm17 = Ext.widget({
        xtype: 'form',
        layout: 'form',
		//renderTo: 'render17',
        id: 'frm17',
        url: '<?=base_url()?>index.php/entry_sdm/main/simpan_infomasi_lain',
        frame: false,
		border: false,
        title: '17. Butir Informasi Lain',
        fieldDefaults: {
            msgTarget: 'side',
            labelWidth: 80
        },
        plugins: {
            ptype: 'datatip'
        },
        defaultType: 'textfield',
        items: [
		{
            xtype: 'hiddenfield',
            name: 'id',
            value: '<?php echo $data['id']?>',
        },		
		{
            xtype: 'hiddenfield',
            name: 'kode_jabatan',
            value: '<?php echo $data['kode']?>'
        },		
		{
		    xtype: 'htmleditor',
            name: 'informasi_lain',
            fieldLabel: 'Informasi Lain',
			value: '<?php echo $info_lain;?>',
            height: 200,
            anchor: '100%'
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
						   Ext.MessageBox.alert('Success', action.result.message, function(btn,text){
							if(btn == 'ok')
							{
								//frm17.getForm().reset();
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
	
	Ext.create('Ext.panel.Panel', {
		id: 'panel17',
        layout:'fit',
        items: [frm17],
        renderTo: 'render17'
    });  
	
		
});

	</script>
</head>
<body>
    <div id="render17"></div>	
</body>
</html>