/* Section block -------------------------------------- */
(function(wp) {
  const el = wp.element.createElement;
  const InnerBlocks = wp.blockEditor.InnerBlocks;
  const registerBlock = wp.blocks.registerBlockType;
  const groupArgs = {
    title: '(SSLS) Section Block',
    category: 'lowtide-blocks',
    icon: 'editor-insertmore',

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

  registerBlock('lowtide/basic-group', groupArgs);
})(window.wp);

