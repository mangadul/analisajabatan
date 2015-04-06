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

    Ext.define('model_unit_kerja', {
        extend: 'Ext.data.Model',
        fields: ['id', 'unit_kerja']
    });
    

    var store = Ext.create('Ext.data.Store', {
        pageSize: 50,
        model: 'model_unit_kerja',
        proxy: {
            type: 'ajax',
            url:  '<?=base_url()?>index.php/unit_kerja/main/DataListunit_kerja',
            reader: {
                root: 'unit_kerja_data',
                totalProperty: 'totalCount'
            },
            simpleSortMode: true
        },
        sorters: [{
            property: 'urut',
            direction: 'ASC'
        }],
        remoteSort: true
    });
    
    
    
    
    
    
    
    function SaveData(form, win) {
        var unit_kerja =  form.query('textfield[name="unit_kerja"]')[0].getValue('unit_kerja');
        
        var conn = new Ext.data.Connection();
        conn.request({
            method: 'POST',
            url: '<?=base_url()?>index.php/unit_kerja/main/Insertunit_kerja',
            params: {
                unit_kerja : unit_kerja
            },
            success: function() {
                Ext.example.msg('Sukses', 'Data unit_kerja sudah tersimpan.');
                form.getForm().reset();
                store.loadPage(1);
                win.hide();
            }
        });
    }

    
    var win;
    var form;
    function Formunit_kerja(tag){
        if (!win) {
            form = Ext.create('Ext.form.Panel', {
                url: '<?=base_url()?>index.php/unit_kerja/main/Insertunit_kerja',
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
						xtype: 'textfield',
						//id: 'unit_kerja',
						name: 'unit_kerja',
						fieldLabel: 'unit_kerja',
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
							Updateunit_kerja(form, win);
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
                title: 'FORM unit_kerja',
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
			form2.findField('unit_kerja').setValue(rec.get('unit_kerja'));
        } else {
            form2.reset();
			form2.findField('id').hide();
        }
        
        
        win.show();
    }

    var grid = Ext.create('Ext.grid.Panel', {
        region: 'center',
        title: 'unit_kerja',
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
                    Formunit_kerja(0);
                }
            },'-',{
                text: 'HAPUS',
                iconCls: 'icon-remove',
                scope: this,
                handler: function(){Hapusunit_kerja()}
            },{
                text: 'UPDATE',
                iconCls: 'icon-save',
                scope: this,
                handler: function(){
					tag_insert = 0;
                    var rec = grid.getSelectionModel().getSelection()[0];
                    var id = rec.get('id');
                    Formunit_kerja(1);                        
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
            id: 'unit_kerja',
            text: "unit_kerja",
            dataIndex: 'unit_kerja',
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
    
    
    function Hapusunit_kerja(){
        var rec = grid.getSelectionModel().getSelection()[0]; 
        
        if (rec.get('id') != '') {
            Ext.MessageBox.confirm('Konfirmasi', 'Apakah Anda akan menghapus unit_kerja : ' + rec.get('unit_kerja') ,function(btn) {
                if (btn == 'yes') {
                   
                   $.post("<?=base_url()?>index.php/unit_kerja/main/Hapusunit_kerja", 
                   {'id':rec.get('id')},
                   function(data){$('#jqueryExec').html(data)});  
                
                   store.loadPage(1);
                   
                } 
            })
        }
    }

    function Updateunit_kerja(){
        var rec = grid.getSelectionModel().getSelection()[0]; 
		var id = form.query('textfield[name="id"]')[0].getValue('id');
		var unit_kerja =  form.query('textfield[name="unit_kerja"]')[0].getValue('unit_kerja');
		
		
        if (rec.get('id') != '') {
            Ext.MessageBox.confirm('Konfirmasi', 'Apakah Anda akan mengupdate unit_kerja : ' + rec.get('unit_kerja') ,function(btn) {
                if (btn == 'yes') {
                   $.post("<?=base_url()?>index.php/unit_kerja/main/Updateunit_kerja", 
                   {'id': id, 'unit_kerja': unit_kerja},
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