
/* Two Col Main block -------------------------------------- */
(function(wp) {
  const el = wp.element.createElement;
  const registerBlock = wp.blocks.registerBlockType;
  const InnerBlocks = wp.blockEditor.InnerBlocks;
  const twoColMainArgs = {
    title: '(SSLS) Two Column: Main column',
    category: 'lowtide-blocks',
    icon: 'text',

    edit: function(props) {
      return el(
          'div', {
            className: props.className + ' gcp-two-col gcp-two-col-main lowtide',
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
  }; /* end of containerArgs obj*/

  registerBlock('lowtide/two-col-main', twoColMainArgs);
})(window.wp);

