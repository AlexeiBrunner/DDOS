<?php

    namespace App\Databases;

    class DB
    {

        private $host = 'localhost';
        private $user = 'root';
        private $pass = '';
        private $database = '';

        private static $instance = null;
        private $conn;

        private function __construct()
        {
            $this->conn = new \PDO("mysql:host={$this->host};
    dbname={$this->database}", $this->user, $this->pass,
                array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
        }

        public static function getInstance()
        {
            if (!self::$instance) {
                self::$instance = new self();
            }

            return self::$instance;
        }

        /**
         * @return \PDO
         */
        public function getConnection()
        {
            return $this->conn;
        }
    }