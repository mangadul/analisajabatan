<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Entry Rekap SDM</title>

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
.icon-print-pdf { background-image:url(<?=base_url(); ?>assets/images/pdf.png) !important; }
.icon-print-xls { background-image:url(<?=base_url(); ?>assets/images/xls.png) !important; }
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
        fields: [ "id", "kode","nama"],
        idProperty: 'id'
    });

    // create the Data Store
    var storestruk = Ext.create('Ext.data.Store', {
        pageSize: 2000,
        model: 'mdl_strc',
        remoteSort: true,
        proxy: {
            url: '<?=base_url()?>index.php/entry_sdm/main/get_jabatan_struktural',
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
            property: 'id',
            direction: 'DESC'
        }],
		autoLoad: true
    });
	
    var pluginExpanded = true;
	var kode='',nama='',IDJab=0;
		
    // create the grid
    var grid = Ext.create('Ext.grid.Panel', {
        store: storestruk,
		disableSelection: false,		
        columns: [
            {text: "Kode", width: 80, dataIndex: 'kode'},
            {text: "Nama", width: 200, dataIndex: 'nama'},
        ],
        width: 540,
        height: 200,
        dockedItems: [
			{
				xtype: 'toolbar',
				items: [
					'->',
					{
                        xtype: 'searchfield',
						remoteFilter: true,
						store: storestruk,
                        //height: 30,
                        id: 'searchField',
                        //styleHtmlContent: true,
						emptyText: 'masukan kode atau nama jabatan',
                        width: 320,
                        fieldLabel: 'Pencarian',
                    },
				]
			}
		],		
		listeners: {
			cellclick: function(view, td, cellIndex, record, tr, rowIndex, e, eOpts) {
				kode = record.get('kode');
				nama = record.get('nama');
				IDJab = record.get('id');
				Ext.Ajax.request({
					url : '<?=$this->config->item('base_url');?>index.php/entry_sdm/isian_jabatan/set_id_jabatan/'+IDJab,
					method : "POST",
					params: {"id": IDJab,"kode":kode, "nama":nama},
					success : function(response, opts) {
						if(response.responseText=='ok')
						{
							Ext.getCmp('stat_instansi').update('Jabatan: '+kode+', '+nama);						
							var panel = Ext.getCmp('diagram');
							panel.setDisabled(false);
							panel.body.update();
							panel.doLayout();
							//window.location=self.location;
						}
					} , 
					failure : function(response, opts) {          
						alert("Error while loading data : "+response.responseText);                  
					}
				});				
			}
		},		
    });
	
    grid.getSelectionModel().on('selectionchange', function(sm, selectedRecord) {
        if (selectedRecord.length) {
            var detailPanel = Ext.getCmp('detailPanel');
			//Ext.MessageBox.alert(selectedRecord[0].data);
            //detailPanel.update(selectedRecord[0].data);
        }
    });	

	
	var ipanel= new Ext.Panel({
		html: '<iframe width="730" height="430" id="ipanel" name="ipanel" frameborder="0" src="<?=$this->config->item('base_url');?>index.php/entry_sdm/main/form_isian_jabatan"></iframe>',
		autoScroll: true,
		id: 'ipanel',
	});
	
	function diagram_panel(id,kode)
	{
		Ext.Ajax.request({
			url : '<?=$this->config->item('base_url');?>index.php/pemetaan_jabatan/main/get_data_str/'+id,
			method : "GET",
			success : function(response, opts) {
				var detailPanel = Ext.getCmp('ipanel');
				detailPanel.body.update('<iframe width="730" height="430" id="ipanel" name="ipanel" frameborder="0" src="<?=$this->config->item('base_url');?>index.php/entry_sdm/main/form_isian_jabatan"></iframe>');			
			} , 
			failure : function(response, opts) {          
				alert("Error while loading data : "+response.responseText);                  
			}
		});
	}
	
    function doScroll(item) {
        var id = item.id.replace('_menu', ''),
            tab = tabs.getComponent(id).tab;
       
        tabs.getTabBar().layout.overflowHandler.scrollToItem(tab);
    }
	
    var menu = Ext.create('Ext.menu.Menu', {
        id: 'mainMenu',
        items: [
            {
                text: 'I like Ext',
                //checkHandler: onItemCheck
            }, 
			{
               text: 'Choose a Date',
               iconCls: 'calendar',
			},
			{
               text: 'Choose a Color',
			}
        ]
    });
	
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
	
    var tabIsian = Ext.widget('tabpanel', {
        activeTab: -1,
		id:'tbisian',
        plain: true,
        tabPosition: 'left',		
		layout: 'fit',
        defaults :{
            autoScroll: true,
            bodyPadding: 5,
			border:false,
			autoScroll: true,
        },
        items: [
			{
                title: 'Keterangan',
                loader: {
					url: '<?=$this->config->item('base_url');?>index.php/entry_sdm/isian_jabatan/tata_cara_pengisian/', 
                    contentType: 'scripts',
					autoLoad: true,
					autoShow: true,
					scripts : true,
                    loadMask: true,
                },
                listeners: {
                    activate: function(tab) {
                        tab.loader.load();
                    }
                }
			},
			{
                title: '1-5. Isian',
                loader: {
					url: '<?=$this->config->item('base_url');?>index.php/entry_sdm/isian_jabatan/point_1_4/', 
                    contentType: 'scripts',
					scripts : true,
                    loadMask: true
                },
                listeners: {
                    activate: function(tab) {
                        tab.loader.load();
                    }
                }
            },			
			{
                title: '6. Uraian Tugas',
				layout: 'fit',
                loader: {
					url: '<?=$this->config->item('base_url');?>index.php/entry_sdm/isian_jabatan/poin_6_uraian_tugas', 
                    contentType: 'scripts',
					scripts : true,
                    loadMask: true
                },
                listeners: {
                    activate: function(tab) {
                        tab.loader.load();
                    }
                }
            },			
			{
                title: '7. Bahan Kerja',
                loader: {
					url: '<?=$this->config->item('base_url');?>index.php/entry_sdm/isian_jabatan/poin_7_bahan_kerja', 
                    contentType: 'scripts',
					scripts : true,
                    loadMask: true
                },
                listeners: {
                    activate: function(tab) {
                        tab.loader.load();
                    }
                }
            },			
			{
                title: '8. Perangkat / Alat Kerja',
                loader: {
					url: '<?=$this->config->item('base_url');?>index.php/entry_sdm/isian_jabatan/poin_8_perangkat_alat_kerja', 
                    contentType: 'scripts',
					scripts : true,
                    loadMask: true
                },
                listeners: {
                    activate: function(tab) {
                        tab.loader.load();
                    }
                }
            },
			{
                title: '9. Hasil Kerja',
                loader: {
					url: '<?=$this->config->item('base_url');?>index.php/entry_sdm/isian_jabatan/poin_9_hasil_kerja', 
                    contentType: 'scripts',
					scripts : true,
                    loadMask: true
                },
                listeners: {
                    activate: function(tab) {
                        tab.loader.load();
                    }
                }
            },
			{
                title: '10. Tanggung Jawab',
                loader: {
					url: '<?=$this->config->item('base_url');?>index.php/entry_sdm/isian_jabatan/poin_10_tanggungjawab', 
                    contentType: 'scripts',
					scripts : true,
                    loadMask: true
                },
                listeners: {
                    activate: function(tab) {
                        tab.loader.load();
                    }
                }
            },
			{
                title: '11. Wewenang',
                loader: {
					url: '<?=$this->config->item('base_url');?>index.php/entry_sdm/isian_jabatan/poin_11_wewenang', 
                    contentType: 'scripts',
					scripts : true,
                    loadMask: true
                },
                listeners: {
                    activate: function(tab) {
                        tab.loader.load();
                    }
                }
            },
			{
                title: '12. Korelasi Jabatan',
                loader: {
					url: '<?=$this->config->item('base_url');?>index.php/entry_sdm/isian_jabatan/poin_12_korelasi_jabatan', 
                    contentType: 'scripts',
					scripts : true,
                    loadMask: true
                },
                listeners: {
                    activate: function(tab) {
                        tab.loader.load();
                    }
                }
            },
			{
                title: '13. Kondisi Lingkungan kerja',
                loader: {
					url: '<?=$this->config->item('base_url');?>index.php/entry_sdm/isian_jabatan/poin_13_kondisi_lingkungan_kerja', 
                    contentType: 'scripts',
					scripts : true,
                    loadMask: true
                },
                listeners: {
                    activate: function(tab) {
                        tab.loader.load();
                    }
                }
            },
			{
                title: '14. Resiko Bahaya',
                loader: {
					url: '<?=$this->config->item('base_url');?>index.php/entry_sdm/isian_jabatan/poin_14_resiko_bahaya', 
                    contentType: 'scripts',
					scripts : true,
                    loadMask: true
                },
                listeners: {
                    activate: function(tab) {
                        tab.loader.load();
                    }
                }
            },
			{
                title: '15. Syarat Jabatan',
                loader: {
					url: '<?=$this->config->item('base_url');?>index.php/entry_sdm/isian_jabatan/poin_15_syarat_jabatan', 
                    contentType: 'scripts',
					scripts : true,
                    loadMask: true
                },
                listeners: {
                    activate: function(tab) {
                        tab.loader.load();
                    }
                }
            },
			{
                title: '16. Standard Prestasi kerja',
                loader: {
					url: '<?=$this->config->item('base_url');?>index.php/entry_sdm/isian_jabatan/poin_16_standar_prestasi_kerja', 
                    contentType: 'scripts',
					scripts : true,
                    loadMask: true
                },
                listeners: {
                    activate: function(tab) {
                        tab.loader.load();
                    }
                }
            },
			{
                title: '17. Butir Informasi Lain',
                loader: {
					url: '<?=$this->config->item('base_url');?>index.php/entry_sdm/isian_jabatan/poin_17_informasi_lain', 
                    contentType: 'scripts',
					scripts : true,
                    loadMask: true
                },
                listeners: {
                    activate: function(tab) {
                        tab.loader.load();
                    }
                }
            },
			/*
			{
                title: 'Event Tab',
                listeners: {
                    activate: function(tab){
                        setTimeout(function() {
                            alert(tab.title + ' was activated.');
                        }, 1);
                    }
                },
                html: "I am tab 4's content. I also have an event listener attached."
            },
			*/
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
			title: 'JABATAN',
			width: '30%',
			margins: '1 0 0 0',
			layout: 'fit',
			split: true,
			items: [grid]
		},		
		{
			region: 'east',
			stateId: 'navigation-panel',
			id: 'diagram', 
			title: 'Formulir Isian Jabatan',
			disabled: true,
			width: '70%',
			margins: '1 0 0 0',
			layout: 'fit',
			items: [tabIsian],
			dockedItems: [
				menu,
				{
					xtype: 'toolbar',
					id: 'tb',
					items: [
						{
							text: 'Jabatan: {belum dipilih}',
							id: 'stat_instansi',
						},'-',
						{
							xtype: 'button',
							iconCls: 'icon-print-preview',
							text: 'Print Preview',
							id: 'print_preview',
						},'-',
						{
							xtype: 'button',
							iconCls: 'icon-print',
							text: 'Print',
							id: 'print',
						},
						{
							xtype: 'button',
							iconCls: 'icon-print-xls',
							text: 'Export to XLS',
							id: 'print_xls',
						},'-',
						{
							xtype: 'button',
							iconCls: 'icon-print-pdf',
							text: 'Export to PDF',
							id: 'print_pdf',
						},
					]
				}
			],
		}]
	});
});	

	</script>
</head>
<body>
    <div id="topic-grid"></div>	
</body>
</html>
