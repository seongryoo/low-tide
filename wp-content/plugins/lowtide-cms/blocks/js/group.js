/* Section block -------------------------------------- */
var groupArgs = {
  title: '(GCP) Section Block',
  category: 'lowtide-blocks',
  icon: 'editor-insertmore',

  edit: function (props) {
    return el(
      'div', {
        className: props.className + ' gcp-group'
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

wp.blocks.registerBlockType('lowtide/basic-group', groupArgs);