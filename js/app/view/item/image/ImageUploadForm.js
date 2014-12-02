Ext.define('AM.view.item.image.ImageUploadForm', {
    extend: 'Ext.form.Panel',
    alias: 'widget.ImageUploadForm',
    
    requires: [
        'AM.view.item.image.ImageView'
    ],
    
    frame: true,
    border: false,
    padding: 10,
    labelWidth: 150,
    buttonAlign: 'center',
    height: 325,
    
    config: {
        itemId: undefined
    },
    initComponent: function() {
        this.items = [
            {
                xtype: 'filefield',
                anchor: '90%',
                fieldLabel: 'Image',
                name: 'imageFile',
                buttonText: 'Выбрать',
                allowBlank: false,
                blankText: 'Фотография не выбрана',
                msgTarget: 'side'
            },
            {
                xtype: 'panel',
                itemId: 'imgageList',
                frame: true,
                border: false,
                layout: 'fit',
                anchor: '100% 90%',
                margin: '15, 0, 0, 0',
//                autoScroll: true,
                overflowY: 'auto',
                
                items: {
                    xtype: 'imageView',
                    itemId: this.itemId
                }
            }
			];
                        
        this.buttons = [
            {
                text: 'Выбрать основной',
                action: 'avatar',
                hidden: true
                
            },
            {
                text: 'Удалить',
                action: 'delete',
                hidden: true
                
            },
            {
                text: 'Сохранить фото',
                action: 'upload'
            },
            {
                text: 'Завершить',
                action: 'finishItemAdd'
            }
        ];
                        
        this.callParent(arguments);
    }
    
});