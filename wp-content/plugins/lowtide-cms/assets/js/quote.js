/* Quote block -------------------------------------- */
(function(wp) {
  const el = wp.element.createElement;
  const registerBlock = wp.blocks.registerBlockType;
  const quoteArgs = {
    title: '(SSLS) Quote Block',
    category: 'lowtide-blocks',
    icon: 'admin-comments',

    edit: function(props) {
      const domAttrs = {
        className: props.className + ' lowtide',
        type: 'text',
        onChange: function(value) {
          props.setAttributes({
            content: value,
          });
        },
        value: props.attributes.content,
        label: 'Quote block',
      };
      return el(
          wp.components.TextareaControl,
          domAttrs
      );
    },

    save: function(props) {
      return null;
    },
  };
  registerBlock('lowtide/quote-block', quoteArgs);
})(window.wp);

