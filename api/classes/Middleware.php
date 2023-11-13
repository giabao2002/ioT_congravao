<?php

namespace App\Classes;

class Middleware
{
    public static function logged() {
        if (Auth::instance()->check() == null) {
            response(false, 'Bạn chưa đăng nhập');
            die();
        }
    }

    public static function client() {
        if (Auth::instance()->check() != null) {
            response(false, 'Bạn đã đăng nhập');
            die();
        }
    }

    public static function post() {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            response(false, 'Phương thức truy cập không hợp lệ');
            die();
        }
    }

    public static function get() {
        if ($_SERVER['REQUEST_METHOD'] != 'GET') {
            response(false, 'Phương thức truy cập không hợp lệ');
            die();
        }
    }

    public static function admin() {
        if (Auth::instance()->check() == null || Auth::instance()->user()->role == 'user') {
            response(false, 'Không có quyền truy cập');
            die();
        }
    }
}