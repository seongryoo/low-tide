/* Quote block -------------------------------------- */
var quoteArgs = {
  title: '(GCP) Quote Block',
  category: 'lowtide-blocks',
  icon: 'admin-comments',

  edit: function (props) {

    let domAttrs = {
      className: props.className + ' gcp-quote',
      type: 'text',
      onChange: function (value) {
        props.setAttributes({
          content: value,
        });
      },
      value: props.attributes.content,
      label: 'Quote block',
    }
    return el(
      wp.components.TextareaControl,
      domAttrs
    );
  },

  save: function (props) {
    return null;
  }
}
wp.blocks.registerBlockType('lowtide/quote-block', quoteArgs);