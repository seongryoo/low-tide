/* Two Col Related Docs block -------------------------------------- */
var twoColRelatedDocsArgs = {
  title: '(SSLS) Two Column: Related documents list',
  category: 'lowtide-blocks',
  icon: 'images-alt',

  edit: function (props) {
    return el(
      'div', {
        className: props.className + ' gcp-two-col gcp-two-col-related-docs'
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

wp.blocks.registerBlockType('lowtide/two-col-related-docs', twoColRelatedDocsArgs);