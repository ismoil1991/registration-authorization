<?php
session_start();
include 'helpers.php';

$email = $_POST['email'];
$password = $_POST['password'];

$user = get_user_by_email($email);

if (!empty($user)){
    $message = '<strong>Уведомление!</strong> Этот эл. адрес уже занят другим пользователем.';
    $name ='danger';
    set_flash_message($name, $message);
    redirect_to('Location: /page_register.php');
} else {
    add_user($email, $password);
    $message = 'Регистрация успешна';
    $name ='success';
    set_flash_message($name, $message);
    redirect_to('Location: /page_login.php');
}

