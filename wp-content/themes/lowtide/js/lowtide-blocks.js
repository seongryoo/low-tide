var el = wp.element.createElement;
var InnerBlocks = wp.blockEditor.InnerBlocks;

var containerArgs = {
  title: '(GCP) Width Container',
  category: 'lowtide-blocks',
  icon: 'admin-site-alt',

  edit: function( props ) {
    return el(
      'div',
      { className: props.className },
      el( InnerBlocks, {


      renderAppender: () => el( InnerBlocks.ButtonBlockAppender )
      } )
    );
  },

  save: function( props ) {

    return el( InnerBlocks.Content );

  }, /* end of save() */

}; /*end of containerArgs obj*/

wp.blocks.registerBlockType( 'lowtide/width-container', containerArgs );

var cardArgs = {
  title: '(GCP) Bevel Card',
  category: 'lowtide-blocks',
  icon: 'admin-site-alt',
  
  edit: function( props ) {
    return el(
      'div',
      { className: props.className + ' card' },
      el( InnerBlocks, {


      renderAppender: () => el( InnerBlocks.ButtonBlockAppender )
      } )
    );
  },

  save: function( props ) {

    return el( InnerBlocks.Content );

  }, /* end of save() */

}; /* end of cardArgs */
  
wp.blocks.registerBlockType( 'lowtide/card', cardArgs );

var groupArgs = {
  title: '(GCP) Section Block',
  category: 'lowtide-blocks',
  icon: 'admin-site-alt',

  edit: function( props ) {
    return el(
      'div',
      { className: props.className + ' gcp-group' },
      el( InnerBlocks, {


      renderAppender: () => el( InnerBlocks.ButtonBlockAppender )
      } )
    );
  },

  save: function( props ) {

    return el( InnerBlocks.Content );

  }, /* end of save() */

}; /*end of containerArgs obj*/

wp.blocks.registerBlockType( 'lowtide/basic-group', groupArgs );