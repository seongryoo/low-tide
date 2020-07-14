/* Width container block -------------------------------------- */
var containerArgs = {
  title: '(SSLS) Width Container',
  category: 'lowtide-blocks',
  icon: 'editor-contract',

  edit: function (props) {
    return el(
      'div', {
        className: props.className
      },
      el(InnerBlocks, {


        renderAppender: () => el(InnerBlocks.ButtonBlockAppender)
      })
    );
  },

  save: function (props) {

    return el(InnerBlocks.Content);

  },
  /* end of save() */

}; /*end of containerArgs obj*/

wp.blocks.registerBlockType('lowtide/width-container', containerArgs);
