( function( wp ) {
  
  const filterFormats = (settings) => {
    
    console.log("hai");
    if (settings.name !== 'core/link') {
      return settings;
    } else {
      console.log('Looks like meat\'s back on the menu boys');
    }
    
    const newSettings = {
      ...settings,
      
      attributes: {
        ...settings.attributes,
        ariaLabel: {
          type: 'string',
        }
      },
      
      save( props ) {
        settings.save( props );
      }
    }
    
    console.log(newSettings);
    
    return newSettings;
  }
  
  wp.hooks.addFilter(
  'richtext.registerFormatType',
  'sryoo6/gutenberg-aria',
  filterFormats
);
  
} )( window.wp );



console.log('aria.');