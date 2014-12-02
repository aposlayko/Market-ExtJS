Ext.define('AM.store.Autocomplete', {
    extend: 'Ext.data.Store',
    autoLoad: true,
    fields: ['id', 'name'],
    
    proxy: {
        type: 'ajax',
        url: '/admin/autocomplete',
        reader: {
            type: 'json',
            root: 'items'
        }
    }
});