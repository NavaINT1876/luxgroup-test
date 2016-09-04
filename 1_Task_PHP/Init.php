<?php

final class Init
{
    const DB_HOST = 'localhost';
    const DB_NAME = 'lux_group_test';
    const DB_USER = 'igor';
    const DB_PASSWORD = 'kucherenko';
    const DB_TEST_TABLE = 'test2';

    /**
     * Instance of mysqli object which is used to set connection to database `lux_group_test`.
     * @var
     */
    public $db;

    /**
     * Init constructor.
     */
    public function __construct()
    {
        try {
            $dsn = 'mysql:host=' . self::DB_HOST . ';dbname=' . self::DB_NAME;
            $this->db = new PDO($dsn, self::DB_USER, self::DB_PASSWORD);
        } catch (PDOException $e) {
            die('Connection failed: ' . $e->getMessage());
        }
    }

    /**
     * Create table
     */
    private function create()
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS `" . self::DB_TEST_TABLE . "` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `script_name` varchar(25) NOT NULL,
          `strart_time` int(11) NOT NULL,
          `end_time` int(11) NOT NULL,
          `result` varchar(7) NOT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
        ";
        $query = $this->db->prepare($sql);
        $query->execute();
    }

    /**
     * Fill in table with data
     */
    private function fill()
    {
        $sql = "
            INSERT INTO `" . self::DB_TEST_TABLE . "` (`id`, `script_name`, `strart_time`, `end_time`, `result`) 
            VALUES ";
        for ($i = 0; $i <= 30; $i++) {
            $scriptName = 'Script name ' . $i;
            $startTime = 1472929896 + $i;
            $endTime = 1472939896 + 90 + $i;
            if ($i % 10 == 0) {
                $result = 'failed';
            } elseif ($i % 9 == 0) {
                $result = 'illegal';
            } elseif ($i % 8 == 0) {
                $result = 'normal';
            } else {
                $result = 'success';
            }

            $sql .= "(NULL, '" . $scriptName . "', " . $startTime . ", " . $endTime . ", '" . $result . "')";
            $sql .= ($i == 30) ? ';' : ', ';
        }
        $query = $this->db->prepare($sql);
        $query->execute();
    }

    /**
     * Select data from table “test” where “result” is only “normal” or “success”
     */
    public function get()
    {
        $sql = "SELECT `id`, `script_name`, `strart_time`, `end_time`, `result`
            FROM `" . self::DB_NAME . "`.`" . self::DB_TEST_TABLE .
            "` WHERE result=:normal OR result=:success";
        $query = $this->db->prepare($sql);
        $query->execute(['normal' => 'normal', 'success' => 'success']);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
}