/* Back link ---------------------------------------------- */
(function(wp) {
  const el = wp.element.createElement;
  const registerBlock = wp.blocks.registerBlockType;
  const backArgs = {
    title: '(SSLS) Breadcrumbs back link',
    category: 'lowtide-blocks',
    icon: 'undo',

    edit: function(props) {
      const domAttrs = {
        className: props.className + ' gcp-back lowtide',
      };

      const linkTextAttrs = {
        label: 'Link display text',
        value: props.attributes.linkText,
        onChange: function(value) {
          props.setAttributes({
            linkText: value,
          });
        },
      };

      const linkUrlAttrs = {
        label: 'Link URL',
        value: props.attributes.linkUrl,
        onChange: function(value) {
          props.setAttributes({
            linkUrl: value,
          });
        },
      };

      const linkAriaAttrs = {
        label: 'Link ARIA label (Optional)',
        value: props.attributes.linkAria,
        onChange: function(value) {
          props.setAttributes({
            linkAria: value,
          });
        },
      };

      const linkText = el(wp.components.TextControl, linkTextAttrs);
      const linkUrl = el(wp.components.TextControl, linkUrlAttrs);
      const linkAria = el(wp.components.TextControl, linkAriaAttrs);

      return el(
          'div',
          domAttrs, [linkText, linkUrl, linkAria]
      );
    },

    save: function(props) {
      return null;
    },
  };
  registerBlock('lowtide/back-link-block', backArgs);
})(window.wp);
