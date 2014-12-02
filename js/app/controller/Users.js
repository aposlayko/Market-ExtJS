Ext.define('AM.controller.Users', {
    extend: 'Ext.app.Controller',
    stores: [
        'Users'
    ],
    models: ['User'],
    views: [
        'user.List',
        'user.Edit',
        'user.Form',
        'user.Create'
    ],
    init: function() {
        this.control({
//            'viewport > userlist': {
//                itemdblclick: this.editUser,
//                cellcontextmenu: this.deleteUser
//            },
//            'useredit button[action=save]': {
//                click: this.updateUser
//            },
//            'button[action=showcreate]': {
//                click: this.showCreateForm
//            },
//            'usercreate button[action=create]': {
//                click: this.createUser
//            }
        });
    },
    
    editUser: function(grid, record) {
        var view = Ext.widget('useredit');

        view.down('form').loadRecord(record);
    },
    
    updateUser: function(button) {
        var win = button.up('window'),
                form = win.down('form'),
                record = form.getRecord(),
                values = form.getValues();

        record.set(values);
        win.close();
        
        this.getUsersStore().sync();
    },
    
    showCreateForm: function() {
        var view = Ext.widget('usercreate');
    },
    
    createUser: function(button) {
        var win = button.up('window'),
                form = win.down('form'),                
                values = form.getValues();
        
        var user = new AM.model.User({
            name: values.name,
            email: values.email
        });
        this.getUsersStore().add(user);
        this.getUsersStore().sync();
        win.close();
    },
    
    deleteUser: function(self, td, cellIndex, record, tr, rowIndex, e, eOpts) { 
        e.preventDefault();

        var status = Ext.Msg.show({
            title: 'Delete',
            msg: 'Would you like to delete user?',
            buttons: Ext.Msg.YESNO,
            icon: Ext.Msg.QUESTION,
            multiline:false,
            scope: this,
            fn: function(buttonId, text, opt) {
                debugger;
                if (buttonId === 'yes') {
//                    console.log(opt.self);
                    this.getUsersStore().remove(record);                    
                    this.getUsersStore().sync();
                }
            }
        });
    }
});