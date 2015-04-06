<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Formulir Isian Jabatan</title>

<link rel="stylesheet" type="text/css" href="<?=base_url()?>resources/ext4/examples/shared/example.css" />
<script type="text/javascript" src="<?=base_url()?>resources/ext4/examples/shared/include-ext.js"></script>

<link rel="stylesheet" href="<?=$this->config->item('base_url');?>assets/css/ExtJSOrgChart.css"/>
<script type="text/javascript" src="<?=$this->config->item('base_url');?>assets/js/ExtJSOrgChart.js"></script>
<link href="<?=$this->config->item('base_url');?>resources/ext4/resources/css/ext-all.css" rel="stylesheet" type="text/css" />


<!--
<link rel="stylesheet" type="text/css" href="<?=base_url()?>resources/ext4/examples/ux/css/GroupTabPanel.css" />
<link rel="stylesheet" href="<?=$this->config->item('base_url');?>assets/css/bootstrap.min.css"/>
-->
<!--
<script type="text/javascript" src="<?=base_url()?>resources/ext4/examples/shared/options-toolbar.js"></script>
-->

<script type="text/javascript">
Ext.Loader.setConfig({enabled: true});

Ext.Loader.setPath('Ext.ux', '<?=base_url()?>resources/ext4/examples/ux/');
Ext.require([
    '*',
    'Ext.grid.*',
    'Ext.data.*',
    'Ext.util.*',
    'Ext.toolbar.Paging',
	'Ext.Viewport',
	'Ext.data.JsonStore',
	'Ext.tab.Panel',
	'Ext.ux.GroupTabPanel',
    'Ext.ux.PreviewPlugin',
    'Ext.ux.DataTip',	
    'Ext.ModelManager',	
	'Ext.ux.form.SearchField',
    'Ext.tip.QuickTipManager'
]);

Ext.onReady(function(){
    Ext.tip.QuickTipManager.init();
	
    var required = '<span style="color:red;font-weight:bold" data-qtip="Required">*</span>';
	
    // create some portlet tools using built in Ext tool ids
    var tools = [{
        type: 'gear',
        handler: function () {
            Ext.MessageBox.alert('Message', 'The Settings tool was clicked.');
        }
    }, 
	{
        type: 'close',
        handler: function (e, target, panel) {
           panel.ownerCt.remove(panel, true);
        }
    }];

/*
1. Nama jabatan
2. Kode Jabatan
3. Unit Organisasi
4. Kedudukan Dalam Struktur Organisasi
5. Ikhtisar Jabatan
6. Uraian Tugas
7. Bahan Kerja (tabel 2 kolom: bahan kerja, penggunaan dalam tugas)
8. Perangkat / Alat Kerja (tabel 2 kolom, perangkat kerja, digunakan utk tugas)
9. Hasil Kerja (tabel 2 kolom, hasil kerja, satuan hasil)
10. Tanggung Jawab
11. Wewenang
12. Korelasi Jabatan (tabel 3 kolom, jabatan, unit kerja, dalam hal)
13. Kondisi Lingkungan kerja (tabel 2 kolom, aspek, faktor)
14. Resiko Bahaya (tabel 2 kolom, fisik/mental, penyebab)
15. Syarat Jabatan
16. Standard Prestasi kerja (tabel 3 kolom, hasil kerja, jumlah hasil, waktu penyelesaian)
17. Butir Informasi Lain
*/

	
    Ext.create('Ext.Viewport', {
        layout: 'fit',
		width: 400,
        items: [{
            xtype: 'grouptabpanel',
            activeGroup: 0,
            items: [{
                mainItem: 0,
                expanded: true,	
				border: true,
                items: [
				{
                    xtype: 'portalpanel',
                    title: 'Data Formulir Isian Jabatan',
                    tabTip: 'Dashboard tabtip',
                }, 								
				{
                    title: '1. Nama jabatan',
                    tabTip: 'Dashboard tabtip',
					html: 'tes',
                },
				{
                    title: '2. Kode Jabatan',
                    tabTip: 'Users tabtip',
                    style: 'padding: 10px;',
                },
				{
                    title: '3. Unit Organisasi',
                    iconCls: 'x-icon-users',
                    tabTip: 'Users tabtip',
                    style: 'padding: 10px;',
                },				
				{
                    title: '4. Kedudukan Dalam Struktur Organisasi',
                    iconCls: 'x-icon-users',
                    tabTip: 'Users tabtip',
                    style: 'padding: 10px;',
                },				
				{
                    title: '5. Ikhtisar Jabatan',
                    iconCls: 'x-icon-users',
                    tabTip: 'Users tabtip',
                    style: 'padding: 10px;',
                },				
				{
                    title: '6. Uraian Tugas',
                    iconCls: 'x-icon-users',
                    tabTip: 'Users tabtip',
                    style: 'padding: 10px;',
                },				
				{
                    title: '7. Bahan Kerja',
                    iconCls: 'x-icon-users',
                    tabTip: 'Users tabtip',
                    style: 'padding: 10px;',
                },				
				{
                    title: '2. Kode Jabatan',
                    iconCls: 'x-icon-users',
                    tabTip: 'Users tabtip',
                    style: 'padding: 10px;',
                },				
				{
                    title: '2. Kode Jabatan',
                    iconCls: 'x-icon-users',
                    tabTip: 'Users tabtip',
                    style: 'padding: 10px;',
                },				
				{
                    title: '2. Kode Jabatan',
                    iconCls: 'x-icon-users',
                    tabTip: 'Users tabtip',
                    style: 'padding: 10px;',
                },				
				{
                    title: '2. Kode Jabatan',
                    iconCls: 'x-icon-users',
                    tabTip: 'Users tabtip',
                    style: 'padding: 10px;',
                },				
				{
                    title: '2. Kode Jabatan',
                    iconCls: 'x-icon-users',
                    tabTip: 'Users tabtip',
                    style: 'padding: 10px;',
                },				
				{
                    title: '2. Kode Jabatan',
                    iconCls: 'x-icon-users',
                    tabTip: 'Users tabtip',
                    style: 'padding: 10px;',
                },				
				{
                    title: '2. Kode Jabatan',
                    iconCls: 'x-icon-users',
                    tabTip: 'Users tabtip',
                    style: 'padding: 10px;',
                },				
				{
                    title: '2. Kode Jabatan',
                    iconCls: 'x-icon-users',
                    tabTip: 'Users tabtip',
                    style: 'padding: 10px;',
                },				
				{
                    title: '2. Kode Jabatan',
                    iconCls: 'x-icon-users',
                    tabTip: 'Users tabtip',
                    style: 'padding: 10px;',
                },				
				
				]
            }, 
			]
        }]
    });
});
</script>

   <!-- page specific -->
    <style type="text/css">
        /* styles for iconCls */
        .x-icon-tickets {
            background-image: url('images/tickets.png');
        }
        .x-icon-subscriptions {
            background-image: url('images/subscriptions.png');
        }
        .x-icon-users {
            background-image: url('images/group.png');
        }
        .x-icon-templates {
            background-image: url('images/templates.png');
        }
    </style>
	
</head>
<body>
</body>
</html>