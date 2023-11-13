<?php
namespace App\Classes;

class User
{
    public $id;
    public $email;
    public $phone;
    public $money;
    public $name;
    public $access_token;
    public $role;
    public $gender;

    public function __construct($id, $email, $phone, $money, $name, $access_token, $role, $gender)
    {
        $this->id = $id;
        $this->email = $email;
        $this->phone = $phone;
        $this->money = $money;
        $this->name = $name;
        $this->access_token = $access_token;
        $this->role = $role;
        $this->gender = $gender;
    }
}