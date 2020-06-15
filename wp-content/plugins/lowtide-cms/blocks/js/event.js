/* Event block ---------------------------------------------- */
var eventArgs = {
  title: '(GCP) Events data',
  category: 'lowtide-blocks',
  icon: 'calendar-alt',

  edit: function (props) {
    var argsText = {
      type: 'string',
      onChange: function (value) {
        console.log('wat ');
      }
    }

    var updateDate = function (currentDate) {
      console.log("stop");
      console.log(currentDate);
      props.setAttributes({
        date: currentDate
      });
    }

    let args = {
      currentDate: (props.attributes.date == undefined ? null : props.attributes.date),
      onChange: updateDate,
    }

    let date = el(wp.components.DatePicker, args);

    var postDate = function (dateString) {

      if (dateString == undefined) {
        return 'Please choose a date!';
      }

      let dateObj = new Date(dateString);

      // l F jS, Y -> e.g. Monday January 1st, 1999
      let dateFormatted = wp.date.date('l F jS, Y', dateObj);
      return dateFormatted;
    }

    return el(
      'div', [], [date, el('p', argsText, postDate(props.attributes.date))]
    );
  },

  save: function (props) {
    return null;
  }
}
wp.blocks.registerBlockType('lowtide/event-block', eventArgs);