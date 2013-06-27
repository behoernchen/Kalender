<?
  date_default_timezone_set("Europe/Berlin"); 
  include 'lib/calendar.class.php';

  $i = 1;
  if(isset($_REQUEST['timestamp'])) 
  {
    $date = $_REQUEST['timestamp'];
  }
  else if (isset($_GET['month']) && isset($_GET['year']))
  {
    $date = mktime(0, 0, 0, $_GET['month'], 1, $_GET['year']);
  }
  else 
  { 
    $date = time();
  }

  $cal = new Calendar($date);

  if (isset($_POST['event_name']) && isset($_POST['date_input'])) 
  {
    echo ($cal->newEvent($_POST['event_name'], $_POST['date_input']));
    exit();
  }
?>

<!DOCTYPE html>
<html dir="ltr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" href="images/cal.ico">
  <title>Kalender</title>
  
  <link rel="stylesheet" type="text/css" href="stylesheets/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="stylesheets/main.css">

  <script type="text/javascript" src="javascript/jquery.min.js"></script>
  <script type="text/javascript" src="javascript/bootstrap.min.js"></script>
  <script type="text/javascript" src="javascript/main.js"></script>
</head>
<body>
  <section id="container">
    <h1 class="pull-left"><?= $cal->getCurrentMonthName(); ?>, <?= $cal->getCurrentYear(); ?></h1>
    
    <div class="btn-group pull-right">
      <a href="index.php?timestamp=<?= $cal->yearBack() ?>" class="btn"><i class="icon-fast-backward"></i></a>
      <a href="index.php?timestamp=<?= $cal->monthBack() ?>" class="btn"><i class="icon-backward"></i></a>
      <a href="index.php" class="btn">Heute</a>
      <a href="index.php?timestamp=<?= $cal->monthForward() ?>" class="btn"><i class="icon-forward"></i></a>
      <a href="index.php?timestamp=<?= $cal->yearForward() ?>" class="btn"><i class="icon-fast-forward"></i></a>
    </div>

    <table cellpadding="0" cellspacing="0" class="daynames">
      <tbody>
        <tr>
          <? foreach ($cal->getDayNames() as $key => $day) : ?>
            <td class="dayname"><?= $day ?></td>
          <? endforeach; ?>
        </tr>
      </tbody>
    </table>
    <table cellpadding="0" cellspacing="0" class="days">
      <tbody>
        <tr>
          <? foreach ($cal->getData() as $key => $value) : ?>
            <td class="<?= $value['classes'] ?>" data-date="<?= $value['date'] ?>">
              <span class="date"><?= $value['day'] ?></span>
              <? if (($i - 1) % 7 == 0) : ?>
                <span class="kw">KW <?= $value['kw'] ?></span>
              <? endif; ?>
              <? if (isset($value['info'])) : ?>
                <span class="info pull-right"><?= $value['info'] ?></span>
              <? endif; ?>

              <? if (isset($value['schedules'])) : ?>
                <div class="schedules">
                  <? foreach ($value['schedules'] as $key => $value) : ?>
                    <a href="#" data-toggle="tooltip" title="<?= $value ?>" class="event"></a>
                  <? endforeach; ?>
                </div>
              <? endif; ?>
            </td>

            <? if ($i % 7 == 0) : ?>
              </tr><tr>
            <? endif; ?>

            <? $i++; ?>
          <? endforeach; ?>
        </tr>
      </tbody>
    </table><br />

    <div id="newEvent" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="newEvent" aria-hidden="true">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3>Neuer Termin</h3>
      </div>

      <form action="index.php" method="post" id="event-form">
        <div class="modal-body">
          <label for="terminname">Termin:</label>
          <input type="text" name="event_name" id="terminname" class="span6" />

          <label for="date-input">Datum:</label>
          <input type="text" name="date_input" id="date-input" class="span6" value="" />
        </div>

        <div class="modal-footer">
          <button class="btn" data-dismiss="modal" aria-hidden="true">Abbrechen</button>
          <input type="submit" value="Speichern" class="btn btn-primary" />
          </form>
        </div>
    </div>
  </section>
</body>
</html>
