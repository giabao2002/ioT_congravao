<?php

namespace App\Classes;

class Handle
{
    public static function instance() {
        return new Handle();
    }

    public function login() {
        Middleware::client();

        $email = input('email');
        $password = input('password');

        if ($email == '') {
            response(false, 'Email không được để trống');
        }
        if ($password == '') {
            response(false, 'Mật khẩu không được để trống');
        }

        if (!Auth::instance()->login($email, $password)) {
            response(false, 'Email hoặc mật khẩu không chính xác');
        }
        response(true, 'Đăng nhập thành công');
    }

    public function logout() {
        Auth::instance()->logout();
        response(true, 'Đăng xuất thành công');
    }

    public function updateInformation() {
        Middleware::admin();

        $id = input('id');
        $name = input('name');
        $email = input('email');
        $phone = input('phone');
        $gender = input('gender');
        $money = intval(input('money'));

        if ($id == '') {
            response(false, 'ID không được để trống');
        }
        if ($name == '') {
            response(false, 'Họ tên không được để trống');
        }
        if ($email == '') {
            response(false, 'Email không được để trống');
        }
        if ($money == '') {
            response(false, 'Số tiền không được để trống');
        }
        if (intval($money) < 0) {
            response(false, 'Số tiền không được nhỏ hơn 0');
        }
        if ($gender != 'male' && $gender != 'female') {
            response(false, 'Giới tính không hợp lệ');
        }

        $student = Database::instance()->fetch("SELECT * FROM users WHERE id = '$id' AND role = 'user'");
        if (!$student) {
            response(false, 'Không tìm thấy sinh viên');
        }

        Database::instance()->query("UPDATE users SET name = '$name', email = '$email', phone = '$phone', gender = '$gender', money = '$money' WHERE id = '$id'");
        response(true, 'Cập nhật thông tin thành công');
    }

    public function createStudent() {
        Middleware::admin();

        $name = input('name');
        $email = input('email');
        $phone = input('phone');
        $gender = input('gender');
        $money = intval(input('money'));
        $password = input('password');

        if ($name == '') {
            response(false, 'Họ tên không được để trống');
        }
        if ($email == '') {
            response(false, 'Email không được để trống');
        }
        if ($money == '') {
            response(false, 'Số tiền không được để trống');
        }
        if (intval($money) < 0) {
            response(false, 'Số tiền không được nhỏ hơn 0');
        }
        if ($gender != 'male' && $gender != 'female') {
            response(false, 'Giới tính không hợp lệ');
        }
        if ($password == '') {
            response(false, 'Mật khẩu không được để trống');
        }

        $access_token = renderAccessToken();
        Database::instance()->query("INSERT INTO users (email, password, name, phone, money, gender, role, access_token) VALUES ('$email', '$password', '$name', '$phone', '$money', '$gender', 'user', '$access_token')");
        response(true, 'Thêm sinh viên thành công');
    }

    public function payment() {
        Middleware::logged();

        if (Auth::instance()->user()->money == 0) {
            response(false, 'Bạn không cần thanh toán');
        }

        $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
        $partnerCode = 'MOMOBKUN20180529';
        $accessKey = 'klm05TvNBzhg7h7j';
        $serectkey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';
        $orderId = md5(time() . rand(100000, 999999));
        $orderInfo = "Thanh toán qua MoMo";
        $amount = Auth::instance()->user()->money;
        $ipnUrl =  DIR_URL . "api/?action=callback";
        $redirectUrl = DIR_URL . "api/?action=callback";
        $extraData = Auth::instance()->user()->id;

        $requestId = time() . "";
        $requestType = "captureWallet";
        //before sign HMAC SHA256 signature
        $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;
        $signature = hash_hmac("sha256", $rawHash, $serectkey);
        $data = array('partnerCode' => $partnerCode,
            'partnerName' => "Test",
            "storeId" => "MomoTestStore",
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl' => $ipnUrl,
            'lang' => 'vi',
            'extraData' => $extraData,
            'requestType' => $requestType,
            'signature' => $signature);
        $result = execPostRequest($endpoint, json_encode($data));
        $jsonResult = json_decode($result, true); 

        if (isset($jsonResult['payUrl']))
            response(true, 'Lấy link thanh toán thành công', ['url' => $jsonResult['payUrl']]);
    }

    public function getTransactions() {
        $transactions = Database::instance()->fetchAll("SELECT * FROM transactions WHERE user_id = '" . Auth::instance()->user()->id . "' ORDER BY created_at DESC");
        // Group by month
        $transactions = array_reduce($transactions, function($result, $transaction) {
            $month = date('m/Y', strtotime($transaction['created_at']));
            if (!isset($result[$month])) {
                $result[$month] = [];
            }
            array_push($result[$month], $transaction);
            return $result;
        }, []);

        return $transactions;
    }

    public function getHistories() {
        $histories = Database::instance()->fetchAll("SELECT * FROM histories WHERE user_id = '" . Auth::instance()->user()->id . "' ORDER BY created_at DESC");
        return $histories;
    }

    public function callbackMomoWallet() {
        Middleware::logged();

        $amount = input('amount');
        $transId = input('transId');
        $extraData = input('extraData');

        if ($amount == '') {
            notification('error', 'Số tiền không được để trống');
            return redirect(DIR_URL);
        }

        $amount = (int) $amount;

        if (Auth::instance()->user()->id != $extraData) {
            notification('error', 'Người thanh toán không hợp lệ');
            return redirect(DIR_URL);
        }

        $amount = Auth::instance()->user()->money - $amount;

        Database::instance()->query("INSERT INTO transactions (user_id, money, transaction_id) VALUES ('" . Auth::instance()->user()->id . "', '" . $amount . "', '"+ $transId +"')");
        Database::instance()->query("UPDATE users SET money = 0 WHERE id = '" . Auth::instance()->user()->id . "'");
        
        notification('error', 'Thanh toán thành công');
        return redirect(DIR_URL);
    }

    public function getAllStudents() {
        Middleware::admin();

        $students = Database::instance()->fetchAll("SELECT * FROM users WHERE role = 'user'");
        return $students;
    }

    public function getStudent() {
        Middleware::admin();

        $id = input('id');
        $student = Database::instance()->fetch("SELECT * FROM users WHERE id = '" . $id . "'");
        return $student;
    }
}