/* Two Col Related Docs block -------------------------------------- */
(function(wp) {
  const el = wp.element.createElement;
  const registerBlock = wp.blocks.registerBlockType;
  const InnerBlocks = wp.blockEditor.InnerBlocks;
  const twoColRelatedDocsArgs = {
    title: '(SSLS) Two Column: Related documents list',
    category: 'lowtide-blocks',
    icon: 'images-alt',
    edit: function(props) {
      return el(
          'div', {
            className: props.className
              + ' gcp-two-col gcp-two-col-related-docs lowtide',
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
  registerBlock('lowtide/two-col-related-docs', twoColRelatedDocsArgs);
})(window.wp);


