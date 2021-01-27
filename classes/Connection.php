<?php
class Connection {

    private static $host = "cloudfestivaldb.ctvhtpgh3tcu.us-east-1.rds.amazonaws.com";
    private static $database = "CADB";
    private static $username = "admin";
    private static $password = "Ryangraves08";

    public static function getInstance() {
        $dsn = 'mysql:host=' . Connection::$host . ';dbname=' . Connection::$database;

        $connection = new PDO($dsn, Connection::$username, Connection::$password);
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $connection;
    }

}
