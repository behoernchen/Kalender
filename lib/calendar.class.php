<?php
include 'chromephp.class.php';
include 'model.class.php';


class Calendar 
{
  /**
   * UNIX - Timestamp.
   *
   * @var integer
   */
  private $timestamp;

  /**
   * Aktuelles Jahr als Zahl.
   *
   * @var integer
   */
  private $current_year;

  /**
   * Aktueller Monat als Zahl.
   *
   * @var integer
   */
  private $current_month;

  /**
   * Aktueller Tag als Zahl.
   *
   * @var integer
   */
  private $current_day;

  /**
   * Anzahl der Tage, des aktuellen
   * Monats.
   *
   * @var integer
   */
  private $num_of_days;

  /**
   * Aktuelle Woche des Jahres.
   *
   * @var integer
   */
  private $week;

  /**
   * Deutsche Namen aller Monate
   *
   * @var string
   */
  private $month_names;

  /**
   * Deutsche Namen aller Tage
   *
   * @var string
   */
  private $day_names;

  /**
   * Daten
   *
   * @var string
   */
  private $data;

  /**
   * Datenbankverbindung
   *
   * @var Model
   */
  private $model;


  
  public function __construct($_time, $_timezone = "Europe/Berlin")
  {
    date_default_timezone_set($_timezone);
    $this->timestamp = $_time;

    $this->month_names = array(
      "January"   => "Januar",
      "February"  => "Februar",
      "March"     => "M&auml;rz",
      "April"     => "April",
      "May"       => "Mai",
      "June"      => "Juni",
      "July"      => "Juli",
      "August"    => "August",
      "September" => "September",
      "October"   => "Oktober",
      "November"  => "November",
      "December"  => "Dezember"
    );

    $this->day_names = array(
      "Mon" => "Mo", 
      "Tue" => "Di", 
      "Wed" => "Mi", 
      "Thu" => "Do", 
      "Fri" => "Fr",
      "Sat" => "Sa",
      "Sun" => "So"
    );

    // Initialisierungen...
    $this->current_year  = (int)date('o', $this->timestamp);
    $this->current_month = (int)date('n', $this->timestamp);
    $this->current_day   = (int)date('j', $this->timestamp);
    $this->num_of_days   = (int)date('t', $this->timestamp);
    $this->week          = (int)date('W', $this->timestamp);

    // Datenbankverbindung...
    $this->model = new Model();

    // Array mit allen noetigen Informationen aufbauen...
    $this->build();
  }

  
  public function monthBack()
  {
    return mktime(0, 0, 0, date("m", $this->timestamp) - 1, date("d", $this->timestamp), date("Y", $this->timestamp));
  }
  

  public function yearBack()
  {
    return mktime(0, 0, 0, date("m", $this->timestamp), date("d", $this->timestamp), date("Y",$this->timestamp) - 1);
  }


  public function monthForward()
  {
    return mktime(0, 0, 0, date("m", $this->timestamp) + 1, date("d", $this->timestamp), date("Y", $this->timestamp));
  }


  public function yearForward()
  {
    return mktime(0, 0, 0, date("m", $this->timestamp), date("d", $this->timestamp), date("Y", $this->timestamp) + 1);
  }


  public function newEvent($_title, $_date)
  {
    $date = explode(".", $_date);

    if (trim($_title) != "") {
      $this->model->commitEvent($_title, $date);
      return 1;
    }

    return 0;
  }


  private function build()
  {
    $num_of_days_last_month = (int)date('t', mktime(0, 0, 0, date('m', $date) - 1, 0, date('Y', $date)));
    $count = 0;

    $events_this_month = $this->model->getEventsByMonth($this->current_month);

    for ($i = 1; $i < $this->num_of_days + 1; $i++) { 
      $day_name = date('D', mktime(0, 0, 0, $this->current_month, $i, $this->current_year));
      $day_num  = date('j', mktime(0, 0, 0, $this->current_month, $i, $this->current_year));

      // Letzte Tage des letzten Monats
      if ($i == 1) {
        $n = (int)array_search($day_name, array('Mon','Tue','Wed','Thu','Fri','Sat','Sun'));
        for ($n; $n > 0; $n--) { 
          $this->data[$count]['classes'] = 'day last-month';
          $this->data[$count]['day']     = $num_of_days_last_month - ($n - 2);
          $this->data[$count]['date']    = ($num_of_days_last_month - ($n - 2)) . '.' . ($this->current_month - 1) . '.' . $this->current_year;
          $this->data[$count]['kw'] = date('W', mktime(0, 0, 0, $this->current_month - 1, ($num_of_days_last_month - ($n - 2)), $this->current_year));
          $count++;
        }
      }

      // Tage des aktuellen Monats -- Aktueller Tag: Zusaetzliche Infos, Klassen
      if ($i == $this->current_day && $this->current_month == date('n') && $this->current_year == date('o')) {
        $this->data[$count]['classes'] = 'day current';
        $this->data[$count]['day']     = $i;
        $this->data[$count]['info']    = 'Heute';
      } else {
        $this->data[$count]['classes'] = 'day';
        $this->data[$count]['day']     = $i;
      }

      $this->data[$count]['kw'] = date('W', mktime(0, 0, 0, $this->current_month, $i, $this->current_year));
      foreach ($events_this_month as $key => $value) {
        if ($value['tag'] == $i && $value['monat'] == $this->current_month && $value['jahr'] == $this->current_year) {
          $this->data[$count]['schedules'][$key] = $value['termintitel'];
        }
      }

      $this->data[$count]['date'] = $i . '.' . $this->current_month . '.' . $this->current_year;
      $count++;
    }

    // Erste Tage des naechsten Monats
    $j = 1;
    if ($count % 7 !== 0) {
      while ($count % 7 != 0) {
        $this->data[$count]['classes'] = 'day next-month';
        $this->data[$count]['day']     = $j;
        $this->data[$count]['date']    = $j . '.' . ($this->current_month + 1) . '.' . $this->current_year;
        $this->data[$count]['kw'] = date('W', mktime(0, 0, 0, $this->current_month + 1, $j, $this->current_year));
        $count++;
        $j++;
      }
    }
  }


  public function getTimestamp()
  {
    return $this->timestamp;
  }


  public function setTimestamp($_timestamp)
  {
    $this->timestamp = $_timestamp;
  }


  public function getCurrentYear()
  {
    return $this->current_year;
  }


  public function setCurrentYear($_current_year)
  {
    $this->current_year = $_current_year;
  }


  public function getCurrentMonth()
  {
    return $this->current_month;
  }


  public function setCurrentMonth($_current_month)
  {
    $this->current_month = $_current_month;
  }


  public function getCurrentDay()
  {
    return $this->current_day;
  }


  public function setCurrentDay($_current_day)
  {
    $this->current_day = $_current_day;
  }


  public function getNumOfDays()
  {
    return $this->num_of_days;
  }


  public function setNumOfDays($_num_of_days)
  {
    $this->num_of_days = $_num_of_days;
  }


  public function getWeek()
  {
    return $this->week;
  }


  public function setWeek($_week)
  {
    $this->week = $week;
  }


  public function getMonthNames()
  {
    return $this->month_names;
  }


  public function setMonthNames($_month_names)
  {
    $this->month_names = $_month_names;
  }


  public function getData() 
  {
    return $this->data;
  }


  public function getDayNames() {
    return $this->day_names;
  }


  public function getCurrentMonthName()
  {
    return $this->month_names[date('F', $this->timestamp)];
  }


  public function getCurrentDayName()
  {
    return $this->day_names[date('D', $this->timestamp)];
  }
}
