var el = wp.element.createElement;
var InnerBlocks = wp.blockEditor.InnerBlocks;


/* Width container block -------------------------------------- */
var containerArgs = {
  title: '(GCP) Width Container',
  category: 'lowtide-blocks',
  icon: 'admin-site-alt',

  edit: function( props ) {
    return el(
      'div',
      { className: props.className },
      el( InnerBlocks, {


      renderAppender: () => el( InnerBlocks.ButtonBlockAppender )
      } )
    );
  },

  save: function( props ) {

    return el( InnerBlocks.Content );

  }, /* end of save() */

}; /*end of containerArgs obj*/

wp.blocks.registerBlockType( 'lowtide/width-container', containerArgs );


/* Card block -------------------------------------- */
var cardArgs = {
  title: '(GCP) Bevel Card',
  category: 'lowtide-blocks',
  icon: 'admin-site-alt',
  
  edit: function( props ) {
    return el(
      'div',
      { className: props.className + ' card' },
      el( InnerBlocks, {


      renderAppender: () => el( InnerBlocks.ButtonBlockAppender )
      } )
    );
  },

  save: function( props ) {

    return el( InnerBlocks.Content );

  }, /* end of save() */

}; /* end of cardArgs */
  
wp.blocks.registerBlockType( 'lowtide/card', cardArgs );


/* Section block -------------------------------------- */
var groupArgs = {
  title: '(GCP) Section Block',
  category: 'lowtide-blocks',
  icon: 'admin-site-alt',

  edit: function( props ) {
    return el(
      'div',
      { className: props.className + ' gcp-group' },
      el( InnerBlocks, {


      renderAppender: () => el( InnerBlocks.ButtonBlockAppender )
      } )
    );
  },

  save: function( props ) {

    return el( InnerBlocks.Content );

  }, /* end of save() */

}; /*end of containerArgs obj*/

wp.blocks.registerBlockType( 'lowtide/basic-group', groupArgs );


/* Quote block -------------------------------------- */
var quoteArgs = {
  title: '(GCP) Quote Block',
  category: 'lowtide-blocks',
  icon: 'admin-site-alt',
  
  edit: function( props ) {
    
    let domAttrs = {
      className: props.className + ' gcp-quote',
      type: 'text',
      onChange: function( value ) {
        props.setAttributes( { content: value, } );
      },
      value: props.attributes.content,
      label: 'Quote block',
    }
    return el(
      wp.components.TextareaControl,
      domAttrs
    );
  },
  
  save: function( props ) {
    return null;
  }
}
wp.blocks.registerBlockType( 'lowtide/quote-block', quoteArgs );



/* Back link ---------------------------------------------- */
var backArgs = {
  title: '(GCP) Breadcrumbs back link',
  category: 'lowtide-blocks',
  icon: 'admin-site-alt',
  
  edit: function( props ) {
    let domAttrs = {
      className: props.className + ' gcp-back',
    }
    
    let linkTextAttrs = {
      label: 'Link display text',
      value: props.attributes.linkText,
      onChange: function( value ) {
        props.setAttributes( { linkText: value, } );
      },
    }
    
    let linkUrlAttrs = {
      label: 'Link URL',
      value: props.attributes.linkUrl,
      onChange: function( value ) {
        props.setAttributes( { linkUrl: value, } );
      },
    }
    
    let linkAriaAttrs = {
      label: 'Link ARIA label (Optional)',
      value: props.attributes.linkAria,
      onChange: function( value ) {
        props.setAttributes( { linkAria: value, } );
      },
    }
    
    let linkText = el( wp.components.TextControl, linkTextAttrs );
    let linkUrl  = el( wp.components.TextControl, linkUrlAttrs );
    let linkAria = el( wp.components.TextControl, linkAriaAttrs );
    
    return el(
      'div',
      domAttrs,
      [ linkText, linkUrl, linkAria ]
    );
  },
  
  save: function( props ) {
    return null;
  }
}
wp.blocks.registerBlockType( 'lowtide/back-link-block', backArgs );



/* Event block ---------------------------------------------- */




var eventArgs = {
  title: '(GCP) Events data',
  category: 'lowtide-blocks',
  icon: 'admin-site-alt',
  
  edit: function( props ) {
    var argsText = {
      type: 'string',
      onChange: function( value ) {
        console.log( 'wat ' );
      }
    }
    
    var updateDate = function( currentDate ) {
      console.log("stop");
      console.log( currentDate );
      props.setAttributes( { date: currentDate } );
    }
    
    let args = {
      currentDate: (props.attributes.date == undefined ? null : props.attributes.date ),
      onChange: updateDate,
    }
    
    let date = el( wp.components.DatePicker, args );
    
    var postDate = function( dateString ) {
      
      if ( dateString == undefined ) {
        return 'Please choose a date!';
      }
      
      let dateObj = new Date( dateString );
      
      // l F jS, Y -> e.g. Monday January 1st, 1999
      let dateFormatted = wp.date.date( 'l F jS, Y', dateObj );
      return dateFormatted;
    }
    
    return el(
      'div',
      [],
      [ date, el( 'p', argsText, postDate( props.attributes.date ) )]
    );
  },
  
  save: function( props ) {
    return null;
  }
}
wp.blocks.registerBlockType( 'lowtide/event-block', eventArgs );