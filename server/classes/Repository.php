<?php

include_once $_SERVER['DOCUMENT_ROOT']. '/config.php';

class Repository
{
    private static function connect()
    {
        try {
            $pdo = new PDO("mysql:host=" . SERVERNAME . ";dbname=" . DBNAME . ";charset=utf8", USERNAME, PASSWORD);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "</br>";
        }
    }

    /**
     * Query to database
     *
     * @param $query
     * @param array $params
     * @return array|mixed|string
     */
    public static function query($query, $params = array())
    {
        try {
            $statement = self::connect()->prepare($query);
            $statement->execute($params);
            if (strpos($query, 'SELECT COUNT') !== false) { //check if query string includes 'SELECT COUNT'
                return $statement->fetchColumn();
            } elseif (strpos($query, 'SELECT') !== false) { //check if query string includes 'SELECT'
                $data = $statement->fetchAll(PDO::FETCH_ASSOC);
                return $data;
            }
        } catch (PDOException $e) {
            return "Error!: " . $e->getMessage() . "</br>";
        }
    }
}
