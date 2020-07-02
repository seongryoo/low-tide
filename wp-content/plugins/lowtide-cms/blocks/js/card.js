/* Card block -------------------------------------- */
var cardArgs = {
  title: '(SSLS) Bevel Card',
  category: 'lowtide-blocks',
  icon: 'format-aside',

  edit: function (props) {
    return el(
      'div', {
        className: props.className + ' card'
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

}; /* end of cardArgs */

wp.blocks.registerBlockType('lowtide/card', cardArgs);