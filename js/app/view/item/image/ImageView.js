Ext.define('AM.view.item.image.ImageView', {
    extend: 'Ext.view.View',
    alias: 'widget.imageView',
    
    config: {
        itemId: undefined
    },
    padding: 3,
    initComponent: function() {
        // append custom css for image list
        Ext.util.CSS.createStyleSheet("\
			.imageThumbnail\
			{\
				margin : 5px;\
				border: 1px solid #FFFFFF;\
				cursor: pointer;\
			}\
			.x-imgList-over\
			{\
				border: 1px solid #ccc;\
			}\
			.x-imgList-selected\
			{\
				border: 1px solid skyblue;\
			}\
			");

        // create html template for image list
        var tpl = new Ext.XTemplate(
                '<tpl for=".">',
                '<img class="imageThumbnail" width="{width}" height="{height}" src="{url}" alt="{name}" title="{name}" />',
                '</tpl>'
                );

        // set config params
        this.store = Ext.create('AM.store.Images');
                this.tpl = tpl,
                this.singleSelect = true,
                this.overItemCls = 'x-imgList-over',
                this.selectedItemCls = 'x-imgList-selected',
                this.itemSelector = 'img.imageThumbnail',
                this.emptyText = 'Нет изображений',
                this.loadingText = 'Загрузка...';

        
        this.callParent(arguments);
        this.refreshImageList();
    },
    /**
     * Retrieves image list from server and refreshes the panel, containing image list
     */
    refreshImageList: function() {

        // load image list from server
        this.getStore().load({
            scope: this,
            params: {
                itemId: this.getItemId()
            }
        });
    }
});