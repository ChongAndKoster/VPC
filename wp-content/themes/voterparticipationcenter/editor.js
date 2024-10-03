wp.domReady(() => {
    
    /**
     * Headlines
     */
    wp.blocks.registerBlockStyle( 'core/heading', [ 
        {
            name: 'default',
            label: 'Default',
            isDefault: true,
        },
        {
            name: 'alt',
            label: 'Alternate',
        }
    ]);
    
    /**
     * Buttons
     */
    wp.blocks.unregisterBlockStyle( 'core/button', 'fill' );
    wp.blocks.unregisterBlockStyle( 'core/button', 'outline' );
    wp.blocks.unregisterBlockStyle( 'core/button', 'squared' );

    wp.blocks.registerBlockStyle( 'core/button', {
        name: 'small',
        label: 'Small',
        isDefault: true,
    });

    wp.blocks.registerBlockStyle( 'core/button', {
        name: 'large',
        label: 'Large'
    });

    wp.blocks.registerBlockStyle( 'core/button', {
        name: 'large-blue-gradient',
        label: 'Large: Blue Gradient'
    });

    wp.blocks.registerBlockStyle( 'core/button', {
        name: 'large-blue-green-gradient',
        label: 'Large: Blue/Green Gradient'
    });
});
