<?php

    class Connection
    {
        private $host;
        private $db;
        private $userServer;
        private $password;
        private $charset;

        public function __construct()
        {
            $this->host = 'localhost';
            $this->db = 'songbase';
            $this->userServer = 'root';
            $this->password = '';
            $this->charset = 'utf8';
        }

        public function connect()
        {
            $com = "mysql:host=".$this->host.";dbname=".$this->db.";charset=".$this->charset;
            $link = new PDO($com, $this->userServer, $this->password);
            return($link);
        }

    }

?>