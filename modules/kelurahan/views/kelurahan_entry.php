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
</style>

<script type="text/javascript" src="<?=base_url()?>resources/js/jquery.js"></script>
<script type="text/javascript" src="<?=base_url()?>resources/ext4/examples/shared/examples.js"></script>


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
 
	Ext.define('model_kelurahan', {
        extend: 'Ext.data.Model',
        fields: ['id', 'id_kecamatan', 'nama', 'nama_kecamatan']
    });
    

    var store = Ext.create('Ext.data.Store', {
        pageSize: 50,
        model: 'model_kelurahan',
        proxy: {
            type: 'ajax',
            url:  '<?=base_url()?>index.php/kelurahan/main/DataListKelurahan',
            reader: {
                root: 'kelurahan_data',
                totalProperty: 'totalCount'
            },
            simpleSortMode: true
        },
        sorters: [{
            property: 'id',
            direction: 'ASC'
        }],
        remoteSort: true
    });
    
    
    
    
    
    
    
    function SaveData(form, win) {
		var id = form.getForm().findField('id').getValue();
        var nama =  form.query('textfield[name="nama"]')[0].getValue('nama');
		var id_kecamatan =  form.getForm().findField('id_kecamatan').getValue();
        
        var conn = new Ext.data.Connection();
        conn.request({
            method: 'POST',
            url: '<?=base_url()?>index.php/kelurahan/main/InsertKelurahan',
            params: {
				id : id,
                nama : nama,
				id_kecamatan: id_kecamatan
            },
            success: function() {
                Ext.example.msg('Sukses', 'Data kelurahan sudah tersimpan.');
                form.getForm().reset();
                store.loadPage(1);
                win.hide();
            }
        });
    }

    
    var win;
    var form;
    function FormKelurahan(tag){
        if (!win) {
            form = Ext.create('Ext.form.Panel', {
                url: '<?=base_url()?>index.php/kelurahan/main/InsertKelurahan',
                border: false,
                bodyPadding: 5,
                
                fieldDefaults: {
                    labelAlign: 'left',
                    labelWidth: 80,
                    anchor: '100%'
                },
                
                items: [{
						xtype: 'textfield',
						//id: 'id',
						name: 'id',
						fieldLabel: 'ID',
						allowBlank: false,
						width: 100				
				},{
					xtype: 'combobox',
					name: 'id_kecamatan',
					id: 'id_kecamatan',
					allowBlank: false,
					fieldLabel: 'KECAMATAN',
					emptyText: '',
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
							url: '<?=base_url()?>index.php/kelurahan/main/LoadKecamatan'
						}
					}				
				},
				{
						xtype: 'textfield',
						//id: 'agama',
						name: 'nama',
						fieldLabel: 'KELURAHAN',
						allowBlank: false,
						width: 100				
				}],
                
                buttons: [{
                   text: 'SIMPAN',
                   iconCls: 'icon-save',
                   scope: this,
                   handler: function() {
						if (tag_insert)
							SaveData(form, win);
						else 
							UpdateKelurahan(form, win);
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
                title: 'FORM KELURAHAN',
                closeAction: 'hide',
                width: 300,
                height: 200,
                layout: 'fit',
                resizeable: false,
                modal: true,
                items: form
            });
        }
        
        
        var rec = grid.getSelectionModel().getSelection()[0];
        var form2 = form.getForm();
        if (tag){
            form2.findField('id').show();			
			form2.findField('id').setValue(rec.get('id'));
			form2.findField('id').setReadOnly(1);
			form2.findField('nama').setValue(rec.get('nama'));
			form2.findField('id_kecamatan').setValue(rec.get('id_kecamatan'));
        } else {
            form2.reset();
			form2.findField('id').hide();
        }
        
        
        win.show();
    }

    var grid = Ext.create('Ext.grid.Panel', {
        region: 'center',
        title: 'MASTER KELURAHAN',
        collapsible: false,
        
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
                    FormKelurahan(0);
                }
            },'-',{
                text: 'HAPUS',
                iconCls: 'icon-remove',
                scope: this,
                handler: function(){HapusKelurahan()}
            },{
                text: 'UPDATE',
                iconCls: 'icon-save',
                scope: this,
                handler: function(){
					tag_insert = 0;
                    var rec = grid.getSelectionModel().getSelection()[0];
                    var id = rec.get('id');
                    FormKelurahan(1);                        
                }                
            },'->','CARI : ',{
                width: 250,
                xtype: 'searchfield',
                store: store
            }]
        }],
        columns:[{
            id: 'id',
            text: "ID",
            dataIndex: 'id',
            width: 80
        },{
			id: 'nama_kecamatan',
			text: 'KECAMATAN',
			dataIndex: 'nama_kecamatan',
			width: 100
		},{
            id: 'nama',
            text: "KELURAHAN",
            dataIndex: 'nama',
            width: 100,
            flex: 1
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
    
    
    function HapusKelurahan(){
        var rec = grid.getSelectionModel().getSelection()[0]; 
        
        if (rec.get('id') != '') {
            Ext.MessageBox.confirm('Konfirmasi', 'Apakah Anda akan menghapus Kelurahan : ' + rec.get('nama') ,function(btn) {
                if (btn == 'yes') {
                   
                   $.post("<?=base_url()?>index.php/kelurahan/main/HapusKelurahan", 
                   {'id':rec.get('id')},
                   function(data){$('#jqueryExec').html(data)});  
                
                   store.loadPage(1);
                   
                } 
            })
        }
    }

    function UpdateKelurahan(){
        var rec = grid.getSelectionModel().getSelection()[0]; 
		var id = form.query('textfield[name="id"]')[0].getValue('id');
		var id_kecamatan = form.query('textfield[name="id_kecamatan"]')[0].getValue('id_kecamatan');
		var nama =  form.query('textfield[name="nama"]')[0].getValue('nama');
		
		
        if (rec.get('id') != '') {
            Ext.MessageBox.confirm('Konfirmasi', 'Apakah Anda akan mengupdate Kelurahan : ' + rec.get('nama') ,function(btn) {
                if (btn == 'yes') {
                   $.post("<?=base_url()?>index.php/kelurahan/main/UpdateKelurahan", 
                   {'id': id, 'id_kecamatan': id_kecamatan, 'nama': nama},
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