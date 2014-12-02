Ext.define('AM.view.user.Form', {
    extend: 'Ext.form.Panel',
    alias: 'widget.userform',
    
    initComponent: function() {
        this.items = [
            {
                xtype: 'textfield',
                name: 'name',
                fieldLabel: 'Name'
            },
            {
                xtype: 'textfield',
                name: 'email',
                fieldLabel: 'Email'
            }
        ];        

        this.callParent(arguments);
    }
});