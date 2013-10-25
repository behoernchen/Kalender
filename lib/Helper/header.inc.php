<?php
  date_default_timezone_set("Europe/Berlin"); 

  require_once 'chromephp.class.php';
  require_once($_SERVER['DOCUMENT_ROOT'] . '/Kalender/lib/Controller/calendar.class.php');
  require_once($_SERVER['DOCUMENT_ROOT'] . '/Kalender/lib/Models/sql.class.php'); 

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
