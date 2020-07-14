const displayDateFromString = function(dateString) {
  if (dateString == undefined) {
    return 'Please choose a date!';
  }
  const dateObj = new Date(dateString);

  // l F jS, Y -> e.g. Monday January 1st, 1999
  const dateFormatted = wp.date.date( 'l F jS, Y', dateObj);

  return dateFormatted;
};
