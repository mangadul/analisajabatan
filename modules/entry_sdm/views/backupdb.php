<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Backup Database</title>

<link href="<?=$this->config->item('base_url');?>resources/ext4/resources/css/ext-all.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?=base_url()?>resources/ext4/examples/shared/include-ext.js"></script>
<!--
<link rel="stylesheet" type="text/css" href="<?=base_url()?>resources/ext4/examples/shared/example.css" />
<script type="text/javascript" src="<?=base_url()?>resources/ext4/examples/shared/options-toolbar.js"></script>
-->

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
            labelWidth: 100,
            buttonText: '',
            buttonConfig: {
                iconCls: 'upload-icon'
            },
        }],

        buttons: [{
            text: 'Save',
            handler: function(){
                var form = this.up('form').getForm();
                if(form.isValid()){
                    form.submit({
                        url: '<?=base_url();?>index.php/entry_sdm/main/upload_db',
                        waitMsg: 'Upload Database...',
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

Ext.create('Ext.Button', {
    text: 'Backup database',
    renderTo: Ext.getBody(),
    handler: function() {
		window.location = '<?=base_url()?>index.php/entry_sdm/main/download_sql';
    }
});

Ext.create('Ext.Button', {
    text: 'Optimize database',
    renderTo: Ext.getBody(),
    handler: function() {
        Ext.MessageBox.show({
           title: 'Please wait',
           msg: 'Optimisasi database...',
           progressText: 'progress...',
           width:300,
           progress:true,
           closable:false,
           timeout : 100,
           animateTarget: 'animate'
       });

        Ext.Ajax.request({
            url: '<?=base_url()?>index.php/entry_sdm/main/optimize_tbl',
            method: 'POST',
            params: {
                'action' : 'optimize_tbl',
            },                              
            success: function(response) {
                Ext.MessageBox.alert('Status', response.responseText);
            },
            failure: function(response) {
                Ext.MessageBox.alert('Failure', 'Error due to connection problem!');
            }
        });                       
		//window.location = '<?=base_url()?>index.php/entry_sdm/main/optimize_tbl';	
    }
});

Ext.create('Ext.Button', {
    text: 'Import database (restore)',
    renderTo: Ext.getBody(),
    handler: function() {
        winfrmupl.on('show', function(win) {       
        });
        winfrmupl.doLayout();                       
        winfrmupl.show();                       
    }
});

    var formPanel = Ext.create('Ext.form.Panel', {
        frame: false,
        width: '80%',
		height: '80%',
        bodyPadding: 5,
        fieldDefaults: {
            labelAlign: 'left',
            labelWidth: 90,
            anchor: '100%'
        },

        items: [
		{
            xtype: 'textareafield',
            name: 'status',
			height: 200,
			id:'status',
			//readOnly:true,
            value: '<?php echo !empty($status) ? $status : 'status idle...';?>'
		}
		]
    });

    formPanel.render('form');

});
</script>
</head>
<body>
<h1>Database Tools</h1>
<div id="bck"></div>
<div id="optimize"></div>
<div id="form"></div>
<div id='animate' style="visibility: hidden;"></div>
</body>
</html>