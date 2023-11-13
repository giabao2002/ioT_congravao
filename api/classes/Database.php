<?php

namespace App\Classes;

class Database
{
    public $conn;
    public function __construct()
    {
        $this->conn = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    }

    public static function instance() {
        return new Database();
    }

    public function query($sql) {
        $result = mysqli_query($this->conn, $sql);
        if (!$result) {
            return false;
        }
        return $result;
    }

    public function fetch($sql) {
        $result = $this->query($sql);
        if (!$result) {
            return false;
        }
        $data = mysqli_fetch_assoc($result);
        return $data;
    }

    public function fetchAll($sql) {
        $result = $this->query($sql);
        if (!$result) {
            return false;
        }
        $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return $data;
    }
}