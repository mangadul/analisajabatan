<style>
.icon-pay {
    background-image: url('<?=base_url()?>resources/images/toolbar/yes.gif') !important;
}
.icon-add {
    background-image: url('<?=base_url()?>resources/images/toolbar/add.gif') !important;
}
.icon-next {
    background-image: url('<?=base_url()?>resources/images/toolbar/next.gif') !important;
}
.icon-remove {
    background-image: url('<?=base_url()?>resources/images/toolbar/delete.gif') !important;
}
.icon-reset {
    background-image: url('<?=base_url()?>resources/images/toolbar/refresh.gif') !important;
}
.icon-save {
    background-image: url('<?=base_url()?>resources/images/toolbar/save.gif') !important;
}

.custom-first-last .x-grid-row-selected .x-grid-cell-last {background-image: url('<?=base_url()?>resources/images/toolbar/yes.gif'); background-position: right; background-repeat: no-repeat;}
</style>

<script type="text/javascript" src="<?=base_url()?>resources/js/jquery.js"></script>
<script type="text/javascript" src="<?=base_url()?>resources/ext4/examples/shared/examples.js"></script>

<script type="text/javascript">

    function columnWrap(val){
        return '<div style="white-space:normal !important;">'+ val +'</div>';
    }
	
</script>

<script type="text/javascript">

var tag_insert = 0;

Ext.Loader.setConfig({enabled: true});
Ext.Loader.setPath('Ext.ux', '<?=base_url()?>resources/ext4/examples/ux');  
Ext.require([
    'Ext.ux.form.SearchField',
    'Ext.data.*', 
    'Ext.grid.*',
    'Ext.form.*',
    'Ext.layout.container.Column',
    'Ext.selection.CellModel',
    'Ext.util.*',
    'Ext.state.*',
    'Ext.ux.CheckColumn',
    'Ext.window.MessageBox',
    'Ext.tip.*'
]);

Ext.onReady(function(){
    Ext.tip.QuickTipManager.init();
 
 	Ext.define('model_pegawai', {
        extend: 'Ext.data.Model',
        fields: ['id_pegawai', 'nip', 'nama', 'nama_jabatan','nama_golongan','alamat_asal','alamat_sekarang',
				 'kelurahan','rt','rw','no_rmh','no_telp','kode_pos','email','agama','jenis_kelamin','tempat_lahir',
				 'tgl_lahir','bulan_lahir','tahun_lahir','pendidikan_terakhir','status_marital','upload',
				 'status_pns','tgl_lahir_gab']
    });
    

    var store = Ext.create('Ext.data.Store', {
        pageSize: 100,
        model: 'model_pegawai',
        proxy: {
            type: 'ajax',
            url:  '<?=base_url()?>index.php/pegawai/main/DataListPegawai',
            reader: {
                root: 'pegawai_data',
                totalProperty: 'totalCount'
            },
            simpleSortMode: true
        },
        sorters: [{
            property: 'nip',
            direction: 'ASC'
        }],
        remoteSort: true
    });
    
      
    function SaveData(form, win) {
		var id = form.getForm().findField('id_pegawai').getValue();
        var nama =  form.query('textfield[name="nama"]')[0].getValue('nama');
		var id_kecamatan =  form.getForm().findField('id_kecamatan').getValue();
        
        var conn = new Ext.data.Connection();
        conn.request({
            method: 'POST',
            url: '<?=base_url()?>index.php/pegawai/main/InsertPegawai',
            params: {
				id : id,
                nama : nama,
				id_kecamatan: id_kecamatan
            },
            success: function() {
                Ext.example.msg('Sukses', 'Data Pegawai sudah tersimpan.');
                form.getForm().reset();
                store.loadPage(1);
                win.hide();
            }
        });
    }

    
    var win;
    var form;
    function FormPegawai(tag){
        if (!win) {
            form = Ext.create('Ext.form.Panel', {
                url: '<?=base_url()?>index.php/pegawai/main/InsertPegawai',
                border: false,
                bodyPadding: 8,
                
                fieldDefaults: {
                    labelAlign: 'left',
                    labelWidth: 80,
                    anchor: '100%'
                },
                
                items: [{
						xtype: 'hiddenfield',
						name: 'id_pegawai'
				},{
					 xtype: 'fieldset',
					 title: '<font color=blue>NIP & NAMA PEGAWAI</font>',
					 defaultType: 'textfield',
					 layout: 'anchor',
					 collapsible: true,
					 collapsed: false,
					 margins: '0 0 0 0',
					 defaults: {
						anchor: '100%',
						labelAlign: 'right',
						margins: '0 0 0 0'
					 },
					 items:[{
						xtype: 'container',
						padding: '0 0 5 0',
						defaultType: 'textfield',
						layout: 'hbox',
						defaults: {
							anchor: '100%',
							readOnly: false,
							allowBlank: false,
							labelAlign: 'left',
							margins: '0 5 0 0'
						},
						items: [{
							name: 'nip',
							fieldLabel: 'NIP',
							emptyText: 'NIP Pegawai',
							labelWidth: 30,
							width: 250,
							margins: '0 15 0 0'
						},{
							name: 'nama',
							fieldLabel: 'NAMA',
							emptyText: 'Nama Pegawai',
							labelWidth: 40,
							flex: 1
						}]
					}]
				},{
					 xtype: 'fieldset',
					 title: '<font color=blue>TEMPAT & TANGGAL LAHIR</font>',
					 defaultType: 'textfield',
					 layout: 'anchor',
					 collapsible: true,
					 collapsed: false,
					 margins: '0 0 0 0',
					 defaults: {
						anchor: '100%',
						labelAlign: 'top',
						margins: '0 0 0 0'
					 },
					 items:[{
						xtype: 'container',
						padding: '0 0 5 0',
						defaultType: 'textfield',
						layout: 'hbox',
						defaults: {
							anchor: '100%',
							readOnly: true,
							labelAlign: 'right',
							margins: '0 5 0 0'
						},
						items: [{
								fieldLabel: 'TEMPAT LAHIR',
								name: 'tempat_lahir',
								readOnly: false,
								emptyText: 'Cilegon',
								value: 'Cilegon',
								labelAlign: 'top',
								labelWidth: 100,
								width: 100,
								flex: 2
						},{
								xtype:'combo',
								mode:'local',
								value:'normal',
								triggerAction:'all',
								forceSelection:true,
								editable:false,
								fieldLabel:'TANGGAL',
								name:'tgl_lahir',
								displayField:'name',
								valueField:'value',
								queryMode:'local',
								readOnly: false,
								labelAlign: 'top',
								labelWidth: 80,
								width: 60,
								emptyText: '1',
								value: '1',
								store:Ext.create('Ext.data.Store', {
									fields : ['name', 'value'],
									data   : [
										{name : '01', value: '01'},{name : '02', value: '02'},{name : '03', value: '03'},
										{name : '04', value: '04'},{name : '05', value: '05'},{name : '06', value: '06'},
										{name : '07', value: '07'},{name : '08', value: '08'},{name : '09', value: '09'},
										{name : '10', value: '10'},{name : '11', value: '11'},{name : '12', value: '12'},
										{name : '13', value: '13'},{name : '14', value: '14'},{name : '15', value: '15'},
										{name : '16', value: '16'},{name : '17', value: '17'},{name : '18', value: '18'},
										{name : '19', value: '19'},{name : '20', value: '20'},{name : '21', value: '21'},
										{name : '22', value: '22'},{name : '23', value: '23'},{name : '24', value: '24'},
										{name : '25', value: '25'},{name : '26', value: '26'},{name : '27', value: '27'},
										{name : '28', value: '28'},{name : '29', value: '29'},{name : '30', value: '30'},
										{name : '31', value: '31'}
									]
								})
						},{
								xtype:'combo',
								mode:'local',
								value:'normal',
								triggerAction:'all',
								forceSelection:true,
								editable:false,
								fieldLabel:'BULAN',
								name:'bulan_lahir',
								displayField:'name',
								valueField:'value',
								queryMode:'local',
								readOnly: false,
								labelAlign: 'top',
								labelWidth: 80,
								width: 100,
								value: '1',
								emptyText: 'Januari',
								store:Ext.create('Ext.data.Store', {
									fields : ['name', 'value'],
									data   : [
										{name : 'Januari', value: '01'},{name : 'Februari', value: '02'},{name : 'Maret', value: '03'},
										{name : 'April', value: '04'},{name : 'Mei', value: '05'},{name : 'Juni', value: '06'},
										{name : 'Juli', value: '07'},{name : 'Agustus', value: '08'},{name : 'September', value: '0S9'},
										{name : 'Oktober', value: '10'},{name : 'Nopember', value: '11'},{name : 'Desember', value: '12'}
									]
								})
						},{
								fieldLabel: 'TAHUN',
								name: 'tahun_lahir',
								emptyText: '0000',
								labelAlign: 'top',
								labelWidth: 100,
								width: 50,
								value: '1980',
								enforceMaxLength: true,
								maxLength: 4,
								align: 'right',	
								//flex: 2,
								readOnly: false
						}]
					 }]
				},{
					 xtype: 'fieldset',
					 title: '<font color=blue>STATUS, PANGKAT, & JABATAN</font>',
					 defaultType: 'textfield',
					 layout: 'anchor',
					 collapsible: true,
					 collapsed: false,
					 margins: '0 0 0 0',
					 defaults: {
						anchor: '100%',
						labelAlign: 'right',
						margins: '0 0 0 0'
					 },
					 items:[{
						
							xtype: 'combobox',
							name: 'kode_jabatan',
							id: 'kode_jabatan',
							allowBlank: false,
							fieldLabel: 'JABATAN',
							labelAlign: 'left',
							emptyText: 'Jabatan Pegawai',
							triggerAction: 'all',
							forceSelection: true,
							queryMode: 'local',
							minChars: 1,
							typeAhead: true,
							displayField:'name',
							valueField:'value',
							store: {
								autoLoad: true,
								fields: ['name','value'],
								proxy: {
									type: 'ajax',
									url: '<?=base_url()?>index.php/pegawai/main/LoadJabatan'
								}
							}
						
					},{
						xtype: 'container',
						padding: '0 0 5 0',
						defaultType: 'textfield',
						layout: 'hbox',
						defaults: {
							anchor: '100%',
							readOnly: false,
							allowBlank: false,
							labelAlign: 'top',
							margins: '0 5 0 0'
						},
						items: [{
							xtype: 'combobox',
							name: 'status_pns',
							allowBlank: false,
							fieldLabel: 'STATUS PNS',
							emptyText: 'Status PNS',
							triggerAction: 'all',
							forceSelection: true,
							queryMode: 'local',
							minChars: 1,
							value: '3',
							width: 100,
							typeAhead: true,
							displayField:'name',
							valueField:'value',
							store: {
								autoLoad: true,
								fields: ['name','value'],
								proxy: {
									type: 'ajax',
									url: '<?=base_url()?>index.php/pegawai/main/LoadStatusPNS'
								}
							}
						},{
							xtype: 'combobox',
							name: 'pendidikan_terakhir',
							allowBlank: false,
							fieldLabel: 'PENDIDIKAN',
							emptyText: 'Pendidikan Terakhir',
							triggerAction: 'all',
							forceSelection: true,
							queryMode: 'local',
							minChars: 1,
							width: 100,
							value: '3',
							typeAhead: true,
							displayField:'name',
							valueField:'value',
							store: {
								autoLoad: true,
								fields: ['name','value'],
								proxy: {
									type: 'ajax',
									url: '<?=base_url()?>index.php/pegawai/main/LoadPendidikan'
								}
							}
						},{
							xtype: 'combobox',
							name: 'id_golongan',
							id: 'id_golongan',
							allowBlank: false,
							fieldLabel: 'GOLONGAN',
							emptyText: 'Golongan Pegawai',
							triggerAction: 'all',
							forceSelection: true,
							queryMode: 'local',
							minChars: 1,
							width: 80,
							typeAhead: true,
							displayField:'name',
							valueField:'value',
							store: {
								autoLoad: true,
								fields: ['name','value'],
								proxy: {
									type: 'ajax',
									url: '<?=base_url()?>index.php/pegawai/main/LoadGolongan'
								}
							}
						},{
							xtype:'combo',
							mode:'local',
							value:'normal',
							triggerAction:'all',
							forceSelection:true,
							editable:false,
							fieldLabel:'MARITAL',
							name:'status_marital',
							displayField:'name',
							valueField:'value',
							queryMode:'local',
							readOnly: false,
							flex: 1,
							value: 'BELUM MENIKAH',
							emptyText: 'Status Pernikahan',
							store:Ext.create('Ext.data.Store', {
								fields : ['name', 'value'],
								data   : [
									{name : 'Belum Menikah', value: 'BELUM MENIKAH'},
									{name : 'Menikah', value: 'MENIKAH'},
									{name : 'Janda', value: 'JANDA'},
									{name : 'Duda', value: 'DUDA'}
								]
							})
						}]
					}]
				},{
					xtype: 'fieldset',
					 title: '<font color=blue>RESIDEN/DOMISILI</font>',
					 defaultType: 'textfield',
					 layout: 'anchor',
					 collapsible: true,
					 collapsed: false,
					 margins: '0 0 0 0',
					 defaults: {
						anchor: '100%',
						labelAlign: 'right',
						margins: '0 0 0 0'
					 },
					 items:[{
						xtype: 'container',
						padding: '0 0 5 0',
						defaultType: 'textfield',
						layout: 'hbox',
						defaults: {
							anchor: '100%',
							readOnly: false,
							allowBlank: false,
							labelAlign: 'left',
							margins: '0 5 0 0'
						},
						items: [{
							xtype: 'textfield',
							name: 'alamat_asal',
							fieldLabel: 'ALAMAT ASAL',
							labelWidth: 100,
							allowBlank: true,
							flex: 1,
							emptyText: 'Alamat Asal Pegawai',
							
						}]
					},{
						xtype: 'container',
						padding: '0 0 10 0',
						defaultType: 'textfield',
						layout: 'hbox',
						defaults: {
							anchor: '100%',
							readOnly: false,
							allowBlank: false,
							labelAlign: 'top',
							margins: '0 5 0 0'
						},
						items: [{
							xtype: 'textfield',
							name: 'alamat_sekarang',
							fieldLabel: 'ALAMAT SEKARANG',
							labelAlign: 'top',
							allowBlank: true,
							emptyText: 'Alamat Sekarang',
							flex: 1			
						},{
							xtype: 'textfield',
							name: 'no_rmh',
							labelAlign: 'top',
							width: 100,
							fieldLabel: 'NO.RUMAH',
							allowBlank: true,
							emptyText: 'Nomor Rumah'
						},{
							xtype: 'textfield',
							name: 'rt',
							fieldLabel: 'RT',
							width: 40,
							allowBlank: true,
							emptyText: 'RT'
						},{
							xtype: 'textfield',
							name: 'rw',
							fieldLabel: 'RW',
							width: 40,
							allowBlank: true,
							emptyText: 'RW'
						}]
					},{
						xtype: 'container',
						padding: '0 0 10 0',
						defaultType: 'textfield',
						layout: 'hbox',
						defaults: {
							anchor: '100%',
							readOnly: false,
							allowBlank: false,
							labelAlign: 'top',
							margins: '0 5 0 0'
						},
						items: [{
							xtype: 'textfield',
							name: 'email',
							fieldLabel: 'ALAMAT EMAIL',
							allowBlank: true,
							emptyText: 'Alamat E-mail',
							vtype: 'email',
							flex: 1
						},{
							xtype: 'textfield',
							name: 'no_telp',
							fieldLabel: 'TELEPON/HP',
							allowBlank: true,
							emptyText: 'Nomor Telepon'
						},{
							xtype: 'textfield',
							name: 'kode_pos',
							fieldLabel: 'KODE POS',
							allowBlank: true,
							emptyText: 'Kode Pos',
							enforceMaxLength: true,
							maxLength: 5,
							width: 80
						}]
					}]
				}],
                
                buttons: [{
                   text: 'SIMPAN',
                   iconCls: 'icon-save',
                   scope: this,
                   handler: function() {
						if (tag_insert)
							SaveData(form, win);
						else 
							UpdatePegawai(form, win);
                   }                   
                },{
                   text: 'BATAL',
                   iconCls: 'icon-remove',
                  
                   handler: function() {
                       this.up('form').getForm().reset();
                       this.up('window').hide();
                   }
                }]
            });
            
            
            win = Ext.widget('window',{
                title: 'FORM PEGAWAI',
                closeAction: 'hide',
                width: 700,
                height: 500,
                layout: 'fit',
                resizeable: false,
                modal: true,
                items: form
            });
        }
        
        
        var rec = grid.getSelectionModel().getSelection()[0];
        var form2 = form.getForm();
        if (tag){
            //form2.findField('id_pegawai').show();			
			//form2.findField('id_pegawai').setValue(rec.get('id_pegawai'));
			//form2.findField('id_pegawai').setReadOnly(1);
			//form2.findField('nama').setValue(rec.get('nama'));
			//form2.findField('id_kecamatan').setValue(rec.get('id_kecamatan'));
        } else {
            form2.reset();
			//form2.findField('id_pegawai').hide();
        }
        
        
        win.show();
    }

    var grid = Ext.create('Ext.grid.Panel', {
        region: 'center',
        title: 'MASTER DATA PEGAWAI',
        collapsible: false,
        cls: 'custom-first-last',
        store: store,
        dockedItems: [{
            dock: 'top',
            xtype: 'toolbar',
            items: [
            {
                text: 'BARU',
                iconCls: 'icon-add',
                scope: this,
                handler: function(){
					tag_insert = 1;
                    FormPegawai(0);
                }
            },'-',{
                text: 'HAPUS',
                iconCls: 'icon-remove',
                scope: this,
                handler: function(){HapusPegawai()}
            },{
                text: 'UPDATE',
                iconCls: 'icon-save',
                scope: this,
                handler: function(){
					tag_insert = 0;
                    var rec = grid.getSelectionModel().getSelection()[0];
                    var id = rec.get('id_pegawai');
                    FormPegawai(1);                        
                }                
            },'->','CARI : ',{
                width: 250,
                xtype: 'searchfield',
                store: store
            }]
        }],
        columns:[{
			id: 'nip',
			text: 'NIP',
			dataIndex: 'nip',
			width: 150
		},{
            id: 'nama',
            text: "NAMA",
            dataIndex: 'nama',
            width: 150,
			renderer: columnWrap
        },{
			id: 'nama_jabatan',
			text: 'JABATAN',
			dataIndex: 'nama_jabatan',
			width: 150,
			renderer: columnWrap
		},{
			id: 'nama_golongan',
			text: 'GOL.',
			dataIndex: 'nama_golongan',
			width: 50
		},{
			id: 'alamat_asal',
			text: 'ALAMAT ASAL',
			dataIndex: 'alamat_asal',
			width: 200,
			flex: 1,
			renderer: columnWrap
		},{
			id: 'agama',
			text: 'AGAMA',
			dataIndex: 'agama',
			width: 70
		},{
			id: 'jenis_kelamin',
			text: 'J/K',
			dataIndex: 'jenis_kelamin',
			width: 50
		},{
			id: 'tgl_lahir_gab',
			text: 'TGL<br/>LHR',
			dataIndex: 'tgl_lahir_gab',
			width: 70
		},{
			id: 'pendidikan_terakhir',
			text: 'PEND.<br/>AKHIR',
			dataIndex: 'pendidikan_terakhir',
			width: 50
		},{
			id: 'status_marital',
			text: 'STS<br/>KAWIN',
			dataIndex: 'status_marital',
			width: 50
		},{
			id: 'status_pns',
			text: 'STS<br/>PNS',
			dataIndex: 'status_pns',
			width: 50
		} ],

        bbar: Ext.create('Ext.PagingToolbar',{
            store: store,
            displayInfo: true,
            displayMsg: 'Displaying Data : {0} - {1} of {2}',
            emptyMsg: "No Display Data"
        })
    });
    
    
    Ext.create('Ext.container.Viewport', {
        layout: 'border',
        padding: '5',
        items: [grid]
    });
    
    
    function HapusPegawai(){
        var rec = grid.getSelectionModel().getSelection()[0]; 
        
        if (rec.get('id_pegawai') != '') {
            Ext.MessageBox.confirm('Konfirmasi', 'Apakah Anda akan menghapus pegawai dengan<br/>Nipp : ' + rec.get('nama') ,function(btn) {
                if (btn == 'yes') {
                   
                   $.post("<?=base_url()?>index.php/pegawai/main/HapusPegawai", 
                   {'id_pegawai':rec.get('id_pegawai')},
                   function(data){$('#jqueryExec').html(data)});  
                
                   store.loadPage(1);
                   
                } 
            })
        }
    }

    function UpdatePegawai(){
        var rec = grid.getSelectionModel().getSelection()[0]; 
		var id = form.query('textfield[name="id"]')[0].getValue('id_pegawai');
		var id_kecamatan = form.query('textfield[name="id_kecamatan"]')[0].getValue('id_kecamatan');
		var nama =  form.query('textfield[name="nama"]')[0].getValue('nama');
		
		
        if (rec.get('id_pegawai') != '') {
            Ext.MessageBox.confirm('Konfirmasi', 'Apakah Anda akan mengupdate Pegawai : ' + rec.get('nama') ,function(btn) {
                if (btn == 'yes') {
                   $.post("<?=base_url()?>index.php/pegawai/main/UpdatePegawai", 
                   {'id_pegawai': id, 'nama': nama},
                   function(data){$('#jqueryExec').html(data)}); 
                    form.getForm().reset();
					store.loadPage(1);
					win.hide();
                } 
            })
        }
    }
    
    
    store.loadPage(1);
});
</script>
<div id='jqueryExec'></div>