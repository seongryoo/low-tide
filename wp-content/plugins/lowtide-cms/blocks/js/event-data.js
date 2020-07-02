(function(wp) {
  const el = wp.element.createElement;
  const registerBlock = wp.blocks.registerBlockType;
  const eventDataEdit = function(props) {
    const updateDate = function(newDate) {
      props.setAttributes({date: newDate});
    };
    const getChosenDate = function() {
      return props.attributes.date == undefined ? null : props.attributes.date;
    };
    const calendarArgs = {
      currentDate: getChosenDate(),
      onChange: updateDate,
    };
    const calendarElement = el(
        wp.components.DatePicker,
        calendarArgs
    );
    const timeArgs = {
      onChange: function(value) {
        props.setAttributes({time: value});
      },
      label: 'Event time (e.g. 8:00 AM - 12:00 PM',
      value: props.attributes.time,
    };
    const time = el(
        wp.components.TextControl,
        timeArgs
    );
    const locArgs = {
      onChange: function(value) {
        props.setAttributes({loc: value});
      },
      label: 'Event location',
      value: props.attributes.loc,
    };
    const loc = el(
        wp.components.TextControl,
        locArgs
    );
    const nameArgs = {
      onChange: function(value) {
        props.setAttributes({name: value});
      },
      label: 'Event name',
      value: props.attributes.name,
    };
    const name = el(
        wp.components.TextControl,
        nameArgs
    );
    const descArgs = {
      onChange: function(value) {
        props.setAttributes({desc: value});
      },
      placeholder: 'Event description',
      value: props.attributes.desc,
      multiline: true,
      className: 'event-description',
    };
    const desc = el(
        wp.editor.RichText,
        descArgs
    );
    return el(
        'div',
        [],
        [name, calendarElement, time, loc, desc]
    );
  };

  const eventDataArgs = {
    title: '(SSLS) Event Data',
    category: 'lowtide-blocks',
    icon: 'calendar-alt',
    attributes: {
      name: {
        type: 'string',
        source: 'meta',
        meta: 'post_event_meta_name',
      },
      date: {
        type: 'string',
        source: 'meta',
        meta: 'post_event_meta_date',
      },
      desc: {
        type: 'string',
        source: 'meta',
        meta: 'post_event_meta_desc',
      },
      time: {
        type: 'string',
        source: 'meta',
        meta: 'post_event_meta_time',
      },
      loc: {
        type: 'string',
        source: 'meta',
        meta: 'post_event_meta_loc',
      },
    },
    edit: eventDataEdit,
    save: function(props) {
      return null;
    },
  };
  registerBlock('lowtide/event-data', eventDataArgs);
})(window.wp);
