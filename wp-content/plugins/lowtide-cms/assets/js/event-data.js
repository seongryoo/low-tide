(function(wp) {
  const el = wp.element.createElement;
  const registerBlock = wp.blocks.registerBlockType;
  const eventDataEdit = function(props) {
    // Date field
    const updateDate = function(newDate) {
      props.setAttributes({date: newDate});
    };
    const getChosenDate = function() {
      return props.attributes.date == undefined ? null : props.attributes.date;
    };
    const calendarArgs = {
      currentDate: getChosenDate(),
      onChange: updateDate,
      id: 'date-select',
    };
    const calendarElement = el(
        wp.components.DatePicker,
        calendarArgs
    );
    const calendarLabel = el(
        'label',
        {
          for: 'date-select',
        },
        'Event date:'
    );
    const calendarWrapped = el(
        'div',
        {
          className: 'components-base-control',
        },
        el(
            'div',
            {
              className: 'components-base-control__field',
            },
            [calendarLabel, calendarElement]
        )
    );
    // Time field
    const timeArgs = {
      onChange: function(value) {
        props.setAttributes({time: value});
      },
      label: 'Event time:',
      placeholder: 'e.g. 8:00 AM - 9:00 AM',
      value: props.attributes.time,
    };
    const time = el(
        wp.components.TextControl,
        timeArgs
    );
    // Location field
    const locArgs = {
      onChange: function(value) {
        props.setAttributes({loc: value});
      },
      label: 'Event location:',
      placeholder: 'e.g. Georgia Tech',
      value: props.attributes.loc,
    };
    const loc = el(
        wp.components.TextControl,
        locArgs
    );
    // Description field
    const descArgs = {
      onChange: function(value) {
        props.setAttributes({desc: value});
      },
      value: props.attributes.desc,
      multiline: true,
      className: 'event-description',
      id: 'event-desc',
      placeholder: 'Start typing...',
    };
    const desc = el(
        wp.editor.RichText,
        descArgs
    );
    const descLabel = el(
        'label',
        {
          for: 'event-desc',
        },
        'Event description:'
    );
    const descLabelled = el(
        'div',
        {
          className: 'components-base-control__field',
        },
        [descLabel, desc]
    );
    const descWrapped = el(
        'div',
        {
          className: 'components-base-control event-description-block',
        },
        descLabelled
    );
    // Final element
    return el(
        'div',
        {
          className: 'lowtide',
        },
        [name, calendarWrapped, time, loc, descWrapped]
    );
  };

  const eventDataArgs = {
    title: '(SSLS) Event Data',
    category: 'lowtide-blocks',
    icon: 'calendar-alt',
    attributes: {
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
