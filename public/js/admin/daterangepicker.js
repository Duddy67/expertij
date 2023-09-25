(function($) {

  // Run a function when the page is fully loaded including graphics.
  $(window).on('load', function() {
      $.fn.initDatePickers();
  });

  // IMPORTANT: Do not use the jQuery # selector to get elements by id or the elements
  //            containing a dot in their id won't be taken into account. 

  $.fn.initDatePickers = function() {
      $('.date').daterangepicker({
          'singleDatePicker': true,
          'timePicker': true,
          'timePicker24Hour': true,
          'showDropdowns': true,
          //'autoUpdateInput': false,
      },
      function(start, end, label) {
        //console.log('New date range selected: ' + start.format('YYYY-MM-DD h:mm A') + ' to ' + end.format('YYYY-MM-DD h:mm A') + ' (predefined range: ' + label + ')');
      });

      $('.date').on('apply.daterangepicker', function(ev, picker) {
          // Convert the selected datetime in MySQL format. 
          let date = picker.startDate.format('YYYY-MM-DD');
          let time = picker.startDate.format('HH:mm');

          // Set the hidden field to the selected datetime
          $('input[id="_'+$(this).attr('id')+'"]').val(date+' '+time);

          // Set the data attributes.
          $('input[id="'+$(this).attr('id')+'"]').attr('data-date', date);
          // Check first whether a time picker is used.
          if ($('input[id="'+$(this).attr('id')+'"]').data('time') !== undefined) {
              $('input[id="'+$(this).attr('id')+'"]').attr('data-time', time);
          }
      });

      $('.date').on('show.daterangepicker', function(ev, picker) {
          //$('.daterangepicker').hide();
      });

      $.fn.initStartDates();   
  }

  $.fn.initStartDates = function() {
      // The fields to initialized.
      let fields = $('.date');
      
      for (let i = 0; i < fields.length; i++) {
          // Check first whether the element exists.
          if ($('input[id="'+fields[i].id+'"]').length) {
              // Check if a date format is available for this field or set it to the default format.
              let format = document.getElementById(fields[i].id).hasAttribute('data-format') ? $('input[id="'+fields[i].id+'"]').data('format') : 'DD/MM/YYYY HH:mm';

              // Change the locale date format of that picker. 
              $('input[id="'+fields[i].id+'"]').data('daterangepicker').locale.format = format;
              // Check whether a time picker is needed (ie: whether a "time" dataset is defined).
              let timePicker = ($('input[id="'+fields[i].id+'"]').data('time') === undefined) ? false : true;
              // Set the datepicker accordingly.
              $('input[id="'+fields[i].id+'"]').data('daterangepicker').timePicker = timePicker;

              // By defaut set the start date to the current date.
              let startDate = moment().format(format);

              // A datetime has been previously set.
              if ($('input[id="'+fields[i].id+'"]').data('date') != 0) {
                  // Set time to 00:00 when no time picker is available.
                  let time = (timePicker) ? $('input[id="'+fields[i].id+'"]').data('time') : '00:00';
                  // Concatenate date and time dataset parameters. 
                  let datetime = $('input[id="'+fields[i].id+'"]').data('date')+' '+time;
                  startDate = moment(datetime).format(format);
                  // Set the hidden field to the datetime previously set.
                  $('input[id="_'+fields[i].id+'"]').val(datetime);
              }
              else {
                  // Set the hidden field to the current datetime in MySQL format.
                  $('input[id="'+fields[i].id+'"]').val(moment().format('YYYY-MM-DD HH:mm'));
              }

              // Initialize the date field.
              $('input[id="'+fields[i].id+'"]').data('daterangepicker').setStartDate(startDate);

              // Check if the field should be empty in the first place.
              // N.B: Do not use the autoUpdateInput property here has it generates a weird behavior (bug ?).
              if (document.getElementById(fields[i].id).hasAttribute('data-options') && $('input[id="'+fields[i].id+'"]').data('options').includes('startEmpty')) {
                  $('input[id="'+fields[i].id+'"]').val('');
              }
          }
      }
  }

})(jQuery);

