<? 
  require 'lib/Helper/header.inc.php';
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
    
    <div class="btn-group fixed">
      <a href="index.php?timestamp=<?= $cal->yearBack() ?>" class="btn btn-default"><i class="glyphicon glyphicon-backward"></i></a>
      <a href="index.php?timestamp=<?= $cal->monthBack() ?>" class="btn btn-default"><i class="glyphicon glyphicon-chevron-left"></i></a>
      <a href="index.php" class="btn btn-default">Heute</a>
      <a href="index.php?timestamp=<?= $cal->monthForward() ?>" class="btn btn-default"><i class="glyphicon glyphicon-chevron-right"></i></a>
      <a href="index.php?timestamp=<?= $cal->yearForward() ?>" class="btn btn-default"><i class="glyphicon glyphicon-forward"></i></a>
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

    <div id="newEvent" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="newEvent" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h4 class="modal-title">Neuer Termin</h4>
          </div>

          <form action="index.php" method="post" id="event-form">
            <div class="modal-body">
              <div class="form-group">
                <label for="terminname">Termin:</label>
                <input type="text" name="event_name" id="terminname" class="form-control" />
              </div>
              <div class="form-group">
                <label for="date-input">Datum:</label>
                <input type="text" name="date_input" id="date-input" class="form-control" value="" />
              </div>
            </div>

            <div class="modal-footer">
              <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Abbrechen</button>
              <input type="submit" value="Speichern" class="btn btn-success" />
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
</body>
</html>
