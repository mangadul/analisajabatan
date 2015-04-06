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

    Ext.define('model_o18', {
        extend: 'Ext.data.Model',
        fields: ['ka_no', 'ka_nm', 'kel_sarana', 'jumlah', 'urut']
    });
    

    var store = Ext.create('Ext.data.Store', {
        pageSize: 50,
        model: 'model_o18',
        groupField: 'ka_no',
        proxy: {
            type: 'ajax',
            url:  '<?=base_url()?>index.php/o18_entry/main/DataListO18',
            reader: {
                root: 'mo18',
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
    
    
    
    
    Ext.define('FormGridModel',{
        extend: 'Ext.data.Model',
        fields: [   {name: 'kel_sarana', type: 'text'},
                    {name: 'jumlah', type: 'number'},
                    {name: 'urut', type: 'number'},
                ]
    });
    
    var storeForm = Ext.create('Ext.data.ArrayStore',{
        model: 'FormGridModel',
        proxy: {
            type: 'ajax',
            url:  '<?=base_url()?>index.php/o18_entry/main/LoadO18Form',
            reader: {
                root: 'o18'
            }
        }
    });
    
    var groupingFeature = Ext.create('Ext.grid.feature.Grouping',{
        groupHeaderTpl: 'KA {name} ({rows.length} Item {[values.rows.length > 1 ? "" : ""]})'
    });
    
    var cellEditing = Ext.create('Ext.grid.plugin.CellEditing', {
        clicksToEdit: 1
    });
   
    
    function SaveData(storeForm, form, win) {
        var ka_no =  form.query('textfield[name="ka_no"]')[0].getValue('ka_no');
        var jsonData = Ext.encode(Ext.pluck(storeForm.data.items, 'data'));
        
        var conn = new Ext.data.Connection();
        
        conn.request({
            method: 'POST',
            url: '<?=base_url()?>index.php/o18_entry/main/InsertO18',
            params: {
                ka_no : ka_no,
                data : jsonData
            },
            success: function() {
                Ext.example.msg('Sukses', 'Data sudah tersimpan.');
                form.getForm().reset();
                store.loadPage(1);
                win.hide();
            }
        });
    }

    
    var win;
    var form;
    function FormO18(tag){
        if (!win) {
            var gridForm = Ext.create('Ext.grid.Panel',{
                    store: storeForm,
                    height: 300,
                    //selModel: {selType: 'cellmodel'},
                    plugins: [cellEditing],
                    listeners: {itemclick: function(grid, record, item, index, e) {idx = index;} },
                    dockedItems: [{
                        dock: 'top',
                        xtype: 'toolbar',
                        items: [{ 
                                    text: 'TAMBAH',
                                    iconCls: 'icon-add',
                                    scope: this,
                                    handler: function(){
                                        var r = Ext.create('FormGridModel', {
                                            no_ka:'', jumlah: '1', urut: storeForm.data.items.length+1
                                        });
                                
                                        storeForm.insert(storeForm.data.items.length,r);
                                        cellEditing.startEditByPosition({row: storeForm.data.items.length-1, column: 1});
                                    }// end handler
                                },{ 
                                    text: 'HAPUS', 
                                    iconCls: 'icon-remove',
                                    scope: this,
                                    handler: function(){
                                        var sm = gridForm.getSelectionModel();
                                        storeForm.remove(sm.getSelection());
                                    }// end handler
                                },{ 
                                    text: 'RESET',
                                    iconCls: 'icon-reset',
                                    scope: this,
                                    handler: function(){
                                        storeForm.removeAll();
                                        storeForm.sync();
                                    }// end handler
                                }]
                    }],
                
                columns: [{
                        id: 'urut2',
                        text: 'URUT',
                        align: 'center',
                        width: 80,
                        sortable: false,
                        dataIndex: 'urut',
                        editor: {
                            xtype: 'numberfield',
                            minValue: 1,
                            maxValue: 100,
                            value: 1
                        }
                    },{
                        id: 'kel_sarana2',
                        text: 'KEL. SARANA',
                        flex: 1,
                        sortable: false,
                        dataIndex: 'kel_sarana',
                        editor: {
                           xtype: 'combobox',
                            name: 'kel_sarana',
                            emptyText: '', 
                            triggerAction: 'query',
                            hideTrigger: true, 
                            minChars: 1, 
                            triggerAction: 'query', 
                            typeAhead: true, 
                            displayField:'name',
                            valueField:'value',
                            store: { 
                                fields: ['name','value'], 
                                proxy: { 
                                    type: 'ajax',
                                    url: '<?=base_url()?>index.php/o18_entry/main/LoadKelSarana' 
                                } 
                            } 
                        }
                    },{
                        id: 'jumlah2',
                        text: 'JUMLAH',
                        align: 'center',
                        width: 80,
                        sortable: false,
                        dataIndex: 'jumlah',
                        editor: {
                            xtype: 'numberfield',
                            minValue: 1,
                            maxValue: 100
                        }
                    }]
                
            });
            
            form = Ext.create('Ext.form.Panel', {
                url: '<?=base_url()?>index.php/o18_entry/main/InsertO18',
                border: false,
                bodyPadding: 5,
                
                fieldDefaults: {
                    labelAlign: 'left',
                    labelWidth: 80,
                    anchor: '100%'
                },
                
                items: [{
                    xtype: 'combobox',
                    name: 'ka_no',
                    emptyText: '', 
                    triggerAction: 'query',
                    fieldLabel: 'No. KA',
                    hideTrigger: true,
                    allowBlank:false,
                    minChars: 1, 
                    triggerAction: 'query', 
                    typeAhead: true, 
                    displayField:'name',
                    valueField:'value',
                    store: { 
                        fields: ['name','value'], 
                        proxy: { 
                            type: 'ajax',
                            url: '<?=base_url()?>index.php/o18_entry/main/LoadKA' 
                        } 
                    },
                    listeners: {
                      afterrender: function(field) {
                        field.focus(false, 1000);
                      }
                    }
                },gridForm],
                
                buttons: [{
                   text: 'SIMPAN',
                   iconCls: 'icon-save',
                   scope: this,
                   handler: function() {
                        SaveData(storeForm, form, win);  
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
                title: 'FORM O.18',
                closeAction: 'hide',
                width: 300,
                height: 400,
                layout: 'fit',
                resizeable: false,
                modal: true,
                items: form
            });
        }
        
        
        var rec = grid.getSelectionModel().getSelection()[0];
        var form2 = form.getForm();
        if (tag){
            form2.findField('ka_no').setValue(rec.get('ka_no'));
        } else {
            form2.reset();
        }
        
        
        win.show();
    }

    var grid = Ext.create('Ext.grid.Panel', {
        region: 'center',
        title: 'POLA DINASAN SARANA (O.18)',
        collapsible: false,
        features: [groupingFeature],
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
                    storeForm.proxy.extraParams = { key: 'test' };
                    storeForm.load();
                    FormO18(0);
                }
            },'-',{
                text: 'HAPUS',
                iconCls: 'icon-remove',
                scope: this,
                handler: function(){HapusO18()}
            },{
                text: 'UPDATE',
                iconCls: 'icon-save',
                scope: this,
                handler: function(){
                    var rec = grid.getSelectionModel().getSelection()[0];
                    var ka_no = rec.get('ka_no');

                    storeForm.proxy.extraParams = { ka_no: ka_no };
                    storeForm.load();
                    FormO18(1);                        
                }                
            },'->','CARI : ',{
                width: 250,
                xtype: 'searchfield',
                store: store
            }]
        }],
        columns:[{
            id: 'ka_no',
            text: "NO. KA",
            dataIndex: 'ka_no',
            width: 80
        },{
            id: 'ka_nm',
            text: "NAMA KA",
            dataIndex: 'ka_nm',
            width: 100,
            flex: 1
        },{
            id: 'urut',
            text: "URUT",
            dataIndex: 'urut',
            width: 80
        },{
            id: 'kel_sarana',
            text: "KEL. SARANA ",
            dataIndex: 'kel_sarana',
            flex: 1
        },{
            id: 'jumlah',
            text: "JUMLAH",
            dataIndex: 'jumlah',
            width: 100
        }],

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
    
    
    function HapusO18(){
        var rec = grid.getSelectionModel().getSelection()[0]; 
        
        if (rec.get('kel_sarana') != '') {
            Ext.MessageBox.confirm('Konfirmasi', 'Apakah Anda akan menghapus O.18 KA : ' + rec.get('ka_no') + ' (' + rec.get('ka_nm') + ')',function(btn) {
                if (btn == 'yes') {
                   
                   $.post("<?=base_url()?>index.php/o18_entry/main/HapusO18Form", 
                   {'ka_no':rec.get('ka_no')},
                   function(data){$('#jqueryExec').html(data)});  
                   
                   store.loadPage(1);
                   
                } 
            })
        }
    }
    
    
    store.loadPage(1);
});
</script>
<div id='jqueryExec'></div>