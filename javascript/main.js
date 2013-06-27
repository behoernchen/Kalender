var selectedDay;

$(document).ready(function() {
  console.log("Document ready fired...");
  // AJAX Links...
  makeAJAXLinks();

  // Kalendertage auswaehlbar machen...
  makeDaysClickable();

  // Eventanzeige...
  $('.event').tooltip();

  // AJAX Formular...
  makeFormAsync();
});


function makeAJAXLinks() {
  $('a').bind('click',function(event) {
    event.preventDefault();
    var url  = this.href;
    var body = $('body');

    $('#container').fadeOut(200, function() {
      body.load(url + ' #container', function() {
        makeAJAXLinks();
        makeDaysClickable();
        makeFormAsync();
        $('.event').tooltip();
        // $('#container').fadeIn(1200);
      });
    });
  });
}


function makeDaysClickable() {
  $('.day').bind('click', function(event) {
    if (selectedDay != undefined) {
      selectedDay.removeClass('selected');  
    }
    
    selectedDay = $(this);
    selectedDay.addClass('selected');
  });

  $('.day').bind('dblclick', function(event) {
    $('#date-input').val($(this).attr('data-date'));
    $('#newEvent').modal();
  });
}


function makeFormAsync() {
  $("#event-form").submit(function(event) {
    event.preventDefault();
   
    var form = $(this),
        name = form.find('input[name="event_name"]').val(),
        date = form.find('input[name="date_input"]').val(),
        url  = form.attr('action');
   
    var posting = $.post(url, { 'event_name': name, 'date_input': date });
   
    posting.done(function(data) {
      if (data == '1') {
        day = $(document).find('[data-date="' + date + '"]');
        if (day.find('.schedules').length) {
          day.find('.schedules').append('<a href="#" data-toggle="tooltip" title="" class="event" data-original-title="' + name + '"></a>');
        } else {
          day.append('<div class="schedules"><a href="#" data-toggle="tooltip" title="" class="event" data-original-title="' + name + '"></a></div>')
        }
      }
      form.find('input[name="event_name"]').val("");
      $('#newEvent').modal('hide');

      // Eventanzeige...
      $('.event').tooltip();
    });
  });
}
