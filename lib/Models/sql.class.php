<?php
  class SQL
  {
    private $dbh;

    // Konstruktor
    public function __construct()
    {
      $db_data = simplexml_load_file($_SERVER['DOCUMENT_ROOT'] . "/Kalender/config/_database.xml");
      $db_data_json = json_decode($_SERVER['DOCUMENT_ROOT'] . "/Kalender/config/_data.json");
      ChromePhp::log($db_data_json);
      $this->connect($db_data);
    }

    // Verbindung aufbauen
    private function connect($_data)
    {
      $dsn  = $_data->adapter . ':dbname=' . $_data->database . ';host=' . $_data->host . ':' . $_data->port;
      $user = $_data->user;
      $pass = $_data->password;

      try {
        $this->dbh = new PDO($dsn, $user, $pass);
      } catch (PDOException $e) {
        echo "Connection Failed: " . $e->getMessage();
        die;
      }
    }


    // Termin speichern
    public function commitEvent($_title, $_date)
    {
      $sql = 'INSERT INTO kalender(termintitel, tag, monat, jahr) VALUES(:title, :tag, :monat, :jahr);';
      $stmt = $this->dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
      
      $stmt->execute(array(':title' => (string)$_title,
                           ':tag'   => (int)$_date[0], 
                           ':monat' => (int)$_date[1], 
                           ':jahr'  => (int)$_date[2]));
    }

    // Termine auslesen
    public function getEventsByMonth($_month)
    {
      $sql  = 'SELECT * FROM kalender WHERE monat = :month;';
      $stmt = $this->dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

      $stmt->execute(array(':month' => (int)$_month));
      return $stmt->fetchAll();
    }
  }
