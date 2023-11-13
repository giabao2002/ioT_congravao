<?php

namespace App\Classes;

class Auth
{
    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function instance() {
        return new Auth();
    }

    public function user() {
        if (!$this->check()) {
            return null;
        }
        $access_token = $this->session('access_token');
        $user = Database::instance()->fetch("SELECT * FROM users WHERE access_token = '$access_token'");
        return new User($user['id'], $user['email'], $user['phone'], $user['money'], $user['name'], $user['access_token'], $user['role'], $user['gender']);
    }

    public function login($email, $password) {
        $password = md5($password);
        $user = Database::instance()->fetch("SELECT * FROM users WHERE email = '$email' AND password = '$password'");
        if (!$user) {
            return false;
        }
        $user = new User($user['id'], $user['email'], $user['phone'], $user['money'], $user['name'], $user['access_token'], $user['role'], $user['gender']);
        $this->session('access_token', $user->access_token);
        return true;
    }

    public function logout() {
        session_destroy();
    }

    public function check() {
        return $this->session('access_token') != null;
    }

    public function session($field, $value = '') {
        if ($value == '') {
            return isset($_SESSION[$field]) ? $_SESSION[$field] : null;
        } else {
            $_SESSION[$field] = $value;
        }
    }

    public function unsetSession($field) {
        unset($_SESSION[$field]);
    }
}