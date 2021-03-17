<?php
session_start();
include 'functions.php';

$email = $_POST['email'];
$password = $_POST['password'];

$user = login($email,$password);

if ($user){
    $name = 'success';
    $message = 'OK';
    set_flash_message($name,$message);

    redirect_to('Location: /users.php');
} else {
    $name = 'danger';
    $message = 'Неправильный пароль или имя пользователя';
    set_flash_message($name,$message);

    redirect_to('Location: /page_login.php');
}