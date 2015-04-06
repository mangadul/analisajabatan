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
	
    var frmpangkat = Ext.widget({
        xtype: 'form',
        layout: 'form',
        id: 'frmpangkatForm',
        url: '<?php echo base_url();?>index.php/entry_sdm/isian_jabatan/simpan_sj_pangkat_golongan',
        frame: false,
		border: false,
        title: 'a. Pangkat dan Golongan',
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
				name: 'kode_jabatan',
				value: '<?=$data['kode'];?>',
			},
			{
				xtype: 'hiddenfield',			
				name: 'id',
				value: '<?=$dt['id'];?>',
			},
			{
				xtype: 'textfield',			
				fieldLabel: 'Pangkat',
				name: 'pangkat',
				value:'<?=$dt['pangkat'];?>',
			},
			{
				xtype: 'textfield',			
				fieldLabel: 'Golongan',
				name: 'golongan',
				value:'<?=$dt['golongan'];?>',
			},
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
								//frmpangkat.getForm().reset();
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

    var frmpengker = Ext.widget({
        xtype: 'form',
		title: 'd. Pengalaman Kerja',
        layout: 'form',
        id: 'frmpengker',
        url: '<?php echo base_url();?>index.php/entry_sdm/isian_jabatan/simpan_sj_pengker',
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
				name: 'kode_jabatan',
				value: '<?=$data['kode'];?>',
			},
			{
				xtype: 'hiddenfield',			
				name: 'id',
				value: '<?=$pengker['id'];?>',
			},
			{
				xtype: 'textfield',			
				fieldLabel: 'Pengalaman Kerja',
				name: 'pengalaman',
				value: '<?=$pengker['pengalaman'];?>',				
			},
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
								//frmpengker.getForm().reset();
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
	
	
    var frmkondisifisik = Ext.widget({
        xtype: 'form',
		title: 'k. Kondisi Fisik',
        layout: 'form',
        id: 'frmkondisifisik',
        url: '<?php echo base_url();?>index.php/entry_sdm/isian_jabatan/simpan_sj_kondisifisik',
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
				name: 'kode_jabatan',
				value: '<?=$data['kode'];?>',
			},
			{
				xtype: 'hiddenfield',			
				name: 'id',
				value: '<?=$df['id'];?>',
			},
			{
				xtype: 'textfield',			
				fieldLabel: 'Jenis Kelamin',
				name: 'jenis_kelamin',
				value: '<?=$df['jenis_kelamin']?>',
			},
			{
				xtype: 'textfield',			
				fieldLabel: 'Umur',
				name: 'umur',
				value: '<?=$df['umur']?>',
			},
			{
				xtype: 'textfield',			
				fieldLabel: 'Tinggi Badan',
				name: 'tinggi_badan',
				value: '<?=$df['tinggi_badan']?>',
			},			
			{
				xtype: 'textfield',			
				fieldLabel: 'Berat Badan',
				name: 'berat_badan',
				value: '<?=$df['berat_badan']?>',
			},			
			{
				xtype: 'textfield',			
				fieldLabel: 'Fostur Badan',
				name: 'postur_badan',
				value: '<?=$df['postur_badan']?>',
			},			
			{
				xtype: 'textfield',			
				fieldLabel: 'Penampilan',
				name: 'penampilan',
				value: '<?=$df['penampilan']?>',
			},			
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
								//frmkondisifisik.getForm().reset();
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
	
	
	var pendidikan = Ext.create('Ext.panel.Panel', {
		layout: 'fit',		
		height: '100%',
		title: 'b. Pendidikan',
		id: 'pendidikan',
		loader: {
			url: '<?=$this->config->item('base_url');?>index.php/entry_sdm/isian_jabatan/sj_pendidikan', 
			contentType: 'scripts',
			scripts : true,
			loadMask: true,
			autoLoad: true,
		},
	});

	var pengetahuan = Ext.create('Ext.panel.Panel', {
		layout: 'fit',		
		height: '100%',
		title: 'e. Pengetahuan Kerja',
		id: 'pengetahuan',
		loader: {
			url: '<?=$this->config->item('base_url');?>index.php/entry_sdm/isian_jabatan/sj_pengetahuan', 
			contentType: 'scripts',
			scripts : true,
			loadMask: true,
			autoLoad: true,
		},
	});
	
	var diklat = Ext.create('Ext.panel.Panel', {
		layout: 'fit',		
		height: '100%',
		title: 'c. Kursus & Diklat',
		id: 'kurdik',
		loader: {
			url: '<?=$this->config->item('base_url');?>index.php/entry_sdm/isian_jabatan/sj_kursus_diklat', 
			contentType: 'scripts',
			scripts : true,
			loadMask: true,
			autoLoad: true,
		},
	});

	var keterampilan = Ext.create('Ext.panel.Panel', {
		layout: 'fit',		
		height: '100%',
		title: 'f. Keterampilan Kerja',
		id: 'keterampilan_kerja',
		loader: {
			url: '<?=$this->config->item('base_url');?>index.php/entry_sdm/isian_jabatan/sj_keterampilan', 
			contentType: 'scripts',
			scripts : true,
			loadMask: true,
			autoLoad: true,
		},
	});

	var bakat_kerja = Ext.create('Ext.panel.Panel', {
		layout: 'fit',		
		height: '100%',
		title: 'g. Bakat Kerja',
		id: 'bakat_kerja',
		loader: {
			url: '<?=$this->config->item('base_url');?>index.php/entry_sdm/isian_jabatan/sj_bakat_kerja', 
			contentType: 'scripts',
			scripts : true,
			loadMask: true,
			autoLoad: true,
		},
	});

	var temperamen = Ext.create('Ext.panel.Panel', {
		layout: 'fit',		
		height: '100%',
		title: 'h. Temperamen Kerja',
		id: 'temperamen_kerja',
		loader: {
			url: '<?=$this->config->item('base_url');?>index.php/entry_sdm/isian_jabatan/sj_temperamen', 
			contentType: 'scripts',
			scripts : true,
			loadMask: true,
			autoLoad: true,
		},
	});

	var minat_kerja = Ext.create('Ext.panel.Panel', {
		layout: 'fit',		
		height: '100%',
		title: 'i. Minat Kerja',
		id: 'minat_kerja',
		loader: {
			url: '<?=$this->config->item('base_url');?>index.php/entry_sdm/isian_jabatan/sj_minat_kerja', 
			contentType: 'scripts',
			scripts : true,
			loadMask: true,
			autoLoad: true,
		},
	});
	
	var upaya_fisik = Ext.create('Ext.panel.Panel', {
		layout: 'fit',		
		height: '100%',
		title: 'j. Upaya Fisik',
		id: 'upaya_fisik',
		loader: {
			url: '<?=$this->config->item('base_url');?>index.php/entry_sdm/isian_jabatan/sj_upaya_fisik', 
			contentType: 'scripts',
			scripts : true,
			loadMask: true,
			autoLoad: true,
		},
	});

	var fungsi_pekerjaan = Ext.create('Ext.panel.Panel', {
		layout: 'fit',		
		height: '100%',
		title: 'l. Fungsi Pekerjaan',
		id: 'fungsi_pekerjaan',
		loader: {
			url: '<?=$this->config->item('base_url');?>index.php/entry_sdm/isian_jabatan/sj_fungsi_pekerjaan', 
			contentType: 'scripts',
			scripts : true,
			loadMask: true,
			autoLoad: true,
		},
	});
	
	Ext.create('Ext.panel.Panel', {
		title: '15. Syarat Jabatan',
		margins: '1 0 0 0',
		layout: 'accordion',	
		loadMask: true,
		modal:true,
		items: [
			frmpangkat,
			pendidikan,
			diklat,
			frmpengker,
			pengetahuan,
			keterampilan,
			bakat_kerja,
			temperamen,
			minat_kerja,
			upaya_fisik,
			frmkondisifisik,
			fungsi_pekerjaan,
		],
        renderTo: 'sarjab'
    });  

});

	</script>
</head>
<body>
    <div id="sarjab"></div>	
</body>
</html>