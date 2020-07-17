/* File upload block --------------------------------------------- */
(function(wp) {
  const el = wp.element.createElement;
  const registerBlock = wp.blocks.registerBlockType;
  const MediaUpload = wp.blockEditor.MediaUpload;
  const MediaUploadCheck = wp.blockEditor.MediaUploadCheck;
  const fileUploadArgs = {
    title: '(SSLS) File Link',
    category: 'lowtide-blocks',
    icon: 'upload',

    edit: function(props) {
      const renderButton = el(
          MediaUploadCheck,
          {},
          el(
              MediaUpload,
              {
                onSelect: function( media ) {
                  props.setAttributes( {
                    mediaId: media.id,
                    mediaName: media.filename,
                    mediaUrl: media.url,
                  } );
                },
                value: props.attributes.mediaId,
                render: function({open}) {
                  return el(
                      wp.components.Button,
                      {onClick: open},
                      'Upload file'
                  );
                },
              }

          )
      );

      const chosenFile = el(
          'a',
          {
            className: 'chosen-file-label',
            href: props.attributes.mediaUrl,
          },
          props.attributes.mediaName
      );

      const displayTextArgs = {
        className: 'choose-display-text',
        onChange: function(value) {
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
        label: 'Descriptive label '
        + '(e.g. "Open the 2018 sea level sensors report")',
        value: props.attributes.aria,
      };

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
          [chosenFile, renderButton, displayText, ariaText]
      );
    },

    save: function( props ) {
      return null;
    },
  };
  registerBlock('lowtide/file-upload', fileUploadArgs);
})(window.wp);
