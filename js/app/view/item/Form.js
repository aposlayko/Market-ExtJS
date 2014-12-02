Ext.define('AM.view.item.Form', {
    extend: 'Ext.form.Panel',
    alias: 'widget.itemform',
    bodyPadding: 5,
//    title: this.ucfirst(this.getMode()) + ' Item',
    
//    url: 'save-form.php',
    
    layout: 'anchor',
    defaults: {
        anchor: '80%',
        allowBlank: false        
    },
    
    config: {
        mode: 'create'
    },
    
    initComponent: function() {
        this.items = [
            {
                xtype: 'hiddenfield',
                name: 'id',
                valueField: 'id'
            },
            {
                xtype: 'textfield',
                name: 'name',
                fieldLabel: 'Name'
            },
            {
                xtype: 'combobox',
                fieldLabel: 'Category',
                displayField: 'name',
                valueField: 'id',
                name: 'category',
                store: 'Categories'
//                itemId: 'categories'
            },
            {
                xtype: 'combobox',
                fieldLabel: 'Brand',                
                displayField: 'name',
                valueField: 'id',
                name: 'brand',
                store: 'Brands'
            },
            {
                xtype: 'numberfield',
                name: 'price',
                fieldLabel: 'Price',
                minValue: 1,
                decimalPrecision: 2 
            },
            {
                xtype: 'checkbox',
                fieldLabel: 'In stock',
                boxLabel: '',
                inputValue: '1',
                name: 'in_stock',
                allowBlank: true
            },
            {
                xtype: 'textareafield',
                fieldLabel: 'Description',
                name: 'description',
                displayField: 'discription'
            }
            
        ]; 
        //add button 'image' for editing
        if(this.mode === 'edit') {
            this.items.push({
                xtype: 'button',
                text: 'Image',
                anchor: '10%',
                action: 'images'
            });
        }
        
        this.buttons = [
            {
                text: this.ucfirst(this.getMode()),
                action: this.getMode()
            },
            {
                text: 'Cancel',
                scope: this,
                action: 'cancel'
            }
        ];

        this.callParent(arguments);
    },
    
    ucfirst: function(str) {
        var f = str.charAt(0).toUpperCase();        
        return f + str.substr(1, str.length - 1);
    }
});