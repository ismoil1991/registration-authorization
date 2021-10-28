<?php
session_start();
include 'functions.php';


$edit = edit_credentials($_SESSION['user']['id'], $_POST['email'], $_POST['password']);
if (!$edit){
    $message = '<strong>Уведомление!</strong> Этот эл. адрес уже занят другим пользователем.';
    $name ='danger';
    set_flash_message($name, $message);

    redirect_to('Location: /security.php?id='.$_SESSION['user']['id']);
} else {
    $message = 'Профиль успешно обновлен';
    $name ='success';
    set_flash_message($name, $message);

    redirect_to('Location: /page_profile.php?id='.$_SESSION['user']['id']);
}
