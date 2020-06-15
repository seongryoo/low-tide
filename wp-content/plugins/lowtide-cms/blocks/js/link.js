var linkArgs = {
  title: '(GCP) Related documents web link',
  category: 'lowtide-blocks',
  icon: 'admin-links',
  
  edit: function ( props ) {
    let urlArgs = {
      className: 'choose-url',
      onChange: function( value ) {
        props.setAttributes( { url: value } );
      },
      label: 'Link URL',
      value: props.attributes.url,
    }
    
    let displayTextArgs = {
      className: 'choose-display-text',
      onChange: function( value ) {
        props.setAttributes( { displayText: value } );
      },
      label: 'Display Text',
      value: props.attributes.displayText,
    }
    
    let ariaTextArgs = {
      className: 'aria-text',
      onChange: function( value ) {
        props.setAttributes( { aria: value } );
      },
      label: 'Descriptive label (e.g. "Visit the Georgia Climate Project homepage")',
      value: props.attributes.aria,
    }
    
    let url = el(
      wp.components.TextControl,
      urlArgs
    );
    
    let displayText = el(
      wp.components.TextControl,
      displayTextArgs
    );
    
    let ariaText = el(
      wp.components.TextControl,
      ariaTextArgs
    );
    
    return el(
      'div',
      {
        className: 'choose-link ' + props.attributes.className,
      },
      [ url, displayText, ariaText ]
    );
  },
  
  save: function( props ) {
    return null;
  },
}
wp.blocks.registerBlockType( 'lowtide/link', linkArgs );