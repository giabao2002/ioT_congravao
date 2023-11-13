<?php

use App\Classes\Auth;
use App\Classes\Handle;

session_start();
include './config.php';
include __DIR__ . '/classes/kernel.php';

$action = isset($_GET['action']) ? $_GET['action'] : '';
$method = $_SERVER['REQUEST_METHOD'];

if ($action == '') {
    response(false, 'Không tìm thấy action');
}

switch ($action) {
    case 'layout.get':
        if (!Auth::instance()->check()) {
            response(true, 'Bạn chưa đăng nhập', ['html' => loadLayout('login')]);
            break;
        }
        $layout = input('layout');
        $html = loadLayout($layout);

        if ($layout == 'history') {
            $transactions = Handle::instance()->getTransactions();
            $html = loadLayout($layout, ['months' => $transactions]);
        }
        if ($layout == 'notification') {
            $transactions = Handle::instance()->getHistories();
            $html = loadLayout($layout, ['histories' => $transactions]);
        }

        response(true, 'Lấy layout thành công', ['html' => $html]);
        break;
    case 'layout.sub.get':
        $layout = input('layout');
        $html = loadLayoutSub($layout);

        if ($layout == 'manager') {
            $students = Handle::instance()->getAllStudents();
            $html = loadLayoutSub($layout, ['students' => $students]);
        }
        if ($layout == 'update-student') {
            $student = Handle::instance()->getStudent();
            $html = loadLayoutSub($layout, ['student' => $student]);
        }

        response(true, 'Lấy layout thành công', ['html' => $html]);
        break;
    case 'callback':
        $handle = new Handle();
        $handle->callbackMomoWallet();
        break;
    case 'handle':
        $function = input('function');
        $handle = new Handle();
        if (!method_exists($handle, $function)) {
            response(false, 'Thao tác không hợp lệ');
            break;
        }
        $handle->$function();
        break;
}

function input($field) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST')
        return isset($_POST[$field]) ? $_POST[$field] : '';
    return isset($_GET[$field]) ? $_GET[$field] : '';
}

function response(bool $status, $message, $data = []) {
    $response = [
        'status' => $status,
        'message' => $message,
        'data' => $data
    ];
    $notification = notification();
    if ($notification) {
        $response = array_merge($response, ['sweetAlert' => $notification]);
    }

    echo json_encode($response);
    die();
    return;
}

function debugJson($data) {
    echo json_encode($data);
    die();
    return;
}

function loadLayout($layout, $data = []) {
    if (!file_exists('./views/' . $layout . '.php')) {
        return '';
    }
    if ($layout != 'login' && Auth::instance()->check()) {
        $data = array_merge($data, ['user' => Auth::instance()->user()]);
    }

    extract($data);
    ob_start();
    include './views/' . $layout . '.php';
    $html = ob_get_contents();
    ob_end_clean();
    return $html;
}

function loadLayoutSub($layout, $data = []) {
    if (!file_exists('./views/sub/' . $layout . '.php')) {
        return '';
    }
    if (Auth::instance()->check()) {
        $data = array_merge($data, ['user' => Auth::instance()->user()]);
    }
    extract($data);
    ob_start();
    include './views/sub/' . $layout . '.php';
    $html = ob_get_contents();
    ob_end_clean();
    return $html;
}

function notification($status = false, $message = '')
{
    if ($message == '') {
        $notification = Auth::instance()->session('notification');
        if ($notification == null) {
            return false;
        }
        $message = $notification['message'];
        $status = $notification['status'];
        Auth::instance()->unsetSession('notification');
        return ['message' => $message, 'status' => $status];
    }
    Auth::instance()->session('notification', ['message' => $message, 'status' => $status]);
    return ['message' => $message, 'status' => $status];
}

function redirect($url) {
    header('Location: ' . $url);
    die();
    return;
}

function execPostRequest($url, $data)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data))
    );
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

function renderAccessToken() {
    $text =  md5(rand(100000, 999999) . time()) . md5(rand(100000, 999999) . time());
    $text = substr($text, 0, 15);
    return $text;
}