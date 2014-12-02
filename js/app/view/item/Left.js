Ext.define('AM.view.item.Left' ,{
    extend: 'Ext.panel.Panel',
    alias: 'widget.left', 
    
    items: [
        {
            xtype: 'combobox',
            fieldLabel: 'Category',
            margin: '5',
            displayField: 'name',
            store: 'Categories',
            valueField: 'id'
        },
        {
            xtype: 'combobox',
            fieldLabel: 'Brand',
            margin: '5',
            displayField: 'name',
            store: 'Brands',
            valueField: 'id'            
        },
        {
            xtype: 'fieldcontainer',
            fieldLabel: 'In stock',
            defaultType: 'checkboxfield',
            margin: '5',
            items: [
                {
                    name: 'topping',
                    inputValue: '1',
                    id: 'checkbox1'
                }
            ]
        }
//        {
//            xtype: 'button',
//            text: 'Reset' 
//        }
    ]
});