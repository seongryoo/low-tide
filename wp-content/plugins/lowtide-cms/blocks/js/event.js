const displayDateFromString = function( dateString ) {
  if ( dateString == undefined ) {
    return 'Please choose a date!';
  }
  
  let dateObj = new Date( dateString );
  
  // l F jS, Y -> e.g. Monday January 1st, 1999
  let dateFormatted = wp.date.date( 'l F jS, Y', dateObj );
  
  return dateFormatted;
}

const displayTimeFromString = function( dateString ) {
  if ( dateString == undefined ) {
    return 'Please choose a date!';
  }
  
  let dateObj = new Date( dateString );
  
  // g:i A -> e.g. 5:02 PM
  let dateFormatted = wp.date.date( 'g:i A', dateObj );
  
  return dateFormatted;
}

const getHours = function( dateString ) {
  if ( dateString == undefined ) {
    return 0;
  }
  
  
  let dateObj = wp.date.getDate( dateString );
  
  return dateObj.getHours();
}

const eventEdit = function( props ) {
  
  let updateDate = function( newDate ) {
    props.setAttributes( { date: newDate } );
  }
  
  let dateChosen = function() {
    return props.attributes.date == undefined ? null : props.attributes.date;
  }
  
  let calendarArgs = {
    currentDate: dateChosen(),
    onChange: updateDate,
  }
  
  let calendarElement = el(
    wp.components.DatePicker,
    calendarArgs
  );
  
  let calendarLabel = el(
    'p',
    [],
    'Event day:'
  );  
  let calendarLiveLabel = el(
    'p',
    [],
    displayDateFromString( props.attributes.date )
  );
  
  let chooseDateElement = el(
    'div',
    [],
    [ calendarLabel, calendarLiveLabel, calendarElement ]
  );
  
  let eventInfoArgs = {
    onChange: function( value ) {
      props.setAttributes( { eventInfo: value } );
    },
    label: 'Event time and/or location (e.g. 8:00 AM - 12:00 PM, Ballroom H)',
    value: props.attributes.eventInfo,
  };
  let eventInfo = el(
    wp.components.TextControl,
    eventInfoArgs
  );
  
  let eventNameArgs = {
    onChange: function( value ) {
      props.setAttributes( { name: value } );
    },
    label: 'Event name',
    value: props.attributes.name,
  }
  let eventName = el(
    wp.components.TextControl,
    eventNameArgs
  );
  
  let eventDescArgs = {
    
    onChange: function( value ) {
      props.setAttributes( { desc: value } );
    },
    placeholder: 'Event description',
    value: props.attributes.desc,
    multiline: true,
    className: 'event-description',
  }
  
  let eventDesc = el(
    wp.editor.RichText,
    eventDescArgs
  );
  
  return el(
    'div',
    [],
    [ eventName, calendarElement, eventInfo, eventDesc ]
  );
  
}

const eventArgs = {
  title: '(GCP) Event Data',
  category: 'lowtide-blocks',
  icon: 'calendar-alt',
  
  edit: eventEdit,
  
  save: function( props ) {
    return null;
  }
  
}

wp.blocks.registerBlockType('lowtide/event-block', eventArgs);
