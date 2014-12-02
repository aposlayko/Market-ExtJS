Ext.define('AM.store.Brands', {
    extend: 'Ext.data.Store',
    model: 'AM.model.Brand',
    autoLoad: true,
    
    proxy: {
        type: 'ajax',
        url: '/admin/brand',
        reader: {
            type: 'json',
            root: 'brands'
        }

    }
});