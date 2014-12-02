Ext.define('AM.store.Items', {
    extend: 'Ext.data.Store',
    model: 'AM.model.Item',
    autoLoad: true,
    pageSize: 3,
    
    proxy: {
        type: 'ajax',
        url: '/admin/list',
        reader: {
            type: 'json',
            root: 'items'
        }

    }
});