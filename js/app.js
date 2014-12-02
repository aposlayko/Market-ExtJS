//Ext.Loader.setPath('App', '/js/app');
Ext.application({
    requires: ['AM.view.Main'],
    name: 'AM',
    
    appFolder: '/js/app',
    
    controllers: [
        'Users',
        'Items',
        'Filter',
        'Images'
    ],
    
    launch: function() {
//        Ext.create('Ext.container.Viewport', {
//            layout: 'fit',
//            items: {
//                xtype: 'userlist'
//            }
//        });
        Ext.create('widget.main');
        
    }
});