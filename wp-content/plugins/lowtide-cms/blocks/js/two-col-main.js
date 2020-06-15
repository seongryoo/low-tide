
/* Two Col Main block -------------------------------------- */
var twoColMainArgs = {
  title: '(GCP) Two Column: Main column',
  category: 'lowtide-blocks',
  icon: 'text',

  edit: function (props) {
    return el(
      'div', {
        className: props.className + ' gcp-two-col gcp-two-col-main'
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

wp.blocks.registerBlockType('lowtide/two-col-main', twoColMainArgs);