(function(wp) {
  const el = wp.element.createElement;
  const registerBlock = wp.blocks.registerBlockType;
  const linkArgs = {
    title: '(SSLS) Related documents web link',
    category: 'lowtide-blocks',
    icon: 'admin-links',
    edit: function(props) {
      const urlArgs = {
        className: 'choose-url',
        onChange: function( value ) {
          props.setAttributes({url: value});
        },
        label: 'Link URL',
        value: props.attributes.url,
      };
      const displayTextArgs = {
        className: 'choose-display-text',
        onChange: function( value ) {
          props.setAttributes({displayText: value});
        },
        label: 'Display Text',
        value: props.attributes.displayText,
      };

      const ariaTextArgs = {
        className: 'aria-text',
        onChange: function( value ) {
          props.setAttributes({aria: value});
        },
        label: 'Descriptive label'
          + ' (e.g. "Visit the Georgia Climate Project homepage")',
        value: props.attributes.aria,
      };

      const url = el(
          wp.components.TextControl,
          urlArgs
      );

      const displayText = el(
          wp.components.TextControl,
          displayTextArgs
      );

      const ariaText = el(
          wp.components.TextControl,
          ariaTextArgs
      );

      return el(
          'div',
          {
            className: 'lowtide ' + props.attributes.className,
          },
          [url, displayText, ariaText]
      );
    },

    save: function( props ) {
      return null;
    },
  };
  registerBlock( 'lowtide/link', linkArgs );
})(window.wp);
