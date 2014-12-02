Ext.define('AM.store.Categories', {
    extend: 'Ext.data.Store',
    model: 'AM.model.Category',
    autoLoad: true,
    
    proxy: {
        type: 'ajax',
        url: '/admin/category',
        reader: {
            type: 'json',
            root: 'categories'
        }
    }
});