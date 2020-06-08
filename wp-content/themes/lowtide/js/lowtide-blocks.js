wp.blocks.registerBlockType('lowtide/contained-width', {
  title: 'GCP Contained Width',
  icon: 'smiley',
  category: 'layout',
  
  edit: function( props ) {
    console.log( props );
    return wp.element.createElement( wp.blockEditor.RichText, {
      tagName: 'p',
      className: 'contained',
      value: props.attributes.content,
      onChange: function( newContent ) {
        props.setAttributes( { content: newContent } );
        console.log( props.attributes.content );
      },
    } );
  },
  
	save: function( props ) {
		return null;
	},
  
} );

console.log('enqueed');