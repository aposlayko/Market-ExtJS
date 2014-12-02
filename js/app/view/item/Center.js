Ext.define('AM.view.item.Center', {
    extend: 'Ext.panel.Panel',
    alias: 'widget.center',
    
    title: 'Grid',
    layout: 'fit',
    tbar: [
        {
            xtype: 'button',
            text: 'Add',
            action: 'showcreate'
        },
        {
            xtype: 'textfield',
            name: 'search',            
            margin: '0 0 0 300',
            action: 'quicksearch',
            fieldLabel: 'Search'
        },
//        {
//            xtype: 'combobox',
//            fieldLabel: 'Autocomplete',
//            action: 'autocomplete',
//            displayField: 'name',
//            store: 'Autocomplete',
//            valueField: 'name',
//            margin: '0 0 0 100',
//            queryMode: 'remote',
//            typeAhead:true,
//            hideTrigger: true,
//            enableKeyEvents: true,
//            minChars: 1
//            
//        }
    ],
    items: [
        {
            xtype: 'itemlist'
        }        
    ]
});