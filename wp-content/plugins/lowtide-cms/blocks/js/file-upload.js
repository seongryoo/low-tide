/* File upload block --------------------------------------------- */
var fileUploadArgs = {
  title: '(GCP) File Link',
  category: 'lowtide-blocks',
  icon: 'upload',

  edit: function (props) {
    
    let renderButton = el(
      MediaUploadCheck,
      {
        
      },
      el(
        MediaUpload,
        {
          onSelect: function( media ) {
            console.log( media );
            props.setAttributes( {
              mediaId: media.id,
              mediaName: media.filename,
              mediaUrl: media.url,
            } );
          },
          value: props.attributes.mediaId,
          render: function( { open } ) {
            return el( 
              wp.components.Button,
              {
                onClick: open,
              },
              'Upload file'
            );
          }
        }
      
      )
    );
    
    
    let chosenFile = el(
      'a',
      {
        className: 'chosen-file-label',
        href: props.attributes.mediaUrl,
      },
      props.attributes.mediaName
    );
    
    let chooseAFile = el(
      'div',
      {},
      [ chosenFile, renderButton ]
    );
    
    
    let displayTextArgs = {
      className: 'choose-display-text',
      onChange: function( value ) {
        props.setAttributes( { displayText: value } );
      },
      label: 'Display Text',
      value: props.attributes.displayText,
    }
    
    let ariaTextArgs = {
      className: 'aria-text',
      onChange: function( value ) {
        props.setAttributes( { aria: value } );
      },
      label: 'Descriptive label (e.g. "Open the 2018 sea level sensors report")',
      value: props.attributes.aria,
    }
    
    let displayText = el(
      wp.components.TextControl,
      displayTextArgs
    );
    
    let ariaText = el(
      wp.components.TextControl,
      ariaTextArgs
    );
    
    return el(
      'div',
      {
        className: 'choose-file ' + props.attributes.className,
      },
      [ chosenFile, renderButton, displayText, ariaText ]
    );
    
  },
  
  save: function( props ) {
    return null;
  },
}
wp.blocks.registerBlockType( 'lowtide/file-upload', fileUploadArgs );