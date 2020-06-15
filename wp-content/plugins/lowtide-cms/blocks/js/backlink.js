/* Back link ---------------------------------------------- */
var backArgs = {
  title: '(GCP) Breadcrumbs back link',
  category: 'lowtide-blocks',
  icon: 'undo',

  edit: function (props) {
    let domAttrs = {
      className: props.className + ' gcp-back',
    }

    let linkTextAttrs = {
      label: 'Link display text',
      value: props.attributes.linkText,
      onChange: function (value) {
        props.setAttributes({
          linkText: value,
        });
      },
    }

    let linkUrlAttrs = {
      label: 'Link URL',
      value: props.attributes.linkUrl,
      onChange: function (value) {
        props.setAttributes({
          linkUrl: value,
        });
      },
    }

    let linkAriaAttrs = {
      label: 'Link ARIA label (Optional)',
      value: props.attributes.linkAria,
      onChange: function (value) {
        props.setAttributes({
          linkAria: value,
        });
      },
    }

    let linkText = el(wp.components.TextControl, linkTextAttrs);
    let linkUrl = el(wp.components.TextControl, linkUrlAttrs);
    let linkAria = el(wp.components.TextControl, linkAriaAttrs);

    return el(
      'div',
      domAttrs, [linkText, linkUrl, linkAria]
    );
  },

  save: function (props) {
    return null;
  }
}
wp.blocks.registerBlockType('lowtide/back-link-block', backArgs);