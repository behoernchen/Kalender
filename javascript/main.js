var makeAJAXLinks, makeDaysClickable, makeFormAsync, selectedDay;

selectedDay = void 0;

makeAJAXLinks = function() {
  return $("a").bind("click", function(event) {
    var body, url;
    event.preventDefault();
    url = this.href;
    body = $("body");
    return $("#container").fadeOut(200, function() {
      return body.load(url + " #container", function() {
        makeAJAXLinks();
        makeDaysClickable();
        makeFormAsync();
        return $(".event").tooltip();
      });
    });
  });
};

makeDaysClickable = function() {
  $(".day").bind("click", function(event) {
    if (selectedDay !== undefined) {
      selectedDay.removeClass("selected");
    }
    selectedDay = $(this);
    return selectedDay.addClass("selected");
  });
  return $(".day").bind("dblclick", function(event) {
    $("#date-input").val($(this).attr("data-date"));
    return $("#newEvent").modal();
  });
};

makeFormAsync = function() {
  return $("#event-form").submit(function(event) {
    var date, form, name, posting, url;
    event.preventDefault();
    form = $(this);
    name = form.find("input[name=\"event_name\"]").val();
    date = form.find("input[name=\"date_input\"]").val();
    url = form.attr("action");
    posting = $.post(url, {
      event_name: name,
      date_input: date
    });
    return posting.done(function(data) {
      var day;
      if (data === "1") {
        day = $(document).find("[data-date=\"" + date + "\"]");
        if (day.find(".schedules").length) {
          day.find(".schedules").append("<a href=\"#\" data-toggle=\"tooltip\" title=\"\" class=\"event\" data-original-title=\"" + name + "\"></a>");
        } else {
          day.append("<div class=\"schedules\"><a href=\"#\" data-toggle=\"tooltip\" title=\"\" class=\"event\" data-original-title=\"" + name + "\"></a></div>");
        }
      }
      form.find("input[name=\"event_name\"]").val("");
      $("#newEvent").modal("hide");
      return $(".event").tooltip();
    });
  });
};

$(document).ready(function() {
  makeAJAXLinks();
  makeDaysClickable();
  $(".event").tooltip();
  return makeFormAsync();
});
