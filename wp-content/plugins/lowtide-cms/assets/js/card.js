/* Card block -------------------------------------- */
(function(wp) {
  const el = wp.element.createElement;
  const registerBlock = wp.blocks.registerBlockType;
  const InnerBlocks = wp.blockEditor.InnerBlocks;
  const cardArgs = {
    title: '(SSLS) Bevel Card',
    category: 'lowtide-blocks',
    icon: 'format-aside',

    edit: function(props) {
      return el(
          'div', {
            className: props.className + ' card lowtide',
          },
          el(InnerBlocks, {
            renderAppender: () => el(InnerBlocks.ButtonBlockAppender),
          })
      );
    },

    save: function(props) {
      return el(InnerBlocks.Content);
    },
    /* end of save() */

  }; /* end of cardArgs */

  registerBlock('lowtide/card', cardArgs);
})(window.wp);
