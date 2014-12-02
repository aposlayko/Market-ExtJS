Ext.define('AM.controller.Items', {
    extend: 'Ext.app.Controller',
    stores: [        
        'Items',
        'Categories',
        'Brands',
        'Autocomplete'
    ],
    models: [        
        'Item'
    ],
    views: [
        'item.List',        
        'item.Form',
        'item.Center',
        'item.Left'        
    ],
    
    init: function() {
        this.control({
            'button[action=showcreate]': {
                click: this.showCreateForm
            },
            'button[action=create]': {
                click: this.createItem
            },
            'button[action=cancel]': {
                click: this.cancelItem
            },
            'itemlist': {
                itemdblclick: this.editItem,
                delclick: this.deleteItem
            },
            'button[action=edit]': {
                click: this.updateItem
            },            
            'textfield[action=quicksearch]': {
                change: this.searchItem
            },
            'combobox[action=autocomplete]': {
                select: this.enterAutocomplete
            }
        });
    },
    
    showCreateForm: function(button) {
        var panel = button.up('panel'),        
                list = panel.down('grid');
       
        list.hide();
        panel.add({            
                xtype: 'itemform'
        });
    },
    
    createItem: function(button) {
        this.sendForm('create');
    },
    
    cancelItem: function(button) {
        var formPanel = button.up('form'),
                caentralPanel = formPanel.up('center'),
                list = Ext.ComponentQuery.query('itemlist')[0];
        caentralPanel.remove(formPanel);
        list.show();
    },
    
    editItem: function(grid, record) {
        var list = Ext.ComponentQuery.query('itemlist')[0],
                centralPanel = list.up('center');

        list.hide();
        centralPanel.add({
                xtype: 'itemform',
                mode: 'edit'
        });
        
        Ext.Ajax.request({
            url: '/admin/getitem',
            params: {
                id: record.get('id')
            },
            success: function(response) {
                var text = response.responseText,
                        params = JSON.parse(text),
                        form = Ext.ComponentQuery.query('itemform')[0];
                
                form.down('hiddenfield').setValue(params.id);
                form.down('textfield').setValue(params.name);
                form.down('[name=category]').setValue(params.id_category);
                form.down('[name=brand]').setValue(params.id_brand);
                form.down('numberfield').setValue(params.price);
                form.down('checkbox').setValue(params.in_stock);
                form.down('textareafield').setValue(params.description);
            }
        });
    },
    
    updateItem: function(button) {
        this.sendForm('update');
    },
    
    sendForm: function(param) {
        var formPanel = Ext.ComponentQuery.query('itemform')[0],
                values = formPanel.getValues();
        
        var form = formPanel.getForm();
        
        if (form.isValid()) {
            form.submit({
                url: '/admin/' + param,
                waitTitle: 'Conection',
                waitMsg: 'Sending data...',
                scope: this,
                success: function(form, action) {
                    formPanel.destroy();
                    var list = Ext.ComponentQuery.query('itemlist')[0];
                    list.getStore().reload();
                    list.show();
                },
                failure: function(form, action) {
                    switch (action.failureType) {
                        case Ext.form.action.Action.CLIENT_INVALID:
                            Ext.Msg.alert('Failure', 'Form fields may not be submitted with invalid values');
                            break;
                        case Ext.form.action.Action.CONNECT_FAILURE:
                            Ext.Msg.alert('Failure', 'Ajax communication failed');
                            break;
                        case Ext.form.action.Action.SERVER_INVALID:
                            Ext.Msg.alert('Failure', action.result.msg);
                    }
                }
            });
        }
    },
    
    deleteItem: function(grid, record) {
        var status = Ext.Msg.show({
            title: 'Delete',
            msg: 'Would you like to delete item?',
            buttons: Ext.Msg.YESNO,
            icon: Ext.Msg.QUESTION,
            multiline: false,
            scope: this,
            fn: function(buttonId, text, opt) {
                if (buttonId === 'yes') {

                    Ext.Ajax.request({
                        url: '/admin/delitem',
                        params: {
                            id: record.get('id')
                        },
                        success: function(response) {
                            var list = Ext.ComponentQuery.query('itemlist')[0];
                            list.getStore().reload();
                        }
                    });
        
                }
            }
        });
    },
    
    searchItem: function(button) {
        var center = button.up('center'),
                list = center.down('itemlist'),
                search_text = center.down('textfield[name=search]').getValue(),
                panel = Ext.ComponentQuery.query('left')[0],
                category = panel.down('combobox[fieldLabel=Category]').getValue(),
                brand = panel.down('combobox[fieldLabel=Brand]').getValue(),
                checkbox = panel.down('checkbox').getValue();
        
//        console.log(category, brand, checkbox);
//        if(category !== '') {
//            this.filterReset();
            list.down('pagingtoolbar').getStore().getProxy().extraParams = {search_text: search_text};
            list.down('pagingtoolbar').moveFirst();
//            list.show();
//        }
        
    },
    
    enterAutocomplete: function(combo, records, eOpts) {
        var id = records[0].internalId,
                center = combo.up('center'),
                list = center.down('itemlist');
        
        list.getStore().getProxy().extraParams = {single_id: id};
        list.getStore().reload();
    },
    
    filterReset: function() {
        var panel = Ext.ComponentQuery.query('left')[0];
        
        panel.down('combobox[fieldLabel=Category]').setValue('');
        brand = panel.down('combobox[fieldLabel=Brand]').setValue('');
        panel.down('checkbox').setValue('');
    }    
    

});