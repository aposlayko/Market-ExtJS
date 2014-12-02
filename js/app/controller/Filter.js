Ext.define('AM.controller.Filter', {
    extend: 'Ext.app.Controller',
    stores: [    
        'Categories',
        'Brands'
    ],
    models: [        
        'Item',
        'Category',
        'Brand'
    ],
    views: [
        'item.Left',
        'item.List',
        'item.Center'
    ],
    
    init: function() {
        this.control({
            'left checkboxfield, left combobox' : {
                'change': this.filterChange
            },
            'left button[text=Reset]' : {
                'click': this.filterReset
            }
        });
    },
    
    filterChange: function() {
        var panel = Ext.ComponentQuery.query('left')[0],
                filter_params = {},
                list = Ext.ComponentQuery.query('itemlist')[0],
                category = panel.down('combobox[fieldLabel=Category]').getValue(),
                brand = panel.down('combobox[fieldLabel=Brand]').getValue(),
                in_stock = panel.down('checkbox').getValue(),
                search = Ext.ComponentQuery.query('textfield[action=quicksearch]')[0].getValue();
        
        if(category) {
            filter_params.category = category;
        }
        if(brand) {
            filter_params.brand = brand;
        }
        if(in_stock) {
            filter_params.in_stock = 1;
        }
        
        if (search !== '') {
            this.searchReset();
        }
        
        list.getStore().getProxy().extraParams = filter_params;
        list.getStore().reload();
        list.show();
        
    },
    
    searchReset: function () {
        var search = Ext.ComponentQuery.query('textfield[action=quicksearch]')[0];
        search.setValue('');
    }
});