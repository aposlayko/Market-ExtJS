    Ext.define('AM.view.item.List', {
    extend: 'Ext.grid.Panel',
    alias: 'widget.itemlist',
    store: 'Items',
    initComponent: function() {
        this.dockedItems = [{
                xtype: 'pagingtoolbar',
                store: 'Items',
                dock: 'bottom',
                displayInfo: true
            }];
        
        function converYesNo(value) {
            if(value === '1') {
                return 'yes';
            }
            return 'no';
        }
        
        this.columns = [
//            {header: 'Id',  dataIndex: 'id',  flex: 1},
            {header: 'Name', dataIndex: 'name', flex: 1},
            {header: 'Category', dataIndex: 'category', flex: 1},
            {header: 'Brand', dataIndex: 'brand', flex: 1},
            {header: 'Price', dataIndex: 'price', flex: 1},
            {header: 'Descripion', dataIndex: 'description', flex: 2},
            {header: 'In stock', dataIndex: 'in_stock', flex: 1, renderer: converYesNo },
            {xtype: 'actioncolumn',
                width: 50,
                scope: this,
                items: [
                    {
                        icon: 'images/edit.gif',
                        tooltip: 'Edit',
                        handler: function(grid, rowIndex, colIndex) {
                            var record = grid.getStore().getAt(rowIndex);
                            
                            this.fireEvent('itemdblclick', this, record);
                        }
                    },
                    {
                        icon: 'images/icon-error.gif',
                        tooltip: 'Delete',
                        action: 'delete',                        
                        handler: function(grid, rowIndex, colIndex) {
                            var record = grid.getStore().getAt(rowIndex);

                            this.fireEvent('delclick', this, record);
                        }
                    }]
            }
        ];

        this.callParent(arguments);
    }
});

