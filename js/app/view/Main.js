Ext.define('AM.view.Main', {
    extend: 'Ext.container.Viewport',
    alias: 'widget.main',
    layout: 'border',
    
    initComponent: function() {
        this.items = [{
                region: 'west',
                collapsible: true,
                split: true,
                title: 'Filters',
                width: 280,
                items: [
                    {
                        xtype: 'left'
                    }
                ]
            },
            {
                region: 'center',
                items: [
                    {
                        xtype: 'center'
                    }
                ]
            }
        ];
        
        this.callParent(arguments);
    }
    
});