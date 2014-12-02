Ext.define('AM.controller.Images', {
    extend: 'Ext.app.Controller',
    stores: [              
        'Images'
    ],
    models: [        
        
    ],
    views: [        
        'item.image.ImageModalWindow'        
    ],
    
    init: function() {
        this.control({
            'button[action=images]': {
                click: this.showImages
            },
            'button[action=upload]': {
                click: this.uploadImage
            },
            'button[action=finishItemAdd]': {
                click: this.finishItemAdd
            },
            'imageView': {
                selectionchange: this.itemSelect
            },
            'button[action=delete]': {
                click: this.deleteImage
            },
            'button[action=avatar]': {
                click: this.chooseAvatar
            }
            
        });
    },
    
    showImages: function(button) {
        var form = button.up('itemform');
        form.add({
            xtype: 'ImageModalWindow',
            itemId: form.down('hiddenfield').value
        });
        form.down('ImageModalWindow').show();
    },
    
    uploadImage: function(button) {
        var formPanel = button.up('form'),
                form = formPanel.getForm(),
                imagePanel = formPanel.down('panel'),
                itemForm = Ext.ComponentQuery.query('itemform')[0];
        
//        console.log(itemForm.getValues().id);

        if (form.isValid())
        {
            form.submit({
                url: '/image/upload',
                waitTitle: 'Соединение',
                waitMsg: 'Отправка данных...',
                params: {
                    itemId: /*formPanel.getItemId()*/itemForm.getValues().id
                },
                success: function(form, action) {
                    // refresh panel, containing images
                    imagePanel.down('imageView').refreshImageList();
                },
                failure: function(form, action) {
                    switch (action.failureType) {
                        case Ext.form.action.Action.CLIENT_INVALID:
                            Ext.Msg.alert('Failure', 'Form fields may not be submitted with invalid values');
                            break;
                        case Ext.form.action.Action.CONNECT_FAILURE:
                            Ext.Msg.alert('Failure', 'Ajax communication failed');
                            break;
                        case Ext.form.action.Action.SERVER_INVALID:
                            Ext.Msg.alert('Failure', action.result.message);
                            break;                        
                    }
                }
            });
        }
    },
    
    finishItemAdd: function (button) {
        button.up('ImageModalWindow').close();
    },
    
    itemSelect: function(view, record, item, index, e) {
        var buttonDelete = Ext.ComponentQuery.query('button[action=delete]')[0],
                buttonAvatar = Ext.ComponentQuery.query('button[action=avatar]')[0];
        if(record.length !== 0) {            
            buttonDelete.show();
            buttonAvatar.show();
        } else {
            buttonDelete.hide();
            buttonAvatar.hide();
        }
    },
    
    deleteImage: function(button) {
        var view = button.up('ImageUploadForm').down('imageView'),
                selectedImage = view.getSelectionModel().getSelection()[0];
        
        Ext.Ajax.request({
            url: '/image/deleteimage',
            params: {
                folder: selectedImage.get('folder'),
                imageName: selectedImage.get('imageName')
            },
            success: function(response) {
                view.refreshImageList();
            },
            failure: function(form, action) {
                    switch (action.failureType) {
                        case Ext.form.action.Action.CLIENT_INVALID:
                            Ext.Msg.alert('Failure', 'Form fields may not be submitted with invalid values');
                            break;
                        case Ext.form.action.Action.CONNECT_FAILURE:
                            Ext.Msg.alert('Failure', 'Ajax communication failed');
                            break;
                        case Ext.form.action.Action.SERVER_INVALID:
                            Ext.Msg.alert('Failure', action.result.message);
                            break;                        
                    }
                }
        });
    },
    
    chooseAvatar: function(button) {
        var view = button.up('ImageUploadForm').down('imageView'),
                selectedImage = view.getSelectionModel().getSelection()[0];
        
        Ext.Ajax.request({
            url: '/image/chooseavatar',
            params: {
                folder: selectedImage.get('folder'),
                imageName: selectedImage.get('imageName')
            },
            success: function(response) {
//                view.refreshImageList();
//                var params = JSON.parse(response.responseText);
                view.refreshImageList();
//                selectedImage.set('imageName', params.newPictureName);
//                console.dir(params);
//                console.dir(selectedImage.getData());

            },
            failure: function(form, action) {
                    switch (action.failureType) {
                        case Ext.form.action.Action.CLIENT_INVALID:
                            Ext.Msg.alert('Failure', 'Form fields may not be submitted with invalid values');
                            break;
                        case Ext.form.action.Action.CONNECT_FAILURE:
                            Ext.Msg.alert('Failure', 'Ajax communication failed');
                            break;
                        case Ext.form.action.Action.SERVER_INVALID:
                            Ext.Msg.alert('Failure', action.result.message);
                            break;                        
                    }
                }
        });
    }
    
});