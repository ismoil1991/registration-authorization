<?php
session_start();
include 'functions.php';

$edit = edit_information($_SESSION['user']['id'], $_POST['firstname'], $_POST['job_title'], $_POST['phone'], $_POST['address']);

if ($edit){
    $message = '<strong>Успешно изменен</strong>';
    $name ='success';
    set_flash_message($name, $message);

    redirect_to('Location: /users.php');
} else {
    $message = '<strong>Что-то пошло не так</strong>';
    $name ='danger';
    set_flash_message($name, $message);

    redirect_to('Location: /edit.php?id='.$_GET['id']);
}
