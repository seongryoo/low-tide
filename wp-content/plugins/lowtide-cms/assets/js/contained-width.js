/* Width container block -------------------------------------- */
(function(wp) {
  const el = wp.element.createElement;
  const registerBlock = wp.blocks.registerBlockType;
  const InnerBlocks = wp.blockEditor.InnerBlocks;
  const containerArgs = {
    title: '(SSLS) Width Container',
    category: 'lowtide-blocks',
    icon: 'editor-contract',

    edit: function(props) {
      return el(
          'div', {
            className: props.className + ' lowtide',
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

  registerBlock('lowtide/width-container', containerArgs);
})(window.wp);
