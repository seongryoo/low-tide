var el = wp.element.createElement;
var InnerBlocks = wp.blockEditor.InnerBlocks;

var args = {
  title: 'Width Container',
  category: 'layout',

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

    var colData = {
      className: 'col-md-9',
    }

    var rowData = {
      className: props.className + ' row justify-content-md-center'
    }

    return el(
      'div',
      rowData,
      el(
        'div',
        colData,
        el( InnerBlocks.Content )
      )
    );

  },

}; /*end of args obj*/

wp.blocks.registerBlockType( 'lowtide/width-container', args );
  