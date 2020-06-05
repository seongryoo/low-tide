wp.blocks.registerBlockType('lowtide/contained-width', {
  title: 'GCP Contained Width',
  icon: 'smiley',
  category: 'layout',
  attributes: {
    content: {
      type: 'string',
      source: 'html',
      selector: 'p',
    },
  },
  
  edit: function( props ) {
    return wp.element.createElement( wp.editor.RichText, {
      tagName: 'p',
      className: props.className,
      value: props.attributes.content,
      onChange: function( content ) {
        props.setAttributes( { content: content } );
      },
    } );
  },
  
  save: function( props ) {
    return null;
  },
  
} );

console.log('enqueed');