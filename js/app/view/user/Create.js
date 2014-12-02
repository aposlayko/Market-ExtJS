Ext.define('AM.view.user.Create', {
    extend: 'Ext.window.Window',
    alias: 'widget.usercreate',

    title: 'Create User',
    layout: 'fit',
    autoShow: true,

    initComponent: function() {
        this.items = [
            {
                xtype: 'userform'                
            }
        ];

        this.buttons = [
            {
                text: 'Create',
                action: 'create'
            },
            {
                text: 'Cancel',
                scope: this,
                handler: this.close
            }
        ];

        this.callParent(arguments);
    }
});