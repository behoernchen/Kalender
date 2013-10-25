selectedDay = undefined


makeAJAXLinks = ->
  $("a").bind "click", (event) ->
    event.preventDefault()
    url = @href
    body = $("body")
    $("#container").fadeOut 200, ->
      body.load url + " #container", ->
        # AJAX Links
        makeAJAXLinks()

        # Make days selectable
        makeDaysClickable()

        # AJAX form
        makeFormAsync()

        # Tooltip on Events
        $(".event").tooltip()


makeDaysClickable = ->
  $(".day").bind "click", (event) ->
    selectedDay.removeClass "selected"  unless selectedDay is `undefined`
    selectedDay = $(this)
    selectedDay.addClass "selected"

  $(".day").bind "dblclick", (event) ->
    $("#date-input").val $(this).attr("data-date")
    $("#newEvent").modal()


makeFormAsync = ->
  $("#event-form").submit (event) ->
    event.preventDefault()
    form = $(this)
    name = form.find("input[name=\"event_name\"]").val()
    date = form.find("input[name=\"date_input\"]").val()
    url = form.attr("action")
    posting = $.post(url,
      event_name: name
      date_input: date
    )
    posting.done (data) ->
      if data is "1"
        day = $(document).find("[data-date=\"" + date + "\"]")
        if day.find(".schedules").length
          day.find(".schedules").append "<a href=\"#\" data-toggle=\"tooltip\" title=\"\" class=\"event\" data-original-title=\"" + name + "\"></a>"
        else
          day.append "<div class=\"schedules\"><a href=\"#\" data-toggle=\"tooltip\" title=\"\" class=\"event\" data-original-title=\"" + name + "\"></a></div>"
      form.find("input[name=\"event_name\"]").val ""
      $("#newEvent").modal "hide"
      
      # Eventanzeige...
      $(".event").tooltip()


$(document).ready ->
  makeAJAXLinks()
  makeDaysClickable()
  $(".event").tooltip()
  makeFormAsync()
