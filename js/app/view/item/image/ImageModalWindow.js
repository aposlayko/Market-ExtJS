Ext.define('AM.view.item.image.ImageModalWindow', {
    extend: 'Ext.window.Window',
    alias: 'widget.ImageModalWindow',
    requires: [
        'AM.view.item.image.ImageUploadForm'
    ],
    title: 'Images',
    modal: true,
    closable: true,
    draggable: true,
    resizable: true,
    autoShow: true,
    width: 420,
    height: 360,
    config: {
        itemId: undefined
    },
    initComponent: function() {
        this.items = [{
                xtype: 'ImageUploadForm',
                itemId: this.itemId
        }];
        this.callParent(arguments);
    }
    
});