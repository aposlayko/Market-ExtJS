Ext.define('AM.store.Images', {
    extend: 'Ext.data.Store',
    fields: ['folder', 'url', 'name', 'imageName', 'width', 'height'],
        
    proxy: {
        type: 'ajax',
        url: '/image/list',
        reader: {
            type: 'json',
            root: 'arrImages'
        }

    }
});